<?php

namespace App\DataTables\Admin\Settings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LanguageDataTable extends DataTable
{
    /**
     * Build DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_rtl', fn(Language $language) => isActiveBadge($language->is_rtl, trans('Yes'), trans('No')))
            ->editColumn('is_active', fn(Language $language) => isActiveBadge($language->is_active))
            ->editColumn('created_at', fn ($query) => dateFormat($query->created_at))
            ->addColumn('actions', 'admin.settings.languages.table.actions')
            ->setRowId('id')
            ->rawColumns(['is_rtl', 'is_active', 'actions']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Language $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('language-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->autoWidth(false)
            ->responsive()
            ->stateSave()
            ->dom(config('app.datatables.dom'))
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make([
                    'extend' => 'export',
                    'text' => trans('Export'),
                ]),
            ])
            ->parameters([
                'initComplete' => 'function () {
                    $("#user-language_filter input[type=search]").removeClass("form-control-sm");
                }',
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => trans('Search...')
                ]
            ]);
    }

    /**
     * Get columns.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title(trans('Name')),
            Column::make('code')->title(trans('Code')),
            Column::make('is_rtl')->title(trans('Is RTL')),
            Column::make('is_active')->title(trans('Status')),
            Column::make('created_at')->title(trans('Created')),
            Column::computed('actions')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title(trans('Actions')),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Language_'.date('YmdHis');
    }
}
