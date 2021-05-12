<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
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
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if (!$user->is_admin) {
            return response('Unauthorised', 401);
        }
        
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'username' => 'required|max:50|unique:users',
            'password' => 'required',
            'designation_id' => 'integer|exists:App\Models\Designation,id',
            'department_id' => 'integer|exists:App\Models\Department,id',
            'mobile' => 'digit:10|unique:users'
        ]);

        $password = Hash::make($request->input('password'));

        User::create([
            'title' => $request->input('title'),
            'username' => $request->input('username'),
            'password' => $password,
            'designation_id' => $request->input('designation_id'),
            'department_id' => $request->input('department_id'),
            'mobile' => $request->input('mobile'),
        ]);

        return response()->json(['message' => 'User Created Success']);
    }

    public function select(Request $request) {
        $this->validate($request, [
            'username' => 'required|max:50',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->input('username'))->first();

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
            'designation_id' => 'integer|exists:App\Models\Designation,id',
            'department_id' => 'integer|exists:App\Models\Department,id',
        ]);

        $userToUpdate = User::findOrFail($request->input('user_id'));
        $userToUpdate->title = $request->input('title');
        $userToUpdate->designation_id = $request->input('designation_id');
        $userToUpdate->department_id = $request->input('department_id');
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

    public function createDepartment(Request $request) {
        $this->validate($request, [
            'title' => 'required|unique:departments'
        ]);

        $department = Department::create([
            'title' => $request->input('title')
        ]);

        return response()->json($department);
    }

    public function createDesignation(Request $request) {
        $this->validate($request, [
            'title' => 'required|unique:designations',
            'department_id' => 'required|exists:App\Models\Department,id'
        ]);

        $designation = Designation::create([
            'title' => $request->input('title'),
            'department_id' => $request->input('department_id')
        ]);

        return response()->json($designation);
    }

    private function token() {
        $token = openssl_random_pseudo_bytes(32);
        return bin2hex($token);
    }
}
