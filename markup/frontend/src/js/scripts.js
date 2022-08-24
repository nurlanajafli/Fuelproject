//=include vendor/isFilled.js

// IE polyfill
if (!Array.prototype.includes) {
    //or use Object.defineProperty
    Array.prototype.includes = function (search) {
        return !!~this.indexOf(search);
    }
}
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = (function (Object, max, min) {
        "use strict";
        return function indexOf(member, fromIndex) {
            if (this === null || this === undefined) throw TypeError("Array.prototype.indexOf called on null or undefined");

            var that = Object(this), Len = that.length >>> 0, i = min(fromIndex | 0, Len);
            if (i < 0) i = max(0, Len + i); else if (i >= Len) return -1;

            if (member === void 0) {
                for (; i !== Len; ++i) if (that[i] === void 0 && i in that) return i; // undefined
            } else if (member !== member) {
                for (; i !== Len; ++i) if (that[i] !== that[i]) return i; // NaN
            } else for (; i !== Len; ++i) if (that[i] === member) return i; // all else

            return -1; // if the value was not found, then return -1
        };
    })(Object, Math.max, Math.min);
}
if (!Element.prototype.matches) {
    Element.prototype.matches =
        Element.prototype.msMatchesSelector ||
        Element.prototype.webkitMatchesSelector;
}
if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        var el = this;

        do {
            if (Element.prototype.matches.call(el, s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

// Bootstrap template JS
function initSBAdminTemlate() {
    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        };

        // Toggle the side navigation when window is resized below 480px
        if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
            $("body").addClass("sidebar-toggled");
            $(".sidebar").addClass("toggled");
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        e.preventDefault();
        $("html, body").animate({scrollTop: 0}, 600);
    });

}

// GLOBAL VARIABLES
var dataTablesArray = [];
var globalSavedSelected = [];

// dataTable column().title() plugin
// https://datatables.net/plug-ins/api/column().title()
$.fn.dataTable.Api.register('column().title()', function () {
    var colheader = this.header();
    return $(colheader).text().trim();
});

// dataTable defaults
// https://datatables.net/examples/advanced_init/defaults.html
$.extend( true, $.fn.dataTable.defaults, {
    stateSaveParams: function (settings, data) {
        data.selected = this.api().rows({selected: true})[0];
        delete data.start;
        delete data.search;
    },
    stateLoadParams: function (settings, data) {
        globalSavedSelected = data.selected;
        for (var x = 0; x < data.columns.length; x++) {
            delete data.columns[x].search.search;
        }
    }
} );

// DataTable Init Function
/***
 *  DT - DataTable
 *  TD - TableDropdown
 ***/
