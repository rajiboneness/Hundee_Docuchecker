<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Document;
use App\Models\Borrower;
use App\Models\Office;
use App\Models\Agreement;
use App\Models\AgreementDocument;
use App\Models\CustomerLoanDocument;

class DocumentController extends Controller
{
    public function index(){
        $parent = Document::orderby('document_name', 'asc')->where('parent_id', null)->where('status', 1)->get();
        return view('admin.document.index', compact('parent'));
    }
    public function create(Request $request){
        $create = new Document;
        $create->document_name = ucwords($request->document_name);
        $create->parent_id = $request->parent_id != "new-parent" ? $request->parent_id : NULL;
        $create->save();
        if($create){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function edit(Request $request){
        $update = Document::findOrFail($request->id);
        $update->document_name = $request->document_name;
        $update->save();
        if($update){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function delete(Request $request){
        // dd($request->all());
        $delete = Document::findOrFail($request->id);
        $delete->status = 0;
        $delete->save();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);
        // return redirect()->route('user.document.list')->with('success', 'Successfully deleted');
    }
    public function view(Request $request){
        $document = Document::where('id', $request->id)->where('status', 1)->first();
        $siblingsDocuments = Document::where('parent_id', $request->id)->where('status', 1)->get();
        return response()->json(['status' => 200, 'document' => $document->document_name, 'siblingsDocuments' => $siblingsDocuments]);
    }
    public function sub_delete(Request $request){
        $delete = Document::findOrFail($request->id);
        $delete->status = 0;
        $delete->delete();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);

    }
    //  Docu Checker
    public function CustomerList(){
        $allCustomer = Borrower::select('id', 'name_prefix', 'full_name', 'email', 'mobile')->latest('id')->get();
        return view('admin.document.customer', compact('allCustomer'));
    }
    public function AddLoanType(Request $request){
        $update = Borrower::findOrFail($request->borrower_id);
        $update->Loan_Type = $request->loan_type;
        $update->save();
        return response()->json(['status' => 200]);
    }
    public function Customerchecker($id){
        $customerDetails = Borrower::select('name_prefix', 'full_name')->where('id', $id)->first();
        $customer = $customerDetails->name_prefix.' '.$customerDetails->full_name;
        return view('admin.document.docuchecker', compact('customer'));
    }
    // public function Customerchecker($id){
    //     $Borrower = Borrower::where('id', $id)->first();
    //     $Agreement = Agreement::where('name', $Borrower->Loan_Type)->first();
    //     $document = AgreementDocument::where('agreement_id', $Agreement->id)->get();
    //     foreach($document as $key => $data){
    //         $getDocument = Document::where('id', $data->document_id)->first();
    //         $existData = CustomerLoanDocument::where('borrower_id', $Borrower->id)->where('document_name', $getDocument->document_name)->first();
    //         if(empty($existData)){
    //             $ExpiryDate =Date("Y-m-d", strtotime('+10 days'));
    //             $upload = new CustomerLoanDocument;
    //             $upload->borrower_id =$Borrower->id;
    //             $upload->borrower_loan_type = $Borrower->Loan_Type;
    //             $upload->document_name = $getDocument->document_name;
    //             $upload->expiry_date = $ExpiryDate;
    //             $upload->save();
    //         }
    //     }
    //     $loanDocument = CustomerLoanDocument::where('borrower_id', $id)->orderby('expiry_date', 'ASC')->get();
    //     $dataCount = CustomerLoanDocument::where('borrower_id', $id)->where('status', 1)->get()->count();
    //     $array_data =array();
    //     foreach($loanDocument as $dataValue){
    //         $array_data[] = $dataValue->documentData->parent_id;
    //         $status[] = $dataValue->status;
    //     }
    //     $finalLoanType = array_unique($array_data);
    //     return view('admin.document.docuchecker', compact('Borrower', 'finalLoanType', 'loanDocument', 'dataCount'));
    // }
    public function DocucheckerStatus(Request $request){
        // dd($request->all());
        $status = CustomerLoanDocument::findOrFail($request->id);
        $status->status = $request->status;
        $status->save();
        if($status){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function DocucheckerExpiryDate(Request $request){
        $date = CustomerLoanDocument::findOrFail($request->id);
        $date->expiry_date = $request->date;
        $date->save();
        if($date){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
}
