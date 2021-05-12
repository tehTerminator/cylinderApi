<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OxygenRequestController extends Controller
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
            'father' => 'required|string|max:100',
            'age' => 'required|integer|min:1|max:150',
            'mobile' => 'required|digits:10',
            'narration' => 'string',
            'bed_number' => 'required|integer|min:1',
            'has_oxygen_line' => 'boolean',
            'ward_id' => 'required|exists:App\Models\Ward,id'
        ]);

        $patient = Patient::where('date_of_discharge', NULL)
        ->where('ward_id', $request->input('ward_id'))
        ->where('bed_number', $request->input('bed_number'))->first();

        if (is_null($patient)) {
            $patient = Patient::create([
                'title' => $request->input('title'),
                'father' => $request->input('father'),
                'age' => $request->input('age'),
                'mobile' => $request->input('mobile'),
                'narration' => $request->input('narration'),
                'bed_number' => $request->input('bed_number'),
                'has_oxygen_line' => $request->input('has_oxygen_line'),
                'ward_id' => $request->input('ward_id'),
            ]);

            return response()->json($patient);
        }

        return response('Bed Already Occupied By Patient #' . $patient->id, 409);
    }

    public function dischargePatient(Request $request) {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $patient = Patient::findOrFail($request->input('id'));
        $patient->date_of_discharge = Carbon::now();
        $patient->save();

        $patient = $patient->fresh();
        return response()->json($patient);
    }
}
