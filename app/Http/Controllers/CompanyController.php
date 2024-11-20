<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\planner;


class CompanyController extends Controller
{

    public function index()
    {
        return Company::all();
    }

    public function show($id)
    {
        return Company::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:45',
            'address' => 'required|string|max:45',
            'email' => 'required|email|max:45|unique:companies',
            'phone' => 'required|string|max:45',
        ]);

        $company = Company::create($validatedData);
        return response()->json($company, 201);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:45',
            'address' => 'sometimes|required|string|max:45',
            'email' => 'sometimes|required|email|max:45|unique:companies,email',
            'phone' => 'sometimes|required|string|max:45',
        ]);

        $company->update($validatedData);
        return response()->json($company);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }
    public function destroy_planner($id)
    {
        $planner = planner::findOrFail($id);
        $planner->delete();

        return response()->json(['message' => 'planner deleted successfully']);
    }
    public function sendResetPasswordCode(Request $request) {
        $email = $request->input('email');

        $resetCode = bin2hex(random_bytes(16));
        $company = Company::where('email', $email)->first();
        if ($company) {
            $company->reset_password_code = $resetCode;
            $company->save();


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
        $company = Company::where('email', $email)->where('reset_password_code', $resetCode)->first();
        if ($company) {
            // Update the password
            $company->password = password_hash($newPassword, PASSWORD_BCRYPT);
            $company->reset_password_code = null; // Clear the reset code
            $company->save();

            return response()->json(['message' => 'Password has been reset.']);
        } else {
            return response()->json(['message' => 'Invalid reset code.'], 400);
        }
    }

}


