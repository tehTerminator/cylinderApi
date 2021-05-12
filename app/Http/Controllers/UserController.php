<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if (!$user->is_admin) {
            return response('Unauthorised', 401);
        }
        
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'username' => 'required|max:50|unique:users',
            'password' => 'required',
            'mobile' => 'digits:10|unique:users',
            'designation' => 'required|string'
        ]);

        $password = Hash::make($request->input('password'));

        User::create([
            'title' => $request->input('title'),
            'username' => $request->input('username'),
            'password' => $password,
            'mobile' => $request->input('mobile'),
            'designation' => $request->input('designation')
        ]);

        return response()->json(['message' => 'User Created Success']);
    }

    public function select(Request $request) {
        $this->validate($request, [
            'username' => 'required|max:50',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if (is_null($user)) {
            return response('Unauthorised', 401);
        }

        if (Hash::check($request->input('password'), $user->password)) {
            $user->token = $this->token();
            $user->save();
            $user = $user->fresh();

            return response()->json($user);
        }

        return response('Unauthorised', 401);
    }

    public function updateBasicInfo(Request $request) {
        $this->validate($request, [
            'mobile' => 'digit:10',
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $old_password = $request->input('old_password');
        $new_password = Hash::make($request->input('new_password'));
        
        if (Hash::check($user->password, $old_password)) {
            $user->mobile = $request->input('mobile');
            $user->password = $new_password;
            $user->save();

            return response()->json(['message' => 'Password Changed Successfully']);
        }

        return response('Unauthorised', 401);
        
    }

    public function updateBasicDetails(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if (!$user->is_admin) {
            return response('Unauthorised', 401);
        }

        $this->validate($request, [
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'title' => 'string',
        ]);

        $userToUpdate = User::findOrFail($request->input('user_id'));
        $userToUpdate->title = $request->input('title');
        $userToUpdate->save();

        return response()->json(['message' => 'User Updated Successfully']);
    }

    public function resetPassword(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if (!$user->is_admin) {
            return response('Unauthorised', 401);
        }

        $this->validate($request, [
            'user_id' => 'required|integer|exists:App\Models\User,id',
        ]);

        $userToUpdate = User::findOrFail($request->input('user_id'));
        $newPasswod = Hash::make('password');
        $userToUpdate->password = $newPasswod;
        $userToUpdate->save();

        return response()->json(['message' => 'User Updated Successfully']);
    }

    private function token() {
        $token = openssl_random_pseudo_bytes(32);
        return bin2hex($token);
    }
}
