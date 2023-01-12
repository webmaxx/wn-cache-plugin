<?php

return [
    'plugin' => [
        'name' => 'wmCache',
        'description' => 'Working with cache',
    ],
    'reportWidgets' => [
        'clearCache' => [
            'label' => 'Clear cache',
            'title' => [
                'default' => 'Clear cache',
            ],
            'del_upload_image_thumbs' => [
                'title' => 'Remove image thumbs?',
            ],
            'upload_images_path' => [
                'title' => 'Path to image folder',
            ],
            'upload_image_thumbs_regex' => [
                'title' => 'Regex for thumb file names',
            ],
            'btn_clear' => 'Clear',
        ],
    ],
];
