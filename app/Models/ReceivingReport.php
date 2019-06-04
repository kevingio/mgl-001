<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivingReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_date',
        'received_at',
        'received_by',
    ];

    /**
     * Data serialization
     *
     * @var array
     */
     protected $casts = [
         'shipping_date' => 'date:l, jS F Y',
         'received_at' => 'date:l, jS F Y',
     ];

    public function details()
    {
        return $this->hasMany(ReceivingDetail::class);
    }
}
