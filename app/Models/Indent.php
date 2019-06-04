<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'posting_id',
        'status',
        'note',
    ];

    public function posting()
    {
        return $this->belongsTo(Posting::class);
    }

    public function details()
    {
        return $this->hasMany(IndentDetail::class);
    }
}
