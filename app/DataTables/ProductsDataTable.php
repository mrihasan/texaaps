<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($product) {
                return view('product.action', compact('product'));
            })
            ->setRowId('id');
    }

    public function query(Product $product): QueryBuilder
    {

        return $product->newQuery()->with('company_name')->with('product_type')->with('brand')->with('unit')
            ->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('products-table')
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
            Column::make('title')
                ->title('Title')
                ->addClass('text-left'),
            Column::make('product_type.title')
                ->title('Type')
                ->addClass('text-center'),
            Column::make('company_name.title')
                ->title('Company')
                ->addClass('text-center'),
            Column::make('brand.title')
                ->title('Brand')
                ->addClass('text-center'),
            Column::make('unit.title')
                ->title('Unit')
                ->addClass('text-center'),
//            Column::make('unitbuy_price')
//                ->title('Unit Buy Price')
//                ->addClass('text-center'),
//            Column::make('unitsell_price')
//                ->title('Unit Sell Price')
//                ->addClass('text-center'),
            Column::make('status')
                ->title('Status')
                ->addClass('text-center'),
            Column::make('action')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
