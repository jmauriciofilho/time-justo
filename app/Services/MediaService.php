<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 30/04/17
 * Time: 23:13
 */

namespace app\Services;


use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class MediaService
{
	private $media;

	function __construct(Media $media)
	{
		$this->media = $media;
	}

	public function uploadMedia(UploadedFile $file, $pathDestination, $fileName)
	{
            $data = [];
			$data['file'] = $file;
			$data['filename'] = str_slug($fileName) . '-' . str_random(10);
			$data['extension'] = $file->getClientOriginalExtension();
			$data['filenameWithExtension'] = $data['filename'] . '.' . $data['extension'];
			$data['size'] = $file->getSize();
			$data['folder'] = '/' . $pathDestination . '/';
			$data['path'] = '/' . $pathDestination . '/' . $data['filenameWithExtension'];

			$file->move(public_path($pathDestination), $data['filenameWithExtension']);

			//dd([public_path($pathDestination), $data['filenameWithExtension']]);

			return Media::create($data);
	}

	public function deleteMedia($id)
	{
		$file = Media::find($id);
		if ($file != null){
            $filename = $file->filename . "." . $file->extension;
            $pathDestination = 'media';
            //dd($filename);
            unlink(public_path($pathDestination."/".$filename));
            $file->delete();
            return 200;
        }
		return 404;
	}

}