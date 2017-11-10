<?php

namespace App\Services\Files;

use Auth;
use Storage;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image as InterventionImage;
use Intervention\Image\Image;

class ImageService
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var Image
     */
    public $image;

	public $messages;
    public $errors;
    public $files;

	public function __construct()
	{
    }

    /**
     * @param UploadedFile $image
     * @return $this
     */
    public function setImage(UploadedFile $image) {
        $this->file = $image;
        $this->image = InterventionImage::make($image);
        return $this;
    }

    /**
     * @param Model $model
     * @return ImageService
     */
    public function uploadAs(Model $model): ImageService
	{
		try {
            $storableType = $model->getMorphClass();
            $imageName = $this->file->getClientOriginalName();
			$directory = 'public/' . $storableType . '/' . $model->id;
	        Storage::makeDirectory($directory);

	        $path = storage_path('app/' . $directory . '/' . $imageName);
	        $this->image->save($path);

	        $file = [
	        	'user_id' => Auth::check() ? Auth::user()->id : null,
	        	'storable_id' => $model->id,
	        	'storable_type' => $storableType,
	        	'type' => 'image',
	        	'mime' => $this->image->mime(),
	        	'path' => '/'.$storableType.'/'.$model->id.'/'.$imageName,
	        	'name' => $imageName,
	        	'ext' => $this->file->getClientOriginalExtension(),
	        	'size' => filesize($path),
	        	'width' => $this->image->width(),
	        	'height' => $this->image->height()
	        ];

	        File::create($file);

	        $this->messages[] = "File \"{$imageName}\" successfully added.";
            $this->files[] = $file;
	    } catch (Exception $e) {
            Log::error($e);
            $this->errors[] = "File \"{$imageName}\" has not been added.";
            return $this;
        }

        $this->errors[] = "File \"{$imageName}\" has not been added.";
        return $this;
	}

    /**
     * @param int|null $newWidth
     * @param int|null $newHeight
     * @param bool|null $aspectRatio
     * @return ImageService
     * @internal param bool|null $keepRation
     */
    public function resize(?int $newWidth, ?int $newHeight, ?bool $aspectRatio = true): ImageService {
        // resize the image to a width of 300 and constrain aspect ratio (auto height)
        $this->image->resize($newWidth, $newHeight, function ($constraint) use ($aspectRatio) {
            if ($aspectRatio) {
                $constraint->aspectRatio();
            }
        });

        return $this;
    }

    public function success()
    {
        return count($this->errors) === 0;
    }

    public function error()
    {
        return count($this->errors) !== 0;
    }
}