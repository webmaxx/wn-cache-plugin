<?php

return [
    'plugin' => [
        'name' => 'wmCache',
        'description' => 'Плагин для работы с кэшем',
    ],
    'reportWidgets' => [
        'clearCache' => [
            'label' => 'Очистка кэша',
            'title' => [
                'default' => 'Очистка кэша',
            ],
            'del_upload_image_thumbs' => [
                'title' => 'Удалять превью изображений?',
            ],
            'upload_images_path' => [
                'title' => 'Путь к папке с изображениями',
            ],
            'upload_image_thumbs_regex' => [
                'title' => 'Регулярное выражение для имен файлов превью',
            ],
            'btn_clear' => 'Очистить',
        ],
    ],
];
