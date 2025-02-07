<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function itemRequest()
    {
        return $this->hasOne(Item::class);
    }
}
