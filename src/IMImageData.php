<?php

namespace Mhe\ImagetoolsCli;

use Imagick;
use Mhe\Imagetools\Data\ImageData;

class IMImageData implements ImageData
{
    protected string $filepath;
    protected Imagick $srcimg;
    protected string $workdir = __DIR__ . '/../_resample/';

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->srcimg = new Imagick($filepath);
    }

    public function getWidth(): int
    {
        return $this->srcimg->getImageWidth();
    }

    public function getHeight(): int
    {
        return $this->srcimg->getImageHeight();
    }

    public function getFilesize(): int
    {
        return $this->srcimg->getImageLength();
    }

    public function getPublicPath(): string
    {
        return $this->filepath;
    }

    public function resize($width): ImageData
    {
        // Get new dimensions
        $height = $this->getHeight() * ($width / $this->getWidth());

        // Output filepath
        $basename = basename($this->filepath, '.jpg');
        $destpath = $this->workdir . $basename . '_' . $width . '.jpg';

        $destimg = clone $this->srcimg;
        $destimg->scaleImage((int) $width, (int) $height);

        file_put_contents($destpath, $destimg);
        return new IMImageData($destpath);
    }
}
