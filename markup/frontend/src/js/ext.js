function setVal(selector, value) {
    $(selector).val(value).trigger("change");
}

function loadStopCompanyChanged(rowData) {
    $("#location_directions").text(rowData[8]);
    $("#location_notes").text(rowData[9]);
    setVal("#loadstop-address", rowData[2]);
    setVal("#loadstop-city", rowData[3]);
    setVal("#loadstop-state_id", rowData[5]);
    setVal("#loadstop-zip", rowData[6]);
    setVal("#location-address", rowData[2]);
    setVal("#location-city", rowData[3]);
    setVal("#location-state_id", rowData[5]);
    setVal("#location-zip", rowData[6]);
    setVal("#location-lat", '');
    setVal("#location-lon", '');
    $(".locationMap").addClass('d-none');
    $(".field-loadstop-company_id input[type='text']").css('opacity','1');
}

function locationBillToChanged(rowData) {
    setVal("#location-address", rowData[2]);
    setVal("#location-city", rowData[3]);
    setVal("#location-state_id", rowData[4]);
    setVal("#location-zip", rowData[5]);
}

function adjCodeChanged(row) {
    let type = 0;
    let id = 0;
    if (row[0]) {
        id = row[0];
    }
    if (row[1]) {
        type = 1;
        id = row[1];
    }
    if (row[2]) {
        type = 2;
        id = row[2];
    }
    tddRowClick($("#dadj-post-to"), [["data-type", type], ["data-id", id]]);
    tddRowClick($("#dadj-account"), [["data-account", row[4]]]);
    setVal("#driveradjustment-amount", row[6]);
}

function payrollAdjCodeChanged(row) {
    let type = 0;
    let id = 0;
    if (row[0]) {
        id = row[0];
    }
    if (row[1]) {
        type = 1;
        id = row[1];
    }
    if (row[2]) {
        type = 2;
        id = row[2];
    }
    tddRowClick($("#padj-post-to"), [["data-type", type], ["data-id", id]]);
    tddRowClick($("#padj-account"), [["data-account", row[4]]]);
    setVal("#payrolladjustment-d_amount", row[6]);
}

function dadjPostToAttrs(row) {
    return {
        "data-type": row[0],
        "data-id": row[1]
    };
}

function padjPostToAttrs(row) {
    return {
        "data-type": row[0],
        "data-id": row[1]
    };
}

function dadjAccountAttrs(row) {
    return {"data-account": row[0]};
}

function padjAccountAttrs(row) {
    return {"data-account": row[0]};
}

function loadClearChangeLabels(response, formId) {
    $("#" + formId + " div.col-2.border-left-secondary>.mb-1 p.form-readonly-text").text(
        $("#" + formId + " div.col-2.border-left-secondary").data("n" + (response.load_cleared + 0))
    );
    $("#" + formId + " div.col-2.border-left-secondary>.text-center:nth-child(2) p.form-readonly-text").text(
        $("#" + formId + " div.col-2.border-left-secondary").data("n" + (response.backup_cleared + 0))
    );
}

function dtSetIDs(dt, dropdownList) {
    if (!dt.$("tr.js-tbs").length) {
        $(dropdownList + " option:selected").prop("selected", false);
        dt.rows({selected: true}).every(function () {
            let data = this.data();
            $(dropdownList + " option[value=" + data[0] + "]").prop("selected", true);
        });
    }
}

function payrollPayCalcSum(dt, listedOnly) {
    $("#importdispatchpay-sum tr:nth-child(2) td:nth-child(2)").text(dt.column(7).data().sum());
    $("#importdispatchpay-sum tr:nth-child(2) td:nth-child(3)").text(dt.column(8).data().sum());
    $("#importdispatchpay-sum tr:nth-child(2) td:nth-child(4)").text(dt.column(11).data().sum());

    if (!listedOnly) {
        let selRows = dt.rows({selected: true});
        $("#importdispatchpay-sum tr:last-child td:nth-child(2)").text(selRows.data().pluck(7).sum());
        $("#importdispatchpay-sum tr:last-child td:nth-child(3)").text(selRows.data().pluck(8).sum());
        $("#importdispatchpay-sum tr:last-child td:nth-child(4)").text(selRows.data().pluck(11).sum());
    }
}

function padjFormCb(resp) {
    $("#pay-calculator-outcome").html(resp.outcome);
    $("#padj-dt").DataTable().ajax.reload();
    $("#dt-payroll").DataTable().ajax.reload();
}

