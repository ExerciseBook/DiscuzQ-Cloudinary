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

            $cloudinary_config = Arr::get($filesystem_config, 'disks.cloudinary', null);

            if ($cloudinary_config === null) {
                throw new Exception("No Cloudinary configuration Found.");
            }

            $CloudinaryAdapter = new CloudinaryAdapter($cloudinary_config);

            return new Filesystem($CloudinaryAdapter);
        });
    }
}
