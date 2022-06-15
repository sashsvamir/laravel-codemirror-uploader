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
note: defined `model_classname` in config, also will be using to attach observer to this model event listener (to delete images on model deleting)
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









Next, you can add `<x-slbc::textarea-codemirror-uploader>` component to implement uploading images for edited model.
Gallery will be added only if model is exists (saved):
```html
<x-slbc::textarea-codemirror>

    <textarea name="body">Your text here</textarea>
    
    <x-slbc::textarea-codemirror-uploader :model="$model" />
    
</x-slbc::textarea-codemirror>
```



