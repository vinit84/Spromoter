<?php

namespace App\DataTables\User\Emails;

use App\Models\OrderEmail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmailStatusDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filter(function (QueryBuilder $query) {
                if ($this->request->get('filter_status') == 'scheduled') {
                    $query->whereNotNull('scheduled_at');
                }

                if ($this->request->get('filter_status') == 'sent') {
                    $query->whereNotNull('sent_at');
                }

                if ($this->request->get('filter_status') == 'opened') {
                    $query->whereNotNull('opened_at');
                }

                if ($this->request->get('filter_status') == 'reviewed') {
                    $query->whereNotNull('reviewed_at');
                }

                if ($this->request->get('filter_status') == 'not_sent') {
                    $query->whereNull('sent_at');
                }

                if ($this->request->get('filter_status') == 'limit_exceeded') {
                    $query->whereNotNull('limit_exceeded_at');
                }
            })
            ->addColumn('product', fn (OrderEmail $orderEmail) => view('user.emails.email-status.table.product', compact('orderEmail')))
            ->editColumn('sent_at', fn (OrderEmail $orderEmail) => dateFormat($orderEmail->sent_at))
            ->editColumn('scheduled_at', fn (OrderEmail $orderEmail) => dateFormat($orderEmail->scheduled_at))
            ->editColumn('created_at', fn (OrderEmail $orderEmail) => dateFormat($orderEmail->created_at))
            ->addColumn('actions', fn (OrderEmail $orderEmail) => view('user.emails.email-status.table.actions', compact('orderEmail')))
            ->setRowId('id')
            ->with([
                'allCount' => OrderEmail::currentStore()->count(),
                'scheduledCount' => OrderEmail::currentStore()->whereNotNull('scheduled_at')->count(),
                'sentCount' => OrderEmail::currentStore()->whereNotNull('sent_at')->count(),
                'openedCount' => OrderEmail::currentStore()->whereNotNull('opened_at')->count(),
                'reviewedCount' => OrderEmail::currentStore()->whereNotNull('reviewed_at')->count(),
                'notSentCount' => OrderEmail::currentStore()->whereNotNull('sent_at')->count(),
                'limitExceededCount' => OrderEmail::currentStore()->whereNotNull('limit_exceeded_at')->count(),
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(OrderEmail $orderEmail): QueryBuilder
    {
        return $orderEmail->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('email-status-table')
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
            ->ajax([
                'data' => 'function (d) {
                    d.filter_status = $("input[name=filter_status]:checked").val();
                }',
            ])
            ->parameters([
                'initComplete' => 'function () {
                    $(document).on("confirmActionSuccess", function () {
                        window.LaravelDataTables["email-status-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["email-status-table"].draw();
                    });
                    $("#emailstatus-table_filter input[type=search]").removeClass("form-control-sm");

                    $("input[name=filter_status]").change(function () {
                        window.LaravelDataTables["email-status-table"].draw();
                    });

                    $(document).on("ajaxLinkComplete", ".resend-link", function () {
                        window.LaravelDataTables["email-status-table"].draw();
                    });
                }',
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => trans('Search...')
                ],
                'drawCallback' => 'function() {
                    $("#allCount").text(this.api().ajax.json().allCount);
                    $("#scheduledCount").text(this.api().ajax.json().scheduledCount);
                    $("#sentCount").text(this.api().ajax.json().sentCount);
                    $("#openedCount").text(this.api().ajax.json().openedCount);
                    $("#reviewedCount").text(this.api().ajax.json().reviewedCount);
                    $("#notSentCount").text(this.api().ajax.json().notSentCount);
                    $("#limitExceededCount").text(this.api().ajax.json().limitExceededCount);
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(trans('ID')),
            Column::make('product')->title(trans('Product')),
            Column::make('scheduled_at')->title(trans('Scheduled At')),
            Column::make('sent_at')->title(trans('Sent At')),
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
        return 'EmailStatus_' . date('YmdHis');
    }
}
