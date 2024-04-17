<?php

namespace App\Traits;

trait Images
{

    // set images name
    public function setImagesName(array $images): array
    {
        return array_map(function ($image) {
            return uniqid() . '_' . substr(str_shuffle(
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_',
            ), 0, 50) . '.' . $image->getClientOriginalExtension();
        }, $images);
    }

    // save images
    public function saveImages(array $images, array $names, string $path)
    {
        for ($i = 0; $i < count($images); $i++) {
            $images[$i]->storeAs($path, $names[$i]);
        }
    }

    // delete images
    public static function deleteImages(string $path, array $names)
    {
        foreach ($names as $name) {
            unlink(public_path($path . '/' . $name));
        }
    }

}
