<?php

return [
    'groups' => [
        [
            'key' => 'images',
            'label' => '圖片組',
            'abilities' => [
                'images.read' => '取得圖片列表',
                'images.upload' => '上傳圖片',
                'images.delete' => '刪除圖片',
            ],
        ],
        [
            'key' => 'albums',
            'label' => '相簿組',
            'abilities' => [
                'albums.read' => '取得相簿列表',
                'albums.delete' => '刪除相簿',
            ],
        ],
        [
            'key' => 'management',
            'label' => '管理組',
            'abilities' => [
                'profile.read' => '取得使用者資料',
                'strategies.read' => '取得可用策略',
            ],
        ],
    ],
    'forbidden_message' => '當前金鑰無權執行此操作',
];
