<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Office;
use Exception;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    public function index(Request $request){
        $data = Employee::where([
            [function ($query) use ($request) {
                if($request->term =="Relationship Manager"){
                    $getDesignation = Designation::where('name', $request->term)->first();
                    $query->orWhere('designation_id', $getDesignation->id)->get();
                }else{
                    if ($term = $request->term) {
                        $query
                            ->orWhere('name', 'LIKE', '%' . $term . '%')
                            ->orWhere('emp_id', 'LIKE', '%' . $term . '%')
                            ->orWhere('email', 'LIKE', '%' . $term . '%')
                            ->orWhere('mobile', 'LIKE', '%' . $term . '%')
                            ->get();
                    }
                }
                
            }]
        ])
        ->latest('id')
        ->paginate(15)
        ->appends(request()->query());
    return view('admin.employee.index', compact('data'));
    }

    public function create(){
        $data = (object)[];
        $data->users = Employee::select('id', 'name')->orderBy('name')->get();
        $data->departments = Department::select('id', 'name')->orderBy('name')->get();
        $data->designations = Designation::select('id', 'name')->orderBy('name')->get();
        $data->offices = Office::select('id', 'name')->orderBy('name')->get();
        // dd($data->users);
        return view('admin.employee.create', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request->email);
        $request->validate([
            'name' => 'required|string|min:1|max:255',
            'employee_id' => 'required|string|min:1|max:255',
            'email' => 'required|string|email',
            'phone_number' => 'nullable|integer|digits:10',
            // 'department' => 'required|integer|min:1',
            // 'designation' => 'required|integer|min:1',
            'reporting_manager' => 'nullable|numeric|min:1',
            'office' => 'required|numeric|min:1',
            'street_address' => 'nullable',
        ], [
            'phone_number.*' => 'Please enter a valid 10 digit phone number'
        ]);
        DB::beginTransaction();
        try {
            $user = new Employee;
            $user->name = $request->name;
            $user->emp_id = $request->employee_id;
            $user->email = $request->email;
            $user->mobile = $request->phone_number;
            $user->department_id = $request->department;
            $user->designation_id = $request->designation;
            $user->reporting_manager = $request->reporting_manager ? $request->reporting_manager : 0;
            $user->office_id = $request->office;
            $user->street_address = $request->street_address;
            $user->save();
            DB::commit();
            return redirect()->route('user.emp.list')->with('success', 'Employee created');
        } catch(Exception $e) {
            DB::rollback();
            $error['email'] = 'Something went wrong.';
            return redirect(route('user.emp.create'))->withErrors($error)->withInput($request->all());
        }
    }
    public function show(Request $request)
    {
        $data = Employee::with(['department', 'designation'])->where('id', $request->id)->first();

        // dd($data);
        $reporting_manager = $data->reporting_manager ? $data->manager->name : null;
        $user_department = $data->department_id != 0 ? $data->department->name : null;
        $user_designation = $data->designation_id != 0 ? $data->designation->name : null;
        $user_office = $data->office_id ? $data->office->name : null;
        return response()->json(['error' => false, 'data' => ['name' => $data->name, 'email' => $data->email, 'mobile' => $data->mobile, 'image_path' => asset($data->image_path), 'reporting_manager' => $reporting_manager, 'emp_id' => $data->emp_id, 'department' => $user_department, 'designation' => $user_designation, 'office' => $user_office]]);
    }
    public function edit(Request $request, $id)
    {
        $data = (object)[];
        $data->user = Employee::findOrFail($id);
        $data->users = Employee::select('id', 'name')->orderBy('name')->where('id', '!=', $id)->get();
        $data->departments = Department::select('id', 'name')->orderBy('name')->get();
        $data->designations = Designation::select('id', 'name')->orderBy('name')->get();
        $data->offices = Office::select('id', 'name')->orderBy('name')->get();
        return view('admin.employee.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|min:1|max:255',
            'employee_id' => 'required|string|min:1|max:255',
            'phone_number' => 'nullable|integer|digits:10',
            // 'department' => 'required|integer|min:1',
            // 'designation' => 'required|integer|min:1',
            'reporting_manager' => 'nullable|numeric|min:1',
            'office' => 'required|numeric|min:1',
        ], [
            'phone_number.*' => 'Please enter a valid 10 digit phone number'
        ]);

        $user = Employee::findOrFail($id);
        $user->name = $request->name;
        $user->emp_id = $request->employee_id;
        $user->email = $request->email;
        $user->mobile = $request->phone_number;
        $user->department_id = $request->department;
        $user->designation_id = $request->designation;
        $user->reporting_manager = $request->reporting_manager ? $request->reporting_manager : 0;
        $user->office_id = $request->office;
        $user->street_address = $request->street_address;
        $user->save();
        return redirect()->route('user.emp.list')->with('success', 'Employee updated');
    }

    public function block(Request $request)
    {
        $user = Employee::findOrFail($request->id);
        if ($user->block == 0) {
            $user->block = 1;
            $title = 'Blocked';
            $message = 'Employee is blocked';
        } else {
            $user->block = 0;
            $title = 'Active';
            $message = 'Employee is active';
        }
        $user->save();

        return response()->json(['error' => false, 'title' => $title, 'message' => $message, 'type' => 'success']);
    }
    public function destroy(Request $request)
    {
        Employee::where('id', $request->id)->delete();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);
    }
}
