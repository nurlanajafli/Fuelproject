$(document).ready(function () {
    $('body').on('click', '.modal-footer button.btn-primary, .js-submit', function () {
        var form;
        if ($(this).hasClass('js-submit')) {
            form = $('#' + $(this).attr('data-form-id'));
        } else {
            form = $('.modal-body form', $(this).parents('.modal-content'));
        }

        if (form.length) {
            var hidden = $('<input type="hidden" name="_btn" value="' + $(this).attr('data-id') + '">').appendTo(form);
            $(form).yiiActiveForm('submitForm');
            hidden.remove();
        }
    });

    $('body').on('input clear', 'input.dropdown-toggle', function () {
        $('table[data-input=' + $(this).attr('id') + ']').DataTable().search(this.value).draw();
    });

    _document.on('afterValidateAttribute', 'form', function (event, attribute, messages) {
        if ($(attribute.input).hasClass('js-tdd-hidden')) {
            var ti = $('input[type=text]', $(attribute.input).parent());
            ti.parents('.form-group').removeClass('has-error has-success').addClass(messages.length ? 'has-error' : 'has-success');
            $('.help-block-error', ti.parent()).text(messages.length ? messages[0] : '');
        }
    });

    _document.on('afterValidate', 'form', function () {
        if ($('ul.nav-tags', this) && jQuery('.has-error', this).length) {
            jQuery('ul.nav-tabs>li.active, .tab-content>.tab-pane.active', this).removeClass('active');
            jQuery('ul.nav-tabs>li:eq(' + jQuery('.has-error', this).first().parents('.tab-pane').index() + ')', this).addClass('active');
            jQuery('.has-error', this).first().parents('.tab-pane').addClass('active');
        }
    });

    $("#setting-save").click(function () {
        let form = $('<form method="post" action="' + $(this).data("action") + '" class="hidden"></form>');
        $(".panel-body form").each(function (index) {
            $(this).yiiActiveForm("validate", true);
            $("input", $(this)).each(function () {
                if ((index == 0) || ($(this).attr("name").indexOf("[") != -1)) {
                    form.append($(this).clone().removeAttr("id").attr("name", $(this).attr("name").replace("[", "[" + index + "][")));
                }
            });
        });
        if ($(".panel-body .form-group.has-error").length == 0) {
            $("body").append(form);
            form.trigger("submit");
            setTimeout(function () {
                form.remove();
            }, 3000);
        }
    });

    $("form.prevent-submit").on("beforeSubmit", function () {
        return false;
    });

    $(".sidebar-toggle").on(_touchTap, function () {
        Cookies.set('sidebar-toggled', !$('body').hasClass('sidebar-collapse'), {
            domain: window.location.host,
            expires: 365
        });
    });

    $(document).on("select.dt", "#users", function (e, dt, types, indexes) {
        let url = $(this).data("url");
        dt.rows({selected: true}).every(function () {
            let id = this.data()[0];
            $("#userPermissionsForm input[type=text]").val(id);
            $("#userPermissionsForm select option:selected").prop("selected", false);
            $("#userPermissions").DataTable().ajax.url(url.replace("%7Bid%7D", id)).load(function () {
                $("#userPermissions").DataTable().rows().every(function (index) {
                    if (this.data()[3]) {
                        $("#userPermissions").DataTable().row(index).select();
                        $("#userPermissionsForm select option[value=\"" + this.data()[2] + "\"]").prop("selected", true);
                    }
                });
            });
        });
    });

    $(document).on("select.dt deselect.dt", "#userPermissions", function (e, dt, types, indexes) {
        $("#userPermissionsForm select option:selected").prop("selected", false);
        dt.rows({selected: true}).every(function () {
            $("#userPermissionsForm select option[value=\"" + this.data()[2] + "\"]").prop("selected", true);
        });
    });

    $("#userPermissionsForm").on("beforeSubmit", function () {
        if ($("#users").DataTable().rows({selected: true}).ids().length) {
            let url = $(this).attr("action");
            let fd = new FormData(this);
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#userPermissions__message').html('<div class="alert alert-' + (response.status == 'ok' ? 'success' : 'danger') + '" role="alert">' + (response.status == 'ok' ? 'Saved' : 'Internal Server Error') + '</div>');
                    setTimeout(function () {
                        $('#userPermissions__message').html('');
                    }, 2000);
                },
                error: function () {
                    console.log("error");
                }
            });
        }
    });
});

if (jQuery('.has-error').length) {
    jQuery('ul.nav-tabs>li.active, .tab-content>.tab-pane.active', jQuery('.has-error').first().parents('form')).removeClass('active');
    jQuery('ul.nav-tabs>li:eq(' + jQuery('.has-error').first().parents('.tab-pane').index() + ')', jQuery('.has-error').first().parents('form')).addClass('active');
    jQuery('.has-error').first().parents('.tab-pane').addClass('active');
}
