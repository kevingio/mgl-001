<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'qty',
    ];

    /**
     * The attributes that are assign to date.
     *
     * @var array
     */
    protected $dates =['deleted_at'];
}
