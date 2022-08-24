function openModal(url) {
    $.ajax({
        url: url,
        success: function (data) {
            if(data.redirect) {
                alert(data.msg);
                location.href = data.redirect;
            } else {
                $(data).appendTo('#modals');
                $('#modals > .modal').eq(-1).addClass("js-modal-tbd").modal("show");
            }
        },
        error: function () {
            alert("Something has gone wrong");
        }
    });
}

$(document).ready(function () {
    $(document).on("hidden.bs.modal", ".modal", function () {
        let visibleModals = $('.modal:visible');
        // buttons unfocus
        $('.btn').blur();

        if (visibleModals.length) {
            $("html").addClass('modal-open');
            visibleModals[visibleModals.length - 1].focus();
        } else {
            $("html").removeClass('modal-open');
        }

        if ($(this).hasClass("js-modal-tbd")) {
            $(this).remove();
        }
    });


    $('body').on('beforeSubmit', 'form.js-fp', function () {
        return false;
    }).on('ajaxComplete', function (event, jqXHR) {
        if (jqXHR.responseJSON && ("saved" in jqXHR.responseJSON)) {
            if (jqXHR.responseJSON.close_modal) {
                $(event.target).closest(".modal.show").modal("hide");
            }
            if ($(event.target).data("cb"))
                window[$(event.target).data("cb")](jqXHR.responseJSON, event.target.id);
        }
    });
});
