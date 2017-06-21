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
			return "Evento criado com sucesso!";
		}else{
			return "Erro ao criar evento! ";
		}
	}

	public function update(Request $request)
	{
		$updateEvent = $this->event->where('id', $request->get('id'))->update($request->all());

		if ($updateEvent){
			return "Evento atualizado com sucesso!";
		}else{
			return "Erro ao atualizar evento!";
		}
	}

	public function delete(Request $request)
	{
		$deleteEvent = $this->event->where('id', $request->get('id'))->delete();

		if ($deleteEvent){
			return "Evento excluido com sucesso!";
		}else{
			return "Erro ao excluir evento!";
		}
	}

	public function setIsConfirmation(Request $request)
	{
		$event = $this->event->where('id', $request->get('id'))->first();
		$event->isEventConfirmed = $request->get('isEventConfirmed');
		$event->save();

		if ($event->isEventConfirmed){
			return "Evento confirmado!";
		} else {
			return "Evento cancelado!";
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
            return "Imagem do evento alterada com sucesso!";
        }else{
            $avatar = $this->media->uploadMedia($file);
            $event->media_id = $avatar->id;
            $event->save();
            return "Imagem do evento salva com sucesso!";
        }

    }

	public function eventAttendance(Request $request)
	{
		$event = $event = $this->event->where('id', $request->get('id'))->first();

		return json_encode($event->users);
	}

	public function allEvents()
	{
		$event = Event::all();

		return json_encode($event);
	}

}