<?php
use Illuminate\Support\Facades\Route;
use Sashsvamir\LaravelCodemirrorUploader\UploadableController;
use Sashsvamir\LaravelCodemirrorUploader\Config;

$aliases = Config::getAliases();

// add routes
foreach ($aliases as $alias) {

    $route_name = Config::getRouteName($alias);
    $route_uri = Config::getRouteUri($alias);
    $middlewares = Config::getRouteMiddlewares($alias);
    $prefix = Config::getRoutePrefix($alias);

    Route::middleware($middlewares)
        ->prefix($prefix)
        ->post($route_uri, UploadableController::class)
        ->name($route_name)
    ;

}

