<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;
use App\Models\user;

class RequirementsController extends Controller
{
    public function index()//show all
    {
        $requirements = requirement::all();
        return response()->json($requirements);
    }

    public function store(Request $request,$id)
    {
        $user = user::findOrFail($id);
        $Requirements = new Requirement($request->all());
        $Requirements->user()->associate($user);
        $Requirements->save();
        return response()->json($Requirements, 201);
        // $requirements = requirement::create($request->all());
        // return response()->json($requirements, 201);
    }

    public function show($id)
    {
            $requirement = requirement::find($id);
            if ($requirement) {
                return response()->json(['requirement' => $requirement]);
            } else {
                return response()->json(['error' => "requirement with ID $id not found"], 404);
            }
    }

    public function update(Request $request, $id)
    {

        try {
            $requirements = requirement::findOrFail($id);
            $requirements->update($request->all());
            return response()->json($requirements, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "requirements with ID $id not found"], 404);
        }
    }

    public function destroy($id)
    {
        
        try {
            $requirement = Requirement::findOrFail($id);
            $requirement->delete();
            return response()->json(['message'=>$requirement], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Requirement with ID $id not found"], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to delete Requirement"], 500);
        }
    }

    public function showEvent($requirementId)
    {
        $requirement = Requirement::findOrFail($requirementId);
        $event = $requirement->event;

        return response()->json(['event' => $event], 200);
    }
}
