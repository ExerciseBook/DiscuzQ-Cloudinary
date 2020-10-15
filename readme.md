# 概述
Discuz Q Cloudinary 存储支持

# 配置
1. 使用指令 `composer require exercisebook/discuzq-cloudinary` 下载本库。
2. 在 `config/config.php` 中的 `providers` 添加 `ExerciseBook\DiscuzQCloudinary\FilesystemServiceProvider::class` 使得 DiscuzQ 可以正常加载本库。
3. 在 `config/config.php` 中的 `filesystems.disks` 添加您的 Cloudinary 登陆信息。
    ```
    'cloudinary' => [
        'driver'        => 'cloudinary',
        'cloud_name'    => 'XXX',
        'api_key'       => 'XXX',
        'api_secret'    => 'XXX',
        'secure'        => true
    ],
   ```
4. 在 `config/config.php` 中的 `filesystems.disks` 里你想要使用 Cloudinary 存储的部分的 `driver` 改为 `cloudinary`。

最终配置文件示意：
```php
    //文件系统配置
    'filesystems' => [
        'default' => 'local',
        'cloud' => 'cos',
        'disks' => [
            'public' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => 'public',
                'visibility' => 'public',
            ],
            'avatar' => [
                'driver' => 'local',
                'root' => storage_path('app/public/avatars'),
                'url' => 'avatar',
                'visibility' => 'public',
            ],
            'avatar_cos' => [
                'driver' => 'cos',
                'root' => storage_path('app/public/avatars'),
                'url' => 'avatar',
                'visibility' => 'public',
            ],
            'attachment' => [
                'driver' => 'cloudinary', // 附件选用 Cloudinary 存储
                'root'   => storage_path('app'),
                'url'    => 'attachment'
            ],
            'attachment_cos' => [
                'driver' => 'cos',
                'root'   => storage_path('app/public/attachment'),
                'url'    => 'attachment'
            ],

            ////////////////////////////////
            'local' => [
                'driver' => 'local',
                'root' => storage_path('app'),
            ],
            'cos' => [
                'driver' => 'cos',
                'region' => '', //设置一个默认的存储桶地域
                'schema' => 'https', //协议头部，默认为http
                'bucket' => '',
                'read_from_cdn' => false, //是否从cdn读取，如果为true ， 设置cdn地址
                'credentials'=> [
                    'secretId'  => '',  //"云 API 密钥 SecretId";
                    'secretKey' => '', //"云 API 密钥 SecretKey";
                    'token' => '' //"临时密钥 token";
                ]
            ],
            'cloudinary' => [
                'driver'        => 'cloudinary',
                'cloud_name'    => 'XXX',
                'api_key'       => 'XXX',
                'api_secret'    => 'XXX',
                'secure'        => true
            ],
        ]
    ],
``` 

# 已知问题
* 在下载阶段，附件文件名会丢失。
