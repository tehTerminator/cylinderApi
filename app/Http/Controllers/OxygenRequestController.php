<?php

namespace App\Http\Controllers;

use App\Models\OxygenRequest;
use App\Models\Patient;
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
            'patient_id' => 'required|exists:App\Models\Patient,id',
            'ward_id' => 'required|exists:App\Models\Ward,id',
            'bed_number' => 'required|integer|min:1',
            'spo2_level' => 'required|integer|min:1|max:100',
            'comment' => 'string'
        ]);

        $patient = Patient::findOrFail($request->input('patient_id'));

        if (is_null($patient->date_of_discharge)) {
            $oxyRequest = OxygenRequest::create([
                'patient_id' => $request->input('patient_id'),
                'ward_id' => $request->input('ward_id'),
                'bed_number' => $request->input('bed_number'),
                'spo2_level' => $request->input('spo2_level'),
                'comment' => $request->input('comment'),
                'state' => 'ACTIVE'
            ]);
            return response()->json($oxyRequest);
        }

        return response('Patient #' . $patient->id . ' is Already Discharge', 409);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'state' => 'required|in:APPROVED,REJECTED',
            'id' => 'required|exists:App\Models\OxygenRequest,id'
        ]);

        $oxyRequest = OxygenRequest::find($request->input('id'));
        $oxyRequest->state = $request->input('state');
        $oxyRequest->save();

        return response()->json($oxyRequest);
    }
}
