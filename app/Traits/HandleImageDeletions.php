<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandleImageDeletions
{
    protected static function bootHandleImageDeletions(): void
    {
        static::updating(function ($model) {
            $originalImage = $model->getOriginal('image');
            $updatedImage = $model->image;

            // Check if the original image differs from the updated image
            if ($originalImage && $originalImage !== $updatedImage) {
                if (Storage::disk('public')->exists($originalImage)) {
                    Storage::disk('public')->delete($originalImage);
                }
            }
        });

        static::deleting(function ($model) {
            $imageToDelete = $model->image;

            if ($imageToDelete && Storage::disk('public')->exists($imageToDelete)) {
                Storage::disk('public')->delete($imageToDelete);
            }
        });
    }
}
