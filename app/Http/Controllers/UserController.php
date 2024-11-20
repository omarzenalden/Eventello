<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Planner;
use App\Models\place;
use App\Models\event;
use App\Models\Pending_request;
use Throwable;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password',]);
        $token = auth('api')->attempt($credentials);
        return $this->createNewToken($token);
    }
    public function index(){
        $planners = planner::all();
        return response()->json($planners);
    }

     /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',

        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
     /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth('api')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth::refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
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
            'user' => auth()->user()
        ]);
}

public function showOrders($userId)
    {
        $user = User::findOrFail($userId);
        $orders = $user->orders;

        return response()->json(['orders' => $orders], 200);
    }
    public function search(request $request){
       $search=$request->search;
        $data=planner::where('name' , 'like','%'. $search .'%')->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'No planners found matching the search term'], 404);
        }
        return response()->json([
            'data' => $data,200
            ]);
        }

        public function sendResetPasswordCode(Request $request) {
            $email = $request->input('email');
            $resetCode = bin2hex(random_bytes(16));
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->reset_password_code = $resetCode;
                $user->save();
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
            $user = User::where('email', $email)->where('reset_password_code', $resetCode)->first();
            if ($user) {
                // Update the password
                $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
                $user->reset_password_code = null; // Clear the reset code
                $user->save();

                return response()->json(['message' => 'Password has been reset.']);
            } else {
                return response()->json(['message' => 'Invalid reset code.'], 400);
            }
        }

        public function sendEventRequest(Request $request,$user_id,$event_id,$place_id,$planner_id)
      {
        try {
        $user = user::find($user_id);
        $place = place::find($place_id);
        $event = event::find($event_id);
        $planner = planner::find($planner_id);
        $status = $request->input('status');
        if (!$user || !$place || !$event || !$planner)
         {
            return response()->json(['message' => 'لم يتم العثور على أحد الكائنات المطلوبة.'], 404);
          }
        $Pending_request = new Pending_request($request->all());
        $Pending_request->user()->associate($user);
        $Pending_request->place()->associate($place);
        $Pending_request->event()->associate($event);
        $Pending_request->planner()->associate($planner);
        $Pending_request->save();
        return response()->json(['message' => 'تم إرسال الطلب بنجاح.', "data" => $Pending_request], 201);
    }  catch (\Exception $e) {
        if (!isset($user) || !isset($place) || !isset($event) || !isset($planner)) {
            return response()->json(['message' => 'لم يتم العثور على أحد الكائنات المطلوبة.'], 404);
         }
         else {
            return response()->json(['message' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
        }
    }
}

public function sendEventDetails(Request $request)
{
    $eventRequest = EventRequest::where('user_id', auth()->id())->firstOrFail();
    if ($eventRequest->status != 'accepted') {
        abort(403, 'غير مسموح لك بإرسال التفاصيل الآن.');
    }
    $eventRequest->update([
        'details' => $request->input('details'),
        'guests' => $request->input('guests'),
    ]);

    return redirect()->back()->with('success', 'تم إرسال تفاصيل الحفل بنجاح.');
}
public function show_Pending_order($id)
    {
        $Pending_order = Pending_request::where('user_id',$id)->get();
        return response()->json($Pending_order);
    }
    public function Status_of_my_order(Request $request,$id)
    {
        $pendingOrder = Pending_request::where('user_id', $id)->firstOrFail();
    if ($pendingOrder->status == 'rejected') {
        return response()->json([
            'message' => 'لم يتم الموافقة على طلبك.'
        ], 200);
    } elseif ($pendingOrder->status == 'accepted') {
        // إذا كانت حالة الطلب 'accepted'
        return response()->json([
            'message' => 'تم الموافقة على طلبك. يرجى إرسال متطلباتك العامة الخاصة واسماء الضيوف.',
            'orderDetails' => $pendingOrder->toArray()
        ], 200);
    } else {
        abort(404, 'طلب غير موجود أو حالة الطلب غير معروفة.');
    }
    }
 }
