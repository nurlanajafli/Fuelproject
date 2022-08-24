function tddRowClick(table, condition) {
    let p = table.DataTable().page();
    let l = table.DataTable().page.len();
    let cond = "";
    condition.forEach(element => cond = cond + "[" + element[0] + "=" + element[1] + "]");
    table.DataTable().page.len(-1).draw();
    setTimeout(function () {
        if ($("tbody tr" + cond, table).length) {
            $("tbody tr" + cond + " td:first-child", table).trigger(_touchTap);
        } else {
            $(".js-reset-btn", table.prev()).trigger("click");
        }
        table.DataTable().page.len(l).page(p).draw('page');
    }, 500);
}

$(document).ready(function () {
    $("body").on(_touchTap, ".js-dd tbody td", function () {
        let table = $(this).parents('table.js-datatable');
        let row = table.DataTable().row($(this).closest('tr')).data();
        let destConf = table.data("dest");
        let destInputSelector = destConf;
        let formName = table.data("form-name").toLowerCase();
        if (destConf === undefined) return false;
        if (typeof destConf == "object") {
            for (let attribute in destConf.attributes) {
                let id = formName + "-" + attribute.toLowerCase();
                $("#" + id).val("");
                if (row[destConf.i] == destConf.attributes[attribute])
                    destInputSelector = id;
            }
        }
        $("#" + table.data("input")).val(row[parseInt(table.data("display"))]);
        $("#" + destInputSelector).val(row[parseInt(table.data("lookup"))]).trigger("change");
        if (table.data("callback")) {
            window[table.data("callback")](row);
        }
        $(this).parents(".dropdown-menu--table").removeClass("show");
    });
});