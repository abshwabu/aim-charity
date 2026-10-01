<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait CleansUpMediaOnDeleteAndReplace
{
    /**
     * Boot the trait to prune replaced and deleted media files from storage.
     */
    public static function bootCleansUpMediaOnDeleteAndReplace(): void
    {
        static::updating(function ($model): void {
            $attributes = $model->getMediaAttributes();

            foreach ($attributes as $attribute) {
                if ($model->isDirty($attribute)) {
                    $original = $model->getOriginal($attribute);

                    if (is_string($original) && filled($original) && Storage::disk('public')->exists($original)) {
                        Storage::disk('public')->delete($original);
                    }
                }
            }
        });

        static::deleted(function ($model): void {
            $attributes = $model->getMediaAttributes();

            foreach ($attributes as $attribute) {
                $file = $model->{$attribute};

                if (is_string($file) && filled($file) && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        });
    }

    /**
     * List of model attributes storing public storage relative file paths.
     *
     * @return list<string>
     */
    public function getMediaAttributes(): array
    {
        return property_exists($this, 'mediaAttributes')
            ? $this->mediaAttributes
            : ['photo', 'logo', 'image', 'cover_image', 'qr_image', 'author_photo'];
    }
}
