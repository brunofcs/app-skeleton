// JSON Message Types
var JSON_MESSAGE_REDIR           = 1,

    JSON_MESSAGE_FORM_VALIDATION = 2,

    JSON_MESSAGE_EXCEPTION       = 3,

    JSON_MESSAGE_FORM            = 4,

    JSON_MESSAGE_CLEAR_FORM      = 5;

// Error Constants to form validation

var FORM_ERROR_CODE_UNKNOWN          = 0,

    FORM_ERROR_CODE_EMPTY_FIELD      = 1,

    FORM_ERROR_CODE_INCORRECT_EMAIL_FORMAT = 2;


var dataSwap = null;

// Trigger whenever ajax start
$(document).ajaxStart(function () {
    'use strict';
    NProgress.start();
});

// Complete the NProcess when ajax end
$(document).ajaxComplete(function () {
    'use strict';
    NProgress.done();
});

function getFormErrConstraintName(code) {
    'use strict';

    var constraintName = "";

    switch (code) {

    case FORM_ERROR_CODE_EMPTY_FIELD:
        constraintName = "required";
        break;

    case FORM_ERROR_CODE_INCORRECT_EMAIL_FORMAT:
        constraintName = "type-email";
        break;

    default:
        constraintName = "";
        break;
    }
    return constraintName;
}

function DSINConnect() {
    'use strict';

    return;
}

DSINConnect.defaults = {
    formID: null,
    messagesContainer: null,
    clearFormCallBack: null,
    fillFormCallBack: null,

    url: null,
    method: 'POST',
    data: null
};

DSINConnect.parseJSONResponse = function (jsonMessage, options) {
    'use strict';

    var params = $.extend({}, DSINConnect.defaults, options),
        errContent,
        i,
        errMsg,
        fieldErr;

    switch (jsonMessage[0].type) {

    case JSON_MESSAGE_EXCEPTION:
        if (jsonMessage[0].showAlert) {

            dsinMsgBox.alert({
                alertMessage: jsonMessage[0].message,
                alertButton: dsinMsgBox.BUTTON_OK_DANGER
            });

        } else {
            errContent = "<ul class='parsley-error-list'><li class='type' style='display: list-item;'>" + jsonMessage[0].message + "</li></ul>";
            $(params.messagesContainer).html(errContent);
        }

        break;

    case JSON_MESSAGE_REDIR:
        document.location.href = jsonMessage[0].url;
        break;

    case JSON_MESSAGE_FORM_VALIDATION:

        errContent = "<ul class='parsley-error-list'>";
        for (i = 0; i < jsonMessage[0].errors.length; i++) {

            errMsg = "";
            fieldErr = $(params.formId + ' input[name=' + jsonMessage[0].errors[i].field + ']');
            errContent += "<li class='type' style='display: list-item;'>";
            errMsg = fieldErr.attr("parsley-" + getFormErrConstraintName(jsonMessage[0].errors[i].errCode) + "-message");
            if (fieldErr && errMsg) {
                errContent += errMsg;
            } else {
                errContent += jsonMessage[0].errors[i].msg;
            }

            errContent += "</li>";

            fieldErr.addClass("parsley-validated parsley-error");

        }
        errContent += "</ul>";

        $(params.messagesContainer).html(errContent);

        $(params.formId + ' input[name=' + jsonMessage[0].fieldFocus + ']').focus();

        break;

    case JSON_MESSAGE_FORM:
        if (jsonMessage[0].message) {
            if (jsonMessage[0].showAlert) {
                dsinMsgBox.alert({
                    alertMessage: jsonMessage[0].message
                });
            } else {
                errContent = "<ul class='parsley-error-list'><li class='type' style='display: list-item;'>" + jsonMessage[0].message + "</li></ul>";
                $(params.messagesContainer).html(errContent);
            }
        }

        if ("function" === typeof params.fillFormCallBack) {
            params.fillFormCallBack(jsonMessage[0].content);
        }

        if (jsonMessage[0].fieldFocus) {
            $(params.formId + ' input[name=' + jsonMessage[0].fieldFocus + ']').focus();
        }

        break;

    case JSON_MESSAGE_CLEAR_FORM:

        if ("function" === typeof params.clearFormCallBack) {
            params.clearFormCallBack();
        }

        if (jsonMessage[0].fieldFocus) {
            $(params.formId + ' input[name=' + jsonMessage[0].fieldFocus + ']').focus();
        }

        break;
    }
};

