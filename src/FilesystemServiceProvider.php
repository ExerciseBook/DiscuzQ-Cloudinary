<?php

namespace ExerciseBook\DiscuzQCloudinary;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Filesystem\LocalAdapter;
use Illuminate\Filesystem\FilesystemServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
use League\Flysystem\Filesystem;

class FilesystemServiceProvider extends ServiceProvider
{

    /**
     * @param string $string
     * @param $default
     * @return mixed
     */
    public function get_config($app, string $string, $default)
    {
        return (Arr::get(app()['discuz.config'], $string, $default));
    }


    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->app->make('filesystem')->extend('cloudinary', function ($app, $config) {
            $filesystem_config = $this->get_config($app,'filesystems', null);

            if ($filesystem_config === null) {
                throw new Exception("No filesystem configuration declared.");
            }

            // 找到配置
            $cloudinary_config = Arr::get($filesystem_config, 'disks.cloudinary', null);

            // 找到备份配置
            $default_config =  Arr::get($filesystem_config, 'default', 'local');
            $fallback_config = Arr::get($cloudinary_config, 'fallback', $default_config);

            // 确认备份配置
            $fallback_config = Arr::get($filesystem_config, 'disks.local', null);
            if ($fallback_config === null) {
                throw new Exception("No fallback configuration");
            }

            $LocalAdapter = new LocalAdapter($fallback_config);
            $CloudinaryAdapter = new CloudinaryAdapter($cloudinary_config);

            return new Filesystem($CloudinaryAdapter);
        });
    }
}
