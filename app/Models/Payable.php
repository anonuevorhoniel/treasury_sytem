<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payable extends Model
{
    use LogsActivity;
    protected $table = "tbl_payables";
    protected $fillable = ['type',    'dv_number',    'check_number',    'obr_number',    'date',    'particulars', 'fund_type',  'office_id', 'value', 'deduction'];
    protected $searchable = ['type',    'dv_number',    'check_number',    'obr_number',    'date',    'particulars', 'fund_type',  'office_id', 'value', 'deduction'];

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

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
