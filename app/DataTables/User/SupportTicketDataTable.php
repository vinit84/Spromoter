<?php

namespace App\DataTables\User;

use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupportTicketDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $total = SupportTicket::whereUserId(auth()->id())->withTrashed()->count();
        $trashed = SupportTicket::whereUserId(auth()->id())->onlyTrashed()->count();
        $open = SupportTicket::whereUserId(auth()->id())->where('status', SupportTicket::STATUS_OPEN)->count();
        $closed = SupportTicket::whereUserId(auth()->id())->where('status', SupportTicket::STATUS_CLOSED)->count();
        $resolvePercentage = round(nullSafeDivide($open, $total, percentage: true), 2);

        return (new EloquentDataTable($query))
            ->editColumn('department', fn(SupportTicket $supportTicket) => str($supportTicket->department)->title())
            ->editColumn('priority', fn(SupportTicket $supportTicket) => view('user.support-tickets.table.priority', compact('supportTicket')))
            ->editColumn('status', fn(SupportTicket $supportTicket) => view('user.support-tickets.table.status', compact('supportTicket')))
            ->editColumn('created_at', fn(SupportTicket $supportTicket) => view('user.support-tickets.table.created_at', compact('supportTicket')))
            ->addColumn('actions', fn(SupportTicket $supportTicket) => view('user.support-tickets.table.actions', compact('supportTicket')))
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
            ->whereUserId(auth()->id())
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
            ->buttons([
                Button::make('export'),
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
            Column::make('id')->title(trans('ID')),
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
