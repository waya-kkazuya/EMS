<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Edithistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'edit_mode',
        'operation_type',
        'item_id',
        'category_id',
        'edited_field',
        'old_value',
        'new_value',
        'edit_user',
    ];

    public function scopeItemHistory()
    {
        
        
    }


    public function scopeBetweenDate($query, $startDate = null, $endDate = null)
    {
        if(is_null($startDate) && is_null($endDate))
        { return $query; }

        if(!is_null($startDate) && is_null($endDate))
        { return $query->where('edited_at', ">=", $startDate); }

        if(is_null($startDate) && !is_null($endDate))
        {
            $endDate1 = Carbon::parse($endDate)->addDays(1);
            return $query->where('edited_at', '<=', $endDate1);
        }

        if(!is_null($startDate) && !is_null($endDate))
        {
            return $query->where('edited_at', ">=", $startDate)
            ->where('edited_at', '<=', $endDate);
        }
    }
}
