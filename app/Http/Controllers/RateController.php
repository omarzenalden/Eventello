<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\plannercontriller;
use App\Models\Planner;


class RateController extends Controller
{
    /**
     * Store a newly created rating in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
            'like' => 'required|boolean',
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'place_id' => 'required|exists:places,id',
            'planner_id' => 'required|exists:planners,id',

        ]);

        $event = Event::findOrFail($request->event_id);
        $planner = planner::findOrFail($request->planner_id);
        $rate = Rate::create([
            'rate' => $request->rate,
            'comment' => $request->comment,
            'like' => $request->like,
            'event_id' => $request->event_id,
            'user_id' => Auth::id(),
            'place_id' => $request->place_id,
           'planner_id' => $request->planner_id,

        ]);

        return response()->json([
            'message' => 'Rate created successfully',
            'data' => $rate
        ], 201);
    }
    public function store_planner(Request $request)
    {
        $request->validate([
            'rate' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
            'like' => 'required|boolean',
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'place_id' => 'required|exists:places,id',
            'planner_id' => 'required|exists:planners,id',
        ]);

        $event = Event::findOrFail($request->event_id);
        $rate = Rate::create([
            'rate' => $request->rate,
            'comment' => $request->comment,
            'like' => $request->like,
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'place_id' => $request->place_id,
            'planner_id' =>  Auth::id(),
        ]);

        return response()->json([
            'message' => 'Rate created successfully',
            'data' => $rate
        ], 201);
    }
     /**
     * Confirm the specified rating.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, Rate $rate)
    {
        // Ensure the rating belongs to the authenticated user
        if ($rate->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Confirm the rating
        $rate->is_confirmed = true;
        $rate->save();

        return response()->json(['message' => 'Rating confirmed']);
    }
 /**
     * Display the specified rating.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        return response()->json($rate);
    }

    /**
     * Update the specified rating in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        $request->validate([
            'rate' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
            'like' => 'required|boolean',
        ]);

        $rate->update($request->all());
        return response()->json($rate);
    }

    /**
     * Remove the specified rating from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        $rate->delete();
        return response()->json(null, 204);
    }}
