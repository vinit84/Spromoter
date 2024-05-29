<?php

namespace App\DataTables\Admin\Frontend;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PageDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', fn(Page $page) => view('admin.frontend.pages.table.status', ['page' => $page]))
            ->editColumn('slug', fn(Page $page) => view('admin.frontend.pages.table.slug', ['page' => $page]))
            ->editColumn('created_at', fn (Page $page) => dateFormat($page->created_at))
            ->addColumn('actions', fn(Page $page) => view('admin.frontend.pages.table.actions', ['page' => $page]))
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Page $page): QueryBuilder
    {
        return $page->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('page-table')
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
                        window.LaravelDataTables["page-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["page-table"].draw();
                    });
                    $("#page-table_filter input[type=search]").removeClass("form-control-sm");
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
            Column::make('slug')->title(trans('Visit')),
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
        return 'Page_' . date('YmdHis');
    }
}
