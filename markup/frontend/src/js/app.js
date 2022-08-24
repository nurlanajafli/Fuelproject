$(document).ready(function () {
    $(document).on('hide.bs.modal', '#work-orders-modal', function (event) {
        $('#workOrders').DataTable().ajax.reload();
    });
    $(document).on('hide.bs.modal', '#equipment-service', function (event) {
        $('#workOrderRes').DataTable().ajax.reload();
    });
});