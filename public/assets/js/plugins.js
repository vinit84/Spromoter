let spinnerSM = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;
const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': CSRF_TOKEN
    }
});

$.fn.initFormValidation = function () {
    let validator = $(this).validate({
        errorClass: 'is-invalid text-danger',
        highlight: function (element, errorClass) {
            let elem = $(element);
            if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
            } else if (elem.hasClass('input-group')) {
                $('#' + elem.add("id")).parents('.input-group').append(errorClass);
            } else if (elem.hasClass('chat-input')) {
                // Do nothing
            } else {
                elem.addClass(errorClass);
            }
        },
        unhighlight: function (element, errorClass) {
            let elem = $(element);
            if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
            } else {
                elem.removeClass(errorClass);
            }
        },
        errorPlacement: function (error, element) {
            let elem = $(element);
            if (elem.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + elem.attr("id") + "-container").parent();
                error.insertAfter(element);
            } else if (elem.parents().hasClass('iti--allow-dropdown')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('form-check')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('form-floating')) {
                error.insertAfter(element.parent().css('color', 'text-danger'));
            } else if (elem.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('custom-checkbox')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('message-form-footer')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $(this).on('select2:select', function () {
        if (!$.isEmptyObject(validator.submitted)) {
            validator.form();
        }
    });

    $.fn.initEmailMask = function () {
        new Inputmask({
            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
            greedy: false,
            onBeforePaste: function (pastedValue) {
                pastedValue = pastedValue.toLowerCase();
                return pastedValue.replace("mailto:", "");
            },
            definitions: {
                "*": {
                    validator: '[0-9A-Za-z!#$%&"*+/=?^_`{|}~\-]', cardinality: 1, casing: "lower"
                }
            }
        }).mask(this);
    };
};

let stopAjaxFormSubmit = false;
$.fn.initFormSubmit = function (callBack = drawDataTable) {
    $(this).initFormValidation();

    let $this = $(this);
    let insideForm = $this.find('button[type="submit"]');

    let submitButton = insideForm.length ? insideForm : $(document).find('button[form="' + $this.attr('id') + '"]');
    let submitButtonText = submitButton.html();

    $(this).ajaxForm({
        dataType: 'json',
        beforeSubmit: function (formData, $form, options) {
            submitButton.prop('disabled', true);
            submitButton.html(spinnerSM);

            $this.trigger('formSubmitBefore', [formData, $form, options]);
            $this.trigger('ajaxFormSubmitBefore', [formData, $form, options]);

            if (stopAjaxFormSubmit) {
                submitButton.prop('disabled', false);
                submitButton.html(submitButtonText);

                return false;
            }
        },
        success: function (response) {
            submitButton.prop('disabled', false);
            if (!response) {
                location.reload();
            }
            if (response.redirect) {
                //Save to local storage
                if (response.message) {
                    localStorage.setItem('messageType', response.status);
                    localStorage.setItem('message', response.message);
                    localStorage.setItem('hasMessage', response.hasMessage);
                }
                window.location.href = response.redirect;
            } else if (response.two_factor) {
                window.location.href = '/two-factor-challenge';
            } else if (response.silent) {
                // Do nothing
            } else {
                if (response.type === 'warning' || response.status === 'warning') {
                    flash('warning', response.message);
                } else if (response.message) {
                    flash('success', response.message);
                } else {
                    flash('success', 'Successfully saved');
                }
            }

            if (response.reset) {
                $this[0].reset();
            }


            submitButton.html(submitButtonText);
            // Fire event for success
            $this.trigger('formSubmitSuccess', [response]);
            $this.trigger('ajaxFormSuccess', [response]);
        },
        error: function (response) {
            submitButton.prop('disabled', false);
            if (response.status == 0) {
                submitButton.prop('disabled', false);
                submitButton.html(submitButtonText);

                flash('error', 'Device is offline. Cannot complete request.\'');
                return;
            }

            const errors = response?.responseJSON;
            if (errors.redirect) {
                //Save to local storage
                if (errors.message) {
                    localStorage.setItem('messageType', errors.status);
                    localStorage.setItem('message', errors.message);
                    localStorage.setItem('hasMessage', errors.hasMessage);
                }
                window.location.href = errors.redirect;
            }

            if (errors?.type === 'warning') {
                flash('warning', response.message || response?.responseJSON?.message || 'Something went wrong');
            } else {
                flash('error', response.message || response?.responseJSON?.message || 'Something went wrong');
            }
            showInputErrors(errors);
            submitButton.html(submitButtonText);

            $this.trigger('formSubmitError', [response]);
            $this.trigger('ajaxFormError', [response]);
        },
        complete: function () {
            submitButton.prop('disabled', false);

            // Fire event for complete
            $this.trigger('formSubmitComplete');
            $this.trigger('ajaxFormComplete');
        }
    });
};

/**
 * Initialize Show error to each input
 * @param {Array} errors
 */

let showInputErrors = function (errors) {
    if (typeof errors !== 'object') {
        return;
    }

    $.each(errors['errors'], function (index, value) {
        $('#' + index + '-error').remove();

        let elem = $('#' + index);

        if (elem.parent().hasClass('input-group')) {
            elem.addClass('is-invalid')
                .parent()
                .after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')

        } else if (elem.parent().hasClass('form-check')) {
            elem.addClass('is-invalid')
                .parent()
                .after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')

        } else if (elem.parent().hasClass('form-floating')) {
            elem.addClass('is-invalid')
                .parent()
                .after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')

        } else {
            elem.addClass('is-invalid')
                .after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')
        }
    });
}

let drawDataTable = function () {
    if (typeof $.fn.DataTable === 'undefined') {
        return;
    }
    $('.dataTable').DataTable().draw(false);
    $('.check-all').prop('checked', false);
}

$('.ajaxform').each(function () {
    $(this).initFormSubmit();
});

let flash = function (type, message) {
    flasher.flash(type, message, {
        timeout: 3000,
    });
}

function trans(key, replaces = null) {
    let translations = JSON.parse(localStorage.getItem('translations'));
    const locale = $('html').attr('lang');

    const expirationTime = localStorage.getItem('translationsExpirationTime');

    // Check if expiration time is expired
    if (expirationTime && expirationTime < new Date().getTime()) {
        localStorage.removeItem('translations');
        localStorage.removeItem('translationsExpirationTime');
        translations = null;
    }

    let notFoundKey = function (key) {
        for (const placeholder in replaces) {
            if (replaces.hasOwnProperty(placeholder)) {
                const value = replaces[placeholder];
                key = key.replace(':' + placeholder, value);
            }
        }

        return key;
    }

    // Check if translations is null
    if (!translations) {
        $.ajax({
            url: route('api.translations', {locale}),
            dataType: 'json',
            type: 'GET',
            success: function (translations) {
                const expirationTime = new Date().getTime() + (30 * 60 * 1000);
                localStorage.setItem('translationsExpirationTime', expirationTime.toString());

                localStorage.setItem('translations', JSON.stringify(translations));
            }
        });

        return getTranslation()
    } else {
        return getTranslation()
    }

    function getTranslation() {
        if (translations && translations.hasOwnProperty(key)) {
            for (const placeholder in replaces) {
                if (replaces.hasOwnProperty(placeholder)) {
                    const value = replaces[placeholder];
                    translations[key] = translations[key].replace(':' + placeholder, value);
                }
            }

            return translations[key];
        } else {
            return notFoundKey(key);
        }
    }
}



// -------------------------------------------- Start Select2 ------------------------------------------------------- //
let select2FocusFixInitialized = false;
let initSelect2 = function () {
    let elements = [].slice.call(document.querySelectorAll('[data-control="select2"]'));

    if (elements.length > 0) {
        if (typeof jQuery == 'undefined') {
            alert('Select2 requires jQuery to be included.');
        } else if (typeof $.fn.select2 === 'undefined') {
            alert('Select2 is not included.');
        } else{
            elements.map(function (element) {
                $(element).wrap('<div class="position-relative"></div>');

                let options = {
                    dir: document.body.getAttribute('direction')
                };

                options.dropdownParent = $(element).parent();

                if (element.getAttribute('data-hide-search')) {
                    options.minimumResultsForSearch = Infinity;
                }

                if (element.hasAttribute('data-placeholder')) {
                    options.placeholder = element.getAttribute('data-placeholder');
                }

                if (element.hasAttribute('data-tags')) {
                    options.tags = true;
                }

                $(element).select2(options);
            });

            if (select2FocusFixInitialized === false) {
                select2FocusFixInitialized = true;

                $(document).on('select2:open', function () {
                    let elements = document.querySelectorAll('.select2-container--open .select2-search__field');
                    if (elements.length > 0) {
                        elements[elements.length - 1].focus();
                    }
                });
            }
        }
    }
};

(function () {
    initSelect2();
})();
// --------------------------------------------- End Select2 -------------------------------------------------------- //



// -------------------------------------------- Start jQuery Confirm- ----------------------------------------------- //
//Confirm delete
$(document).on('click', 'a.confirm-delete', function (e) {
    e.preventDefault();

    let url = $(this).attr('href');
    let dataRemovableId = $(this).attr('data-removable-id');
    let dataRemovableElement = $(this).closest('div[data-removable]');

    let button = $(this);
    let oldButton = $(this).html();

    let title = $(this).data('title') || trans('Are you sure!');
    let text = $(this).data('text') || trans("You won't be able to revert this!");
    let confirmButtonText = $(this).data('confirm-button-text') || trans('Yes, delete it!');
    let cancelButtonText = $(this).data('cancel-button-text') || trans('No, cancel!');

    Swal.fire({
        title,
        text,
        confirmButtonText,
        cancelButtonText,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-label-danger me-3',
            cancelButton: 'btn btn-label-primary'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            button.html(spinnerSM)
            $.ajax({
                url: url,
                method: 'DELETE',
                accept: 'application/json',
                beforeSend: function () {
                    button.addClass('disabled');
                },
                success: function (response) {
                    if (response.redirect) {
                        //Save to local storage
                        localStorage.setItem('messageType', response.status);
                        localStorage.setItem('message', response.message);
                        localStorage.setItem('hasMessage', response.hasMessage);
                        window.location.href = response.redirect;
                        return;
                    }

                    flash(response.status, response.message);
                    drawDataTable();

                    button.trigger('confirmDeleteSuccess', [response]);

                    if (dataRemovableId !== undefined && dataRemovableElement !== undefined) {
                        $(dataRemovableElement).remove();
                    }
                },
                error: function (response) {
                    button.trigger('confirmDeleteError', [response]);
                    flash('error', response.message || response.responseJSON.message || 'Something went wrong');
                    button.removeClass('disabled');
                },
                complete: function () {
                    button.trigger('confirmDeleteComplete');
                    button.html(oldButton);
                    button.removeClass('disabled')
                }
            })
        }
    });
})

