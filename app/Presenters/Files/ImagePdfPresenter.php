<?php

namespace App\Presenters\Files;

use Hemp\Presenter\Presenter;

class ImagePdfPresenter extends Presenter
{
    private $_width;
    private $_height;

    /**
     * ImagePdfPresenter constructor.
     * @param $model
     */
    public function __construct($model)
    {
        parent::__construct($model);
        // resize($this, 800, 1000);
    }

    /**
     * Fit image by max possible width & height, but with save the same ratio
     * @param int $maxWidthPx
     * @param int $maxHeightPx
     * @return ImagePdfPresenter
     */
    public function resize($maxWidthPx = 800, $maxHeightPx = 1000): ImagePdfPresenter
    {
        $this->_width = $this->model->width;
        $this->_height = $this->model->height;
        $ratioHW = $this->model->height / $this->model->width;
        $ratioWH = $this->model->width / $this->model->height;

        // resize by width
        if ($this->model->width > $maxWidthPx) {
            $this->_width = $maxWidthPx;
            $this->_height = $maxWidthPx * $ratioHW;
        }

        // resize by height
        if ($this->model->height > $maxHeightPx) {
            $this->_height = $maxHeightPx;
            $this->_width = $this->_height * $ratioWH;
        }

        return $this;
    }

    public function getWidthAttribute()
    {
        return $this->_width;
    }

    public function getHeightAttribute()
    {
        return $this->_height;
    }
}