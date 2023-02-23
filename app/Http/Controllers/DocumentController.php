<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\Document;
use App\Models\MailLog;
use App\Models\Borrower;
use App\Models\DocuMail;
use App\Models\Office;
use App\Models\Employee;
use App\Models\Agreement;
use App\Models\AgreementDocument;
use App\Models\CustomerLoanDocument;
use App\Models\RejectionReason;
use App\Models\RejectDocument;
use App\Models\AdditionalDocument;
use App\Models\Docuchecker;

class DocumentController extends Controller
{
    public function index(){
        $parent = Document::orderby('document_name', 'asc')->where('parent_id', null)->where('status', 1)->get();
        return view('admin.document.index', compact('parent'));
    }
    public function RejectionReasonList(){
        $parent = Document::orderby('document_name', 'asc')->where('parent_id', null)->where('status', 1)->get();
        return view('admin.document.rejection', compact('parent'));
    }
    public function create(Request $request){
        $create = new Document;
        $create->document_name = ucwords($request->document_name);
        // $create->parent_id = $request->parent_id != "new-parent" ? $request->parent_id : NULL;
        $create->save();
        if($create){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function RejectionReasonCreate(Request $request){
        $create = new RejectionReason;
        $create->rejection_reason = ucwords($request->document_description);
        $create->document_parent_id = $request->parent_id;
        $create->save();
        if($create){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function RejectionReasonEdit(Request $request){
        // dd($request->all());
        $update = RejectionReason::findOrFail($request->id);
        $update->rejection_reason = $request->document_reason;
        $update->save();
        if($update){
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
    public function RejectionReasonDelete(Request $request){
        // dd($request->all());
        $delete = RejectionReason::findOrFail($request->id);
        $delete->delete();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);
    }
    public function delete(Request $request){
        // dd($request->all());
        $delete = Document::findOrFail($request->id);
        $delete->status = 0;
        $delete->save();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);
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
        $Docuchecker = Docuchecker::where('borrower_id', $id)->get();
        $agreement = Agreement::orderBy('name', 'ASC')->get();
        $Office = Office::orderBy('name', 'ASC')->get();
        $customerDetails = Borrower::select('name_prefix', 'full_name')->where('id', $id)->first();
        $customer = $customerDetails->name_prefix.' '.$customerDetails->full_name;
        return view('admin.document.docuchecker', compact('customer','agreement', 'Office', 'id', 'Docuchecker'));
    }

    public function DocucheckerNewEnquiry(Request $request){
        if($request->has('loanAcNo')){
            $rules['loanAcNo'] = 'required|unique:docucheckers,loan_ac_no';
        }
        $validate = validator()->make($request->all(), $rules);
        if (!$validate->fails()) {
            $GetAgrName = Agreement::where('id', $request->agrType)->first();
            $create = new Docuchecker;
            $create->borrower_id = $request->borrower_id;
            $create->agreement_id = $request->agrType;
            $create->agreement_name = $GetAgrName->name;
            $create->branch_id = $request->Branch;
            $create->rm_id = $request->RM;
            $loanAccountNo = $GetAgrName->loan_acc_no_format.$request->loanAcNo;
            $create->loan_ac_no = $loanAccountNo;
            $create->loanAmount = $request->loanAmount;
            $create->save();
            if($create){
                return response()->json(['status' => 200]);
            }else{
                return response()->json(['status' => 400]);
            }
        }else{
            return response()->json(['status' => 100]);
        }
    }
    public function DocucheckerUpdateEnquiry(Request $request){
        // dd($request->all());
        $GetAgrName = Agreement::where('id', $request->updateAgrType)->first();
            $create = Docuchecker::findOrFail($request->EnquiryId);
            $create->borrower_id = $request->borrower_id;
            $create->agreement_id = $request->updateAgrType;
            $create->agreement_name = $GetAgrName->name;
            if($request->loanAcNo){
                $loanAccountNo = $GetAgrName->loan_acc_no_format.$request->loanAcNo;
                $create->loan_ac_no = $loanAccountNo;
            }
            $create->loanAmount = $request->loanAmount;
            $create->first_submission_date = $request->firstSdate;
            $create->save();
            return redirect()->route('user.docu-checker.index', $request->borrower_id)->with('success', 'Updated Enquiry Details');
    }

    public function CustomerDucuchecker($id){
        $Docuchecker = Docuchecker::where('id', $id)->first();
        $AgreementDocument = AgreementDocument::select('document_id')->where('agreement_id', $Docuchecker->agreement_id)->get();
        // dd($AgreementDocument);
        // $parent = array();
        // $TotalDocument_id = array();
        // foreach($AgreementDocument as $parentDocument){
        //     $getDocument = Document::select('parent_id')->where('id', $parentDocument->document_id)->first();
        //     $parent[]=$getDocument->parent_id;
        //     $TotalDocument_id[]=$parentDocument->document_id;
        // }
        // $Finalparent = array_unique($parent);
        $pendingData = DB::table('customer_loan_documents')->where('docuchecker_id', $Docuchecker->id)->whereIn('status', array(1, 4, 0))->get();
        // Additional Documents
        $AdditionalRejectDocument = CustomerLoanDocument::where('docuchecker_id', $id)->where('additional_doc', 1)->get();
        return view('admin.document.details', compact('AgreementDocument', 'Docuchecker', 'pendingData','AdditionalRejectDocument'));
    }
    public function DocucheckerStatus(Request $request){
        $getBorrower = Docuchecker::where('id', $request->docuCheckId)->first();
            $DocumentData = Document::where('id', $request->id)->first();
            $document = CustomerLoanDocument::where('docuchecker_id', $request->docuCheckId)->where('borrower_id', $getBorrower->borrower_id)->where('document_name', $DocumentData->document_name)->first();
           
           if($document == null){
               $create = new CustomerLoanDocument;
               $create->borrower_id = $getBorrower->borrower_id;
               $create->docuchecker_id = $request->docuCheckId;
               $create->document_name = $DocumentData->document_name;
               $create->status = $request->status;
               $create->save();
           }else{
               $create = CustomerLoanDocument::findOrFail($document->id);
               $create->status = $request->status;
               $create->save();
           }
           if($request->status == 4){
            $reject_documents = new RejectDocument;
            $reject_documents->document_reason = $request->ReasonData;
            $reject_documents->docucheckers_id = $request->docuCheckId;
            $reject_documents->customer_loan_documents_id = $create->id;
            $reject_documents->observation = $request->Observation;
            $reject_documents->save();
        }
           if($create){
               return response()->json(['status' => 200]);
           }else{
               return response()->json(['status' => 400]);
           }
    }
    public function DocucheckerReasonList(Request $request){
        $data = RejectionReason::where('document_parent_id', $request->Reason_head)->get();
        $items = array();
        foreach($data as $value){
            $items[] = array($value->id, "$value->rejection_reason");
        }
        if(count($items)>0){
            return response()->json(["status" => 200, "data" => $items]);
        }else{
            return response()->json(["status" => 400]);
        }
    }
    public function AddtionalReasonList(Request $request){
        $data = AdditionalDocument::where('parent_id', $request->DocParent)->get();
        $items = array();
        foreach($data as $value){
            $items[] = array($value->id, "$value->name");
        }
        if(count($items)>0){
            return response()->json(["status" => 200, "data" => $items]);
        }else{
            return response()->json(["status" => 400]);
        }
    }
    public function DocucheckerExpiryDate(Request $request){
     $getBorrower = Docuchecker::where('id', $request->DocuId)->first();
     $DocumentData = Document::where('id', $request->id)->first();
        $document = CustomerLoanDocument::where('docuchecker_id', $request->DocuId)->where('borrower_id', $getBorrower->borrower_id)->where('document_name', $DocumentData->document_name)->first();
        if($document == null){
            $create = new CustomerLoanDocument;
            $create->borrower_id = $getBorrower->borrower_id;
            $create->docuchecker_id = $request->DocuId;
            $create->document_name = $DocumentData->document_name;
            $create->expiry_date = $request->date;
            $create->save();
        }else{
            $create = CustomerLoanDocument::findOrFail($document->id);
            $create->expiry_date = $request->date;
            $create->save();
        }
        if($create){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }

    public function EnquiryList(){
        $AllEnquiry = Docuchecker::orderBy('id', 'ASC')->get();
        return view('admin.document.enquiry-list', compact('AllEnquiry'));
    }
    public function AdditionalList(){
        $AdditionalDocument = AdditionalDocument::select('parent_id')->distinct()->get();
        return view('admin.document.additional', compact('AdditionalDocument'));
    }
    public function AdditionalStore(Request $request){
        $create = new AdditionalDocument;
        $create->name = ucwords($request->document_name);
        $create->parent_id = $request->parent_id;
        $create->save();
        if($create){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function AdditionalUpdate(Request $request){
        $update = AdditionalDocument::findOrFail($request->id);
        $update->name = ucwords($request->document_edit);
        $update->parent_id = $request->parent_id;
        $update->save();
        if($update){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }
    public function AdditionalDelete(Request $request){
        $delete = AdditionalDocument::findOrFail($request->id);
        $delete->delete();
        return response()->json(['error' => false, 'title' => 'Deleted', 'message' => 'Record deleted', 'type' => 'success']);
    }

    // Notify Mail

    public function NotifyMail(Request $request){
        $getDocuchecker = Docuchecker::where('id', $request->docuId)->where('status', 1)->first();
        
        $notifydate = $request->notifydate;
        $RMName = $getDocuchecker->RMData->name;
        $LoanNo = $getDocuchecker->loan_ac_no;
        $RMEmail = $getDocuchecker->RMData->email;
        $RMreporting_manager = $getDocuchecker->RMData->reporting_manager;
        $ReportingManager = Employee::where('id', $RMreporting_manager)->first();
        $ReportingManagerMail = $ReportingManager==null ? $RMEmail : $ReportingManager->email;
        $ReportingManagerName = $ReportingManager==null ? $RMName : $ReportingManager->name;
        $customername = $getDocuchecker->CustomerData->name_prefix.' '.$getDocuchecker->CustomerData->full_name;
         // email data
         $data = [
            'docuId' => $request->docuId,
            'RMName' => $RMName,
            'documents' => $request->documents,
            'status' => $request->status,
            'subject' => 'Rejection of loan account no.('.$LoanNo.') Customer name ('.$customername.')',
            'LoanNo' => $LoanNo,
            'customername' => $customername,
            'RMEmail' => $RMEmail,
            'notifydate' => $notifydate,
            'SubjectType' => "Notify Mail",
            'ReportingManagerMail' => $ReportingManagerMail,
            'ReportingManagerName' => $ReportingManagerName,
            'blade_file' => 'esign/notify',
        ];
        SendMail($data);
        return redirect()->route('user.docu-checker.details', $request->docuId)->with('success', 'Send mail to '.$RMName);
    }
    public function RmMailDetails(){
        $AllDocuMail = DocuMail::orderBy('id', 'ASC')->get()->unique('docuchecker_id');
        // dd($AllDocuMail);
        return view('admin.document.maildetails', compact('AllDocuMail'));
    }

    public function EnquiryExport(Request $request){
        // dd($request->all());
        if(isset($request->EnquiryDetails)){
            $endDate=$startDate="";
            $start = $request->to_date;
            $startDate=$start;
            $start_date=$start." 00:00:00";
            $end = $request->from_date;
            $endDate=$end;
            $end_date=$end." 23:59:59";
            $AllEnquiries = Docuchecker::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
            if(count($AllEnquiries)==0){
                return redirect()->route('user.customer.enquiry-list')->with('Warning', 'Data Not Found !');
            }else{
                $delimiter = ",";
                $filename = "Customer-Agreement-Enquiry-List-".date('Y-m-d').".csv";
                $fields = array('SR', "Customer Name", 'Agreement Name', 'Branch', 'RM', 'Loan Acc. No', 'Request Amount', 'Sanction Amount', 'Sanction Date', 'Status', 'Date');

                $f = fopen('php://memory', 'w');
                 // Set column headers 
                 fputcsv($f, $fields, $delimiter);
                 $count = 1;  
                foreach($AllEnquiries as $row) {
                    $cname = $row->CustomerData->name_prefix.' '.$row->CustomerData->full_name;
                    $Branch = $row->OfficeData->name;
                    $RelationshipManager = $row->RMData->name;
                    $status = $row['status'];
                    if($status==1){
                        $status ="Pending";
                    }else{
                        $status ="Sanction";
                    }
                    $datetime = date('j F, Y h:i A', strtotime($row['created_at']));
                    $lineData = array($count, $cname, $row['agreement_name'],$Branch,$RelationshipManager, $row['loan_ac_no'], $row['loanAmount'],  $row['sanction_amount'], $row['sanction_date'],$status,$datetime);
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
            }
        }
    }

    public function AdditionalReasonStore(Request $request){
        $getCustomer = Docuchecker::select('borrower_id')->where('id', $request->Docuchecker)->first();
        $create = new CustomerLoanDocument;
        $create->docuchecker_id = $request->Docuchecker;
        $create->document_name = $request->AddDocuments;
        $create->borrower_id = $getCustomer->borrower_id;
        $create->additional_doc = 1;
        $create->status = 1;
        $create->save();
        return redirect()->route('user.docu-checker.details', $request->Docuchecker)->with('success', 'Added new Additional reason');
    }
    public function AdditionalStatus(Request $request){
        $updateStatus = CustomerLoanDocument::findOrFail($request->id);
        $updateStatus->status = $request->value;
        $updateStatus->save();
        if($updateStatus){
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
       
    }
    public function DefailtLoanNumberSet(Request $request){
        // dd($request->all());
        $data = Agreement::select('loan_acc_no_format')->where('id', $request->id)->first();
        if(isset($data)){
            return response()->json(["status" => 200, "data" => $data->loan_acc_no_format]);
        }else{
            return response()->json(["status" => 400]);
        }

    }

}
