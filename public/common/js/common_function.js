
(function ($) {
    "use strict";

    $(document).on('submit', "form.ajax-request", function (event) {
        event.preventDefault();
        var enctype = $(this).prop("enctype");
        if (!enctype) {
            enctype = "application/x-www-form-urlencoded";
        }

        $("form.ajax-request button[type='submit']").attr("disabled", "true");
        $("form.ajax-request button[type='submit']").prepend('<i class="fa fa-spinner fa-spin"></i> ');
        commonAjaxRequest($(this).prop('method'), $(this).prop('action'), window[$(this).data("handler")], window[$(this).data("handler")], new FormData($(this)[0]));
    });

    $(document).on("click", "a.delete", function () {
        const selector = $(this);
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: $(this).data("url"),
                    success: function (data) {
                        if (data.status == true) {
                            selector.closest('.removable-item').fadeOut('fast');
                            Swal.fire({
                                title: 'Deleted',
                                html: ' <span style="color:red">' + data.message + '</span> ',
                                timer: 2000,
                                icon: 'success'
                            })
                            location.reload()
                        } else {
                            Swal.fire({
                                title: 'Error',
                                html: ' <span style="color:red">' + data.message + '</span> ',
                                timer: 2000,
                                icon: 'error'
                            })
                        }
                    },
                    error: function (data) {
                        if (data.responseJSON.status == false) {
                            Swal.fire({
                                title: 'Error',
                                html: ' <span style="color:red">' + data.responseJSON.message + '</span> ',
                                timer: 2000,
                                icon: 'error'
                            })

                        }
                    }
                })
            }
        })
    });

    $(document).on("click", ".deleteItem", function () {
        let form_id = this.dataset.formid;
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $("#" + form_id).submit();
            } else if (result.dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Your imaginary file is safe :)",
                    "error"
                )
            }
        })
    });

    $(document).ready(function () {
        $(".multiple-basic-single").select2({
            placeholder: "Select Option",
        });

        $(".multiple-select-input").select2({
            tags: true,
            tokenSeparators: [','],
        })
    });

    window.dateFormat = function (date, format = 'MM-DD-YYYY') {
        return moment(date).format(format);
    }

    window.deleteCommonMethod = function (url, id, redirect_url=null) {
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        Swal.fire({
                            title: 'Deleted',
                            html: ' <span style="color:red">Item has been deleted</span> ',
                            timer: 2000,
                            icon: 'success'
                        })
                        toastr.success(data.message);
                        $('#' + id).DataTable().ajax.reload();
                        if(redirect_url){
                            window.location.href = redirect_url;
                        }
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.message)
                    }
                })
            }
        })
    }

    window.commonAjaxRequest = function (type, url, successHandler, errorHandler, data) {
        var ajaxData = {
            type: type,
            url: url,
            dataType: 'json',
            success: successHandler,
            error: errorHandler
        }
        if (typeof (data) != 'undefined') {
            ajaxData.data = data;
        }
        if (type == 'POST' || type == 'post') {
            ajaxData.encType = 'enctype';
            ajaxData.contentType = false;
            ajaxData.processData = false;
        }
        $.ajax(ajaxData);
    }

    window.commonResponseHandler = function (data) {
        var output = '';
        var type = 'error';
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (data['status'] == false) {
            output = output + data['message'];
        } else if (data['status'] === 422) {
            var errors = data['responseJSON']['errors'];
            output = getCommonValidationError(errors);
        } else if (data['status'] === 500) {
            output = data['responseJSON']['message'];
        } else if (typeof data['responseJSON']['error'] !== 'undefined') {
            output = data['responseJSON']['error'];
        } else {
            output = data['responseJSON']['message'];
        }
        alertCommonAjaxMessage(type, output);
    }

    window.alertCommonAjaxMessage = function (type, message) {
        if (type === 'success') {
            toastr.success(message);
        } else if (type === 'error') {
            toastr.error(message);
        } else if (type === 'warning') {
            toastr.error(message);
        } else {
            return false;
        }
    }

    window.editCommonModal = function (url, modalId) {
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $(document).find(modalId).find('.modal-content').html(data);

                if ($(document).find(modalId).find('.sf-select-edit-modal').length) {
                    $(document).find(modalId).find('.sf-select-edit-modal').select2({
                        dropdownCssClass: "sf-select-dropdown",
                        selectionCssClass: "sf-select-section",
                        dropdownParent: $(modalId),
                    });
                }

                if ($(document).find(modalId).find('.sf-select-without-search').length) {
                    $(document).find(modalId).find('.sf-select-without-search').niceSelect();
                }

                if ($(document).find(modalId).find('.date-time-picker').length) {
                    $(document).find(modalId).find('.date-time-picker').each(function () {
                        $(this).closest(".primary-form-group-wrap").addClass("calendarIcon"); // Add your custom class here
                    });
                }

                if ($(document).find(modalId).find('.date-time-picker').length) {
                    $(document).find(modalId).find('.date-time-picker').daterangepicker({
                        singleDatePicker: true,
                        timePicker: true,
                        locale: {
                            format: "Y-M-D h:mm",
                        },
                    });
                }

                if ($(document).find(modalId).find('.summernoteOne').length) {
                    $(document).find(modalId).find('.summernoteOne').summernote({
                        placeholder: "Write description...",
                        tabsize: 2,
                        minHeight: 183,
                        toolbar: [
                            // ["style", ["style"]],
                            // ["view", ["undo", "redo"]],
                            // ["fontname", ["fontname"]],
                            // ["fontsize", ["fontsize"]],
                            // ["font", ["bold", "italic", "underline"]],
                            // ["para", ["ul", "ol", "paragraph"]],
                            // ["color", ["color"]],
                            ["font", ["bold", "italic", "underline"]],
                            ["para", ["ul", "ol", "paragraph"]],
                        ],
                    });
                }

                $(modalId).modal('toggle');
            },
            error: function (error) {
                toastr.error(error.responseJSON.message)
            }
        })
    }

    window.commonResponse = function (response) {
        $("form.ajax-request .fa-spin").remove();
        $("form.ajax-request button[type='submit']").removeAttr("disabled");

        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message']);
            $(".reset").trigger("reset");
            if ($('.dataTable ').length) {
                $('.dataTable').DataTable().ajax.reload();
            }
            if ($('.modal.show').length) {
                $('.modal.show').modal('toggle');
            }
            if ($(document).find('form.reset').length) {
                $(document).find('form.reset')[0].reset();
                if ($('.summernoteOne')) {
                    $('.summernoteOne').summernote('reset');
                }
                if ($('.upload-img-box').find('img')) {
                    var url = window.location.origin+'/assets/images/no-image.jpg';
                    $('.upload-img-box').find('img.preview-img').attr('src', url);
                }
                if ($('.select2-hidden-accessible')) {
                    $('.select2-hidden-accessible').val(null).trigger('change');
                }
                if ($('.sf-select-without-search')) {
                    $('.sf-select-without-search').niceSelect('update');
                }
            }


        } else {
            commonResponseHandler(response)
        }
    }

    function visualNumberFormat(value) {
        try {
            if (value == null || value == undefined || isNaN(value) || value == '') {
                return '0.00';
            }
            value = parseFloat(value);
            if (Number.isInteger(value)) {
                return value.toFixed(2);
            }
            const temp = value.toFixed(8);
            const number = temp.split('.');
            let floatValue = number[1];
            floatValue = floatValue.toString();
            const result = floatValue.replace(/[0]+$/, '');
            if (result.length < 2) {
                return value.toFixed(2);
            }

            return `${number[0]}.${result}`;
        } catch (e) {
            return '';
        }
    }
    window.visualNumberFormat = visualNumberFormat;

    window.getCommonValidationError = function (errors) {
        var output = 'Validation Errors';
        $.each(errors, function (index, items) {
            if (index.indexOf('.') != -1) {
                var name = index.split('.');
                var getName = name.slice(0, -1).join('-');
                var i = name.slice(-1);
                var message = items[0];
                var itemSelect = $(document).find('.' + getName + ':eq(' + i + ')')
                itemSelect.addClass('is-invalid');
                itemSelect.closest('div').append('<span class="text-danger p-2 fs-12 z-index-10 position-relative error-message">' + message + '</span>')
            } else {
                var itemSelect = $(document).find("[name='" + index + "']");
                if (!itemSelect.length) {
                    itemSelect = $(document).find("[name^='" + index + "']");
                }
                itemSelect.addClass('is-invalid');
                itemSelect.closest('div').append('<span class="text-danger p-2 fs-12 z-index-10 position-relative error-message">' + items[0] + '</span>')
            }
        });
        return output;
    }


    window.responseWithPageLoad = function (response) {
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message'])

            setTimeout(() => {
                location.reload()
            }, 1000);

        } else {
            commonResponseHandler(response)
        }
    }

})(jQuery)
