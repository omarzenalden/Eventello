<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use App\Models\Requirement;

class GuestController extends Controller
{
    public function index(Request $request,$id)
    {
        $guests = Guest::where('Requirement_id',$id)->get();
        $formattedGuests = json_encode($guests);
    return response()->json([
        "message" => "هذه الضيوف خاصة بتطلبات الحفل $id",
        "data" => $formattedGuests
    ], 200);
    }

    public function show($id)
    {
        $guest = Guest::findOrFail($id);
        return response()->json($guest);
    }

    public function store(Request $request,$id)
    {
        $requirement = Requirement::findOrFail($id);
        $guest = new guest($request->all());
        $guest->requirement()->associate($requirement);
        $guest->save();
        return response()->json($guest, 201);
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);
        $guest->update($request->all());
        return response()->json($guest, 200);
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        return response()->json(null, 204);
    }
    public function search(request $request){
        $search=$request->search;
        $data=guest::where('name' , 'like','%'. $search.'%')->get();
        return response()->json([
            'data' => $data,
            ]);
    }
}