function initDataTable() {
    if (issetElement('.js-datatable')) {
        var allTables = $('.js-datatable');

        for (var thisTableIndex = 0; thisTableIndex < allTables.length; thisTableIndex++) {
            var thisTable = $(allTables[thisTableIndex]);

            if (!$.fn.DataTable.isDataTable(thisTable)) {
                var initOptions = eval(thisTable.attr('data-var'));
                // moved to default settings
                // initOptions.stateSaveParams = function (settings, data) {
                //     data.selected = this.api().rows({selected: true})[0];
                //     delete data.start;
                //     delete data.search;
                // };
                // initOptions.stateLoadParams = function (settings, data) {
                //     globalSavedSelected = data.selected;
                //     for (var x = 0; x < data.columns.length; x++) {
                //         delete data.columns[x].search.search;
                //     }
                // };

                // datatable initialisation
                let dataTable = thisTable.on( 'init.dt', function (e, settings, json) {
                        initDTFilters();
                        initDTFilterSearch();

                        var dataTableApi = new $.fn.dataTable.Api(settings);

                        if (globalSavedSelected == undefined){
                            globalSavedSelected = -1;
                        }

                        dataTableApi.rows(globalSavedSelected).select();
                        dataTableApi.state.save();

                    }).dataTable(initOptions);

                // inline DT
                // example: /bill.html - Bill Listing table
                thisTable.off().on(_touchTap, 'td.dt-control', function () {
                    var thisTR = $(this).parents('tr');
                    var thisRow = dataTable.api().row(thisTR);

                    if (thisTR.hasClass('shown')) {
                        $('.subtable-container', thisRow.child()).slideUp( function () {
                            thisRow.child.hide();
                            thisTR.removeClass('shown');
                        });
                    } else {
                        if(issetElement('tr.shown')){
                            var activeTR = $('tr.shown');
                            var activeRow = dataTable.api().row(activeTR);
                            activeRow.child.remove();
                            activeTR.removeClass('shown');
                        }

                        thisRow.child('<div class="subtable-container"><div class="row">' +
                                        '<div class="col-8"><div class="table table-responsive"><h4>Details</h4>' +
                                            '<table class="table table-bordered compact js-datatable w-100" id="random-id-1" data-var="billListingDetails">' +
                                                '<thead><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></thead>' +
                                                '<tfoot class="d-none"><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tfoot>' +
                                            '</table>' +
                                            '</div>' +
                                        '</div>' +
                                        '<div class="col-4"><div class="table table-responsive"><h4>Payment History</h4>' +
                                            '<table class="table table-bordered compact js-datatable w-100" id="random-id-2" data-var="billListingHistory">' +
                                                '<thead><th></th><th></th><th></th><th></th></thead>' +
                                                '<tfoot class="d-none"><th></th><th></th><th></th><th></th></tfoot>' +
                                            '</table>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div></div>').show();
                        initDataTable();
                        thisTR.addClass('shown');
                    }
                });

                // only general datatables ( without table dropdown )
                if (!dataTable.hasClass('no-footer')) {
                    dataTablesArray.push(dataTable.api());
                }

                if (dataTable.hasClass('no-footer')) {
                    initTDSearch(dataTable);
                    initTDPaginationFix(dataTable);
                } else {
                    $(dataTable).attr('data-dt-index', thisTableIndex);
                    initDTSearch();
                    initDTFilters();
                    initDTFilterSearch();
                    initJQueryContextMenu();
                    initDateRangeFilter(dataTable.api());
                    initColVisLimit();
                    removeRowFromDT(dataTable.api());
                    dataTable.api().on('column-reorder', function (e, settings, details) {
                        _document.off('change', '.js-table-filter select');
                        $('input.js-column-search').off('input change clear');
                        initDTSearch();
                        initDTFilters();
                        initDTFilterSearch();
                    });

                    // save select state
                    dataTable.api().on('select deselect', function() {
                        dataTable.api().state.save();
                    });

                    // remove load-* classes from column search
                    $('.js-datatable tfoot th[class*="load-"]').removeClass(function (index, css) {
                        return (css.match(/(^|\s)load-\S+/g) || []).join(' ');
                    });
                }
            } else {
                if (allTables.length - 1 == thisTableIndex){
                    if ($('.modal .js-datatable:not(.no-footer)')){
                        var modalDT = $('.modal .js-datatable:not(.no-footer)');
                        var modalDTArray = [];
                        for (var x = 0; x < modalDT.length; x++) {
                            var modalDTApi = $(modalDT[x]).DataTable();
                            modalDTArray.push(modalDTApi);
                            modalDTApi.on('column-reorder', function (e, settings, details) {
                                _document.off('change', '.modal .js-table-filter select');
                                $('.modal input.js-column-search').off('input change clear');
                                initDTSearch(modalDTArray);
                                initDTFilters(modalDTArray);
                                initDTFilterSearch(modalDTArray);
                            });
                        }
                        initDTSearch(modalDTArray);
                        initDTFilters(modalDTArray);
                        initDTFilterSearch(modalDTArray);
                    }
                }
            }
        }
    }
}

// orderable & searchable false fix
function initDTColumnsFix() {

    if (issetElement('.js-datatable')){

        var allTables = $('.js-datatable:not(.no-footer):not(.DTCR_clonedTable)')
        var initOptions;
        var columnsData = [];
        var notSearchable = {};
        var notOrderable = {};

        // get columns
        for (var index = 0; index < allTables.length; index++) {
            var thisTable = $(allTables[index]);
            initOptions = eval(thisTable.attr('data-var'));
            columnsData.push(initOptions.columns);
        }

        // collect not searchable & not orderable columns index
        if (columnsData != undefined) {
            for (var columnIndex = 0; columnIndex < columnsData.length; columnIndex++) {
                notOrderable[columnIndex] = [];
                notSearchable[columnIndex] = [];
                var thisColumn = columnsData[columnIndex];
                for (var property = 0; property < thisColumn.length; property++) {
                    if (thisColumn[property].searchable == false) {
                        notSearchable[columnIndex].push(property);
                    }
                    if (thisColumn[property].orderable == false) {
                        notOrderable[columnIndex].push(property);
                    }
                }

                // searchable: false; fix
                var tableFooter = $(allTables[columnIndex]).find('tfoot th');
                for (var i = 0; i < tableFooter.length; i++) {
                    var thisTableFooter = $(tableFooter[i]);
                    if (notSearchable[columnIndex].includes(i)) {
                        thisTableFooter.attr('data-searchable', false);
                        thisTableFooter.html('');
                    } else {
                        thisTableFooter.html('<input class="form-control js-column-search" type="text"/>');
                    }
                }

                // orderable: false; fix
                var tableHeadings = $(allTables[columnIndex]).find('thead th');
                for (var i = 0; i < tableHeadings.length; i++) {
                    if (notOrderable[columnIndex].includes(i)) {
                        var thisTableHeading = $(tableHeadings[i]);
                        thisTableHeading.attr('data-orderable', false);
                    }
                }
            }
        }
    }
}

