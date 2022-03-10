<?php
namespace Sashsvamir\LaravelCodemirrorUploader;

class Config
{
    protected static string $configName = 'codemirror-uploader';
    protected static string $routeSuffix = 'codemirror-uploader';

    /**
     * @return array<string>
     */
    public static function getAliases(): array
    {
        return array_keys(config(self::$configName, []));
    }

    /**
     * Get model alias by classname
     */
    public static function getModelAlias(string $modelClassName): string
    {
        $result = null;

        foreach (config(self::$configName) as $alias => $arr) {
            if ($arr['model_classname'] === $modelClassName) {
                $result = $alias;
                break;
            }
        }

        if (!$result) {
            throw new \DomainException('Alias for model "' . $modelClassName . '" not found in config.');
        }

        return $result;
    }

    /**
     * Get route name by alias
     */
    public static function getRouteName(string $alias): string
    {
        return static::$routeSuffix . '-' . $alias;
    }

    /**
     * Get route uri by alias
     */
    public static function getRouteUri(string $alias): string
    {
        return static::$routeSuffix . '/' . $alias;
    }

    /**
     *Get model route middlewares
     * @return array<string>
     */
    public static function getRouteMiddlewares(string $alias): array
    {
        return config(self::$configName . '.' . $alias . '.route_middlewares', []);
    }

    /**
     * Get model route prefix
     */
    public static function getRoutePrefix(string $alias): string
    {
        return config(self::$configName . '.' . $alias . '.route_prefix', '');
    }

    /**
     * Get model classname by alias
     */
    public static function getModelClassname(string $alias): string
    {
        $classname = config('codemirror-uploader.' . $alias .'.model_classname');

        if (!$classname) {
            throw new \DomainException('Model classname for alias "' . $alias . '" not found in config.');
        }

        return $classname;
    }

    /**
     * Get storage path of classname model
     */
    public static function getStoragePath(string $alias): string // todo: change argument to alias
    {
        $config_path = self::$configName . '.' . $alias . '.storage_path';

        if (! $path = config($config_path)) {
            throw new \Exception('Config "' . $config_path . '" not found.');
        }

        return $path;
    }


}

