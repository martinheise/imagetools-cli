<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mhe\Imagetools\Data\RenderConfig;
use Mhe\Imagetools\ImageResizer;
use Mhe\ImagetoolsCli\GDImageData;
use Mhe\ImagetoolsCli\IMImageData;


function format_size($size)
{
    if ($size >= 1024 * 1024) {
        return number_format($size / 1024 / 1024, 3) . "MB";
    } else {
        return number_format($size / 1024, 3) . "KB";
    }
}

function usage()
{
    echo "Usage: php resize-image.php -i <inputfile> -s <\"sizes\"> -m <mode (gd/im)>";
}


$options = getopt("i:s:m:");
if (empty($options['i'])) {
    echo "Input file missing\n";
    usage();
    die(1);
}

$sourcefile = $options['i'];
$sizes = array_key_exists('s', $options) ? $options['s'] : "100vw";
$mode = array_key_exists('m', $options) && strtolower($options['m']) == 'gd' ? 'GD' : 'IM';

define('MAXSTEPS', '5');
define('SIZEDIFF', '2000');
define('RETINA', '1');

$resizer = new ImageResizer(200, 2400);

if ($mode == 'IM') {
    $srcimg = new IMImageData($sourcefile);
} else {
    $srcimg = new GDImageData($sourcefile);
}

echo "\n-----\n";
echo "Source:\n";
echo $srcimg->getWidth() . " " . format_size($srcimg->getFilesize()) . "  " . $srcimg->getPublicPath() . "\n";

$config = new RenderConfig($sizes, MAXSTEPS, SIZEDIFF, RETINA);
$result = $resizer->getVariants($srcimg, $config);

echo "Rendered " . count($result) . " images:\n";
foreach ($result as $img) {
    echo $img->getWidth() . " " . format_size($img->getFilesize()) . "  " . $img->getPublicPath() . "\n";
}
