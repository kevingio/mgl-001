<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndentDetail extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'indent_id',
        'product_id',
        'qty',
        'accepted_at',
        'accepted_by',
    ];

    /**
     * Data serialization
     *
     * @var array
     */
     protected $casts = [
         'accepted_at' => 'date:l, jS F Y',
     ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function indent()
    {
        return $this->belongsTo(Indent::class);
    }
}
