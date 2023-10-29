<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required|email|unique:users',
                'age' => 'required',
                'exercise_history' => 'required',
                'form_of_workout' => 'required',
                'fitness_goal' => 'required',
                'goal_timeline' => 'required',
                'city' => 'required',
                'info_source' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'age' => $request->age,
                'exercise_history' => $request->exercise_history,
                'fitness_level' => $request->fitness_level,
                'form_of_workout' => $request->form_of_workout,
                'fitness_goal' => $request->fitness_goal,
                'goal_timeline' => $request->goal_timeline,
                'city' => $request->city,
                'info_source' => $request->info_source
            ]);

            $saved = $user->save();
            if (!$saved) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User registration failed',
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User registration successful',
                'user' => $user,  // Include user data in the response if needed
            ], 201);
        } catch (ValidationException $e) {
            // Handle the validation exception for duplicate email
            return response()->json([
                'status' => 'error',
                'message' => 'Email address is already in use.',
            ], 422); // Return a 422 Unprocessable Entity status code
        }
    }
}