function payrollPayCalcImportCb(resp) {
    $("#pay-calculator-outcome").html(resp.outcome);
    $("#dt-payroll").DataTable().ajax.reload();
}

function payrollPrintBtnClick() {
    $("#payroll-print-btn").trigger(_touchTap);
}

function documentUploadOK() {
    $(".js-documents-table").DataTable().ajax.reload();
}

function automaticAdjReload() {
    $("#automatic-adjustments").DataTable().ajax.reload();
}

function automaticNoteListReload() {
    $("#automatic-note-list").DataTable().ajax.reload();
}

function unitsetlctn(resp, formid) {
    if ("show_modal" in resp) {
        openModal($("#" + formid).data("set-location"));
    }
}

function unitsetlctn2(resp, formid) {
    $(".lctnhid").remove();
    for (k in resp.SetLocation) {
        $("#Unit").append('<input type="hidden" class="lctnhid" name="SetLocation[' + k + ']" value="' + resp.SetLocation[k] + '">');
    }
    $("#Unit .js-submit").trigger("click");
}

function displfunitch(row) {
    $("#dispatchassignment-driver_id").val(row[2]);
    $("#dispatchassignment-driver_pay_type").val(row[11]);
    $("#dispatchassignment-driver_loaded_rate").val(row[12]);
    $("#dispatchassignment-driver_empty_rate").val(row[13]);
    $("#dispatchassignment-driver_percent").val(row[14]);
    $("#dispatchassignment-codriver_id").val(row[4]);
    $("#dispatchload-codriver").removeClass('invisible');
    if (!row[4]) $("#dispatchload-codriver").addClass('invisible');
    $("#dispatchassignment-truck_id").val(row[6]);
    $("#dispatchassignment-trailer_id").val(row[8]);
    $("#dispatchassignment-trailer2_id").val(row[10]).trigger("change");
}

function rateloadmxnuch(r) {
    $('#rateLoadRateBy option').show();
    $('#rateLoadRateBy option:not(:first)').not('.' + r[3]).hide();
    $("#rateLoadRateBy").val(r[2]);
    $("#rateLoadRate").val(r[4]);
    updateRate();
}

function toggleMatrixNumber() {
    if (issetElement('select#rate-source')) {
        var rateSourceValue = $('select#rate-source').val().split(' ').join('');
        if (rateSourceValue != 'LoadMatrix') {
            $('#rate-matrixnumber').closest('.col-6').hide();
        } else {
            $('#rate-matrixnumber').closest('.col-6').show();
        }
    }
}

function fuelPurchaseOnUnitChange(rowData) {
    $("#fuelpurchase-driver_id").val(rowData[1]);
    $("#fuelpurchase-codriver_id").val(rowData[2]);
    tddRowClick($("#fuelpurchase-truck_id-dt"), {0: rowData[6]});
    tddRowClick($("#fuelpurchase-trailer_id-dt"), {0: rowData[7]});
    tddRowClick($("#fuelpurchase-trailer2_id-dt"), {0: rowData[8]});
}

function fuelPurchaseReloadOnClick() {
    $("#fuelpurchase-index").DataTable().ajax.reload();
}

function refreshSetupProductCodes() {
    $("#setup-product-codes-table").DataTable().ajax.reload();
}

function billsRefresh() {
    $("#bill-listing").DataTable().ajax.reload();
}

function billsRowAttrs(data) {
    return {"data-id": data[0]};
}

