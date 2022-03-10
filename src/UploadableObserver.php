<?php
namespace Sashsvamir\LaravelCodemirrorUploader;


use Illuminate\Database\Eloquent\Model;


class UploadableObserver
{

    protected UploadableService $service;

    /**
     * Handle events after all transactions are committed.
     */
    public bool $afterCommit = true;

    public function __construct(UploadableService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->service::deleteAllFiles($model);
    }

    // public function deleting(Model $model): void

}
