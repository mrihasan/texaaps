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

class PurchaseDataTable extends DataTable
{
    private $start_date;
    private $end_date;
    public function dataTable(QueryBuilder $query)
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'purchase.action')
            ->setRowId('id');
    }
    public function query(Invoice $invoice)
    {
//        $query = $invoice->newQuery();
//        return $query;
//        dd($this->start_date, $this->end_date);
        $query = $invoice->newQuery()
            ->with('user')
            ->with('branch')
            ->with('entryBy')
            ->with('updatedBy')
            ->where('transaction_type', 'Purchase')
//            ->whereBetween('transaction_date', ['2023-12-1 00:00:00', '2023-12-31 00:00:00'])
//            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('transaction_code', 'desc');
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
//            $query->whereBetween('transaction_date', ['2024-1-1 00:00:00', '2024-1-31 00:00:00']);
        }
        return $query;
    }
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
//        dd($this);
        return $this;
    }
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
        return $this;
    }
    public function html()
    {
        return $this->builder()
            ->columns([
            ])
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => ['export', 'print', 'reset', 'reload'],
                'ajax' => route('purchaseTransaction'),
            ]);
    }
    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->title('S/N')
                ->addClass('text-center'),
            Column::make('transaction_date')
                ->title('Date')
                ->addClass('text-left'),
            Column::make('transaction_code')
                ->title('Transaction Code')
                ->addClass('text-center'),
            Column::make('transaction_type')
                ->title('Transaction Type')
                ->addClass('text-center'),
            Column::make('user_id')
                ->title('User')
                ->addClass('text-center'),
            Column::make('branch_id')
                ->title('Branch')
                ->addClass('text-center'),
            Column::make('product_total')
                ->title('Product Price')
                ->addClass('text-center'),
            Column::make('total_amount')
                ->title('Total')
                ->addClass('text-center'),
            Column::make('invoice_total')
                ->title('Invoice Total')
                ->addClass('text-center'),
            Column::make('action')
                ->addClass('text-center'),
        ];
    }
    protected function filename(): string
    {
        return 'Purchase_' . date('YmdHis');
    }
}
