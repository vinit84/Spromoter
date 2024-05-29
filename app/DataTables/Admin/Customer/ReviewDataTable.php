<?php

namespace App\DataTables\Admin\Customer;

use App\Exports\ReviewExport;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $scheduledToPublish = Review::where([
            'store_id' => activeStore()->id,
            'status' => 'pending',
        ])->count();

        return (new EloquentDataTable($query))
            ->filter(function (QueryBuilder $query) {
                if ($this->request->get('basic_filter') == 'scheduled') {
                    $query->where('status', 'pending');
                }

                if ($this->request->get('filter_date_range')) {
                    $dateRange = explode(' - ', $this->request->get('filter_date_range'));
                    $query->whereBetween('created_at', [Carbon::parse($dateRange[0])->startOfDay(), Carbon::parse($dateRange[1])->endOfDay()]);
                }

                if ($this->request->get('filter_products')) {
                    $query->whereHas('product', function (QueryBuilder $query) {
                        $query->whereIn('id', $this->request->get('filter_products'));
                    });
                }

                if ($this->request->get('filter_ratings')) {
                    $query->whereIn('rating', $this->request->get('filter_ratings'));
                }
            })
            ->addColumn('checkbox', fn (Review $review) => '#')
            ->addColumn('details', fn (Review $review) => view('user.reviews.moderation.table.details', compact('review')))
            ->setRowId('id')
            ->with([
                'scheduledToPublish' => $scheduledToPublish,
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Review $review): QueryBuilder
    {
        return $review
            ->whereStoreId(activeStore()->id)
            ->latest()
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('review-table')
            ->columns($this->getColumns())
            ->autoWidth(false)
            ->ordering(false)
            ->dom(config('app.datatables.dom'))
            ->stateSave()
            ->buttons([
                Button::raw([
                    // make a dropdown menu for action to use bulk action
                    'extend' => 'collection',
                    'text' => '<i class="ti ti-select"></i> '. trans('Actions'),
                    'attr' => [
                        'class' => 'btn btn-label-primary buttons-collection waves-effect waves-light hide-arrow',
                        'data-bs-toggle' => 'dropdown',
                    ],
                    'autoClose' => true,
                    'buttons' => [
                        Button::raw([
                            'text' => '<i class="ti ti-circle-arrow-up text-primary me-0 me-sm-1 ti-xs"></i> '. trans("Publish"),
                            'className' => 'confirm-action',
                            'tag' => 'a',
                            'attr' => [
                                'title' => trans('Publish Selected Reviews'),
                                'href' => route('user.reviews.moderation.bulk-publish'),
                                'id' => 'bulk-publish',
                                'data-method' => 'PUT'
                            ]
                        ]),
                        Button::raw([
                            'text' => '<i class="ti ti-trash text-danger me-0 me-sm-1 ti-xs"></i> '. trans("Reject"),
                            'className' => 'confirm-action',
                            'tag' => 'a',
                            'attr' => [
                                'title' => trans('Publish Selected Reviews'),
                                'href' => route('user.reviews.moderation.bulk-reject'),
                                'id' => 'bulk-reject',
                                'data-method' => 'PUT'
                            ]
                        ])
                    ]
                ]),
                Button::raw([
                    'extend' => 'export',
                    'attr' => [
                        'class' => 'btn btn-label-primary buttons-collection buttons-export btn-label-secondary',
                    ],
                    'filename' => $this->filename(),
                    'buttons' => [
                        Button::make('excel'),
                        Button::make('csv'),
                    ],
                ]),
            ])
            ->ajax([
                'data' => 'function (d) {
                    d.basic_filter = $("input[name=basic_filter]:checked").val();
                    d.filter_date_range = $("#filter_date_range").val();
                    d.filter_products = $("#filter_products").val();
                    d.filter_ratings = $("input[name=filter_ratings]:checked").map(function () {
                        return this.value;
                    }).get();
                }',
            ])
            ->columnDefs([
                [
                    'targets' => 0,
                    'searchable' => false,
                    'orderable' => false,
                    'render' => 'function (data, type, row) {
                        return `<input type="checkbox" class="dt-checkboxes form-check-input" value="${row[\'id\']}" />`;
                    }',
                    'checkboxes' => [
                        'selectRow' => false,
                        'selectAllRender' => '<input type="checkbox" class="form-check-input" />',
                    ]
                ]
            ])
            ->parameters([
                'initComplete' => 'function () {
                    $(document).on("confirmActionSuccess", function () {
                        window.LaravelDataTables["review-table"].draw();
                    });

                    $(document).on("confirmDeleteSuccess", function () {
                        window.LaravelDataTables["review-table"].draw();
                    });
                    $("#review-table_filter input[type=search]").removeClass("form-control-sm");
                    $("#review-table_wrapper .dt-buttons").addClass("gap-2");
                    $("#review-table_wrapper .dt-buttons").find(".btn-group").each(function () {
                        $(this).removeClass("btn-group");
                    })

                    $("input[name=basic_filter], #filter_date_range, #filter_products, input[name=filter_ratings]").on("change", function () {
                        window.LaravelDataTables["review-table"].draw();
                    });

                    $(".reply-status").on("change", function() {
                        let id = $(this).val();
                        let $this = $(this);
                        $.ajax({
                            url: route("user.reviews.moderation.comment.change-status", id),
                            method: "PUT",
                            success: function (response) {
                                flash("success", response.message);
                            },
                            error: function (xhr) {
                                flash("error", xhr.responseJSON.message);
                                $this.prop("checked", !$(this).prop("checked"));
                            }
                        });
                    });

                    $(document).on("confirmActionBefore", "#bulk-publish, #bulk-reject", function (e, settings) {
                        // Push to form data
                        let ids = window.LaravelDataTables["review-table"].$("input[type=\'checkbox\']:checked").map(function () {
                            return $(this).val();
                        }).get();

                        settings.data = JSON.stringify({
                            ids: ids,
                        });
                    });
                }',
                'drawCallback' => 'function () {
                    $("#scheduledToPublish").text(this.api().ajax.json().scheduledToPublish);
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
            Column::computed('checkbox')->title('#'),
            Column::computed('details')->title(trans('Details')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Review_' . date('YmdHis');
    }

    public function excel()
    {
        return Excel::download(new ReviewExport, $this->filename() . '.xlsx');
    }

    public function csv()
    {
        return Excel::download(new ReviewExport, $this->filename() . '.csv');
    }

    public function print()
    {

    }
}
