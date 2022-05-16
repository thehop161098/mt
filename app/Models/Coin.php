<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = [
        'name',
        'image',
        'alias',
        'range',
        'min',
        'max',
        'is_gold',
        'publish'
    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'is_gold' => 'boolean',
        'publish' => 'boolean',
    ];

    protected $appends = ['resource_url', 'image_url', 'publish_text'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/coins/' . $this->getKey());
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return 'frontend/images/icons/nonameCoin.svg';
        }
        return $this->image;
    }

    public function getPublishTextAttribute()
    {
        if ($this->publish) {
            return 'Published';
        }
        return 'Unpublish';
    }
}
