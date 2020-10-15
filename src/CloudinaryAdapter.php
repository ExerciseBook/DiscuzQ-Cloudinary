<?php

namespace ExerciseBook\DiscuzQCloudinary;

use CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter as Adapter;
use Cloudinary\Api\GeneralError;
use Exception;
use Illuminate\Cache\Repository;
use League\Flysystem\Adapter\CanOverwriteFiles;
use app\Models\Attachment;

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
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        parent::__construct($config);
    }

    /**
     * @return Repository
     */
    public function getCacheRepository(){
        // TODO 可能会有更优雅的写法
        $cache = app()['cache'];
        return $cache->driver($cache->getDefaultDriver());
    }

    /**
     * 获取本地 图片/附件 Url地址
     *
     * @param $path
     * @return mixed
     * @throws GeneralError
     */
    public function getUrl($path)
    {
        $cache = $this->getCacheRepository();
        $cache_key = "cloudinary-url-cache-".$path;

        // 有缓存
        $cache_date = $cache->get($cache_key, null);
        if ($cache_date != null) {
            return $cache_date;
        }

        // 无缓存
        $attachment = Attachment::query()
            ->whereRaw("concat(file_path, attachment) = ?", [$path])
            ->orWhereRaw('concat(file_path, "/", attachment) = ?', [$path])
            ->first();
        $type = explode('/', $attachment->file_type)[0];
        switch ($type) {
            case "image" : $type = "image"; break;

            case "audio" :
            case "video" : $type = "video"; break;

            default : $type = "raw";
        }
        $s = $this->api->resources_by_ids($path, ["resource_type" => $type]);
        try {
            $ret = $s['resources'][0]['secure_url'];
        } catch (Exception $e) {
            $ret = $path;
        }

        // 写入缓存
        $cache->put($cache_key, $ret, 60 * 60 * 24);

        return $ret;
    }
}
