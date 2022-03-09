<?php
namespace Sashsvamir\LaravelCodemirrorUploader\Traits;


use Sashsvamir\LaravelCodemirrorUploader\ImageUploaderService;



trait ImageUploadableModel
{

    /**
     * Sign on model events.
     */
    public static function bootImageUploadableModel()
    {
        // static::deleting(function ($model) {...});

        static::deleted(function ($model) {
            ImageUploaderService::deleteAll($model);
        });
    }

}
