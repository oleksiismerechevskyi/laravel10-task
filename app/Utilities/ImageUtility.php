<?php
namespace App\Utilities;

use function Tinify\setKey;

class ImageUtility {

    public function __construct() {
        $apiKey = env('TINIFY_API_KEY');
        setKey($apiKey);
    }

    public function cropImage(string $image_buffer): string {
        $source = \Tinify\fromBuffer($image_buffer);
        
        $resized = $source->resize(array(
            "method" => "cover",
            "width" => 70,
            "height" => 70
        ));
        
        $result = $resized->result();
        $filename = uniqid('avatar-') . '.' . $result->extension();
        $cropped_filepath = storage_path('app/public/') . $filename;
        $result = $resized->toFile($cropped_filepath);

        return $result ? $filename : '';
    }
}