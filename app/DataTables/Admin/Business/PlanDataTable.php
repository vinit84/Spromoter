<?php

namespace App\DataTables\Admin\Business;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Number;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PlanDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('monthly_price', fn (Plan $plan) => Number::currency($plan->monthly_price))
            ->editColumn('yearly_price', fn (Plan $plan) => Number::currency($plan->yearly_price))
            ->editColumn('is_active', fn (Plan $plan) => view('admin.business.plans.table.status', compact('plan')))
            ->editColumn('created_at', fn (Plan $plan) => dateFormat($plan->created_at))
            ->addColumn('actions', fn (Plan $plan) => view('admin.business.plans.table.actions', compact('plan')))
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Plan $plan): QueryBuilder
    {
        return $plan->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('plan-table')
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
                        window.LaravelDataTables["plan-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["plan-table"].draw();
                    });
                    $("#plan-table_filter input[type=search]").removeClass("form-control-sm");
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
            Column::make('title')->title(trans('Title')),
            Column::make('monthly_price')->title(trans('Monthly Price')),
            Column::make('yearly_price')->title(trans('Yearly Price')),
            Column::make('is_active')->title(trans('Status')),
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
        return 'Plan_' . date('YmdHis');
    }
}
