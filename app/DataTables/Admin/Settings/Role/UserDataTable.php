<?php

namespace App\DataTables\Admin\Settings\Role;

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
            ->filterColumn('user', function ($query, $keyword){
                $query->where('first_name', 'like', '%' . $keyword . '%')
                    ->orWhere('last_name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('roles', function ($query, $keyword){
                $query->whereHas('roles', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->addColumn('user', fn (User $user) => view('admin.settings.roles.table.user', compact('user')))
            ->addColumn('roles', fn (User $user) => view('admin.settings.roles.table.roles', compact('user')))
            ->editColumn('status', fn (User $user) => view('admin.settings.roles.table.status', compact('user')))
            ->editColumn('created_at', fn (User $user) => dateFormat($user->created_at))
            ->addColumn('actions', 'admin.settings.roles.table.actions')
            ->setRowId('id')
            ->rawColumns(['user', 'actions']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model
            ->whereGroup('admin')
            ->with('roles')
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
            ->minifiedAjax()
            ->autoWidth(false)
            ->responsive()
            ->stateSave()
            ->dom(config('app.datatables.dom'))
            ->orderBy(1)
            ->selectStyleSingle()
            ->addTableClass('datatables-users table border-top')
            ->select(false)
            ->buttons([
                Button::make('export'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('user')->title(trans('User')),
            Column::make('roles')->title(trans('Roles')),
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
        return 'User_' . date('YmdHis');
    }
}
