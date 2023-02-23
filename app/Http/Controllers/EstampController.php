<?php

namespace App\Http\Controllers;

use App\Models\Estamp;
use App\Models\BorrowerAgreement;
use Illuminate\Http\Request;

class EstampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['allStamps'] = Estamp::latest()->get();
        return view('admin.estamp.index')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'unique_stamp_code' => 'required|string|min:1|max:255|unique:estamps',
            'back_page' => 'nullable|mimes:png,jpg,jpeg,pdf',
            'front_text' => 'max:250',
            'back_text' => 'nullable|max:250',
            'front_page' => 'required|mimes:png,jpg,jpeg,pdf',
        ], [
            'back_page.required' => 'This field is required',
            'front_page.required' => 'This field is required',
            'unique_stamp_code.required' => 'This field is required',
            'unique_stamp_code.max' => 'Maximum character reached',
            'unique_stamp_code.unique' => 'Already taken',
            'front_text.max' => 'Maximum character reached',
            'back_text.max' => 'Maximum character reached'
        ]);

        // if file has back page
        if($request->hasFile('back_page')){
            $file = $request->file('back_page');
            $fileNameForBack = fileUpload($file,'estamp');
        } else {
            $fileNameForBack = null;
        }

        if($request->hasFile('front_page')){
            $save_location = 'admin/uploads/estamp/';
            $data = $request->image;
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data = base64_decode($image_array_2[1]);
            $imageName = time() . '.png';

            if (file_put_contents($save_location.$imageName, $data)) {
                $fileNameForFront = $save_location.$imageName;        
            }
        } else {
            $fileNameForFront = null;
        }

        $estamp = new Estamp();
        $estamp->unique_stamp_code = $request->unique_stamp_code;
        // $estamp->back_file_path = $fileNameForBack;
        $estamp->front_file_path = $fileNameForFront;
        $estamp->front_text = $request->front_text;
        // $estamp->back_text = $request->back_text;
        $estamp->amount = $request->amount;
        $estamp->save();

        return redirect()->route('user.estamp.list')->with('success', 'Rs '.$request->amount.' Estamp created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = Estamp::findOrFail($request->id);
        return response()->json(['error' => false, 'data' => $data]);
    }

    public function details(Request $request)
    {
        $data = [];
        $data['stamp_details'] = Estamp::findOrFail($request->id);
        return view('admin.estamp.view')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = [];
        $data['stamp_details'] = Estamp::findOrFail($id);
        return view('admin.estamp.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'unique_stamp_code' => 'required|string|min:1|max:255',
            'back_page' => 'nullable|mimes:png,jpg,jpeg,pdf',
            'front_page' => 'nullable|mimes:png,jpg,jpeg,pdf',
            'front_text' => 'nullable',
        ], [
            'back_page.required' => 'This field is required',
            'front_page.required' => 'This field is required',
            'unique_stamp_code.required' => 'This field is required',
            'unique_stamp_code.max' => 'Maximum character reached',
            'unique_stamp_code.unique' => 'Already taken'
        ]);

        $estamp = Estamp::findOrFail($id);

        if($request->hasFile('back_page')){
            $image = $request->file('back_page');
            $fileNameForBack = fileUpload($image,'estamp');
        }else{
            $fileNameForBack = $estamp->file_path;
        }
        
        if($request->hasFile('front_page')){

            $save_location = 'admin/uploads/estamp/';
            $data = $request->image;
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data = base64_decode($image_array_2[1]);
            $imageName = time() . '.png';

            if (file_put_contents($save_location.$imageName, $data)) {
                $fileNameForFront = $save_location.$imageName;        
            }

        }else{
            $fileNameForFront = $estamp->file_path;
        }
        
        $estamp->unique_stamp_code = $request->unique_stamp_code;
        if($request->front_text) {
            $estamp->front_text = $request->front_text;
        }
        // $estamp->back_file_path = $fileNameForBack;
        $estamp->front_file_path = $fileNameForFront;
        $estamp->save();

        return redirect()->route('user.estamp.list')->with('success', 'Estamp updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Estamp::where('id', $request->id)->delete();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Estamp deleted', 'type' => 'success']);
    }
    public function ExportEstamp(Request $request){
       
        $endDate=$startDate="";
        $start = $request->to_date;
        $startDate=$start;
        $start_date=$start." 00:00:00";
        $end = $request->from_date;
        $endDate=$end;
        $end_date=$end." 23:59:59";
        if(isset($request->Rs10)){
            $allStamp = Estamp::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->where('amount', 10)->where('used_flag', 1)->get();
        }elseif($request->Rs50){
            $allStamp = Estamp::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->where('amount', 50)->where('used_flag', 1)->get();
        }elseif($request->Rs100){
            
            $allStamp = Estamp::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->where('amount', 100)->where('used_flag', 1)->get();
        }else{
            $allStamp = Estamp::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->where('amount', 500)->where('used_flag', 1)->get();
        }

        if (count($allStamp) > 0) {
            $delimiter = ",";
            if(isset($request->Rs10)){
                $filename = "Rs-10-Stamp-Data-".date('Y-m-d').".csv";
                $fields = array('SR', "Unique Stamp Code", 'Denomination', "Used By(Borrower's name)", 'Application Id', 'Amount', 'Date');
            }elseif(isset($request->Rs50)){
                $filename = "Rs-50-Stamp-Data-".date('Y-m-d').".csv";
                $fields = array('SR', "Unique Stamp Code", 'Denomination', "Used By(Borrower's name)", 'Application Id', 'Amount', 'Date');
            }elseif(isset($request->Rs100)){
                $filename = "Rs-100-Stamp-Data-".date('Y-m-d').".csv";
                $fields = array('SR', "Unique Stamp Code", 'Denomination', "Used By(Borrower's name)", 'Application Id', 'Amount', 'Date');
            }else{
                $filename = "Rs-500-Stamp-Data-".date('Y-m-d').".csv";
                $fields = array('SR', "Unique Stamp Code", 'Denomination', "Used By(Borrower's name)", 'Application Id', 'Amount', 'Date');
            }
             
            // Create a file pointer 
            $f = fopen('php://memory', 'w');
             // Set column headers 
             fputcsv($f, $fields, $delimiter);
             $count = 1;  
            foreach($allStamp as $row) {
                $datetime = date('j F, Y h:i A', strtotime($row['created_at']));
                $BorrowerAgreement = BorrowerAgreement::where('id', $row->used_in_agreement)->first();
                $borrowerName = $BorrowerAgreement->borrowerDetails->full_name;
                $agreementName = $BorrowerAgreement->agreementDetails->name;
                $applicationId = $BorrowerAgreement->application_id;
                if(isset($request->Rs10)){
                    $lineData = array($count, $row->unique_stamp_code, $agreementName, $borrowerName, $applicationId,$row->amount, $datetime);
                }elseif($request->Rs50){
                    $lineData = array($count, $row->unique_stamp_code, $agreementName, $borrowerName, $applicationId,$row->amount, $datetime);
                }elseif($request->Rs100){
                    $lineData = array($count, $row->unique_stamp_code, $agreementName, $borrowerName, $applicationId,$row->amount, $datetime);
                }else{
                    $lineData = array($count, $row->unique_stamp_code, $agreementName, $borrowerName, $applicationId,$row->amount, $datetime);
                }
                fputcsv($f, $lineData, $delimiter);
                $count++;
            }
            // Move back to beginning of file
            fseek($f, 0);
            // Set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            //output all remaining data on a file pointer
            fpassthru($f);
        }else{
            return redirect()->route('user.estamp.list')->with('Warning', 'Sorry data not found !');
        }

    }


}
