<?php

namespace App\DataTables\Admin;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function Termwind\render;

class SupportTicketDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $total = SupportTicket::withTrashed()->count();
        $trashed = SupportTicket::onlyTrashed()->count();
        $open = SupportTicket::where('status', SupportTicket::STATUS_OPEN)->count();
        $closed = SupportTicket::where('status', SupportTicket::STATUS_CLOSED)->count();
        $resolvePercentage = round(nullSafeDivide($open, $total, percentage: true), 2);

        return (new EloquentDataTable($query))
            ->addColumn('checkbox', '')
            ->addColumn('customer', fn(SupportTicket $supportTicket) => view('admin.support-tickets.table.customer', compact('supportTicket')))
            ->editColumn('department', fn(SupportTicket $supportTicket) => str($supportTicket->department)->title())
            ->editColumn('priority', fn(SupportTicket $supportTicket) => view('admin.support-tickets.table.priority', compact('supportTicket')))
            ->editColumn('status', fn(SupportTicket $supportTicket) => view('admin.support-tickets.table.status', compact('supportTicket')))
            ->editColumn('created_at', fn(SupportTicket $supportTicket) => view('admin.support-tickets.table.created_at', compact('supportTicket')))
            ->addColumn('actions', fn(SupportTicket $supportTicket) => view('admin.support-tickets.table.actions', compact('supportTicket')))
            ->setRowId('id')
            ->rawColumns(['customer', 'priority', 'status', 'actions'])
            ->with([
                'total' => $total,
                'trashed' => $trashed,
                'open' => $open,
                'closed' => $closed,
                'resolvePercentage' => $resolvePercentage,
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SupportTicket $supportTicket): QueryBuilder
    {
        return $supportTicket
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('support-ticket-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->autoWidth(false)
            ->responsive()
            ->stateSave()
            ->dom(config('app.datatables.dom'))
            ->orderBy(1)
            ->selectStyleMultiShift()
            ->buttons([
                Button::raw([
                    // make a dropdown menu for action to use bulk action
                    'extend' => 'collection',
                    'text' => '<i class="ti ti-checkbox"></i>',
                    'tag' => 'a',
                    'attr' => [
                        'class' => 'btn btn-label-primary confirm-action',
                        'href' => route('admin.support-tickets.mark-selected-as-open'),
                        'id' => 'mark-selected-as-open',
                        'title' => trans('Mark Selected As Open'),
                        'data-bs-toggle' => 'tooltip',
                    ]
                ]),
                Button::raw([
                    // make a dropdown menu for action to use bulk action
                    'extend' => 'collection',
                    'text' => '<i class="ti ti-square-x"></i>',
                    'tag' => 'a',
                    'attr' => [
                        'class' => 'btn btn-label-danger confirm-action',
                        'href' => route('admin.support-tickets.mark-selected-as-closed'),
                        'id' => 'mark-selected-as-closed',
                        'title' => trans('Mark Selected As Closed'),
                        'data-bs-toggle' => 'tooltip',
                    ]
                ]),

                Button::make('export'),
            ])
            ->columnDefs([
                [
                    'targets' => 0,
                    'searchable' => false,
                    'orderable' => false,
                    'render' => 'function (data, type, row) {
                        return `<input type="checkbox" class="dt-checkboxes form-check-input" value="${row[\'id\']}" />`;
                    }',
                    'checkboxes' => [
                        'selectRow' => true,
                        'selectAllRender' => '<input type="checkbox" class="form-check-input" />',
                    ]
                ]
            ])
            ->parameters([
                'initComplete' => 'function () {
                    $(document).on("confirmActionSuccess", function () {
                        window.LaravelDataTables["support-ticket-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["support-ticket-table"].draw();
                    });
                    $("#support-ticket-table_filter input[type=search]").removeClass("form-control-sm");
                    $("#support-ticket-table_length select").removeClass("form-select-sm");

                    $(document).on("confirmActionBefore", "#mark-selected-as-open, #mark-selected-as-closed", function (e, settings) {
                        // Push to form data
                        let ids = window.LaravelDataTables["support-ticket-table"].$("input[type=\'checkbox\']:checked").map(function () {
                            return $(this).val();
                        }).get();

                        settings.data = JSON.stringify({
                            ids: ids,
                        });
                    });
                }',
                'drawCallback' => 'function () {
                    $("#totalTickets").text(this.api().ajax.json().total);
                    $("#trashedTickets").text(trans("(:count trashed)", {count: this.api().ajax.json().trashed}));
                    $("#openTickets").text(this.api().ajax.json().open);
                    $("#closedTickets").text(this.api().ajax.json().closed);
                    $("#resolvePercentage").text(this.api().ajax.json().resolvePercentage + "%");

                    initTooltip();
                }',
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => trans('Search...')
                ]
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox'),
            Column::make('id')->title(trans('ID')),
            Column::make('customer')->title(trans('Customer')),
            Column::make('subject')->title(trans('Subject')),
            Column::make('department')->title(trans('Department')),
            Column::make('priority')->title(trans('Priority')),
            Column::make('status')->title(trans('Status')),
            Column::make('created_at')->title(trans('Created At')),
            Column::computed('actions')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title(trans('Actions')),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SupportTicket_' . date('YmdHis');
    }
}
