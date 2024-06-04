<?php

namespace App\Models;

use App\Traits\Columns;
use App\Traits\Sortable;
use App\Traits\HasCrudModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory, HasCrudModel;
    use Sortable, Columns;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    protected $fillable = [
        'hash',
        'name',
        'size',
        'type',
        'sub_case_id'
    ];

    protected $sortable = [
        'id',
        'created_by'
    ];

    protected $filterable = [
        "id",
    ];

    public function rules()
    {
        return [
            'hash' => 'required|string|max:150',
            'name' => 'required|string|max:150',
            'size' => 'required|integer',
            'type' => 'required|string|max:150',
        ];
    }

    public function upload($sub_case_id, UploadedFile $file)
    {
        if (!$file->isValid()) {
            throw new Exception("File is not valid. " . $file->getErrorMessage());
        }

        if ($path = Storage::disk('local')->putFile('', $file)) {
            $attachment = new Attachment;
            $attachment->fill([
                "name" => $file->getClientOriginalName(),
                "hash" => $file->hashName(),
                "type" => $file->getClientMimeType(),
                "size" => $file->getSize(),
                "sub_case_id" => $sub_case_id
            ]);
            $attachment->save();

            return $attachment;
        } else {
            throw new Exception("Failed uploading file");
        }
    }

    public function SubCase()
    {
        return $this->belongsTo(SubCase::class);
    }
}
