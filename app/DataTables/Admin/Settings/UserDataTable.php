<?php

namespace App\DataTables\Admin\Settings;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
            ->filterColumn('roles', function ($query, $keyword) {
                $query->orWhereHas('roles', function ($query) use ($keyword) {
                    $query->orWhere('name', 'like', '%' . $keyword . '%');
                });
            })
            ->orderColumn('user', 'first_name $1, last_name $1')
            ->addColumn('user', fn(User $user) => view('admin.settings.users.table.user', compact('user')))
            ->addColumn('roles', fn(User $user) => view('admin.settings.users.table.roles', compact('user')))
            ->editColumn('status', fn(User $user) => view('admin.settings.users.table.status', compact('user')))
            ->editColumn('created_at', fn(User $user) => dateFormat($user->created_at))
            ->addColumn('actions', fn(User $user) => view('admin.settings.users.table.actions', compact('user')))
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model
            ->with('roles')
            ->when($this->request()->get('role'), function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', $this->request()->get('role'));
                });
            })
            ->when($this->request()->get('status'), function ($query) {
                $query->where('status', $this->request()->get('status'));
            })
            ->whereGroup('admin')
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function (d) {
                    d.role = $("#filterRole").val();
                    d.status = $("#filterStatus").val();
                }',
            ])
            ->autoWidth(false)
            ->responsive()
            ->stateSave()
            ->dom(config('app.datatables.dom'))
            ->orderBy(5)
            ->selectStyleSingle()
            ->buttons([
                Button::make('export'),
            ])
            ->parameters([
                'initComplete' => 'function () {
                    $("#filterRole, #filterStatus").on("select2:select", function (e) {
                        window.LaravelDataTables["user-table"].draw();
                    });
                    $("#filterRole, #filterStatus").on("select2:clear", function (e) {
                        window.LaravelDataTables["user-table"].draw();
                    });

                    $(document).on("confirmActionSuccess", function () {
                        window.LaravelDataTables["user-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["user-table"].draw();
                    });

                    $("#user-table_filter input[type=search]").removeClass("form-control-sm");
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
            Column::make('roles')->title(trans('Roles'))->orderable(false),
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
