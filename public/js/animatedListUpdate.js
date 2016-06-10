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

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

			$element.css('position', 'relative');
        };

        plugin.updateList = function(newListItems) {
			newListItems = typeof newListItems !== 'undefined' ? newListItems : [];


			for (i = 0, len = listItems.length; i < len; i++) {
				// Loop old list items to see which ones need to be removed

				// Find index of the old item in the new items
				var indexes = $.map(newListItems, function(obj, index) {
					if(obj.id == listItems[i].id) {
						return index;
					}
				});
				var positionInNewElements = indexes[0];

				// console.log('Positie van oud element',i,'in nieuwe elementen:',positionInNewElements);

				if(positionInNewElements === undefined) {
					// element needs to be removed

					// animation
					listItems[i].$li.animate({
						top: 40,
						height:0,
						opacity: 0,
						padding: 0
					}, plugin.settings.animationTime, function(){ $(this).remove(); });

					listItems.splice( i, 1 );
					i--;len--;
				}
			}

			for (i = 0, len = newListItems.length; i < len; i++) {
				// List item needs to be added or moved

				// Find index of the new item in the old items
				var indexes = $.map(listItems, function(obj, index) {
					if(obj.id == newListItems[i].id) {
						return index;
					}
				});
				var positionInOldElements = indexes[0];

				//console.log('Position of ', i, 'in old elements is', positionInOldElements);

				if(positionInOldElements === undefined) {
					// this new list item needs to be added
					newListItems[i].$li = addNewListItem(newListItems[i].$liAppend);
					listItems.push(newListItems[i]);

					var offset = ((listItems.length - 1) - i) * - newListItems[i].$li.outerHeight();
					newListItems[i].$li.animate({top: offset}, plugin.settings.animationTime);

				} else {
					// element already exists; need to move it prolly
					var offset = (positionInOldElements - i) * - listItems[positionInOldElements].$li.outerHeight();
					listItems[positionInOldElements].$li
						.animate({
							top: offset
						}, plugin.settings.animationTime);
				}
			}

			setTimeout (function () {
				// Moved/added/deleted elements for animations;
				// Now completely reset all to newListItems
				listItems = newListItems;
				$element.empty();
				for (i = 0, len = newListItems.length; i < len; i++) {
					listItems[i].$li = addNewListItem(newListItems[i].$liAppend);
				}
			}, plugin.settings.animationTime);

        };


		var addNewListItem = function ($liappend) {
			var $newLi = $('<li/>')
							.addClass('list-group-item')
							.css('position', 'relative')
							.append($liappend);
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
