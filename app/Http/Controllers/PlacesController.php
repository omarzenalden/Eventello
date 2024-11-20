<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\image;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PlacesController extends Controller
{
    public function index()
    {
        $places = Place::with('images')->get();
        return response()->json($places);
    }

    public function show($id)
    {
        try {
            $place = Place::findOrFail($id);
            $place->load('images');
            return response()->json($place);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Place with ID $id not found"], 404);
        }
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'start_work'=>'required|date_format:h:i',
            'end_work'=>'required|date_format:h:i',
            'photos'=>'array',
            'photos.*'=>'image|mimes:jpeg,png,jpg,gif',
        ]);
        $place =Place::query()->create($validatedData);
        if($request->has('photos')){
            $photos = $request->photos;
            foreach($photos as $photo){
                $file_extension = $photo->getClientOriginalName();
                $file_name = time().'.'.$file_extension;
                $photo->move(public_path('image'),$file_name);
                $file_name = 'image/'.$file_name;
                    image::create([
                    'image'=>$file_name,
                    'place_id'=>$place->id
                ]);
            }
        }
        $place->load('images');
        return response()->json($place, 201);
    }
    public function update(Request $request, $idplaces)
    {
        try {
            $place = Place::findOrFail($idplaces);
            $place->update($request->all());
            return response()->json($place, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Place with ID $idplaces not found"], 404);
        }
    }

    public function destroy($idplaces)
    {
        try {
            $place = Place::findOrFail($idplaces);
            $place->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Place with ID $idplaces not found"], 404);
        }
    }

    public function showEvents($idplaces)
    {
        try {
            $place = Place::findOrFail($idplaces);
            $events = $place->events;

            return response()->json(['events' => $events], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Place with ID $idplaces not found"], 404);
        }
    }

    public function showPlanners($idplaces)
    {
        try {
            $place = Place::findOrFail($idplaces);
            $planners = $place->planners;

            return response()->json(['planners' => $planners], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Place with ID $idplaces not found"], 404);
        }
    }
    public function search(request $request){
        $search=$request->search;
        $data=place::where('name' , 'like','%'. $search.'%')->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'No places found matching the search term'], 404);}
        return response()->json([
            'data' => $data,
            ]);
        }
}
