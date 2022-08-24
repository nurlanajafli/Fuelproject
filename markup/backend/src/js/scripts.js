// dataTable column().title() plugin
$.fn.dataTable.Api.register('column().title()', function () {
    var colheader = this.header();
    return $(colheader).text().trim();
});

var dataTablesArray = [];
var globalSavedSelected = [];

// DataTable Init Function
/***
 *  DT - DataTable
 *  TD - TableDropdown
 ***/

function initDataTable() {
    if (issetElement('.js-datatable')) {
        var dataTables = $('.js-datatable');

        initDTColumnsFix($('.js-datatable:not(.no-footer):not(.DTCR_clonedTable)'));

        for (var i = 0; i < dataTables.length; i++) {
            var thisDataTable = $(dataTables[i]);

            if (!$.fn.DataTable.isDataTable(thisDataTable)) {
                var dataTableOptions = eval(thisDataTable.attr('data-var'));
                dataTableOptions.stateSaveParams = function (settings, data) {
                    data.selected = this.api().rows({selected: true})[0];
                    delete data.start;
                    delete data.search;
                };
                dataTableOptions.stateLoadParams = function (settings, data) {
                    globalSavedSelected = data.selected;
                    for (var x = 0; x < data.columns.length; x++) {
                        delete data.columns[x].search.search;
                    }
                };
                var dataTable = thisDataTable.on( 'init.dt', function (e, settings, json) {
                    var api = new $.fn.dataTable.Api(settings);

                    if (globalSavedSelected == undefined){
                        globalSavedSelected = -1;
                    }

                    api.rows(globalSavedSelected).select();
                    api.state.save();

                }).dataTable(dataTableOptions);

                // only general datatables ( without table dropdown )
                if (!dataTable.hasClass('no-footer')) {
                    dataTablesArray.push(dataTable.api());
                }

                if (dataTable.hasClass('no-footer')) {
                    initTDSearch(dataTable);
                    initTDPaginationFix(dataTable);
                } else {
                    $(dataTable).attr('data-dt-index', i);
                    initDTSearch(dataTablesArray);
                    initDTFilters(dataTablesArray);
                    initDTFilterSearch(dataTablesArray);
                    initJQueryContextMenu(dataTablesArray);
                    initDateRangeFilter(dataTable.api());
                    initColVisLimit(dataTablesArray);
                    removeRowFromDT(dataTable.api());
                    dataTable.api().on('column-reorder', function (e, settings, details) {
                        _document.off('change', '.js-table-filter select');
                        $('input.js-column-search').off('input change clear');
                        initDTSearch(dataTablesArray);
                        initDTFilters(dataTablesArray);
                        initDTFilterSearch(dataTablesArray);
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
                if (dataTables.length - 1 == i){
                    if ($('.modal .js-datatable:not(.no-footer)')){
                        var modalDT = $('.modal .js-datatable:not(.no-footer)');
                        var modalDTArray = [];
                        for (var x = 0; x < modalDT.length; x++) {
                            var modalDTAPI = $(modalDT[x]).DataTable();
                            modalDTArray.push(modalDTAPI);
                            modalDTAPI.on('column-reorder', function (e, settings, details) {
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

// DataTable Columns orderable & searchable false fix
function initDTColumnsFix(dataTables) {
    var dataTableOptions;
    var columns = [];
    var notSearchable = {};
    var notOrderable = {};

    // get columns
    for (var index = 0; index < dataTables.length; index++) {
        var thisTable = $(dataTables[index]);
        dataTableOptions = eval(thisTable.attr('data-var'));
        columns.push(dataTableOptions.columns);
    }

    // collect not searchable & not orderable columns index
    if (columns != undefined) {
        var columnIndex;
        for (columnIndex = 0; columnIndex < columns.length; columnIndex++) {
            notOrderable[columnIndex] = [];
            notSearchable[columnIndex] = [];
            var thisColumn = columns[columnIndex];
            for (var property = 0; property < thisColumn.length; property++) {
                if (thisColumn[property].searchable == false) {
                    notSearchable[columnIndex].push(property);
                }
                if (thisColumn[property].orderable == false) {
                    notOrderable[columnIndex].push(property);
                }
            }

            // searchable: false; fix
            var tableFooter = $(dataTables[columnIndex]).find('tfoot th');
            for (var i = 0; i < tableFooter.length; i++) {
                var currentFooter = $(tableFooter[i]);
                if (notSearchable[columnIndex].includes(i)) {
                    currentFooter.attr('data-searchable', false);
                    currentFooter.html('');
                } else {
                    currentFooter.html('<input class="form-control js-column-search" type="text"/>');
                }
            }

            // orderable: false; fix
            var tableHeadings = $(dataTables[columnIndex]).find('thead th');
            for (var i = 0; i < tableHeadings.length; i++) {
                if (notOrderable[columnIndex].includes(i)) {
                    var currentHeading = $(tableHeadings[i]);
                    currentHeading.attr('data-orderable', false);
                }
            }
        }
    }
}

// DataTable Columns Individual Search
function initDTSearch(dataTablesArray) {
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
function initDTFilters(dataTablesArray) {
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
function initDTFilterSearch(dataTablesArray) {
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

// DataTable Column Visibilty Button Limit
function initColVisLimit(dataTablesArray) {
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

function initJQueryContextMenu(dataTablesArray) {
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

$(document).ready(function () {

    $('[data-tooltip="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip();

    if(getOS() == 'Linux'){
        $('.dt-buttons').css('right', '280px');
    }

    _document.on('hidden.bs.modal', '.modal', function () {
        _html.removeClass('modal-open');
    });

    _document.on('show.bs.modal', '.modal', function () {
        _html.addClass('modal-open');
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    // file input trigger
    $(document).on("click", ".js-logo-upload", function () {
        var file = $(this).parents().find(".logo-upload");
        file.trigger("click");
    });

    // logo preview
    $('input[type="file"]').change(function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#logo-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });

    // logo remove
    $(document).on('click', '.js-preview-remove', function (e) {
        e.preventDefault();
        var requestURL = $(this).data('url');
        $.ajax({
            type: 'POST',
            url: requestURL,
            success: function () {
                $('input[type="file"]').val('');
                $('#logo-preview').attr('src', 'https://via.placeholder.com/150x75/FFFFFF/FFFFFF');
            },
        });
    })

    // .js-ajax-modal
    $(document).on('click', '.js-ajax-modal', function (e) {
        e.preventDefault();
        openModal($(this).data('url'));
    });

    initDataTable();
    initTDFunctions();
    initDateRangeFilterOptions();
    fixDTFooterOnScroll();
});