// DataTable Columns Individual Search
function initDTSearch() {
    var dataTables = $('.js-datatable[id]:not(.no-footer):not(.DTCR_clonedTable)');

    if (dataTablesArray.length == dataTables.length) {
        for (var i = 0; i < dataTablesArray.length; i++) {
            dataTablesArray[i].columns().every(function () {
                var column = this;
                $('input.js-column-search', this.footer()).on('input change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
        }
    }

    // Enter prevent
    _document.on('keyup', 'input.js-column-search', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });
}

// remove button function
function removeRowFromDT(dataTable) {
    var thisRow;
    var requestMethod;
    var requestURL;
    var confirmModal = $('#js-row-remove');

    // setup & show confirm modal
    _document.on(_touchTap, '.js-row-remove', function (e) {
        e.preventDefault();

        var _this = $(this);
        requestMethod = _this.data('method');
        requestURL = _this.attr('href');
        thisRow = _this.closest('tr');
        var confirmText = _this.data('confirm');
        var modalText = confirmModal.find('.modal-body p');

        if (confirmText != undefined) {
            modalText.html(confirmText);
        }
        confirmModal.modal('show');

    });

    _document.on(_touchTap, '#js-row-remove button[type="submit"]', function (e) {
        e.preventDefault();
        $.ajax({
            type: requestMethod,
            url: requestURL,
            success: function () {
                dataTable.row(thisRow).remove().draw();
                confirmModal.modal('hide');
            },
        });
    })
}

// Init DataTable Filters
function initDTFilters() {
    var filters = $('.js-table-filter');
    var dataTables = $('.js-datatable[data-dt-index]:not(.no-footer):not(.DTCR_clonedTable)');

    // iterate tables and run set options function
    if (dataTablesArray.length == dataTables.length) {
        for (var i = 0; i < dataTablesArray.length; i++) {
            var thisFilter = filters[i];
            $(thisFilter).attr('data-dt-index', i);
            var thisDataTable = dataTablesArray[i];
            setDTFilterOptions(thisDataTable, thisFilter);
        }
    }
}

// Set DataTable Filter Search Options
function setDTFilterOptions(dataTable, filter) {
    var filterSelects = $(filter).find('select');

    for (var i = 0; i < filterSelects.length; i++) {
        var select = $(filterSelects[i]);

        var columnIndex = $(select).attr('data-column-index');
        var reorderedColumnIndex = dataTable.colReorder.transpose(+columnIndex);
        var column = dataTable.column(reorderedColumnIndex);

        select.parent().find('label').remove();
        select.parent().prepend('<label>' + column.title() + '</label>');
        select.empty();
        select.append('<option value="">All</option>');

        column.data().unique().sort().each(function (data) {
            if (column.search() === '^' + data + '$') {
                select.append('<option value="' + data + '" selected="selected">' + data + '</option>');
            } else {
                select.append('<option value="' + data + '">' + data + '</option>');
            }
        });
    }
}

// DataTable Filter Search
function initDTFilterSearch() {
    _document.on('change', '.js-table-filter select', function (e) {
        var _this = $(this);
        var tableIndex = _this.closest('.js-table-filter').attr('data-dt-index');
        var columnIndex = _this.attr('data-column-index');
        var reorderedColumnIndex = dataTablesArray[tableIndex].colReorder.transpose(+columnIndex);
        var column = dataTablesArray[tableIndex].column(reorderedColumnIndex);
        var val = $.fn.dataTable.util.escapeRegex(
            _this.val()
        );

        column.search(val ? '^' + val + '$' : '', true, false).draw();
    });
}

// DataTable Column Visibility Button Limit
function initColVisLimit() {
    var dataTables = $('.js-datatable:not(.no-footer):not(.DTCR_clonedTable)');

    if (dataTablesArray.length == dataTables.length) {
        for (var i = 0; i < dataTablesArray.length; i++) {
            dataTablesArray[i].on('buttons-action', function (e, buttonApi, dataTable, node) {
                var visibleColumnsCount = dataTable.columns(':visible').count();
                var dataTableID = $(this).attr('id');
                var colvisBtn = $('.buttons-colvis[aria-controls=' + dataTableID + ']');
                var colvisLimit = colvisBtn.attr('data-colvis-limit') ? colvisBtn.attr('data-colvis-limit') : 1;
                var colvisBtnParent = colvisBtn.parent();
                if (node.hasClass('buttons-colvis')) {
                    if (visibleColumnsCount <= (+colvisLimit)) {
                        colvisBtnParent.find('.buttons-columnVisibility.active').css({
                            'pointer-events': 'none',
                            'cursor': 'not-allowed'
                        });
                    } else {
                        colvisBtnParent.find('.buttons-columnVisibility.active').css({
                            'pointer-events': 'auto',
                            'cursor': 'pointer'
                        });
                    }
                }
            });
        }

        _document.on(_touchTap, '.buttons-columnVisibility', function () {
            var _this = $(this);
            var dataTableID = _this.attr('aria-controls');
            var dataTableIndex = $('.js-datatable#' + dataTableID).attr('data-dt-index');
            var visibleCount = dataTablesArray[dataTableIndex].columns(':visible').count();
            var colvisBtn = $('.buttons-colvis[aria-controls=' + dataTableID + ']');
            var colvisLimit = colvisBtn.attr('data-colvis-limit') ? colvisBtn.attr('data-colvis-limit') : 1;
            var colvisBtnParent = colvisBtn.parent();
            if (visibleCount <= (+colvisLimit)) {
                colvisBtnParent.find('.buttons-columnVisibility.active').css({
                    'pointer-events': 'none',
                    'cursor': 'not-allowed'
                });
            } else {
                colvisBtnParent.find('.buttons-columnVisibility.active').css({
                    'pointer-events': 'auto',
                    'cursor': 'pointer'
                });
            }
        });
    }
}

// Table Dropdown Search
function initTDSearch(dataTable) {
    // general search
    $('input.dropdown-toggle').on('input clear', function () {
        var query = this.value;
        dataTable.api().search(query).draw();
    });
}

function initTDPaginationFix(dataTable) {
    var thisTableID = dataTable.attr('id');
    dataTable.api().on('draw', function () {
        setTDPaginationMinWidth(thisTableID);
    });
    setTDPaginationMinWidth(thisTableID);
}

function setTDPaginationMinWidth(tableID) {
    var pageItems = $('#' + tableID + '_paginate .page-item:not(.previous):not(.next)');
    if (pageItems.length == 1) {
        $('#' + tableID + '_paginate').remove();
    }
    var pageItemsMinWidth = 100 / pageItems.length;
    pageItems.css('min-width', pageItemsMinWidth + '%');
}

// Table Dropdown Functions
function initTDFunctions() {
    // hide on ESC
    _document.on('keyup', function (e) {
        if (e.keyCode == 27) {
            $('.dropdown-menu--table').removeClass('show');
        }
    });

    // don't close dropdown table on click to tbody
    $('.dropdown-menu--table th').on(_touchTap, function (e) {
        e.stopPropagation();
    });

    $('.dropdown-menu--table .dataTables_paginate').on(_touchTap, function (e) {
        e.stopPropagation();
    });

    // clear on delete
    _document.on('keyup', function (e) {
        if (e.keyCode == 46) {
            $('input.dropdown-toggle').val('');
        }
    });
}

function initTDMinWitdhFix(){
    // min-width == input width
    var dropdownMenuTables = $('.dropdown-menu--table');
    for (var i = 0; i < dropdownMenuTables.length; i++) {
        var _this = $(dropdownMenuTables[i]);
        var thisTable = $(_this.find('table'));
        var thisParent = _this.parent();
        var thisInputWidth = $(thisParent).find('input.dropdown-toggle').css('width');
        thisTable.css('min-width', thisInputWidth);
    }
}

function initDateRangeFilter(dataTable) {
    var dateFormat = 'YYYY/MM/DD';
    if('.js-daterange'){
        var customDateFormat = $('.js-daterange').attr('data-date-format');

        if (customDateFormat){
            dateFormat = customDateFormat;
        }
    }

    var minDate = new DateTime($('.js-daterange-min'), {
        format: dateFormat,
    });
    var maxDate = new DateTime($('.js-daterange-max'), {
        format: dateFormat,
    });

    function filterDateRange() {
        if ($('.js-daterange-min').val() || $('.js-daterange-max').val()) {
            $.fn.dataTableExt.search.push(
                function (oSettings, aData, iDataIndex) {
                    var dateStart = $('.js-daterange-min').val();
                    var dateEnd = $('.js-daterange-max').val();
                    var columnIndex = $('.js-daterange').attr('data-column-index');

                    if(columnIndex){
                        var evalDate = aData[columnIndex];
                    }

                    if (evalDate >= dateStart && evalDate <= dateEnd) {
                        return true;
                    } else {
                        return false;
                    }
                });
        }
    }

    $('.js-daterange-min').change(function () {
        filterDateRange();
        if (maxDate.val() == null) {
            var today = moment().format(dateFormat);
            var max = $('.js-daterange-max');
            max.val(today).change();
        }
        dataTable.draw();
    });

    $('.js-daterange-max').change(function () {
        filterDateRange();
        dataTable.draw();
    });
}

function initDateRangeFilterOptions() {
    var dateFormat = 'YYYY/MM/DD';
    if('.js-daterange'){
        var customDateFormat = $('.js-daterange').attr('data-date-format');

        if (customDateFormat){
            dateFormat = customDateFormat;
        }
    }

    var minDate = $('.js-daterange-min');
    var maxDate = $('.js-daterange-max');
    var today = moment().format(dateFormat);
    var thisQuarter = moment().quarter();

    // this month
    _document.on(_touchTap, '.js-daterange-this-month', function (e) {
        e.preventDefault();

        var startOfThisMonth = moment().startOf('month').format(dateFormat);

        minDate.val(startOfThisMonth).change();
        maxDate.val(today).change();
    });

    // last month
    _document.on(_touchTap, '.js-daterange-last-month', function (e) {
        e.preventDefault();

        var startOfLastMonth = moment().subtract(1, 'month').startOf('month').format(dateFormat);
        var endOfLastMonth = moment().subtract(1, 'month').endOf('month').format(dateFormat);

        minDate.val(startOfLastMonth).change();
        maxDate.val(endOfLastMonth).change();
    });

    // this quarter
    _document.on(_touchTap, '.js-daterange-this-quarter', function (e) {
        e.preventDefault();

        var startOfThisQuarter = moment().quarter(thisQuarter).startOf('quarter').format(dateFormat);

        minDate.val(startOfThisQuarter).change();
        maxDate.val(today).change();
    });

    // last quarter
    _document.on(_touchTap, '.js-daterange-last-quarter', function (e) {
        e.preventDefault();

        var lastQuarter = thisQuarter - 1;
        var startOfLastQuarter = moment().quarter(lastQuarter).startOf('quarter').format(dateFormat);
        var endOfLastQuarter = moment().quarter(lastQuarter).endOf('quarter').format(dateFormat);

        minDate.val(startOfLastQuarter).change();
        maxDate.val(endOfLastQuarter).change();
    });

    // this year
    _document.on(_touchTap, '.js-daterange-this-year', function (e) {
        e.preventDefault();

        var startOfThisYear = moment().startOf('year').format(dateFormat);

        minDate.val(startOfThisYear).change();
        maxDate.val(today).change();
    });

    // last year
    _document.on(_touchTap, '.js-daterange-last-year', function (e) {
        e.preventDefault();

        var startOfLastYear = moment().subtract(1, 'year').startOf('year').format(dateFormat);
        var endOfLastYear = moment().subtract(1, 'year').endOf('year').format(dateFormat);

        minDate.val(startOfLastYear).change();
        maxDate.val(endOfLastYear).change();
    });

    // today
    _document.on(_touchTap, '.js-daterange-today', function (e) {
        e.preventDefault();

        minDate.val(today).trigger('change');
        maxDate.val(today).trigger('change');
    });
}

function toggleMatrixNumber(){
    if (issetElement('select#rate-source')){
        var rateSourceValue = $('select#rate-source').val().split(' ').join('');
        if (rateSourceValue != 'LoadMatrix'){
            $('#rate-matrixnumber').closest('.col-6').hide();
        } else {
            $('#rate-matrixnumber').closest('.col-6').show();
        }
    }
}

// init for ajax modals
function initAjaxModals() {
    _document.on(_touchTap, '.js-ajax-modal', function (e) {
        e.preventDefault();
        openModal($(this).data("url"));
    });

    _document.on(_touchTap, '.btn-primary', function (e) {
        let saveDataCode = this.getAttribute('data-code');

        // if save button is pressed, refresh dataTable
        if (saveDataCode == 'save') {
            initDataTable();
            initTDMinWitdhFix();
        }
    });

    toggleMatrixNumber();

    _document.on('shown.bs.modal', '.modal', function (){
        if ($('.js-datatable', $(this)).length) {
            initDataTable();
            initTDMinWitdhFix();
        }

        if ($('.owl-carousel')){
            initSliders();
        }

        if ($('.custom-file-container', $(this)).length) {
            initFileUpload();
        }

        $('.dropdown-menu--table th').on(_touchTap, function (e) {
            e.stopPropagation();
        });

        $('.dropdown-menu--table .dataTables_paginate').on(_touchTap, function (e) {
            e.stopPropagation();
        });

        $('[data-tooltip="tooltip"]').tooltip();
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function initImageViewer() {
    // show first image by default
    if (issetElement('.js-img-view')) {
        if ($('.js-img-view')[0].hasAttribute('href')) {
            var firstImageSrc = $($('.js-img-view')[0]).attr('href');
        } else {
            var firstImageSrc = $($('.js-img-view')[0]).attr('data-img-src');
        }
        if (firstImageSrc != undefined) {
            $('.file-view-content').empty().append('<img class="file-view-image" src="' + firstImageSrc + '"/>');
            $($('.js-img-view')[0]).addClass('active');
        }
    }

    // change image on click
    _document.on(_touchTap, '.js-img-view', function (e) {
        e.preventDefault();
        var imageSrc = $(this).attr('href');
        if (imageSrc == undefined) {
            imageSrc = $(this).attr('data-img-src');
        }
        $('.js-img-view').removeClass('active');
        $(this).addClass('active');
        $('.file-view-content').empty().append('<img class="file-view-image" src="' + imageSrc + '"/>');
    })

    // link mode
    _document.on(_touchTap, '.js-file-view-link', function (e) {
        e.preventDefault();
        $('.file-view-list').removeClass('file-view-list--thumb');
        if (!issetElement('.file-view-list.file-view-list--link')) {
            $('.file-view-list').addClass('file-view-list--link');
        }
        $('.btn').blur();
    })

    // table mode
    _document.on(_touchTap, '.js-file-view-table', function (e) {
        e.preventDefault();
        $('.file-view-list').removeClass('file-view-list--thumb');
        if (!issetElement('.file-view-list.file-view-list--table')) {
            $('.file-view-list').addClass('file-view-list--table');
        }
        $('.btn').blur();
    })

    // thumb mode
    _document.on(_touchTap, '.js-file-view-thumb', function (e) {
        e.preventDefault();
        $('.file-view-list').removeClass('file-view-list--link file-view-list--table');
        if (!issetElement('.file-view-list.file-view-list--thumb')) {
            $('.file-view-list').addClass('file-view-list--thumb');
        }
        $('.btn').blur();
    })
}

function initLightGallery() {
    // gallery mode
    $('body').lightGallery({
        selector: '.js-lg-gallery',
    });

    // single mode
    $('.js-lg-single').lightGallery({
        selector: 'this',
    });
}

function initFileUpload() {
    if (issetElement('.custom-file-container')) {
        var customControl = $('.custom-file-container__custom-file__custom-file-control');
        var chooseFile = customControl.attr('data-file-choose');
        var browse = customControl.attr('data-file-browse');
        var selectedCount = customControl.attr('data-file-selected');

        var upload = new FileUploadWithPreview('fileUploadWithPreview', {
            text: {
                chooseFile: chooseFile,
                browse: browse,
                selectedCount: selectedCount,
            },
        });
    }
}

function removeNotifications() {
    _document.on(_touchTap, '.js-ntf-remove', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var _this = $(this);
        var notification = _this.closest('.ntf-item');
        var removeURL = notification.attr('href');

        $.ajax({
            type: 'GET',
            url: removeURL,
            success: function () {
                notification.fadeOut('slow').remove();
            },
        });
    })
}

function showLoadCleaning() {
    _document.on(_touchTap, '.js-show-load-cleaning', function (e) {
        e.preventDefault();
        var cleaning = $('.clear-load-modal .js-load-cleaning');
        cleaning.show();
        $('.js-show-load-cleaning').blur();
    })
}

// .modal-full image zoom
function zoomModalImage() {
    _document.on(_touchTap, '.modal-full img', function (e) {
        e.preventDefault();
        $(this).toggleClass('modal-full__image');
    })
}

function initSliders(){
    $('.modal-slider__content.owl-carousel').owlCarousel(
        {
            items: 1,
            nav: true,
            mouseDrag: false,
            touchDrag: false,
            pullDrag: false,
            freeDrag: false,
            navText: [
                '<i class="fas fa-arrow-left"></i> Prev',
                'Next <i class="fas fa-arrow-right"></i>'
            ],
            dots: false,
            autoHeight: true,
        }
    );
}

function initJQueryContextMenu() {
    var thisCellData;
    var thisColumnIndex;
    var thisDataTable;

    var dataTables = $('.js-datatable:not(.no-footer):not(.DTCR_clonedTable)');

    if (dataTablesArray.length == dataTables.length) {
        _document.on('mousedown', '.js-datatable[data-dt-index] tbody td', function(e){
            e.preventDefault();
            _document.off(_touchTap, '.js-dt-filter-by');
            _document.off(_touchTap, '.js-dt-filter-out');
            if( e.button == 2 ) {
                var dataTableIndex = $(this).closest('table').attr('data-dt-index');
                thisDataTable = dataTablesArray[dataTableIndex];
                thisCellData = thisDataTable.cell(this).data();
                thisColumnIndex = thisDataTable.cell(this).index().column;

                initJQueryContextMenuFunctions(thisDataTable, thisColumnIndex, thisCellData);
                return false;
            }
            return true;
        });
    }
}

function initJQueryContextMenuFunctions(dataTable, index, cellData) {
    var _originURL = window.location.origin;

    if (_originURL.indexOf('localhost') || _originURL.indexOf('markup')) {
        _document.on(_touchTap, '.js-dt-sort-asc', function (e) {
            e.preventDefault();

            dataTable.column(index).order('asc').draw();
            var thisMenu = $(this).closest('.context-menu-list');
            thisMenu.contextMenu('hide');
        });

        _document.on(_touchTap, '.js-dt-sort-desc', function (e) {
            e.preventDefault();

            dataTable.column(index).order('desc').draw();
            var thisMenu = $(this).closest('.context-menu-list');
            thisMenu.contextMenu('hide');
        });

        _document.on(_touchTap, '.js-dt-show-all', function (e) {
            e.preventDefault();
            $.fn.dataTable.ext.search.pop();
            dataTable.page.len(-1).draw();
            var thisMenu = $(this).closest('.context-menu-list');
            thisMenu.contextMenu('hide');
        });

        _document.on(_touchTap, '.js-dt-filter-by', function (e) {
            e.preventDefault();

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    return (data[index] == cellData) ? true : false;
                }
            );

            dataTable.draw();
            var thisMenu = $(this).closest('.context-menu-list');
            thisMenu.contextMenu('hide');
        });

        _document.on(_touchTap, '.js-dt-filter-out', function (e) {
            e.preventDefault();

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    return (data[index] !== cellData) ? true : false;
                }
            );

            dataTable.draw();
            var thisMenu = $(this).closest('.context-menu-list');
            thisMenu.contextMenu('hide');
        });
    }
}

