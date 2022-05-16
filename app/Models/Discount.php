<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Brackets\Media\HasMedia\ProcessMediaTrait;
use Brackets\Media\HasMedia\AutoProcessMediaTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Brackets\Media\HasMedia\HasMediaThumbsTrait;

class Discount extends Model implements HasMedia
{
    use ProcessMediaTrait;
    use AutoProcessMediaTrait;
    use HasMediaCollectionsTrait;
    use HasMediaThumbsTrait;

    protected $fillable = [
        'name',
        'date_show_image',
        'deposit',
        'discount',
        'from_date',
        'to_date',

    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url', 'image_url'];

    /* ************************ RELATIONS ************************* */
    public function history()
    {
        return $this->hasMany('App\Models\HistoryDiscount', 'discount_id');
    }

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/discounts/' . $this->getKey());
    }

    public function getImageUrlAttribute()
    {
        $mediaItems = $this->getMedia('image');
        return isset($mediaItems[0]) ? $mediaItems[0]->getUrl() : "";
    }

    /* ************************ MEDIA ************************ */

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image')
            ->accepts('image/*')
            ->maxNumberOfFiles(1);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->autoRegisterThumb200();
    }

    /**
     * Auto register thumb overridden
     */
    public function autoRegisterThumb200()
    {
        $this->getMediaCollections()->filter->isImage()->each(function ($mediaCollection) {
            $this->addMediaConversion('thumb_200')
                ->width(200)
                ->height(200)
                ->fit('crop', 200, 200)
                ->optimize()
                ->performOnCollections($mediaCollection->getName())
                ->nonQueued();
        });
    }

    /* ************************ SET FIELD ************************ */
    public function setDateShowImageAttribute($value)
    {
        if (strlen($value) > 10) {
            $this->attributes['date_show_image'] = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
        }
    }

    public function setFromDateAttribute($value)
    {
        if (strlen($value) > 10) {
            $this->attributes['from_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
        }
    }

    public function setToDateAttribute($value)
    {
        if (strlen($value) > 10) {
            $this->attributes['to_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
        }
    }

}
