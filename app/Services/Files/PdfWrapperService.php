<?php

namespace App\Services\Files;

use Imagick;
use ImagickPixel;
use PDF;
use SnappyImage;
use Spatie\PdfToImage\Pdf as PdfToImage;
use Storage;
use Store;
use Carbon\Carbon;
use Exception;

use App\Models\File;

class PdfWrapperService
{

    public function wrap($file, $data) {
        $path = storage_path('app/public').$file->path;

        if ($file->type === 'pdf') return $this->wrapPdf('png', $path, $data);
        if ($file->type === 'image') return $this->wrapImage(explode('/', $file->mime)[1], $path, $data);

        return false;
    }

    public function wrapPdf($type, $path, $data) {
        $pages = [];
        $imagick = new Imagick($path);
        $pageNumbers = $imagick->getNumberImages();

        for ($pageNumber = 1; $pageNumber <= $pageNumbers; $pageNumber++) {
            $pages[$pageNumber] = $this->getImageBlob($type, $path, $pageNumber);
        }

        $pdf = PDF::loadView('forms.sn-wrapper-pdf', [ 'pages' => $pages, 'data' => $data, 'type' => $type ]);
        $pdf->setOptions([
            'margin-left' => 0,
            'margin-right' => 0,
            'margin-top' => 0,
            'margin-bottom' => 0,
            'page-width' => 139.7,
            'page-height' => 181,
            //'grayscale' => true,
            //'image-dpi' => 181,
            'image-quality' => 35,
            'lowquality' => true,
            'no-background' => true,
            'enable-smart-shrinking' => true,
        ]);

        return $pdf;
    }

    public function wrapImage($type, $path, $data) {
        $imageBlob = $this->getImageBlob($type, $path, 1);
        $snappyImage = SnappyImage::loadView('forms.sn-wrapper-image', [ 'imageBlob' => $imageBlob, 'data' => $data, 'type' => $type ]);
        return $snappyImage;
    }
    
    private function getImageBlob($type, $path, $page) {
        $imagick = new \Imagick();

        $imagick->setResolution(256, 256);
        $imagick->setBackgroundColor(new ImagickPixel('transparent'));
        $imagick->readImage(sprintf('%s[%s]', $path, $page-1));
        $imagick->setFormat($type);
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_OPAQUE);
        $imagick->setImageFormat($type);
        //$imagick->brightnessContrastImage(0.5, 5, 1);
        //$imagick->resizeImage(750, 0, Imagick::FILTER_LANCZOS, 1);
        $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
        //$imagick->setImageCompression(Imagick::COMPRESSION_JPEG2000);
        //$imagick->setImageCompressionQuality(40);

        return $imagick->getImageBlob();
    }
}
