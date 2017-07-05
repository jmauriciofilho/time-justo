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
        DB::beginTransaction();
		$deleteEvent = $this->event->where('id', $request->get('id'))->delete();

		if ($deleteEvent){
            DB::commit();
		}else{
            DB::rollBack();
            throw new \ErrorException();
		}
	}

	public function setIsConfirmation(Request $request)
	{
        DB::beginTransaction();
		$event = $this->event->where('id', $request->get('event_id'))->first();
		if (!empty($event)){
            $numberUserConfirmed = count($event->users()->where('confirmParticipation', '=', true)->get());

            //dd($numberUserConfirmed);

            $confirmed = $event->minimumUsers <= $numberUserConfirmed;
            $event->isEventConfirmed = $confirmed;
            if($event->save()){
                DB::commit();
                return $event->isEventConfirmed;
            }else{
                DB::rollBack();
                throw new \ErrorException();
            }
        }else{
            DB::rollBack();
            throw new \Exception();
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
        if (!empty($event)) {
            //dd($event->users()->where('confirmParticipation', '=', true)->get());
            $confirmed = $event->users()->where('confirmParticipation', '=', true)->get();

            $e = new Collection();
            $e->put('event', $event->toArray());
            $e->put('invites', $event->users->toArray());
            $e->put('confirmed', $confirmed->toArray());

            //dd($json->toArray());

            return $e->toArray();
        }else{
            throw new \ErrorException();
        }
    }

	public function eventAttendance(Request $request)
	{
		$event = $this->event->where('id', $request->get('id'))->first();
        if (!empty($event)){
            return $event->users;
        }else{
            throw new \ErrorException();
        }
	}

	public function allEvents()
	{
	    $allEvents = new \ArrayObject();
		$events = Event::all();
		foreach ($events as $event){
            $confirmed = $event->users()->where('confirmParticipation', '=', true)->get();
            $e = new Collection();
            $e->put('event', $event->toArray());
            $e->put('invites', $event->users->toArray());
            $e->put('confirmed', $confirmed->toArray());
            $allEvents->append($e);
        }

        return $allEvents;
	}

}