function initDataTableWithFeatures(options) {
    console.log('initDataTableWithFeatures called with options:', options);
    
    const tableSelector = options.tableSelector || '#dataTable';
    const bulkDeleteBtnSelector = options.bulkDeleteBtnSelector || '#bulk-delete-btn';
    const selectAllSelector = options.selectAllSelector || '#selectAll';
    const rowHandleSelector = options.rowHandleSelector || 'td.reorder-handle';
    const reorderUrl = options.reorderUrl || null;
    const csrfToken = options.csrfToken || $('meta[name="csrf-token"]').attr('content');
    const bulkDeleteUrl = options.bulkDeleteUrl || null; // <-- added
    
    console.log('Table selector:', tableSelector);
    console.log('Bulk delete button selector:', bulkDeleteBtnSelector);
    console.log('Bulk delete URL:', bulkDeleteUrl);

    // Check if table has data rows (excluding empty state row)
    const dataRows = $(tableSelector + ' tbody tr').filter(function() {
        return !$(this).hasClass('empty-state-row') && $(this).find('td').length > 1;
    });
    
    if (dataRows.length === 0) {
        console.log('No data rows found - DataTable not initialized to avoid warnings');
        return;
    }

    // init DataTable
    const table = $(tableSelector).DataTable({
        pageLength: 10,
        order: [[2, 'asc']],
        rowReorder: {
            selector: rowHandleSelector
        },
        columnDefs: [
            { orderable: false, targets: [0, 1, -1] }
        ],
        responsive: true,
        language: {
            emptyTable: "No stores found. Click 'Add New Store' to get started.",
            zeroRecords: "No matching stores found",
            info: "Showing _START_ to _END_ of _TOTAL_ stores",
            infoEmpty: "Showing 0 to 0 of 0 stores",
            infoFiltered: "(filtered from _MAX_ total stores)",
            lengthMenu: "Show _MENU_ stores per page",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            search: "Search stores:",
            processing: "Loading stores..."
        },
        drawCallback: function(settings) {
            // Hide pagination and info when no data
            if (settings.fnRecordsTotal() === 0) {
                $(tableSelector + '_wrapper .dataTables_paginate').hide();
                $(tableSelector + '_wrapper .dataTables_info').hide();
            } else {
                $(tableSelector + '_wrapper .dataTables_paginate').show();
                $(tableSelector + '_wrapper .dataTables_info').show();
            }
        }
    });

    // RowReorder
    if (reorderUrl) {
        table.on('row-reorder', function (e, diff) {
            if (!diff || diff.length === 0) return;

            const changes = diff.map(chg => {
                const $row = $(chg.node);
                return { id: $row.data('id'), sort_order: chg.newPosition + 1 };
            });

            diff.forEach(chg => {
                table.cell($(chg.node), 2).data(chg.newPosition + 1).draw(false);
            });

            $.post(reorderUrl, { _token: csrfToken, order: changes })
                .done(() => {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Order saved', showConfirmButton: false, timer: 1200 });
                })
                .fail(() => {
                    Swal.fire({ icon: 'error', title: 'Failed to save order' });
                });
        });
    }

    // Select all - Use event delegation for DataTable compatibility
    $(document).on('change', selectAllSelector, function () {
        const isChecked = this.checked;
        $(tableSelector + ' .rowCheckbox').prop('checked', isChecked);
        console.log('Select all changed:', isChecked); // Debug log
        toggleBulkDeleteBtn();
    });

    // Row checkbox - Use event delegation for DataTable compatibility
    $(document).on('change', tableSelector + ' .rowCheckbox', function () {
        const all = $(tableSelector + ' .rowCheckbox').length;
        const checked = $(tableSelector + ' .rowCheckbox:checked').length;
        $(selectAllSelector).prop('checked', all === checked);
        console.log('Row checkbox changed - All:', all, 'Checked:', checked); // Debug log
        toggleBulkDeleteBtn();
    });

    // Additional event binding for DataTable compatibility
    table.on('draw', function() {
        // Re-bind events after table redraw
        $(tableSelector + ' .rowCheckbox').off('change').on('change', function() {
            const all = $(tableSelector + ' .rowCheckbox').length;
            const checked = $(tableSelector + ' .rowCheckbox:checked').length;
            $(selectAllSelector).prop('checked', all === checked);
            console.log('Row checkbox changed (after draw) - All:', all, 'Checked:', checked); // Debug log
            toggleBulkDeleteBtn();
        });
    });

    function toggleBulkDeleteBtn() {
        const checkedCount = $(tableSelector + ' .rowCheckbox:checked').length;
        const isDisabled = checkedCount === 0;
        $(bulkDeleteBtnSelector).prop('disabled', isDisabled);
        console.log('Bulk delete button toggled - Checked:', checkedCount, 'Disabled:', isDisabled); // Debug log
    }

    // Single delete
    $(document).on('click', tableSelector + ' .delete-btn', function (e) {
        e.preventDefault();
        const form = $(this).closest('form')[0];
        Swal.fire({
            title: "Are you sure?",
            text: "This record will be deleted permanently.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-outline-secondary waves-effect"
            },
            buttonsStyling: false
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Bulk delete (no outer form)
    $(document).on('click', bulkDeleteBtnSelector, function (e) {
        console.log('Bulk delete button clicked via event delegation');
        e.preventDefault();
        const ids = $(tableSelector + ' .rowCheckbox:checked').map(function () {
            return $(this).val();
        }).get();

        console.log('Selected IDs:', ids); // Debug log
        console.log('Checkboxes found:', $(tableSelector + ' .rowCheckbox').length); // Debug log
        console.log('Checked checkboxes:', $(tableSelector + ' .rowCheckbox:checked').length); // Debug log
        console.log('Bulk delete URL:', bulkDeleteUrl); // Debug log

        if (ids.length === 0) {
            Swal.fire({
                title: "No Selection",
                text: "Please select at least one store to delete.",
                icon: "warning",
                confirmButtonText: "OK"
            });
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Selected records will be deleted permanently.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete them!",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                cancelButton: "btn btn-outline-secondary waves-effect"
            },
            buttonsStyling: false
        }).then(result => {
            if (result.isConfirmed && bulkDeleteUrl) {
                // Dynamically create and submit form
                let form = $('<form>', {
                    method: 'POST',
                    action: bulkDeleteUrl
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrfToken
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_method',
                    value: 'DELETE'
                }));

                ids.forEach(id => {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'ids[]',
                        value: id
                    }));
                });

                $(document.body).append(form);
                form.submit();
            }
        });
    });
}
