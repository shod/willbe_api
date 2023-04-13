<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    const FILE_AVATAR       = 'avatar';

    const FILE_TYPES = [
        'avatar'       => self::FILE_AVATAR,
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
        $url = asset($file_path);
        return [
            'name' => $this->name,
            'url' => $url,
            'size' => $this->size,
        ];
    }
}
