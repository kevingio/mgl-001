<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostingDetail extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'posting_id',
        'product_id',
        'qty',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function posting()
    {
        return $this->belongsTo(Posting::class);
    }
}
