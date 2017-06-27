<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 09/04/17
 * Time: 00:03
 */

namespace App\Services;


use App\Http\Controllers\MediaController;
use App\Models\Event;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EventService
{
	private $event;
	private $media;

	function __construct(Event $event, MediaController $media)
	{
		$this->event = $event;
		$this->media = $media;
	}

	public function create(Request $request)
	{
		$createEvent = $this->event->create($request->all());

		if ($createEvent){
		    $response = [
		        'cod' => 200,
                'id_event' => $createEvent->id
            ];

		    $json = new Collection();
		    $json->put('response', $response);

			return json_encode($json);
		}else{
			return 400;
		}
	}

	public function update(Request $request)
	{
		$updateEvent = $this->event->where('id', $request->get('id'))->update($request->all());

		if ($updateEvent){
			return 200;
		}else{
			return 400;
		}
	}

	public function delete(Request $request)
	{
		$deleteEvent = $this->event->where('id', $request->get('id'))->delete();

		if ($deleteEvent){
			return 200;
		}else{
			return 400;
		}
	}

	public function setIsConfirmation(Request $request)
	{
		$event = $this->event->where('id', $request->get('id'))->first();
		$event->isEventConfirmed = $request->get('isEventConfirmed');
		$event->save();

		if ($event->isEventConfirmed){
			return 1;
		} else {
			return 0;
		}
	}

	public function addEventImage(Request $request)
    {
        $file = $request->file('image');
        $event = Event::find($request->get('idEvent'));
        if ($event->media_id != null){
            $id = Media::find($event->media_id);
            $this->media->deleteMedia($id);
            $avatar = $this->media->uploadMedia($file);
            $event->media_id = $avatar->id;
            $event->save();
            return 200;
        }else{
            $avatar = $this->media->uploadMedia($file);
            $event->media_id = $avatar->id;
            $event->save();
            return 200;
        }

    }

    public function returnEvent(Request $request)
    {
        $event = Event::find($request->get('id'));

        $json = new Collection();
        $json->put('event', $event->toArray());
        $json->put('invites', $event->users->toArray());

        //dd($json->toArray());

        return json_encode($json->toArray());
    }

	public function eventAttendance(Request $request)
	{
		$event = $this->event->where('id', $request->get('id'))->first();

		return json_encode($event->users);
	}

	public function allEvents()
	{
		$event = Event::all();

		return json_encode($event);
	}

}