function getOS() {
    var userAgent = window.navigator.userAgent,
        platform = window.navigator.platform,
        macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
        windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
        iosPlatforms = ['iPhone', 'iPad', 'iPod'],
        os = null;

    if (macosPlatforms.indexOf(platform) !== -1) {
        os = 'Mac OS';
    } else if (iosPlatforms.indexOf(platform) !== -1) {
        os = 'iOS';
    } else if (windowsPlatforms.indexOf(platform) !== -1) {
        os = 'Windows';
    } else if (/Android/.test(userAgent)) {
        os = 'Android';
    } else if (!os && /Linux/.test(platform)) {
        os = 'Linux';
    }

    return os;
}

function formatFloatToString(num){
    return num.toFixed(formatNumber.precision)
            .replace('.', formatNumber.separator)
            .replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, formatNumber.thousandsSeparator);
}

function initChatFunctions(){
    if (issetElement('.chat__messages')){
        var chatBody = $('.chat__messages .card-body');
        chatBody.scrollTop(chatBody.prop('scrollHeight'));
    }
}

function fixDTFooterOnScroll(){
    if (issetElement('.dataTables_scroll')){

        var dtFooters = $('.dataTables_scrollFoot');
        for (var idx = 0; idx < dtFooters.length; idx++) {
            var _this = $(dtFooters[idx]);
            var _thisParent = _this.parent();
            var _thisBody = _thisParent.find('.dataTables_scrollBody');
            _this.insertBefore(_thisBody);
        }
    }
}