_document.ready(function () {
    toggleMatrixNumber();

    _document.on('pjax:send', function() {
        $('.preloader').removeClass('d-none');
        $('.preloader').addClass('d-flex');
        $('#dispatchload-form').css('opacity','0.65');
    });
    _document.on('pjax:complete', function() {
        $('.preloader').removeClass('d-flex');
        $('.preloader').addClass('d-none');
        $('#dispatchload-form').css('opacity','1');
    });

    _document.on('change', 'select#rate-source', function () {

        $('#rateLoadRateBy option[selected]').removeAttr('selected');
        $('#rateLoadRateBy option:first').attr('selected', 'selected');
        $('#rateLoadRate').val('');

        $('#rate-matrixnumber').parent().find('input').val('');

        var trimmedValue = $(this).val().split(' ').join('');
        if (trimmedValue != 'LoadMatrix') {
            $('#rate-matrixnumber').closest('.col-6').hide();
        } else {
            $('#rate-matrixnumber').closest('.col-6').show();
        }
        $('#rateLoadRateBy option').show();
        $('#rateLoadRateBy option:not(:first)').not('.' + trimmedValue).hide();
    });

    _document.on('change', '#loadstop-available_from', function () {
        from_val = $(this).val();
        thru_val = $("#loadstop-available_thru").val();
        if (from_val > thru_val)
            $("#loadstop-available_thru").val(from_val);
    });

    _document.on('change', '#loadstop-available_thru', function () {
        thru_val = $(this).val();
        from_val = $("#loadstop-available_from").val();
        if (thru_val < from_val)
            $("#loadstop-available_from").val(thru_val);
    });

    _document.on(_touchTap, '#sidebarToggle', function () {
        Cookies.set('accordion-toggled', $('#accordionSidebar').hasClass('toggled'), {
            domain: window.location.host,
            expires: 365
        });
    });

    $("body").on("click", ".modal-footer button[data-code], .js-submit", function () {
        let target = $(".modal-body form", $(this).closest(".modal-content"));

        thisId = '';
        if(typeof($(this)[0]['id']) != "undefined" && $(this)[0]['id'] !== null) {
            thisId = $(this)[0]['id'];
        }

        if (target.length == 0) {
            target = $(this).closest("form");
        }

        $('<input type="hidden" name="SubmitButtonCode" value="' + $(this).data("code") + '">').appendTo(target);

        if(thisId == 'payroll-print-btn') {
            target[0].submit();
        } else {
            target.trigger("submit");
        }

        setTimeout(function () {
            $("input[name=SubmitButtonCode]", target).remove();
        }, 1500);
    });

    $('body').on('input clear', 'input.dropdown-toggle', function () {
        $('table[data-input=' + $(this).attr('id') + ']').DataTable().search(this.value).draw();
    });

    _document.on(_touchTap, '.js-location-update', function (e) {
        e.preventDefault();
        if ($('.location-map__option.active').length) {
            var fieldValue = '';
            var oldAddress = '';
            var stateFound;
            $.each(['address', 'city', 'state_id', 'zip'], function (index, value) {
                fieldValue = $('#location-' + value).val();
                if (value == 'state_id') {
                    stateFound = false;
                    $.each(states, function (index, value) {
                        if (value.id == fieldValue) {
                            fieldValue = value.stateAbbreviation;
                            stateFound = true;
                        }
                    });
                    if (!stateFound) {
                        fieldValue = '';
                    }
                }
                if (fieldValue.length) {
                    if (oldAddress.length) {
                        oldAddress = oldAddress + ', ' + fieldValue;
                    } else {
                        oldAddress = fieldValue;
                    }
                }
            });
            $('.location-confirm-old').text(oldAddress);
            var d = JSON.parse($('.location-map__option.active').attr('data-response'));
            $('.location-confirm-new').text(d.address);
            $('.location-confirm-modal').modal('show');
        }
    });

    _document.on(_touchTap, '.js-location-modal-yes', function () {
        if ($('.location-map__option.active').length) {
            var d = JSON.parse($('.location-map__option.active').attr('data-response'));
            var field = '';
            $.each(['time_zone', 'city', 'zip', 'street_address', 'lat', 'lon'], function (index, value) {
                field = value;
                if (value == 'street_address') {
                    field = 'address';
                }
                $('#location-' + field).val(d[value]).trigger("change");
            });
            $("#loadstop-company_id").val('');
            $("#field-loadstop-company_id input[type='text']").val('');
            //$(".field-loadstop-company_id input[type='text']").css('opacity','0.65');
            $(".field-loadstop-company_id").addClass('d-none');
            $(".location-company_name").removeClass('d-none');
            $(".field-loadstop-company_id input[type='text']").val('');
            $.each(states, function (index, value) {
                if (d.country_abbreviation == value.countryAbbreviation && d.state_abbreviation == value.stateAbbreviation) {
                    $('#location-state_id').val(value.id).trigger('change');
                }
            });
            if (!$('.location-map__option.active').hasClass('js-exact-location')) {
                $('.location-map__option.js-exact-location').attr('data-response', $('.location-map__option.active').attr('data-response'));
            }
            $('.location-confirm-modal').modal('hide');
            $('.location-map__panel .alert').show();
            setTimeout(function () {
                $('.location-map__panel .alert').hide();
            }, 3000);
        } else {
            $('.location-confirm-modal').modal('hide');
        }
    });

    _document.on(_touchTap, '.js-suggested-address', function () {
        var query = $('#location-address').val();
        var queryZip = $('#location-zip').val();
        $(".locationMap").removeClass('d-none');
        if (query.length != 0 || queryZip.length != 0) {
            if(query.length == 0) {
                query = queryZip;
            }
            var params = 'query=' + query;
            var i = $('#location-state_id').val();
            if (i) {
                $.each(states, function (index, value) {
                    if (value.id == i) {
                        params = params + '&countries=' + value.countryAbbreviation;
                        params = params + '&states=' + value.stateAbbreviation;
                    }
                });
            }
            if(queryZip) {
                params = params + '&postcode=' + queryZip;
            }
            var city = $("#location-city").val();
            if(city) {
                params = params + '&city='+city;
            }

            if (typeof saRequest != 'undefined') {
                saRequest.abort();
            }
            $(".location-map").css('opacity','0.6');
            saRequest = suggestedAddress(params);
        }
    });

    _document.on(_touchTap, '.location-map__option', function () {
        $('.location-map__option.active').removeClass('active');
        $(this).addClass('active');
        var d = JSON.parse($(this).data('response'));
        setMarker([d.lon, d.lat]);
    });

    _document.on('afterValidateAttribute', 'form', function (event, attribute, messages) {
        if ($(attribute.input).hasClass('js-tdd-hidden')) {
            var ti = $('input[type=text]', $(attribute.input).parent());
            ti.removeClass('is-valid is-invalid').addClass(messages.length ? 'is-invalid' : 'is-valid');
            $('.invalid-feedback', ti.parent()).text(messages.length ? messages[0] : '');
        }
        if (attribute.id == "upload-fakefile") {
            $(".custom-file-container__custom-file__custom-file-control").removeClass("border-danger");
            if (messages.length) {
                $(".custom-file-container__custom-file__custom-file-control").addClass("border-danger");
            }
        }
    });

    _document.on(_touchTap, '.js-documents-table tbody td', function () {
        var r = $(this).parents('table.js-datatable').DataTable().row($(this).closest('tr')).data();
        if (r != undefined) {
            if (/\.pdf$/m.test(r[3])) {
                $(this).parents('.col-3').next().html('<iframe src="' + r[3] + '" style="width:100%;height:700px;border:none;"></iframe>');
            } else {
                $(this).parents('.col-3').next().html('<img src="' + r[3] + '">');
            }
        }
    });

    _document.on('ajaxBeforeSend', 'form.custom-file-container', function (event, jqXHR, settings) {
        if ((settings.url.indexOf("js_skip") == -1) && $("#document-file")[0].files.length) {
            jqXHR.abort();
            settings.cache = false;
            settings.contentType = false;
            settings.processData = false;
            settings.data = new FormData(this);
            settings.url = settings.url + "&js_skip=1";
            jqXHR = $.ajax(settings);
        }
    });

    _document.on(_touchTap, ".js-clear-load-tb .js-set", function () {
        $(".js-checkbox-group-1 input[type=checkbox]").prop("checked", true).trigger("change");
    });

    _document.on(_touchTap, ".js-clear-load-tb .js-unset", function () {
        $(".js-checkbox-group-1 input[type=checkbox]").prop("checked", false).trigger("change");
    });

    _document.on(_touchTap, ".js-clear-load-tb .js-undo", function () {
        $(".js-checkbox-group-1 input[type=checkbox][data-init]").each(function () {
            $(this).prop("checked", $(this).data("init") == 1).trigger("change");
        });
    });

    $("#dt-payroll-batch").DataTable().on("select", function (e, dt, type, indexes) {
        let row = dt.row(indexes[0]).data();
        $("#payroll-batch-details .row .col:last-child p:not(.js-ignore)").each(function (index) {
            $(this).text(row[index + 10]);
        });
        $("#dt-payroll").DataTable().ajax.url(row[19]).load();
        $("#print-1").data("url", row[20]);
        $("#batch-1").data("url", row[21]);
    });

    _document.on("select.dt", "#dt-payroll-for", function (e, dt, type, indexes) {
        dtSetIDs(dt, "#edit-ids");
    });

    _document.on("deselect.dt", "#dt-payroll-for", function (e, dt, type, indexes) {
        dtSetIDs(dt, "#edit-ids");
    });

    _document.on("select.dt", "#dt-payroll-pay", function (e, dt, type, indexes) {
        dtSetIDs(dt, "#importdispatchpay-ids");
        payrollPayCalcSum(dt, false);
    });

    _document.on("deselect.dt", "#dt-payroll-pay", function (e, dt, type, indexes) {
        dtSetIDs(dt, "#importdispatchpay-ids");
        payrollPayCalcSum(dt, false);
    });

    _document.on("select.dt", "#dt-payroll-print", function (e, dt, type, indexes) {
        dtSetIDs(dt, "#printsettlements-ids");
    });

    _document.on("deselect.dt", "#dt-payroll-print", function (e, dt, type, indexes) {
        dtSetIDs(dt, "#printsettlements-ids");
    });

    _document.on("change", "#payroll-print-radio__wrapper input[type=radio]", function () {
        $("#payroll-print-toolbar__wrapper > form").hide();
        $("#payroll-print-toolbar__wrapper > form").eq($("#payroll-print-radio__wrapper input[type=radio]:checked").parent().index()).show().trigger("submit");
    });

    _document.on("change", "#payroll-print-toolbar__wrapper select, #payroll-print-toolbar__wrapper input", function () {
        $(this).closest('form').trigger("submit");
    });

    _document.on("change", "#payroll-batchsummary-toolbar__wrapper select", function () {
        $(this).closest('form').trigger("submit");
    });

    _document.on("submit", "#payroll-print-toolbar__wrapper > form", function (e) {
        e.preventDefault();
        $("#dt-payroll-print").css('opacity','0.6');
        $("#dt-payroll-print").DataTable().ajax.url(
            $("#payroll-print-toolbar__wrapper").data("url") + "?" + new URLSearchParams(
            new FormData(
                this
            )
            ).toString()
        ).load(function () {
            $("#dt-payroll-print").css('opacity','1');
        });
    });

    _document.on("submit", "#payroll-batchsummary-toolbar__wrapper > form", function (e) {
        e.preventDefault();
        $("#dt-payroll-batchsummary").css('opacity','0.6');
        $("#dt-payroll-batchsummary").DataTable().ajax.url(
            $("#payroll-batchsummary-toolbar__wrapper").data("url") + "?" + new URLSearchParams(
            new FormData(
                this
            )
            ).toString()
        ).load(function(){
            $("#dt-payroll-batchsummary").css('opacity','1');
            tempUrl = $("#exportUrl").data('tempurl');
            fromVal = $("#dt-payroll-batchsummary tbody > tr:first-child td.fromCell").text();
            toVal = $("#dt-payroll-batchsummary tbody > tr:first-child td.toCell").text();
            checkDateVal = $("#dt-payroll-batchsummary tbody > tr:first-child td.checkDateCell").text();
            batchNoVal = $("#dt-payroll-batchsummary tbody > tr:first-child td.batchNoCell").text();
            typeVal = $("#dt-payroll-batchsummary tbody > tr:first-child td.typeCell").text();
            $("span.fromData").empty().append(fromVal);
            $("span.toData").empty().append(toVal);
            $("span.checkDate").empty().append(checkDateVal);
            $("span.bNo").empty().append(batchNoVal);
            $("span.bType").empty().append(typeVal);
            $("#exportUrl").data('url',tempUrl + '?id=' + batchNoVal);
            $("#exportUrl").attr('data-url',tempUrl + '?id=' + batchNoVal);
        });
    });

    _document.on("keyup", "#payroll-total_hours", function () {
        $("#payroll-ot_hours").val($(this).val());
    });

    _document.on("ajaxComplete", "form#payroll-calculator-form", function (event, jqXHR) {
        if (jqXHR.responseJSON && ("fpa_fields" in jqXHR.responseJSON)) {
            $("#pay-calculator-outcome").html(jqXHR.responseJSON.fpa_fields.outcome);
            $("#payroll-st_rate-c").text(jqXHR.responseJSON.fpa_fields.ap);
            $("#payroll-ot_rate-c").text(jqXHR.responseJSON.fpa_fields.bp);
            $("#payroll-ot_2_rate-c").text(jqXHR.responseJSON.fpa_fields.cp);
            $("#payroll-total_hourly-c").text(jqXHR.responseJSON.fpa_fields.dp);
        }
    });

    _document.on(_touchTap, "#closeNotifications", function () {
        Cookies.set("hide-notifications", true, {
            domain: window.location.host,
            expires: 14
        });
    });

    if (!Cookies.get('hide-notifications') && $("#notificationsDropdown .badge").length) {
        $("#notificationsDropdown > a").trigger(_touchTap);
    }

    $("#notificationsDropdown > a").on(_touchTap, function () {
        if (!$("#notificationsDropdown").hasClass("show"))
            Cookies.set("hide-notifications", false, {
                domain: window.location.host,
                expires: -1
            });
    });

    _document.on("pjax:success", function () {
        initDataTable();
    });

    _document.on("change.yiiActiveForm", "#dispatchload-form :input", function () {
        if ($(this).attr("id") != "dispatchassignment-unit_id") $(this).closest("form").trigger("submit");
    });

    _document.on(_touchTap, "#fuelpurchase-reload", fuelPurchaseReloadOnClick);

    $(".fuelpurchase-datefilter").dtDateTime({format: "MM/DD/YYYY"});

    _document.on("change", ".fuelpurchase-datefilter", function () {
        let url = $(this).parent().data("url");
        url = url.replace("value1", $(".fuelpurchase-datefilter:eq(0)").val());
        url = url.replace("value2", $(".fuelpurchase-datefilter:eq(1)").val());
        $("#fuelpurchase-index").DataTable().ajax.url(url).load();
    });

    _document.on(_touchTap, "#fuelpurchase-datepatterns .dropdown-item", function () {
        $(".fuelpurchase-datefilter:eq(0)").val($(this).data("value1"));
        $(".fuelpurchase-datefilter:eq(1)").val($(this).data("value2"));
        $(".fuelpurchase-datefilter:eq(1)").trigger("change");
    });

    _document.on("change.yiiActiveForm", "#bill-adjustbalance :input", function () {
        $(this).closest("form").trigger("submit");
    });

    _document.on(_touchTap, ".js-bill-delete", function () {
        if (confirm($(this).data("confirm-text"))) {
            console.log("hmm");
        }
    });

    // coDriver percent
    _document.on('change','#driver-co_driver_id',function () {
        _thisVal = $(this).val();
        if(_thisVal || _thisVal > 0) {
            $(".coDriver_percent").removeClass('d-none');
        } else {
            $(".coDriver_percent").addClass('d-none');
        }
    });
});

