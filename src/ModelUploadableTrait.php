<?php
namespace Sashsvamir\LaravelCodemirrorUploader;


trait ModelUploadableTrait
{

    /**
     * Sign on model events.
     */
    public static function bootModelUploadableTrait()
    {
        // static::deleting(function ($model) {...});

        static::deleted(function ($model) {
            UploadableService::deleteAllFiles($model);
        });
    }

}
