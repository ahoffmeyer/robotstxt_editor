;(function($) {

    "use strict";

    var App = {
        editor: $('#robotstxt_editor').val(),

        init: function() {
            this.clickButton();
        },

        trimRows: function() {
            var rows = $('#robotstxt_editor').val().split("\n");

            return rows.map(function(row, index) {
                if (row) {
                    return row.trim();
                }
            });
        },

        clickButton: function() {
            $('#updateFile').on('click', function() {
                $('#robotstxt_editor').val(this.trimRows().join("\n"));
            }.bind(this));
        }
    };

    App.init();

})(TYPO3.jQuery);