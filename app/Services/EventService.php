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
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        $event = $this->event->create($request->all());
        if (!empty($event)){
            DB::commit();
            return $event;
        }else{
            DB::rollBack();
            throw new \ErrorException();
        }
	}

	public function update(Request $request)
	{
        DB::beginTransaction();
        $update = $this->event->where('id', $request->get('id'))
            ->update($request->all());
        if ($update){
            DB::commit();
        }else{
            DB::rollBack();
            throw new \ErrorException();
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

		$numberUserConfirmed = count($event->users()->where('confirmParticipation', '=', true)->get());

		//dd($numberUserConfirmed);

		if ($event->minimumUsers <= $numberUserConfirmed){
            $event->isEventConfirmed = true;
            $event->save();
			return 1;
		} else {
            $event->isEventConfirmed = false;
            $event->save();
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

        //dd($event->users()->where('confirmParticipation', '=', true)->get());
        $confirmed = $event->users()->where('confirmParticipation', '=', true)->get();

        $json = new Collection();
        $json->put('event', $event->toArray());
        $json->put('invites', $event->users->toArray());
        $json->put('confirmed', $confirmed->toArray());

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
		return Event::all();
	}

}