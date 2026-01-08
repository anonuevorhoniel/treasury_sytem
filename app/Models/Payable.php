<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payable extends Model
{
    protected $table = "tbl_payables";
    protected $fillable = ['type',    'dv_number',    'check_number',    'obr_number',    'date',    'particulars',    'ps',    'ps_deduction',    'mooe',    'mooe_deduction',    'co', 'office_id',    'co_deduction'];
    protected $searchable = ['type',    'dv_number',    'check_number',    'obr_number',    'date',    'particulars',    'ps',    'ps_deduction',    'mooe',    'mooe_deduction',    'co', 'office_id',    'co_deduction'];

    public function scopeSearchAllFillable(Builder $query, $search = "")
    {
        if ($search == "") {
            return $query;
        }
        $columns = $this->getFillable();
        return  $query->where(function ($q) use ($search, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, "like", "%$search%");
            }
        });
    }
}
