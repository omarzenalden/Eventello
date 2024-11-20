<?php


namespace App\Http\Controllers;
 use Illuminate\Http\Request;
use App\Models\PlannerHasPlaces;
use App\models\Event;
use App\models\Place;
class PlannerHasPlacesController extends Controller
{
    public function index()
    {
        $plannerHasPlaces = PlannerHasPlaces::all();

        return response()->json(['plannerHasPlaces' => $plannerHasPlaces], 200);
    }


    public function addPlaceToPlannerInEvent(Request $request, $idevents, $plannerId)
    {
        $event = Event::findOrFail($idevents);
        $planner = $event->planners()->where('id', $plannerId)->firstOrFail();
        $placeId = $request->input('place_id');
        $place = Place::findOrFail($placeId);
        $planner->places()->attach($place);
        return response()->json(['message' => 'Place added to planner successfully']);
    }
    public function getPlannerEventPlaces($idevents, $plannerId)
    {
        $event = Event::findOrFail($idevents);
        $planner = $event->planners()->where('id', $plannerId)->firstOrFail();
        $places = $planner->places()->get();
        return response()->json($places);
    }


}
