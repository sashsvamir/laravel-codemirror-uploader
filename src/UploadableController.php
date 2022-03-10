<?php
namespace Sashsvamir\LaravelCodemirrorUploader;


use Illuminate\Database\Eloquent\Model;


class UploadableController
{
    protected UploadableService $service;

    public function __construct(UploadableService $service)
    {
        $this->service = $service;
    }

    public function __invoke(UploadableRequest $request)
    {
        switch ($request->getAction()) {
            case 'get':
                return $this->index($request);
            case 'upload':
                return $this->store($request);
            case 'delete':
                return $this->destroy($request);
        }
    }

    protected static function getModelOrAbort(UploadableRequest $request): Model
    {
        $model_classname = Config::getModelClassname($request->getModelAlias());

        // find model by id
        if (! $model = $model_classname::find($request->getModelId())) {
            abort(404);
        }

        return $model;
    }

    protected function index(UploadableRequest $request)
    {
        $model = static::getModelOrAbort($request);

        return $this->service::getFiles($model);
    }

    protected function store(UploadableRequest $request)
    {
        $model = static::getModelOrAbort($request);

        $file = $request->files->get('file');

        return $this->service::createFile($model, $file);
    }

    protected function destroy(UploadableRequest $request)
    {
        $model = static::getModelOrAbort($request);

        $files = $request->get('files');

        return $this->service::deleteFile($model, $files);
    }

}
