<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivingDetail extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receiving_report_id',
        'product_id',
        'qty',
        'note',
    ];

    public function report()
    {
        return $this->belongsTo(ReceivingReport::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
