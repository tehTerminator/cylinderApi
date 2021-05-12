<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ExampleController extends Controller
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
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'username' => 'required|max:50|unique:users',
            'password' => 'required',
            'designation_id' => 'integer|'
        ]);

        $password = Hash::make($request->input('password'));

        User::create([
            'displayName' => $request->input('displayName'),
            'username' => $request->input('username'),
            'password' => $password,
        ]);
    }

    public function select(Request $request) {
        $this->validate($request, [
            'username' => 'required|digits:10',
            'password' => 'required',
            'role' => 'required|in:ADMINISTRATOR,DELIVERY,SUPPLIER,CUSTOMER'
        ]);

        $user = User::where('username', $request->input('username'))
        ->where('role', $request->input('role'))
        ->first();

        if ($user === NULL) {
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

    public function update(Request $request) {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $old_password = $request->input('old_password');
        $new_password = Hash::make($request->input('new_password'));
        
        if (Hash::check($user->password, $old_password)) {
            if ($request->has('displayName')) {
                $user->displaName = $request->input('displayName');
            }
            $user->password = $new_password;
            $user->save();

            return response()->json(['message' => 'Password Changed Successfully']);
        }

        return response('Unauthorised', 401);
        
    }

    private function token() {
        $token = openssl_random_pseudo_bytes(32);
        return bin2hex($token);
    }
}