function initInlineDT(){
    // hide on click
    _document.on(_touchTap, function (e) {
        if ($(e.target).closest('.inline-dt').length === 0) {
            $('.inline-dt').hide();
            $('.inline-dt-trigger').removeClass('active');
        }
    });

    _document.on(_touchTap, '.inline-dt-trigger', function(e){
        e.stopPropagation();
        $('.inline-dt-trigger').removeClass('active');

        var _this = $(this);
        _this.addClass('active');

        var _thisPageX = e.pageX;
        var _thisPageY = e.pageY;

        var inlineDT = $('.inline-dt');
        var inlineDTWidth = $('.inline-dt').width();
        var inlineDTHeight = $('.inline-dt').height();

        var wrap = $(this).closest('.inline-dt-wrap');
        var wrapHeight = wrap.height();

        var _width = _document.width();
        var _height = _document.height();

        inlineDT.css({
            'top' : _thisPageY - 150,
            'left': _thisPageX - 280,
            'right': 'auto',
            'bottom': 'auto',
        });

        // bottom overflow fix
        if (_thisPageY + inlineDTHeight > _height - 100){
            inlineDT.css({
                'top' : 'auto',
                'bottom' : wrapHeight - _thisPageY + 190,
                'right': 'auto',
            });
        }

        // right overflow fix
        if (_thisPageX + inlineDTWidth > _width - 50){
            inlineDT.css({
                'left' : 'auto',
                'right' : 0
            });
        }

        $('.inline-dt .dataTables_scrollBody').scrollTop(0);
        inlineDT.show();
    })

    // hide on ESC
    _document.on('keyup', function (e) {
        if (e.keyCode == 27) {
            $('.inline-dt-trigger').removeClass('active');
            $('.inline-dt').hide();
        }
    });
}

