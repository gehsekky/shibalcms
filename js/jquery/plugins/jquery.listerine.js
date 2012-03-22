(function ($) {
	var defaults = {
		cols: 1,
		wrapper_style: {
			'position': 'relative'
		},
		column_style: {
			'text-align': 'center'
		},
		listitem_style: {
			
		},
		wrapper_class: 'listerine-wrapper',
		column_class: 'listerine-column',
		listitem_class: 'listerine-listitem', 
		transform: 'columns',
		listitem_hover: {
			'in': function () {
			
			}, 
			'out': function () {
			
			}
		},
		listitem_click: function () {
		
		}, 
		clearfix: true
	};
	
	$.fn.listerine = function(action, options) {
		if (typeof (action) === 'object') {
			options = action;
			action = 'initialize';
		}

		options = $.extend({}, defaults, options);
		
		this.each(function (index, value) {
			var root = this;
			switch(action) {
				case 'initialize':
					if ($('.' + options.wrapper_class, root).length === 0) {
						switch (options.transform) {
							case 'columns':
								(function (that) {
									var num_items = Math.ceil($(that).children().length / options.cols);
									var fragment = document.createDocumentFragment();
									
									// build columns
									for (var i = 0; i < options.cols; i++) {
										var $col = $(document.createElement('div'))
											.addClass(options.column_class)
											.css('width', Math.floor(100 / options.cols) + '%')
											.css('float', 'left')
											.css(options.column_style)
											.append($(that).children().slice(0, num_items)
														.addClass(options.listitem_class)
														.css(options.listitem_style)
													);
										
										fragment.appendChild($col[0]);
									}
									
									// build wrapper for columns
									var $wrapper = $(document.createElement('div'))
										.addClass(options.wrapper_class)
										.appendTo(root)
										.css(options.wrapper_style)
										.append(fragment);
									
									if (options.clearfix) {
										$wrapper.addClass('clearfix');
									}
								})(this);
								break;
							case 'grid':
								(function (that) {
									// build wrapper
									$(document.createElement('div'))
										.addClass(options.wrapper_class)
										.css(options.wrapper_style)
										.append($(that).children()
													.addClass(options.listitem_class)
													.css('float', 'left')
													.css(options.listitem_style)
												)
										.appendTo(root)
										.addClass(options.clearfix ? 'clearfix' : '');
								})(this);
								break;
							default:
								// unrecognized transform value
						}
						
						// attach handlers to listitems
						$('.' + options.listitem_class)
							.on('mouseenter', options.listitem_hover['in'])
							.on('mouseleave', options.listitem_hover['out'])
							.on('click', options.listitem_click);
					}
					break;
				default:
					// unrecognized command
			}
		});
		
		return this;
	}
})(jQuery);