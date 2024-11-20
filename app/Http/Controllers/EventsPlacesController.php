<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Place;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class EventsPlacesController extends Controller
{
    public function showPlaces($idevents)
    {
        try {
            $event = Event::findOrFail($idevents);
            $places = $event->places()->get();

            return response()->json(['places' => $places], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $idevents not found"], 404);
        }
    }

    public function addPlaceToEvent(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $idplaces = $request->input('place_id');

            $event->places()->attach($idplaces);

            $place = Place::findOrFail($idplaces);

            return response()->json(['message' => 'Place added to event', 'place' => $place], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $id not found"], 404);
        }
    }

    public function removePlaceFromEvent($idevents, $idplaces)
    {
        try {
            $event = Event::findOrFail($idevents);

            $event->places()->detach($idplaces);

            return response()->json(['message' => 'Place removed from event'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $idevents not found"], 404);
        }
    }
}