function App() {
    return {
        isFilled: new IsFilled(),
        init: function () {
            this.isFilled.init();
            initSBAdminTemlate();
            initSliders();
            initDTColumnsFix();
            initDataTable();
            initTDFunctions();
            initDateRangeFilterOptions();
            initAjaxModals();
            initImageViewer();
            initLightGallery();
            initFileUpload();
            removeNotifications();
            showLoadCleaning();
            zoomModalImage();
            initChatFunctions();
            fixDTFooterOnScroll();
            initTDMinWitdhFix();
            initInlineDT();
        }
    }
}

_document.ready(function () {
    app = new App();
    app.init();

    _document.on('click', 'a[href*="#"]:not([href="#"])', function (e) {
        var _headerHeight = $('.navbar').height(),
            _thisHref = $(this).attr('href'),
            _sectionId = _thisHref.substr(_thisHref.indexOf('#')),
            _thisSection = $(_sectionId);

        if (_thisSection.length > 0) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: _thisSection.offset().top - _headerHeight
            }, 500);
            return false;
        }
    });

    $('[data-tooltip="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip();

    if(getOS() == 'Linux'){
        $('.dt-buttons').css('right', '280px');
    }

    _document.on('show.bs.modal', '.modal', function () {
        $('.btn').blur();
        _html.addClass('modal-open');
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function () {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    _document.on('hidden.bs.modal', '.modal', function () {
        if(!issetElement('.modal.show')){
            _html.removeClass('modal-open');
        } else {
            $('.modal:visible').find('button.close').focus();
        }
        $('.btn').blur();
    });

    _document.on(_touchTap, function (e) {
        $('.btn').blur();
    });

    autosize($('.chat__messages .card-footer textarea'));
});
