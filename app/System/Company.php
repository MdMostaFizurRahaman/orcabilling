<?php

namespace App\System;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Company extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];

    // public function registerMediaCollection()
    // {
    //     $this->addMediaCollection('avatar')->singleFile();
    // }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile()
            ->registerMediaConversions(function(Media $media){
                $this->addMediaConversion('logo')->width(200);
                $this->addMediaConversion('icon')->width(50);
                // $this->addMediaConversion('icon')->width(50)->height(30);
            });
    }

    public function getLogoAttribute()
    {
        return $this->getFirstMedia('avatar')->getUrl('logo');
    }

    public function getIconAttribute()
    {
        return $this->getFirstMedia('avatar')->getUrl('icon');
    }
}