//Confirm action
$(document).on('click', 'a.confirm-action', function (e) {
    e.preventDefault();
    let $this = $(this);
    let url = $(this).attr('href');
    let method = $(this).attr('data-method') || 'POST';
    let icon = $(this).attr('data-icon') || 'ti ti-alert-triangle-triangle ti-xl';
    let title = $(this).attr('data-title') || $(this).attr('title') || trans('Are you sure!');
    let message = $(this).attr('data-message') || trans('Request cannot be undone!');
    let hasLoader = $(this).attr('data-has-loader') || false;
    let thisEl = $(this);
    let oldButton = $(this).html();

    let confirmIcon = $(this).attr('data-confirm-icon') || 'ti ti-check me-0 me-sm-1 ti-xs';
    let confirmText = `<i class="${confirmIcon} me-0 me-sm-1 ti-xs"></i> ${trans('Yes, do it!')}`

    let cancelIcon = $(this).attr('data-cancel-icon') || 'ti ti-x me-0 me-sm-1 ti-xs';
    let cancelText = `<i class="${cancelIcon} me-0 me-sm-1 ti-xs"></i> ${trans('No, cancel')}`

    Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                beforeSend: function (xhr, formData) {
                    if (hasLoader) {
                        thisEl.html(spinnerSM);
                    }

                    $this.trigger('confirmActionBefore', [formData]);
                },
                url: url,
                method: method,
                contentType: 'application/json',
                accept: 'application/json',
                success: function (response) {
                    if (response.redirect) {
                        //Save to local storage
                        localStorage.setItem('messageType', response.status);
                        localStorage.setItem('message', response.message);
                        localStorage.setItem('hasMessage', response.hasMessage);
                        window.location.href = response.redirect;
                        return;
                    }

                    flash(response.status, response.message);
                    drawDataTable();

                    $this.trigger('confirmActionSuccess', [response]);
                    thisEl.html(oldButton);

                    /*if (dataRemovableId !== undefined && dataRemovableElement !== undefined) {
                        $(dataRemovableElement).remove();
                    }*/
                },
                error: function (response) {
                    $this.trigger('confirmActionError', [response]);
                    flash('error', response.message || response.responseJSON.message || 'Something went wrong');
                    thisEl.html(oldButton);
                },
            })
        }
    });
})
// ************** End jQuery Confirm ************** //

