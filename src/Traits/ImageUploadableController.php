<?php
namespace Sashsvamir\LaravelCodemirrorUploader\Traits;


use Sashsvamir\LaravelCodemirrorUploader\ImageUploaderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


trait ImageUploadableController
{

    abstract protected function getModelClassnameForImageUploadable(): string;

    protected function getModelOrAbort(int $id): Model
    {
        if (! $model = $this->getModelClassnameForImageUploadable()::find($id)) {
            abort(404);
        }
        return $model;
    }

    public function imagesIndex(int $modelId)
    {
        $model = $this->getModelOrAbort($modelId);
        return ImageUploaderService::get($model);
    }

    public function imagesStore(Request $request, int $modelId)
    {
        $model = $this->getModelOrAbort($modelId);

        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->files->get('file');

        return ImageUploaderService::create($model, $file);
    }

    public function imagesDestroy(Request $request, int $modelId)
    {
        $model = $this->getModelOrAbort($modelId);

        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'string'],
        ]);

        $files = $request->get('files');

        return ImageUploaderService::delete($model, $files);
    }
}
