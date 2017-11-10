<?php

namespace App\Http\Requests;

use Exception;
use Store;
use Auth;
use App\Models\File;

use App\Http\Requests\Request;
use App\Validators\FileValidator;

class DeleteFileRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = FileValidator::make();

        $this->rules();
        $this->runValidator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        $user = Auth::user();
        $id = $this->route('file');

        try {
            $file = File::findOrFail($id);
            Store::set('file', $file);

            if (!$user) {
                // if unauthorized user now - can only remove file from order
                if ($file->storable_type === 'order') return true;
            } else
            {
                if ($user->hasRole('administrator')) return true;
                if ($user->id === $file->user_id) return true;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }
}
