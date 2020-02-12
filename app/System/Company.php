<?php

namespace App\System;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Company extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];

    public function registerMediaCollection()
    {
        $this->addMediaCollection('logo')->singleFile()->withResponsiveImages();
    }
}
