<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Maximum Upload Size
    |--------------------------------------------------------------------------
    |
    | Sensible default upload size limit in kilobytes (default 10MB = 10240KB).
    |
    */
    'max_upload_size_kb' => (int) env('MAX_UPLOAD_SIZE_KB', 10240),

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types for Media Uploads
    |--------------------------------------------------------------------------
    */
    'allowed_image_mimes' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/svg+xml',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Optimization Defaults
    |--------------------------------------------------------------------------
    */
    'webp_quality' => (int) env('IMAGE_WEBP_QUALITY', 85),
    'max_dimension_width' => (int) env('IMAGE_MAX_WIDTH', 1920),
    'max_dimension_height' => (int) env('IMAGE_MAX_HEIGHT', 1080),
];
