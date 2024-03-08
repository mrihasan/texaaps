<?php

namespace App\DataTables;

// app/DataTables/ExpenseDataTable.php

use App\Models\Expense;
use Yajra\DataTables\Services\DataTable;

class ExpenseDataTable extends DataTable
{
    // Define start_date and end_date properties
//    private $start_date;
//    private $end_date;

    // Add a constructor to initialize start_date and end_date
//    public function __construct($start_date = null, $end_date = null)
//    {
//        $this->start_date = $start_date;
//        $this->end_date = $end_date;
//    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'path.to.your.action.view') // replace with your action view path
            ->filterColumn('expense_date', function ($query, $keyword) {
                // Handle the date range filtering here
//                if (!empty($this->start_date)) {
//                    $query->whereDate('expense_date', '>=', $this->start_date);
//                }
//                if (!empty($this->end_date)) {
//                    $query->whereDate('expense_date', '<=', $this->end_date);
//                }
                $query->whereBetween('expense_date', [$this->start_date, $this->end_date]);
            });
    }

    public function query(Expense $expense)
    {
        // Apply additional filters based on start_date and end_date
        $query = $expense->newQuery();

//        if (!empty($this->start_date)) {
//            $query->whereDate('expense_date', '>=', $this->start_date);
//        }
//        if (!empty($this->end_date)) {
//            $query->whereDate('expense_date', '<=', $this->end_date);
//        }
        $query->whereBetween('expense_date', [$this->start_date, $this->end_date]);
        dd($this->start_date);
        return $query;
    }
    protected function getColumns()
    {
        return [
            'id',
            'expense_date',
            'expense_amount',
            'comments',
            'user_id',
            'created_at',
            'updated_at',
        ];
    }
}
