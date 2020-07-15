<?php

namespace ExerciseBook\DiscuzQCloudinary;

use CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter as Adapter;
use Discuz\Http\UrlGenerator;
use League\Flysystem\Adapter\CanOverwriteFiles;

/**
 * Class LocalAdapter
 * @package ExerciseBook\DiscuzQCloudinary
 */
class CloudinaryAdapter extends Adapter implements CanOverwriteFiles
{
    /**
     * @var array
     */
    protected $config;

    /**
     * CloudinaryAdapter constructor.
     *
     * @param array $config
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        parent::__construct($config);
    }

    /**
     * 获取本地 图片/附件 Url地址
     *
     * @param $path
     * @return mixed
     */
    public function getUrl($path)
    {
        return 'https://res.cloudinary.com/' . $this->config['cloud_name'] . '/upload' . $path;
    }
}