DSINConnect.post = function (options) {
    'use strict';

    var params = $.extend({}, DSINConnect.defaults, options);

    $.ajax({
        type:     params.method,
        url:      params.url,
        data:     null,
        dataType: 'json',
        success: function (data) {

            if (data && data.message && data.message[0] && data.message[0].type) {

                DSINConnect.parseJSONResponse(data.message, params);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (textStatus === "parsererror") {
                window.alert("Não foi possível identificar a mensagem de resposta");
            }
        }
    });
};

// Envia Formularios
DSINConnect.attachToForm = function (options) {
    'use strict';

    var params = $.extend({}, DSINConnect.defaults, options);

    if (!params.formID) { throw "Incorrect call"; }

    $(params.formID).submit(function (event) {

        // Stop form from submitting normally
        event.preventDefault();

        // Get some values from elements on the page:
        var $form = $(params.formID),
            data  = $form.serializeArray(),
            url   = $form.attr("action");

        // Verify is all fields of form are valid 
        if (!$form.parsley('isValid')) {
            return;
        }

        $.ajax({
            // all forms should have fieldMethod field. This field determina the method used to send form
            type:     $form.find("input[name=method]").val(),
            url:      url,
            data:     data,
            dataType: 'json',
            success: function (data) {
                if (data && data.message && data.message[0] && data.message[0].type) {
                    DSINConnect.parseJSONResponse(data.message, params);
                }
            }
        });
    });
};


function openModal(url, unloadCallback) {
    'use strict';

    var newModal = $(".baseToModal").first().clone();

    newModal.attr("id", url);
    newModal.appendTo("#mainAppContainer");


    newModal.on('hide.bs.modal', function () {
        if (unloadCallback && "function" === typeof unloadCallback) {
            unloadCallback();
        }
    });

    newModal.on('hidden.bs.modal', function () {
        this.remove();

        var hasBackDrop = false,
            backDropLength = $('.modal-backdrop').length - 1;

        $('.modal-backdrop').each(function (index) {
            if (backDropLength !== index) {
                $(this).css('zIndex', parseInt($(this).css('zIndex'), 10) + 5);
            } else {
                $(this).css('zIndex', '');
            }
            hasBackDrop = true;
        });

        if (hasBackDrop) {
            $('.baseToModal .modal').each(function (index) {
                if (index !== 0) {
                    if ($('.baseToModal .modal').length - 1 !== index) {
                        $(this).css('zIndex', parseInt($(this).css('zIndex'), 10) + 10);
                    } else {
                        $(this).css('zIndex', '');
                    }
                }
            });
        }
    });

    newModal.on('show.bs.modal', function () {
        var hasBackDrop = false,
            baseModalModalLength;
        $('.modal-backdrop').each(function () {
            $(this).css('zIndex', $(this).css('zIndex') - 5);
            hasBackDrop = true;
        });

        if (hasBackDrop) {
            baseModalModalLength = $('.baseToModal .modal').length;
            $('.baseToModal .modal').each(function (index) {
                if (index !== 0) {
                    if (baseModalModalLength - 1 !== index) {
                        $(this).css('zIndex', $(this).css('zIndex') - 10);
                    }
                }
            });
        }

    });
    newModal.find('.modal').modal({remote: url});
}

function loadFormValidation(idForm, idContainerMessage) {
    'use strict';

    $(idForm).parsley('destroy');
    $(idForm).parsley({
        errors: {
            container: function (element) {
                var $container = $(idContainerMessage);
                if ($container.length === 0) {
                    $container = $("<div id='' class='parsley-container'></div>").insertBefore(element);
                }
                return $container;
            }
        }
    });
}

function openPrintModal(formID) {
    'use strict';

    var html = '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
        '<h4 class="modal-title">&nbsp;</h4>' +
        '</div>' +
        '<div class="modal-body"><iframe name="printFrame" class="page-icon preview-pane" frameborder="0" height="100%" width="100%" style="left: 638px" src=""></iframe></div>' +
        '<div class="modal-footer"></div>' +
        '</div>' +
        '</div>',

        newModal = $(".baseToModal").first().clone();

    newModal.find('.modal').html(html)
        .find('.modal-dialog').css('width', ($(document).width() * 70) / 100)
        .find('.modal-body').css('height', ($(document).height() * 70) / 100);

    newModal.appendTo("#mainAppContainer");

    newModal.on('hidden.bs.modal', function () {
        this.remove();

        var hasBackDrop = false,
            backDropLength = $('.modal-backdrop').length - 1;

        $('.modal-backdrop').each(function (index) {
            if (backDropLength !== index) {
                $(this).css('zIndex', parseInt($(this).css('zIndex'), 10) + 5);
            } else {
                $(this).css('zIndex', '');
            }
            hasBackDrop = true;
        });

        if (hasBackDrop) {
            $('.baseToModal .modal').each(function (index) {
                if (index !== 0) {
                    if ($('.baseToModal .modal').length - 1 !== index) {
                        $(this).css('zIndex', parseInt($(this).css('zIndex'), 10) + 10);
                    } else {
                        $(this).css('zIndex', '');
                    }
                }
            });
        }
    });

    newModal.on('show.bs.modal', function () {
        var hasBackDrop = false,
            baseModalModalLength;
        $('.modal-backdrop').each(function () {
            $(this).css('zIndex', $(this).css('zIndex') - 5);
            hasBackDrop = true;
        });

        if (hasBackDrop) {
            baseModalModalLength = $('.baseToModal .modal').length;
            $('.baseToModal .modal').each(function (index) {
                if (index !== 0) {
                    if (baseModalModalLength - 1 !== index) {
                        $(this).css('zIndex', $(this).css('zIndex') - 10);
                    }
                }
            });
        }
        $(formID).submit();
    });
    newModal.find('.modal').modal();
}