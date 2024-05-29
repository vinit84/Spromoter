<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filterColumn('user', function ($query, $keyword) {
                $query->where('first_name', 'like', '%' . $keyword . '%')
                    ->orWhere('last_name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            })
            ->setRowClass(function ($customer) {
                return $customer->deleted_at ? 'table-danger' : '';
            })
            ->orderColumn('user', 'first_name $1, last_name $1')
            ->addColumn('user', fn(User $customer) => view('admin.customers.table.user', compact('customer')))
            ->editColumn('status', fn(User $customer) => view('admin.customers.table.status', compact('customer')))
            ->editColumn('created_at', fn(User $customer) => view('admin.customers.table.created_at', compact('customer')))
            ->addColumn('actions', fn(User $customer) => view('admin.customers.table.actions', compact('customer')))
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model
            ->when($this->request()->get('status'), function ($query) {
                $query->where('status', $this->request()->get('status'));
            })
            ->when($this->request()->get('deleted'), function ($query) {
                if ($this->request()->get('deleted') === 'with_deleted') {
                    $query->withTrashed();
                } elseif ($this->request()->get('deleted') === 'only_deleted') {
                    $query->onlyTrashed();
                }
            })
            ->whereGroup('customer')
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customer-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function (d) {
                    d.status = $("#filterStatus").val();
                    d.deleted = $("#filterDeleted").val();
                }',
            ])
            ->autoWidth(false)
            ->responsive()
            ->stateSave()
            ->select(false)
            ->dom(config('app.datatables.dom'))
            ->orderBy(3)
            ->buttons([
                Button::make('export'),
            ])
            ->parameters([
                'initComplete' => 'function () {
                    $("#filterStatus, #filterDeleted").on("select2:select", function () {
                        window.LaravelDataTables["customer-table"].draw();
                    });
                    $("#filterStatus, #filterDeleted").on("select2:clear", function () {
                        window.LaravelDataTables["customer-table"].draw();
                    });

                    $(document).on("confirmActionSuccess", function () {
                        window.LaravelDataTables["customer-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["customer-table"].draw();
                    });

                    $("#customer-table_filter input[type=search]").removeClass("form-control-sm");
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
            Column::make('user')->title(trans('User')),
            Column::make('status')->title(trans('Status'))->addClass('text-center'),
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
        return 'User_' . date('YmdHis');
    }
}
