<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
                'password' => password_hash($request['password'], PASSWORD_DEFAULT)
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

        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'User not found'], 404);
        } else {
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
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'User Not Found'], 404);
        } else {
            DB::beginTransaction();
            try {
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->contact = $request['contact'];
                $user->pincode = $request['pincode'];
                $user->address = $request['address'];
                $user->status = $request['status'];
                $user->save();
                DB::commit();
                return response()->json([
                    'status' => 1,
                    'message' => 'User Updated Successfully',
                    'data' => $user
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 0, 'message' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = DB::table('users')->find($id);

        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'User not found'], 404);
        } else {
            try {
                DB::beginTransaction();
                /* Delete user data from users table first to avoid foreign key constraint error */
                // $user->delete();
                DB::table('users')->where('id', $id)->delete();
                DB::commit();
                return response()->json(['status' => 1, 'message' => 'User deleted successfully']);
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json(['status' => 0, 'message' => 'Internal server error', 'error' => $exception], 500);
            }
        }
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            try{
                if(password_verify($request['old_pass'], $user->password)){
                    if($request['password_confirmation'] == $request['password']){
                        $user->password = password_hash($request['password'], PASSWORD_DEFAULT);
                        $user->save();
                        return response()->json(['status' => 1, 'message' => 'Password Change Success', 'data' => $user], 200);
                    } else{
                        return response()->json(['status' => 0, 'message' => 'Password not matched'], 400);
                    }
                } else{
                    return response()->json(['status' => 0, 'message' => 'Old passward is incorrect'], 400);
                }
            }catch(\Exception $exception) {
                return response()->json([
                    "status" => 0,
                    "message"=> "Internel Server Error."
                ], 500);
            }
        } else {
            return response()->json([
                "status" => 0,
                "message" => "The user does not exist."

            ], 404);
        }
    }
}
