<?php

namespace App\Http\Controllers;

use App\Models\PlannerHasEvents;
use App\Models\Planner;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;


class PlannerHasEventsController extends Controller
{
    public function index()
    {
        $plannerHasEvents = PlannerHasEvents::all();

        return response()->json(['plannerHasEvents' => $plannerHasEvents], 200);

    }
// Add Event to Planner
public function addEventToPlanner($plannerId, Request $request)
{
    $planner = Planner::findOrFail($plannerId);
    $event = Event::findOrFail($request->input('event_id'));
    $planner->events()->attach($event->id);
    return response()->json(['message' => 'Event added to planner successfully'], 200);
}

// Get Planner Events
public function getPlannerEvents($plannerId)
{
    $planner = Planner::with('events')->findOrFail($plannerId);
    return response()->json($planner->events, 200);
}
public function removePlannerFromEvent($idevents, $idplanner)
    {
        try {
            $event = Event::findOrFail($idevents);

            $event->planners()->detach($idplanner);

            return response()->json(['message' => 'planner removed from event'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Event with ID $idevents not found"], 404);
        }
    }

}
