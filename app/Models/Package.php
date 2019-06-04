<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name_with_products'];

    /**
    * Get the post title.
    *
    * @param  string  $value
    * @return string
    */
    public function getNameWithProductsAttribute($value)
    {
        $products = $this->find($this->attributes['id'])->details;
        $productsToString = '';
        foreach ($products as $key => $value) {
            $productsToString .= $key != 0 && $key != sizeof($products) ? ', ' : '';
            $productsToString .= $value->qty . ' ' . $value->product->name;
        }
        return $this->attributes['name'] . ' (' . $productsToString . ')';
    }

    public function details()
    {
        return $this->hasMany(PackageDetail::class);
    }
}
