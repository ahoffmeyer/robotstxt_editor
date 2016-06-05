;(function($, TYPO3){
    "use strict";

    var App = {

        init: function() {
            this.checkContent()
        },

        checkContent:  function() {
            var _this = this;

            $('.checkFile').on('click', function(e) {
                e.preventDefault();

                var ajaxUrl = TYPO3.settings.ajaxUrls['BackupModuleController::checkFileContent'];
                var file = $(this).data('file');

                $.get(ajaxUrl, {data: file}, function(data, status) {
                    if (status === 'success') {
                        _this.contentSlide(data);
                    }
                });
            });
        },

        contentSlide: function(data) {
            var _this = this;

            return $('<div />', {
                id: 'fileContents',
                css: {
                    right: -450
                }
            })
                .animate({
                    right: 0
                }, function() {
                    _this.closeSlider();
                })
                .appendTo('body')
                .html('<div class=inner>'+ data +'</div>');
        },

        closeSlider: function() {
            return $('<div />', {
                id: 'closeSlider'
            })
                .appendTo('#fileContents')
                .html('<i class="fa fa-times" aria-hidden="true"></i>')
                .on('click', function(e) {
                    e.preventDefault();
                    $('#fileContents').animate({
                        right: -450
                    }, function() {
                        $(this).remove();
                    });
                });
        }

    };

    App.init();

})(TYPO3.jQuery, TYPO3);