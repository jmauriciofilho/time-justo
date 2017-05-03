<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    private $mediaService;

    function __construct(MediaService $mediaService)
    {
    	$this->mediaService = $mediaService;
    }

    public function uploadMedia(Request $request)
    {
    	$file = $request->file('image');
		$pathDestination = 'media';
		$fileName = 'image';

    	return $this->mediaService->uploadMedia($file, $pathDestination, $fileName);
    }

    public function deleteMedia(Request $request)
    {
		return $this->mediaService->deleteMedia($request);
    }
}
