<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    const FILE_AVATAR       = 'avatar';
    const FILE_TEST         = 'test';
    const FILE_STORAGE_INFO = 'storage_info';

    const FILE_TYPES = [
        'avatar'        => self::FILE_AVATAR,
        'test'          => self::FILE_TEST,
        'storage_info'  => self::FILE_STORAGE_INFO,
    ];

    protected $fillable = [
        'type',
        'name',
        'path',
        'size',
        'object_id',
        'updated_at'
    ];

    public function getInfo()
    {
        $path = $this->path;
        $file_path = $path . $this->name;
        $file_path = Storage::url($file_path);
        $url = asset($file_path);
        return [
            'name' => $this->name,
            'url' => $url,
            'size' => $this->size,
        ];
    }
}
