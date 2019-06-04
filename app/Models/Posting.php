<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'pic',
        'type_id',
    ];

    /**
     * Data serialization
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:l, jS F Y',
    ];

    public function details()
    {
        return $this->hasMany(PostingDetail::class);
    }

    public function type()
    {
        return $this->belongsTo(PostingType::class, 'type_id');
    }

    public function indent()
    {
        return $this->hasOne(Indent::class);
    }

}
