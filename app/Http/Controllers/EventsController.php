<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class EventsController extends Controller
{

        // List all events

    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }
    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);
            return response()->json(['event' => $event]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $id not found"], 404);
        }
    }
    public function store(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {

        try {
            $event = Event::findOrFail($id);
            $event->update($request->all());
            return response()->json($event, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $id not found"], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(['message'=>$event], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $id not found"], 404);
        }
    }


    public function showPlaces($eventId)
    {
        $event = Event::findOrFail($eventId);
        $places = $event->places;

        return response()->json(['places' => $places], 200);
    }

    public function showGuests($eventId)
    {
        $event = Event::findOrFail($eventId);
        $guests = $event->guests;

        return response()->json(['guests' => $guests], 200);
    }
}
