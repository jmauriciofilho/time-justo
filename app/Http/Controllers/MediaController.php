<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class MediaController extends Controller
{
    private $mediaService;

    function __construct(MediaService $mediaService)
    {
    	$this->mediaService = $mediaService;
    }

    public function uploadMedia(UploadedFile $file)
    {
		$pathDestination = 'media';
		$fileName = 'image';

    	return $this->mediaService->uploadMedia($file, $pathDestination, $fileName);
    }

    public function deleteMedia($id)
    {
		return $this->mediaService->deleteMedia($id);
    }
}
