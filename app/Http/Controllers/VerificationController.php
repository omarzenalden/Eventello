<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Planner;
use App\Models\Verification;
use App\Mail\VerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class VerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required_without:planner_id|exists:users,id',
            'planner_id' => 'required_without:user_id|exists:planners,id',
        ]);

        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        if ($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);

            Verification::create([
                'user_id' => $user->id,
                'code' => $code,
                'expires_at' => $expiresAt,
            ]);

            Mail::to($user->email)->send(new VerificationMail($code));
        } elseif ($request->has('planner_id')) {
            $planner = Planner::findOrFail($request->planner_id);

            Verification::create([
                'planner_id' => $planner->id,
                'code' => $code,
                'expires_at' => $expiresAt,
            ]);

            Mail::to($planner->email)->send(new VerificationMail($code));
        } else {
            return response()->json(['message' => 'User or Planner ID is required'], 400);
        }

        return response()->json(['message' => 'Verification code sent']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required_without:planner_id|exists:users,id',
            'planner_id' => 'required_without:user_id|exists:planners,id',
            'code' => 'required',
        ]);

        if ($request->has('user_id')) {
            $verification = Verification::where('user_id', $request->user_id)
                                        ->where('code', $request->code)
                                        ->first();
        } elseif ($request->has('planner_id')) {
            $verification = Verification::where('planner_id', $request->planner_id)
                                        ->where('code', $request->code)
                                        ->first();
        } else {
            return response()->json(['message' => 'User or Planner ID is required'], 400);
        }

        if (!$verification || $verification->expires_at->isPast()) {
            return response()->json(['message' => 'Invalid or expired verification code'], 400);
        }

        // Perform any action after successful verification

        $verification->delete();

        return response()->json(['message' => 'Verification successful']);
    }
}