$.contextMenu({
    selector: '#available-loads tbody td',
    items: {
        'Dispatch Load': {
            name: 'Dispatch Load',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("dispatch").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        'Dispatch Summary': {
            name: 'Dispatch Summary',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("summary").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        '-': '-',
        'Reserve Load': {
            name: 'Reserve Load',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("reserve").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        }
    }
});

$.contextMenu({
    selector: '#enroute-loads tbody td',
    items: {
        'Arrive Load': {
            name: 'Arrive Load',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("arrive").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        'Edit Dispatch': {
            name: 'Edit Dispatch',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("dispatch").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        'Dispatch Summary': {
            name: 'Dispatch Summary',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("summary").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        '-': '-',
        'Send Message': {
            name: 'Send Message',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("message").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        }
    }
});

$.contextMenu({
    selector: '#fuel tbody td',
    items: {
        'edit': {
            name: 'Fuel Edit',
            //icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("edit").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        'delete': {
            name: 'Fuel Delete',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("delete").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
    }
});

$.contextMenu({
    selector: '#billing-loads tbody td',
    items: {
        'Load Booking': {
            name: 'Load Booking',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                window.location.href = opt.$trigger.closest("table").data("booking").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]);
                return true;
            }
        },
        'Load Clearing': {
            name: 'Load Clearing',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("clearing").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        'Dispatch Summary': {
            name: 'Dispatch Summary',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                openModal(opt.$trigger.closest("table").data("summary").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]));
                return true;
            }
        },
        'Hold Billing': {
            name: 'Hold Billing',
            // icon: 'fas fa-exclamation',
            callback: function (itemKey, opt, e) {
                if (confirm("Are you really want to change status for hold billing ?"))
                    $.get(
                        opt.$trigger.closest("table").data("hold").replace("%7Bid%7D", opt.$trigger.closest("table").DataTable().row(opt.$trigger.closest("tr")).data()[0]),
                        function () {
                            window.location.reload();
                        }
                    );
                return true;
            }
        },
        '-': '-',
        'show-all': {
            name: 'Show All',
            icon: 'fas fa-border-all',
            className: 'js-dt-show-all'
        },
        'filter-by': {
            name: 'Filter By',
            icon: 'fas fa-crosshairs',
            className: 'js-dt-filter-by'
        },
        'filter-out': {
            name: 'Filter Out',
            icon: 'fas fa-search-minus',
            className: 'js-dt-filter-out'
        }
    }
});