(function ( $ ) {

    $.fn.animatedListUpdateOld = function(newListItems, options) {

		newListItems = typeof newListItems !== 'undefined' ? newListItems : [];
		options = typeof options !== 'undefined' ? options : {};

        var settings = $.extend({
			numberPrefix: true
        }, options );

		var i,len;

		// this is <ul>

		return this.each(function(index, list) {
			// Get the old list of items
			var oldListItems = [];
			$(list).find('li span').each(function() {
				oldListItems.push($(this).text());
			});

			console.log('Old list items:',oldListItems);

			if(oldListItems.length) {
				// Compare arrays
				for (i = 0, len = oldListItems.length; i < len; i++) {
					console.log('Position of existing element',i,newListItems.findIndex(function(element, index, array) {
						return oldListItems[i] == element;
					}));
				}
			} else {
				// No old list items yet, just add them all
				console.log('Add new items', newListItems);
				for (i = 0, len = newListItems.length; i < len; i++) {
					$(this).append($('<li/>').append($('<span/>').text(newListItems[i])));
				}
			}


	    });

    };

}( jQuery ));





(function($) {

    $.animatedListUpdate = function(element, options) {

        var defaults = {
			numberPrefix: true,
			animationTime: 1000
        };

        var plugin = this;

        plugin.settings = {};

        var $element = $(element);
    	element = element;

		var listItems = [];
		var $listItemElements = [];

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

			$element.css('position', 'relative');
        };

        plugin.updateList = function(newListItems) {
			newListItems = typeof newListItems !== 'undefined' ? newListItems : [];

			var movements = [];

			var compareElementToThis = function(element, index, array, newIndex) {
				return this == element;
			};

			for (i = 0, len = listItems.length; i < len; i++) {
				// Loop old list items to see which ones need to be removed
				//var positionInNewElements = newListItems.findIndex(compareElementToThis, listItems[i]);
				var positionInNewElements = newListItems.indexOf(listItems[i]);

				if(positionInNewElements == -1) {
					// element needs to be removed

					// animation
					//$listItemElements[i].hide('slow', function(){ $(this).remove(); });
					$listItemElements[i].animate({
						top: 40,
						height:0,
						opacity: 0,
						padding: 0
					}, plugin.settings.animationTime);

					listItems.splice( i, 1 );
					$listItemElements.splice( i, 1 );
				}
			}

			for (i = 0, len = newListItems.length; i < len; i++) {
				// List item needs to be added or moved

				var positionInOldElements = listItems.indexOf(newListItems[i]);

				if(positionInOldElements == -1) {
					// this new list item needs to be added
					$newLi = addNewListItem(i+1,newListItems[i]);
					listItems.push(newListItems[i]);

					var offset = ((listItems.length - 1) - i) * - $newLi.outerHeight();
					$newLi.animate({top: offset}, plugin.settings.animationTime);

					$listItemElements.push($newLi);
				} else {
					// element already exists; need to move it prolly
					var offset = (positionInOldElements - i) * - $listItemElements[positionInOldElements].outerHeight();
					$listItemElements[positionInOldElements]
						.animate({
							top: offset
						}, plugin.settings.animationTime)
						.find('.numberPrefix').text((i + 1) + '. ');
				}
			}

			setTimeout (function () {
				// Moved/added/deleted elements for animations;
				// Now completely reset all to newListItems
				listItems = newListItems;
				$listItemElements = [];
				$element.empty();
				for (i = 0, len = newListItems.length; i < len; i++) {
					$newLi = addNewListItem(i+1,newListItems[i]);
					$listItemElements.push($newLi);
				}
			}, plugin.settings.animationTime);

        };


		var addNewListItem = function (number, text) {
			var $newLi = $('<li/>')
							.addClass('list-group-item')
							.css('position', 'relative')
							.append($('<span/>', {class:'numberPrefix'}).text(number + '. '))
							.append($('<span/>', {class: 'listItemText'}).text(text));
			$element.append($newLi);
			return $newLi;
		};


        plugin.init();

    };

    $.fn.animatedListUpdate = function(options) {

        return this.each(function() {
            if (undefined === $(this).data('animatedListUpdate')) {
                var plugin = new $.animatedListUpdate(this, options);
                $(this).data('animatedListUpdate', plugin);
            }
        });

    };

})(jQuery);
