<?php

namespace Mhe\ImagetoolsCli;

use GdImage;
use Mhe\Imagetools\Data\ImageData;

class GDImageData implements ImageData
{
    protected string $filepath;
    protected GdImage $srcimg;
    protected string $workdir = __DIR__ . '/../_resample/';

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->srcimg = imagecreatefromjpeg($filepath);
    }

    public function getWidth(): int
    {
        return getimagesize($this->filepath)[0];
    }

    public function getHeight(): int
    {
        return getimagesize($this->filepath)[1];
    }

    public function getFilesize(): int
    {
        return filesize($this->filepath);
    }

    public function getPublicPath(): string
    {
        return $this->filepath;
    }

    public function resize($width): ImageData
    {
        // Get new dimensions
        list($src_width, $src_height) = getimagesize($this->filepath);
        $height = $src_height * ($width / $src_width);

        // Resample
        $dest_image = imagecreatetruecolor((int) $width, (int) $height);
        imagecopyresampled($dest_image, $this->srcimg, 0, 0, 0, 0, (int) $width, (int) $height, $src_width, $src_height);

        // Output
        $basename = basename($this->filepath, '.jpg');
        $destfile = $this->workdir . $basename . '_' . $width . '.jpg';
        imagejpeg($dest_image, $destfile, 90);
        return new GDImageData($destfile);
    }
}
