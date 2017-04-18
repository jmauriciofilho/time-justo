<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 09/04/17
 * Time: 00:03
 */

namespace App\Services;


use App\Models\Event;
use Illuminate\Http\Request;

class EventService
{
	private $event;

	function __construct(Event $event)
	{
		$this->event = $event;
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