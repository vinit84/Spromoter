<?php

namespace App\DataTables\Admin\Business;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('amount', fn (Invoice $invoice) => \Number::currency($invoice->amount, $invoice->currency))
            ->editColumn('currency', fn (Invoice $invoice) => str($invoice->currency)->upper())
            ->addColumn('user', fn (Invoice $invoice) => view('admin.business.invoices.table.user', compact('invoice')))
            ->editColumn('status', fn (Invoice $invoice) => view('admin.business.invoices.table.status', compact('invoice')))
            ->editColumn('hosted_invoice_url', fn (Invoice $invoice) => view('admin.business.invoices.table.hosted_invoice_url', compact('invoice')))
            ->editColumn('invoice_pdf', fn (Invoice $invoice) => view('admin.business.invoices.table.invoice_pdf', compact('invoice')))
            ->editColumn('created_at', fn (Invoice $invoice) => dateFormat($invoice->created_at))
            ->addColumn('actions', 'invoice.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Invoice $invoice): QueryBuilder
    {
        return $invoice
            ->with(['user'])
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoice-table')
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
                        window.LaravelDataTables["invoice-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["invoice-table"].draw();
                    });
                    $("#invoice-table_filter input[type=search]").removeClass("form-control-sm");
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
            Column::make('number')->title(trans('ID')),
            Column::make('amount')->title(trans('Amount')),
            Column::make('currency')->title(trans('Currency')),
            Column::make('user')->title(trans('Customer'))->orderable(false),
            Column::make('status')->title(trans('Status')),
            Column::make('hosted_invoice_url')->title(trans('Invoice URL')),
            Column::make('invoice_pdf')->title(trans('Download PDF')),
            Column::make('created_at')->title(trans('Created At')),
            /*Column::computed('actions')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title(trans('Actions')),*/

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Invoice_' . date('YmdHis');
    }
}
