<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QualificationController extends Controller
{
    public function index()
    {
        $data = (object)DB::select('select * from qualifications where status = ?', [1]);
        // dd($data);
        return view('admin.qualification.index', compact('data'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:2|max:255|unique:designations',
        ];

        $validator = Validator::make($request->all(), $rules);


        if (!$validator->fails()) {
            $data = DB::select('select * from qualifications where name = ?', [$request->name]);
            if (count($data) == 0) {
                $qualification = new Qualification();
                $qualification->name = $request->name;
                $qualification->save();

                $route = "'" . route('user.qualification.show') . "'";

                return response()->json(['status' => 200, 'title' => 'success', 'message' => 'New Qualification added', 'id' => $qualification->id, 'viewRoute' => $route]);
            }
            else{
                return response()->json(['status' => 200, 'title' => 'success', 'message' => 'Already Added']);
            }
        } else {
            return response()->json(['status' => 400, 'title' => 'failure', 'message' => $validator->errors()->first()]);
        }
    }

    public function show(Request $request)
    {
        // dd("Comming Here");
        $data = (object)DB::select('select * from qualifications where id = ?', [1])[0];
        $designation_id = $data->id;
        $designation_name = $data->name;
        $designation_created_at = $data->created_at;

        return response()->json(['error' => false, 'data' => ['id' => $designation_id, 'name' => $designation_name, 'created_at' => $designation_created_at]]);
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|numeric|min:1',
            'name' => 'required|string|min:2|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            DB::update('update qualifications set name = ? where id = ?', [$request->name, $request->id]);

            return response()->json(['status' => 200, 'title' => 'success', 'message' => 'Qualification updated']);
        } else {
            return response()->json(['status' => 400, 'title' => 'failure', 'message' => $validator->errors()->first()]);
        }
    }

    public function destroy(Request $request)
    {
        DB::delete('delete from qualifications where id = ?', [$request->id]);
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);
    }
}
