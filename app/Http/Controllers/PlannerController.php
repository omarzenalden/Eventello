<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\Planner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\user;
use App\Models\Image_planners;
use App\Models\Pending_request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

//use Tymon\JWTAuth\Exceptions\JWTException;
class PlannerController extends Controller
{
    public function __construct() {
        $this->middleware('auth:planner', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:100',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Invalid email or password format'], 400);
    }

    $credentials = $request->only(['email', 'password']);

    $planner = Planner::where('email', $request->email)->first();

    if (!$planner) {
        return response()->json(['message' => 'Email does not exist'], 404); // البريد الإلكتروني غير موجود
    }

    if (!Hash::check($request->password, $planner->password)) {
        return response()->json(['message' => 'Incorrect password'], 401); // كلمة المرور غير صحيحة
    }

    $token = auth('planner')->attempt($credentials);

    if (!$token) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return $this->createNewToken($token);
    }




     /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:planners',
            'phone'=>'required|string|max:10|min:10',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif',
            'password' => 'required|string|min:6',

        ]);
        if($validator->fails()){
            return response()->json(['message'=>'the email is already exist'], 400);
        }
 $plannerData = $validator->validated();
 $plannerData['password'] = Hash::make($request->password);
 $planner = Planner::create($plannerData);
        if ($request->hasFile('image')) { //التحقق من الصورة
            $photo = $request->file('image');
            $file_extension = $photo->getClientOriginalName();
            $file_name = time() . '.' . $file_extension;
            $photo->move(public_path('Image_planners'), $file_name);
            $file_name = 'Image_planners/' . $file_name;

            image_planners::create([
                'image' => $file_name,
                'planner_id' => $planner->id
            ]);
        }

        $planner->load('Image_planners');
        return response()->json([
            'message' => 'Planner successfully registered',
            'user' => $planner
        ], 201);
       ntOriginalExtension();


    }
     /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        auth('planner')->logout();
        return response()->json(['message' => 'planner successfully signed out']);

    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth('planner')->user('planners'));
       //return response()->json('vvev');
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth::factory()->getTTL() * 60,
            'user' => auth('planner')->user()->load('Image_planners')
        ]);
}
public function showPlaces($plannerId)
{
    $planner = Planner::findOrFail($plannerId);
    $places = $planner->places;

    return response()->json(['places' => $places], 200);
}
public function getPlannerEventPlaces($eventId, $plannerId) {
    $event = Event::findOrFail($eventId);
    $planner = Planner::findOrFail($plannerId);

    // Check if the planner is associated with the event
    if (!$event->planners->contains($planner)) {
        return response()->json(['error' => 'Planner does not exist in the event'], 404);
    }

    $places = $planner->places;

    return response()->json(['places' => $places], 200);
}
public function search(request $request){
    $search=$request->search;
    $data=user::where('name' , 'like','%'. $search.'%')->get();
    if ($data->isEmpty()) {
        return response()->json(['message' => 'No users found matching the search term'], 404);}
    return response()->json([
        'data' => $data,
        ]);
    }
    public function sendResetPasswordCode(Request $request) {
        $email = $request->input('email');

        // Generate a unique reset code
        $resetCode = bin2hex(random_bytes(16));

        // Store the reset code in the database
        $planner = Planner::where('email', $email)->first();
        if ($planner) {
            $planner->reset_password_code = $resetCode;
            $planner->save();

            // Send the reset code via email (Email sending logic not included)
            // mail($email, "Reset Password", "Your reset code is: " . $resetCode);

            return response()->json(['message' => 'Reset code sent to your email.']);
        } else {
            return response()->json(['message' => 'Email not found.'], 404);
        }
    }

    public function resetPassword(Request $request) {
        $email = $request->input('email');
        $resetCode = $request->input('reset_code');
        $newPassword = $request->input('new_password');

        // Validate the reset code
        $planner = Planner::where('email', $email)->where('reset_password_code', $resetCode)->first();
        if ($planner) {
            // Update the password
            $planner->password = password_hash($newPassword, PASSWORD_BCRYPT);

            $planner->reset_password_code = null; // Clear the reset code
            $planner->save();

            return response()->json(['message' => 'Password has been reset.']);
        } else {
            return response()->json(['message' => 'Invalid reset code.'], 400);
        }
    }


    public function handleorderRequest(Request $request, $id)
{
            $pendingOrder = Pending_request::findOrFail($id);

            if ($pendingOrder->planner_id != auth()->id()) {
                abort(403, 'غير مسموح لك بمعالجة هذا الطلب.');
            }
        $pend=Pending_request::where('id', $id)->first();
            $status = $request->input('status');
            $pendingOrder->update([
                'status' => $status, // 'accepted' أو 'rejected'
            ]);
        $message = $status === 'accepted' ? 'تم قبول الطلب.من المستخدم' : 'تم رفض الطلب.';
        if ($status === 'accepted') {
        $messageData = $pendingOrder->toArray();
        $response = [
            "pendingOrder" => $pendingOrder->id,
            "message" => [
                "text" => $message,
                "order" => $messageData
            ]
        ];
        }
        else
        {
            $response = [
                "pendingOrder" => $pendingOrder->id,
                "message" => [
                    "text" => $message,
                    ]
        ];
}

return response()->json($response);

}

public function showAllPending_order($id)
    {
        $Pending_order = Pending_request::with([
            'event',
            'user',
            'user.requirement',
            'user.requirement.Guest',
            'user.requirement.SpecialRequirement',
            'place'])->where('planner_id',$id)->where('status','pending')->get();
            if ($Pending_order.'status'==='pending') {
                return response()->json($Pending_order);
            }
            else{
                return response()->json(['message' => 'not found pending order']);
            }
    }

}
