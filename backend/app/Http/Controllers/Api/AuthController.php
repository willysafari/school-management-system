<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;




class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:300',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'role' => 'nullable|in:admin,author,reader',
             'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validator->errors()
            ], 401);
        }

        $user = $request->all();

    // image uploaded

    $imagePath=null;

    if($request->hasFile('profile_picture') && $request->file('profile_picture')->isvalid()){
        $file = $request->file('profile_picture');
        $fileName= time().'_'.$file->getClientOriginalName();

        // move the file to public directory
        $file ->move(public_path('storage/profile'), $fileName);

        // save the relative path to database
        $imagePath = "storage/profile/".$fileName;
    }

    $user['profile_picture'] = $imagePath;

        User::create($user);
        return response()->json([
            'status' => 'success',
            'message' => 'data created successfully'
        ], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

    public function login(Request $request)
    {
        // validate data

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fails',
                'message' => $validator->errors()
            ], 401);
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $datas['token'] = $user->createToken('SchoolApp')->plainTextToken;
            $datas['name'] = $user->name;
            $datas['email'] = $user->email;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successfully',
                'data' => $datas,
            ], 201);
        } else {
            return response()->json([
                'status' => 'failed',
                ' error' => 'email or password not corrent'
            ], 400);
        }
    }

    public function profile()
    {

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 201);
    }
    public function logout()
    {

        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'logout successfully '
        ]);
    }
}
