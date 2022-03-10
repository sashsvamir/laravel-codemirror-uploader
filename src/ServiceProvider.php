<?php
namespace Sashsvamir\LaravelCodemirrorUploader;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot(): void
    {
        // publish config
        $this->publishes([
            __DIR__.'/../config/codemirror-uploader.php' => config_path('codemirror-uploader.php'),
        ], 'codemirror-uploader-config');

        // load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // load model observer for every model
        foreach (Config::getAliases() as $alias) {
            (Config::getModelClassname($alias))::observe(UploadableObserver::class);
        }

        // load components
        $this->loadViewsFrom([
            __DIR__.'/../resources/views'
        ], 'slbc');
    }

}
