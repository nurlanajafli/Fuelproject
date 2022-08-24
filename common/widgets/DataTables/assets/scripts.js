function dtSelectAll() {
    this.rows().select();
}
function dtDeselectAll() {
    this.rows().deselect();
}
$(document).on("dblclick", ".js-datatable tbody tr", function () {
    let t = $(this).parents(".js-datatable").DataTable();
    let row = $(t.row(this).node());
    if (row.data("modal-url")) {
        openModal(row.data("modal-url"));
    }
});