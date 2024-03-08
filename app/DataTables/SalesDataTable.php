<?php

namespace App\DataTables;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;


class SalesDataTable extends DataTable
{
    private $start_date;
    private $end_date;

    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'sales.action')
            ->setRowId('id');
    }

    public function query(Invoice $invoice )
    {
//        dd($request);
//        $this->start_date = $this->request->input('start_date');
//        $this->end_date = $this->request->input('end_date');
//        $query = $invoice->newQuery()
//            ->with('user')
//            ->with('branch')
//            ->with('entryBy')
//            ->with('updatedBy')
//            ->where('transaction_type', 'Sales')
//            ->orderBy('transaction_date', 'desc')
//            ->orderBy('transaction_code', 'desc')
//            ->latest();
//        if ($this->start_date && $this->end_date) {
//            $query->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
//        }
//        dd($query);
//        return $query;
        return $invoice->newQuery()->latest();

    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('sales-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->title('S/N')
                ->addClass('text-center'),
            Column::make('transaction_date')
                ->title('Transaction Date')
                ->addClass('text-left'),
            Column::make('action')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Sales_' . date('YmdHis');
    }

}
