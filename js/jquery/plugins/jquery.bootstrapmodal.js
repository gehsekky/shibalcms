(function ($) {
	var defaults = {
		title: '',
		message: '',
		primaryButtonText: '',
		primaryButtonClick: null,
		disableFooter: false,
		keyboard: true,
		backdrop: 'static'
	};
	
	var options;
	
	$.fn.bootstrapmodal = function (action, runtimeOptions) {
		if (typeof (action) === 'object') {
			runtimeOptions = action;
			action = 'initialize';
		}
		
		options = $.extend({}, defaults, runtimeOptions);
		
		this.each(function () {
			var $this = $(this);
			switch (action) {
				case 'initialize':
					// dialog html
					var html = 	'<div class="modal-header">' + 
									'<a class="close" data-dismiss="modal">×</a>' + 
									'<h3 class="bootstrapmodal-title"></h3>' + 
								'</div>' + 
								'<div class="modal-body">' + 
								'</div>' + 
								'<div class="modal-footer">' + 
									'<a href="javascript:void(0);" class="btn bootstrapmodal-button-close">Close</a>' + 
									'<a href="javascript:void(0);" class="btn btn-primary bootstrapmodal-button-primary"></a>' + 
								'</div>';
					
					// initialize modal container
					$this
						.addClass('modal')
						.html(html);

					// set modal title
					$('.bootstrapmodal-title', $this).html(options.title);
					
					// set message
					$('.modal-body', $this).html(options.message);
					
					// initialize primary button. hide if no text.
					if (options.primaryButtonText === '') {
						$('.bootstrapmodal-button-primary', $this).css('display', 'none');
					} else {
						$('.bootstrapmodal-button-primary', $this)
							.css('display', '')
							.html(options.primaryButtonText);
						
						// bind click
						if (typeof (options.primaryButtonClick) === 'function') {
							$('.bootstrapmodal-button-primary', $this).on('click', options.primaryButtonClick);
						}
					}
					
					// bind click for close button
					$('.bootstrapmodal-button-close', $this).on('click', function () {
						$this.modal('hide');
					});
					
					// set footer visibility
					if (options.disableFooter) {
						$('.modal-footer', $this).css('display', 'none');
					} else {
						$('.modal-footer', $this).css('display', '');
					}
					
					// init and show modal
					$this.modal({
						backdrop: options.backdrop,
						keyboard: options.keyboard,
						show: true
					});
					break;
				case 'destroy':
					$this.empty();
					$this.css('display', 'none');
					break;
				default:
					break;
			}
		});
		
		return this;
	};
})(jQuery);