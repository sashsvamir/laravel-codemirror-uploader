<?php
return [

    /**
     * Example params for Post model:
     *
     * 'my_post' => [                              // alias, please use simple one word string here (internally it's using to identify uploadable model on post requests, also on building url requests),
     *
     *     'model_classname' => 'App\Models\Post', // model class name
     *     'storage_path' => 'posts',              // path of storage relative at ./storage/app/public/
     *
     *     'route_middlewares' => [                // specify route middlewares if needed, be aware — the default is empty
     *         'web',
     *         'auth',
     *         'can:edit-post'
     *     ],
     *     'route_prefix' => '/admin/posts/',
     *
     * ]
     *
     */

];
