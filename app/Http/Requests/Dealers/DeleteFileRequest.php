<?php

namespace App\Http\Requests\Dealers;

use Exception;
use Store;
use Auth;
use App\Models\File;

use App\Http\Requests\Request;

class DeleteFileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        $user = Auth::user();
        if (!$user) return false;

        $id = $this->route('file');

        try {
            $file = File::findOrFail($id);
            Store::set('file', $file);

            if ($user->hasRole('administrator')) return true;
            if ($user->id === $file->user_id) return true;
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
        return [];
    }
}