// Show messages from local storage
$(document).ready(function () {
    let hasMessage = localStorage.getItem('hasMessage') || false;
    let message = localStorage.getItem('message') || null;
    let type = localStorage.getItem('messageType') || null;

    if (hasMessage && message !== null && type !== null) {
        flash(type, message);
        localStorage.removeItem('hasMessage');
        localStorage.removeItem('message');
        localStorage.removeItem('messageType');
    }
})


// -------------------------------------------- Start Check All ------------------------------------------------ //
// Check all checkbox
$(document).on('click', '.check-all', function () {
    let group = $(this).attr('data-group');

    let checkboxes = $('input[data-group-for="' + group + '"]');

    // Toggle all checkboxes
    checkboxes.prop('checked', $(this).prop('checked'));
});

// Check all checkboxes if all checkboxes are checked
$(document).on('change', 'input[data-group-for]', function () {
    let group = $(this).attr('data-group-for');

    let checkboxes = $('input[data-group-for="' + group + '"]');

    // Toggle all checkboxes
    if (checkboxes.length === checkboxes.filter(':checked').length) {
        $('.check-all[data-group="' + group + '"]').prop('checked', true);
    } else {
        $('.check-all[data-group="' + group + '"]').prop('checked', false);
    }
});
// --------------------------------------------- End Check All ------------------------------------------------- //



