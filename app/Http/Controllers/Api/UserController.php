<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        if (count($users) == 0) {
            return response()->json([
                'message' => 'No user found',
                'status' => 0
            ], 404);
        } else {
            return response()->json(['message' => count($users) . " user found", 'status' => 1, "data" => $users], 200);
        }
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['min:8', 'required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password'])
            ]);
            return response()->json(['Success' => 'User Registration Successfull'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => '500 Internal Server Error'], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        
        if(!$user){
            return response()->json([ 'status' => 0, 'message'=> 'User not found'],404);
        }else{
            return response()->json(['status' => 1, "data" => $user], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
