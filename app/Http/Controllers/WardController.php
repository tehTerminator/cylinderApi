<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
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
            'title' => 'required|unique:wards|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $ward = Ward::create([
            'title' => $request->input('title'),
            'capacity' => $request->input('capacity')
        ]);

        return response()->json($ward);
    }
}
