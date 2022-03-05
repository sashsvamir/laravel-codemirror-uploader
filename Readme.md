
## Codemirror with uploader

This is laravel blade component that wrap codemirror component `sashsvamir/laravel-codemirror`
and adding gallery with images and implement uploading these images.



#### Installation:
```sh
composer require sashsvamir/laravel-codemirror-uploader:"dev-main"
```



### Setup


Publish `config/codemirror-uploader.php` config:
```sh
./artisan vendor:publish --provider="Sashsvamir\LaravelCodemirrorUploader\ServiceProvider"
```
...and add directory for storing model images:
```php
return [
    'App\Models\Item' => [
        'path' => 'items',
    ],
];
```



Add to controller trait `ImageUploadable` with actions to get/upload/delete images,
and method `getModelClassnameForImageUploadable()` that must return model classname where images will be attached:
```php
use ImageUploadable;
protected function getModelClassnameForImageUploadable(): string
{
return MyModel::class;
}
```


Next add routes with actions from above controller.
You can define any names or paths as you want, but action name must be named: `imagesIndex`, `imagesStore`, `imagesDestroy`:
```php
Route::get('/admin/api/mymodel/{item}/images', [MyController::class, 'imagesIndex'])->name('admin.api.mymodel.getImages');
Route::post('/admin/api/mymodel/{item}/images', [MyController::class, 'imagesStore'])->name('admin.api.mymodel.uploadImages');
Route::patch('/admin/api/mymodel/{item}/images', [MyController::class, 'imagesDestroy'])->name('admin.api.mymodel.deleteImages');
```


Next, you can add `<x-slbc::codemirror-uploader>` component to implement uploading images for edited model.
Also you must passing urls with `get-url`, `upload-url`, `delete-url` params above action requests,
condition param `visible` need to whether show/hide model (ussaly uploader hiddes for new model):
```html
<x-slbc::codemirror>

    <textarea name="body">Your text here</textarea>
    
    <x-slbc::codemirror-uploader
        :visible="$item->id"
        :get-url="route('admin.api.item.getImages', $item)"
        :upload-url="route('admin.api.item.uploadImages', $item)"
        :delete-url="route('admin.api.item.deleteImages', $item)"
    />
    
</x-slbc::codemirror>
```



