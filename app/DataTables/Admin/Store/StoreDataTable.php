<?php

namespace App\DataTables\Admin\Store;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StoreDataTable extends DataTable
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
                $filterCategory = $this->request->get('filter_category');
                $filterDeleted = $this->request->get('filter_deleted');

                $query->when($filterCategory, function ($query) use ($filterCategory){
                    $query->where('store_category_id', $filterCategory);
                });

                $query->when($filterDeleted, function ($query) use ($filterDeleted){
                    if ($filterDeleted == 'with_trashed'){
                        $query->withTrashed();
                    } elseif ($filterDeleted == 'only_trashed'){
                        $query->onlyTrashed();
                    }
                });
            })
            ->addColumn('user', fn (Store $store) => view('admin.stores.table.user', ['user' => $store->user]))
            ->editColumn('category', fn (Store $store) => view('admin.stores.table.category', ['store' => $store]))
            ->editColumn('url', fn (Store $store) => view('admin.stores.table.url', ['store' => $store]))
            ->editColumn('created_at', fn (Store $store) => dateFormat($store->created_at))
            ->addColumn('actions', fn (Store $store) => view('admin.stores.table.actions', ['store' => $store]))
            ->setRowId('id')
            // Set danger color if store is deleted
            ->setRowClass(function (Store $store) {
                return $store->deleted_at ? 'table-danger' : '';
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Store $store): QueryBuilder
    {
        return $store
            ->with('user')
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('store-table')
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
                    d.filter_category = $("#filter_category").val();
                    d.filter_deleted = $("#filter_deleted").val();
                }',
            ])
            ->parameters([
                'initComplete' => 'function () {
                    function drawTable() {
                        window.LaravelDataTables["store-table"].draw();
                    }

                    $(document).on("confirmActionSuccess", function () {
                        drawTable();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        drawTable();
                    });

                    $(document).on("change", "#filter_category, #filter_deleted", function () {
                        drawTable();
                    });

                    $("#store-table_filter input[type=search]").removeClass("form-control-sm");
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
            Column::make('id')->title(trans('#')),
            Column::make('user')->title(trans('Customer')),
            Column::make('name')->title(trans('Name')),
            Column::make('category')->title(trans('Category')),
            Column::make('url')->title(trans('URL')),
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
        return 'Store_' . date('YmdHis');
    }
}
