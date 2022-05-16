<?php

namespace App\Models;

use Brackets\Media\HasMedia\AutoProcessMediaTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Brackets\Media\HasMedia\HasMediaThumbsTrait;
use Brackets\Media\HasMedia\ProcessMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;

class AutoBot extends Model implements HasMedia
{
    use ProcessMediaTrait;
    use AutoProcessMediaTrait;
    use HasMediaCollectionsTrait;
    use HasMediaThumbsTrait;

    protected $fillable = [
        'name',
        'image',
        'price',
        'commission_f1',
        'commission_7',
        'commission_21',
        'commission_30',
        'commission_90',
        'risk',
    ];
    
    
    protected $dates = [
        'created_at',
        'updated_at',
    
    ];
    
    protected $appends = ['resource_url', 'image_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/auto-bots/'.$this->getKey());
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
}
