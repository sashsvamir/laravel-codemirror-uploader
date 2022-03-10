<?php
namespace Sashsvamir\LaravelCodemirrorUploader;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\WhitespacePathNormalizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Filter applied to query of items
 */
class UploadableService
{

    protected static function getImagesPath(Model $model): string
    {
        $alias = Config::getModelAlias($model::class);
        $config_path = Config::getStoragePath($alias);

        return (new WhitespacePathNormalizer)->normalizePath($config_path . '/' . $model->getKey());
    }

    protected static function getImagesDisk(): FilesystemAdapter
    {
        return Storage::disk('public');
    }

    protected static function generateFilename(string $basename, int $iteration): string
    {
        $filename = pathinfo($basename, PATHINFO_FILENAME);
        $extension = pathinfo($basename, PATHINFO_EXTENSION);
        $suffix = $iteration === 0 ? '' : '_'.$iteration;
        return $filename . $suffix . '.' . $extension;
    }

    public static function getFiles(Model $model)
    {
        $path = self::getImagesPath($model);
        $disk = self::getImagesDisk();

        $files = [];
        foreach ($disk->files($path) as $file) {
            $files[] = [
                'title' => basename($file),
                'thumb' => $disk->url($file), // todo: implement thumbs
                'original' => $disk->url($file),
            ];
        }

        return $files;
    }

    public static function createFile(Model $model, UploadedFile $file)
    {
        $path = self::getImagesPath($model);
        $disk = self::getImagesDisk();
        $basename = $file->getClientOriginalName();

        // if file exists, generate unique filename
        $i = 0;
        while ($disk->fileExists( $path . '/' . ($newname = self::generateFilename($basename, $i)) )) {
            $i++;
        }

        $filepath = $disk->putFileAs($path, $file, $newname);

        return [
            'file_url' => $disk->url($filepath),
        ];
    }

    public static function deleteFile(Model $model, array $files)
    {
        $path = self::getImagesPath($model);
        $disk = self::getImagesDisk();

        foreach ($files as $file) {
            $filePath = $path . '/' . $file;
            if ($disk->fileExists($filePath)) {
                $disk->delete($filePath);
            }
        }

        $imagesCount = count($disk->files($path));

        // delete directory if empty
        if ($imagesCount === 0) {
            $disk->deleteDirectory($path);
        }

        return [
            'filesCount' => $imagesCount,
        ];
    }

    public static function deleteAllFiles(Model $model): void
    {
        $path = self::getImagesPath($model);
        $disk = self::getImagesDisk();

        $result = $disk->deleteDirectory($path);
        // todo: fire log if some errors happens when deleting directory (when $result is false)
    }
}
