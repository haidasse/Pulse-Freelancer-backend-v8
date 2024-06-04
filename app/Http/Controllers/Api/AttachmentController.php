<?php

namespace App\Http\Controllers\Api;

use Exception ;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HasCrudController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ModelNotValidException;

class AttachmentController extends Controller
{
    use HasCrudController;

    protected $model = Attachment::class;

    public function upload(Request $request)
    {
        $v = Validator::make($request->all(), [
            "file" => "required|file" ,
            "sub_case_id" => "required"
        ]);

        if ($v->fails()) {
            throw (new ModelNotValidException())->withData($v->errors());
        }
        return (new Attachment)->upload($request->sub_case_id, $request->file('file')) ;
    }

    public function download(string $name)
    {
        return Storage::download($name);
    }

    public function remove(Request $request, string $name)
    {
        $attachment = Attachment::where('hash', $name)->first();

        if (empty($attachment)) {
            throw new Exception("File Not Found : ".$name);
        }

        if (Storage::delete($attachment->hash)) {
            return ["File deleted" => $attachment->delete()];
        }

        throw new Exception("Failed deleting file");
    }
}