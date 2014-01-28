window.dsinMsgBox = window.dsinMsgBox || (function init($, undefined) {

    "use strict";

    var exports = {
        BUTTON_YES:        1,
        BUTTON_YES_DANGER: 2,
        BUTTON_NO:         4,
        BUTTON_OK:         8,
        BUTTON_OK_DANGER:  16
    },

        defaults = {
            title: 'Atenção',
            confirmMessage: 'Deseja prosseguir com a operação ?',
            confirmButton: (exports.BUTTON_YES | exports.BUTTON_NO),
            confirmCallBack: null,
            alertMessage: 'Atenção',
            alertButton: exports.BUTTON_OK
        };

    exports.confirm = function (options) {

        // Merge configurations
        var params = $.extend({}, defaults, options),
            dialog,

            // dialog html
            html = '<div class="modal container">' +
                    '<div class="modal-dialog dsin-confirmation">' +
                      '<div class="modal-content">' +
                        '<div class="modal-header">' +
                          '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                          '<h4 class="modal-title">' + (params.title || 'Atenção') + '</h4>' +
                        '</div>' +
                        '<div class="modal-body">' + (params.confirmMessage || 'Deseja prosseguir com a operação ?') + '</div>' +
                        '<div class="modal-footer">';

        if (params.confirmButton & exports.BUTTON_YES) {
            html += '<button type="button" data-dsb-button="yes" class="btn btn-primary" name="btnYes">Sim</button>';
        }
        if (params.confirmButton & exports.BUTTON_YES_DANGER) {
            html += '<button type="button" data-dsb-button="yes" class="btn btn-danger" name="btnNo">Sim</button>';
        }
        if (params.confirmButton & exports.BUTTON_NO) {
            html += '<button type="button" data-dsb-button="no"  class="btn btn-default" name="btnNo">Não</button>';
        }
        html += '</div></div></div></div>';

        // modal window
        dialog = $(html);

        // if relative element has passed, the width is relative of this element
        if (params.relativeElement) {
            dialog.find('.modal-dialog').css('width', (params.relativeElement.width() * 65) / 100);
        }

        // Buttons click event for dialog
        dialog.on('click', '.modal-footer button', function (e) {

            if ('function' === typeof params.confirmCallBack) {
                params.confirmCallBack($(this).data('dsb-button') === 'yes');
            }

            // close modal
            dialog.modal('hide');
        });

        // When close,  dialog is detached from container
        dialog.on('hidden.bs.modal', function (e) {
            if (e.target === this) {
                dialog.remove();
            }
        });

        // Put focus on button
        dialog.on('shown.bs.modal', function (e) {
            if (params.confirmButton & exports.BUTTON_YES_DANGER) {
                $('.modal-footer button[data-dsb-button=no]').first().focus();
            } else {
                $('.modal-footer button[data-dsb-button=yes]').first().focus();
            }
        });

        // Show dialog as a modal
        dialog.modal({backdrop: false});
    };

    exports.alert = function (options) {

        // Merge configurations
        var params = $.extend({}, defaults, options),
            dialog,

            // dialog html
            html = '<div class="modal container">' +
                    '<div class="modal-dialog dsin-confirmation">' +
                      '<div class="modal-content">' +
                        '<div class="modal-header">' +
                          '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                          '<h4 class="modal-title">' + (params.title || 'Atenção') + '</h4>' +
                        '</div>' +
                        '<div class="modal-body">' + params.alertMessage + '</div>' +
                        '<div class="modal-footer">';

        if (params.alertButton & exports.BUTTON_OK) {
            html += '<button type="button" data-dsb-button="yes" class="btn btn-primary" name="btnYes">OK</button>';
        }
        if (params.alertButton & exports.BUTTON_OK_DANGER) {
            html += '<button type="button" data-dsb-button="yes"  class="btn btn-danger" name="btnNo">OK</button>';
        }
        html += '</div></div></div></div>';

        // modal window
        dialog = $(html);

        // if relative element has passed, the width is relative of this element
        if (params.relativeElement) {
            dialog.find('.modal-dialog').css('width', (params.relativeElement.width() * 65) / 100);
        }

        // Buttons click event for dialog
        dialog.on('click', '.modal-footer button', function (e) {

            // close modal
            dialog.modal('hide');
        });

        // When close,  dialog is detached from container
        dialog.on('hidden.bs.modal', function (e) {
            if (e.target === this) {
                dialog.remove();
            }
        });

        // Put focus on button
        dialog.on('shown.bs.modal', function (e) {
            $('.modal-footer button[data-dsb-button=no]').first().focus();
        });

        // Show dialog as a modal
        dialog.modal({backdrop: false});
    };

    return exports;

}(window.jQuery));