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

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile()
            ->registerMediaConversions(function(Media $media){
                $this->addMediaConversion('logo')->width(200)->keepOriginalImageFormat();
                $this->addMediaConversion('icon')->width(50)->keepOriginalImageFormat();
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function hasInvoices()
    {
        return $this->invoices()->exists();
    }
}
