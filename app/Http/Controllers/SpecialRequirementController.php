<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpecialRequirement;
use App\Models\Requirement;
class SpecialRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $SpecialRequirements = SpecialRequirement::all();
        return response()->json($SpecialRequirements);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)

    {
        $requirement = Requirement::findOrFail($id);
        $specialRequirement = new SpecialRequirement($request->all());
        $specialRequirement->requirement()->associate($requirement);
        $specialRequirement->save();

        return response()->json($specialRequirement, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $SpecialRequirements = SpecialRequirement::find($id);
        if ($SpecialRequirements) {
            return response()->json(['SpecialRequirement' => $SpecialRequirements]);
        } else {
            return response()->json(['error' => "SpecialRequirement with ID $id not found"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $SpecialRequirements = SpecialRequirement::findOrFail($id);
            $SpecialRequirements->update($request->all());
            return response()->json($SpecialRequirements, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "SpecialRequirements with ID $id not found"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $SpecialRequirements = SpecialRequirement::findOrFail($id);
            $SpecialRequirements->delete();
            return response()->json(['message'=>$SpecialRequirements], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "SpecialRequirements with ID $id not found"], 404);
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to delete SpecialRequirements"], 500);
        }
    }
}