$(document).on('click', '.ajax-link', function (e) {
    e.preventDefault();

    let $this = $(this);
    let submitButton = $(this);
    let submitButtonText = submitButton.html();
    let url = $this.attr('href') || $this.data('url') || $this.attr('action');
    let method = $this.attr('method') || 'POST';

    submitButton.prop('disabled', true);
    submitButton.html(spinnerSM);

    $.ajax({
        url: url,
        method: method,
        success: function (response) {
            if (response.redirect) {
                //Save to local storage
                localStorage.setItem('messageType', response.status);
                localStorage.setItem('message', response.message);
                localStorage.setItem('hasMessage', response.hasMessage);
                window.location.href = response.redirect;
            } else {
                if (response.status === 'warning') {
                    flash('warning', response.message || response?.responseJSON?.message || trans('Operation successful'));
                } else {
                    flash('success', response.message || response?.responseJSON?.message || trans('Operation successful'));
                }
            }

            // Fire event for success
            $this.trigger('ajaxLinkSuccess', [response]);
        },
        error: function (response) {
            var errors = response?.responseJSON;
            if (errors.type === 'warning') {
                flash('warning', response.message || response?.responseJSON?.message || trans('Something went wrong'));
            } else {
                flash('error', response.message || response?.responseJSON?.message || trans('Something went wrong'));
            }
            showInputErrors(errors);
            submitButton.prop('disabled', false);
            submitButton.html(submitButtonText);

            $this.trigger('ajaxLinkError', response);
        },
        complete: function () {
            submitButton.prop('disabled', false);
            submitButton.html(submitButtonText);

            // Fire event for complete
            $this.trigger('ajaxLinkComplete');
        }
    });
})


function blockCard(element) {
    let $this = $(element);
    $this.block({
        message:
            '<div class="sk-fold sk-primary"><div class="sk-fold-cube"></div><div class="sk-fold-cube"></div><div class="sk-fold-cube"></div><div class="sk-fold-cube"></div></div><h5>LOADING...</h5>',
        css: {
            backgroundColor: 'transparent',
            border: '0'
        },
        overlayCSS: {
            backgroundColor: $('html').hasClass('dark-style') ? '#000' : '#fff',
            opacity: 0.55
        }
    });
}

function storeLocalMessage(status, message, hasMessage = true){
    localStorage.setItem('messageType', status);
    localStorage.setItem('message', message);
    localStorage.setItem('hasMessage', hasMessage);
}
