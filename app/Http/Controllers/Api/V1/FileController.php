<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\FileRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Exceptions\GeneralJsonException;
use App\Models\File;

/**
 * For operation with files
 */
class FileController extends Controller
{
    private FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function store(Request $request)
    {
        $type = $request->get('type');

        if (!array_key_exists($type, File::FILE_TYPES)) {
            throw new GeneralJsonException('File type not found!', 404);
        }

        $method_name = 'upload_' . $request->type;

        $file = $this->fileRepository->$method_name($request);
        if ($file === false) {
            throw new GeneralJsonException('File uploaded unsuccessfully', 404);
        }
        $res = $file->getInfo();
        return new FileResource($res);
    }
}
