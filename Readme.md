# Codemirror with uploader

This is laravel blade component is wrapping codemirror textarea component `sashsvamir/laravel-codemirror`
with providing implementation of uploading files for model. Also component include uploaded image gallery attached to textarea codemirror.



## Installation:
```sh
composer require sashsvamir/laravel-codemirror-uploader
```



## Setup:


Publish `config/codemirror-uploader.php` config:
```sh
./artisan vendor:publish --tag=codemirror-uploader-config
```
...and configure params as described:
```php
return [
      'my_post' => [                              // alias, please use simple one word string here (internally it's using to identify uploadable model on post requests, also on building url requests),
     
          'model_classname' => 'App\Models\Post', // model class name (e.g.: \App\Models\Model::class)
          'storage_path' => 'posts',              // path of storage relative at ./storage/app/public/
     
          'route_middlewares' => [                // optional: specify route middlewares if needed, be aware — the default is empty
              'web',
              'auth',
              'can:edit-post'
          ],
          'route_prefix' => '/admin',      // optional: add route prefix if needed
     
      ],
];
```
With this config, will be adding route like (you not needed set this explicit):
```php
Route::middleware(['web', 'auth', 'can:edit-users']) // middleware gets from config
     ->prefix('/admin')                              // prefix gets from config
     ->post('codemirror-uploader/my-post', UploadableController::class) // uri was generated
     ->name('codemirror-uploader-my-post');                             // name was generated
```



If you want uploaded images to be deleted on destroy model, add trait to model:
```php
class Post extends Model
{
    use ModelUploadableTrait;
}
```






Next, you can add `<x-slbc::codemirror-uploader>` component to implement uploading images for edited model.
Gallery will be added only if model is exists (saved):
```html
<x-slbc::codemirror>

    <textarea name="body">Your text here</textarea>
    
    <x-slbc::codemirror-uploader :model="$model" />
    
</x-slbc::codemirror>
```



