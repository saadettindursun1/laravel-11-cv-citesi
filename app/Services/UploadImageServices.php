<?php

namespace App\Services;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadImageServices
{


    private const ALLOWED_EXTENSIONS = ["jpg", "JPEG", "png", "pdf", "svg", "webp"];

    public function uploadMultipleImages(array $images, $folder=null): array
    {
        $uploadImages = [];
        foreach ($images as $image) {
            $uploadImages[] = $this->uploadImage($image,$folder);
        }

        return $uploadImages;
    }

    private function uploadImage(object $image,$folder=null)
    {
        $validationErrors = $this->validateExtension($image);
        if (!empty($validationErrors)) {
            return ["errors" => $validationErrors];
        }

        $fileName = $this->generateFileName($image);
        $extension = $image->getClientOriginalExtension();
        $subfolder = !empty($folder) ? $folder."/": "";
        $path = "uploads/".$subfolder;
        $uploadPath = $path . $fileName;

        $this->createFolder($path);

        if (in_array($extension, ["pdf", "svg"])) {
            $this->saveOriginalImage($image, $uploadPath);
            $newFileName = $fileName . "." . $extension;
        } else {
            $this->resizeAndSaveWebp($image, $uploadPath);

            $newFileName = $fileName . ".webp";
        }

        return [
            "name" => $newFileName,
            "path" => $path . $newFileName,
            "url" => asset($newFileName),
        ];
    }

    private function validateExtension(object $image): array
    {
        $extension = $image->getClientOriginalExtension();
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception("Invalid file extension:" . $extension);
        }
        return [];
    }

    private function generateFileName(object $image): string
    {
        return time() . "-" . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
    }

    private function saveOriginalImage(object $image, string $path): void
    {
        Storage::put($path, file_get_contents($image));
    }

    private function resizeAndSaveWebp(object $image, string $path): void
    {
        try {
            Image::make(file_get_contents($image))->encode("webp", '75')->save($path . '.webp');
        } catch (\Exception $e) {
            throw new \Exception("Error resizing image: " . $e->getMessage());
        }
    }
    public function createFolder(string $directoryPath, int $permissions = 0)
    {
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, $permissions, true);
        }
    }

    public function deleteFile(string $filepath): void
    {
        if (file_exists($filepath)) {
            if (!empty($filepath)) {
                unlink($filepath);
            }
        }
    }
}
