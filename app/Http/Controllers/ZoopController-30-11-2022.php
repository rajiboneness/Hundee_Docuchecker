<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\AgreementData;
use App\Models\AgreementRfq;
use App\Models\BorrowerAgreement;
use App\Models\User;
use App\Models\ESignature;
use Illuminate\Support\Facades\DB;

class ZoopController extends Controller
{
    public function __construct() {
        $this->aadhar_init_url = "https://test.zoop.one/contract/esign/v4/aadhaar/init";
        $this->app_id = "62a1b8581c552a001fb9113e";
        $this->api_key = "HDY9YMP-8V7MY7P-NJSYZBH-6FSPT46";
        $this->response_url = url('/').'/webhook/esign';
        // $this->response_url = "https://webhook.site/f2b5ebd7-2b25-4c45-b357-315d7a1efe28";
        $this->redirect_url = url('/').'/zoop/redirect';

        // borrower info
        // $this->borrower_name = "Suvajit Bardhan";
        // $this->borrower_email = "bardhansuvajit@gmail.com";
        // $this->borrower_city = "Kolkata";
        // $this->borrower_purpose = "Test purpose";

        // // co-borrower info
        // $this->co_borrower_name = "Prabir Das";
        // $this->co_borrower_email = "prabir@onenesstechs.in";
        // $this->co_borrower_city = "Midnapore";
        // $this->co_borrower_purpose = "Test purpose";

        // // authorised signatory info
        // $this->authorised_signatory_name = "Shreyan Dey";
        // $this->authorised_signatory_email = "shreyan.dey@onenesstechs.in";
        // $this->authorised_signatory_city = "North 24 Paraganas";
        // $this->authorised_signatory_purpose = "Test purpose";
    }

    public function getData($agreement, $checkFeld){
        $response = '';
        foreach ($agreement as $key => $value) {
            if($value->field_name == $checkFeld){
                $response = $value->field_value;
                break;
            }
        }
        return $response;
    }

    public function detail(Request $request, $borrowerId, $borrowerAgreementId, $rfqId) {
        // die('Hi');
        $data = (object)[];
        $data->borrower = Borrower::findOrFail($borrowerId);
        $data->agreementData = AgreementData::where('rfq_id', $rfqId)->get();

        

        $data->borrower_name = $this->getData($data->agreementData, 'nameoftheborrower');
        $data->borrower_email = $this->getData($data->agreementData, 'emailidoftheborrower');
        $data->borrower_city = $data->borrower->city ?? $data->borrower->KYC_CITY;

        $data->co_borrower_name = $this->getData($data->agreementData, 'nameofthecoborrower');
        $data->co_borrower_email = $this->getData($data->agreementData, 'emailidofthecoborrower');
        $data->co_borrower_city = $this->getData($data->agreementData, 'cityofthecoborrower');

        $data->co_borrower_name2 = $this->getData($data->agreementData, 'nameofthecoborrower2');
        $data->co_borrower_email2 = $this->getData($data->agreementData, 'emailidofthecoborrower2');
        $data->co_borrower_city2 = $this->getData($data->agreementData, 'cityofthecoborrower2');
        
        $data->guarantor_name = $this->getData($data->agreementData, 'nameoftheguarantor');
        $data->guarantor_email = $this->getData($data->agreementData, 'emailidoftheguarantor');
        $data->guarantor_city = $this->getData($data->agreementData, 'cityoftheguarantor');

        $data->executant_name = '';
        $data->executant_email = '';
        $data->executant_city = '';

        if(CheckVernaculationData($borrowerAgreementId)){
            $data->executant_name = VernaculationData($borrowerAgreementId)['executant_name'];
            $data->executant_email = VernaculationData($borrowerAgreementId)['executant_email'];
            $data->executant_city = '';
        }

        $data->authorised_signatory_name = $this->getData($data->agreementData, 'nameoftheauthorisedsignatory');
        // $data->co_borrower_email = $this->getData($data->agreementData, 'emailidofthecoborrower');
        // $data->co_borrower_city = $this->getData($data->agreementData, 'cityofthecoborrower');

        $all_auth_sig = User::where('user_type', 1)->where('id', '!=', 1)->get();

        $all_e_signatures = ESignature::where('rfqId',$rfqId)->orderBy('id','DESC')->get();
        $e_signatures = [];
        foreach ($all_e_signatures as $value) {
            if(!in_array($value->unique_id,$e_signatures)){
                array_push($e_signatures,$value->unique_id);
            }
        }

        if(BorrowerAgreement::find($borrowerAgreementId)->application_id != null)
            $app_id =  "<i class='text-sm text-bold'>".BorrowerAgreement::find($borrowerAgreementId)->application_id."</i><br>";
        else
            $app_id = "<i class='text-sm text-muted'>No App.ID</i><br>";

        $b_checked = $cb_checked = $cb2_checked = $executant_checked =  $as_checked = "";
        $b_disabled = $cb_disabled = $cb2_disabled = $executant_disabled = $as_disabled = $submit_disabled =  "";

        return view('admin.borrower.esign', compact('data', 'all_auth_sig', 'rfqId', 'e_signatures','b_checked','cb_checked','cb2_checked','as_checked','b_disabled','cb_disabled','cb2_disabled','as_disabled','submit_disabled','borrowerAgreementId','app_id'));
    }
    
    // Esigning Email flow changes, New Mail controller and esign controller

    public function MultipleMail(Request $request)
    {   
        // dd($request->all());

        $request->validate([
            'file'=>'required|file|mimes:pdf',
            'documentInformation'=>'required|string|min:3'
        ],[
            'file.required'=>'You need to upload the pdf-agreement for sending for esignature!'
        ]);

        $converted_doc = base64_encode(file_get_contents($request->file('file')));
        $document_info = $request->documentInformation;

        $uniqid = 'HMUID' . uniqid();


        if ($request->is_borrower) {
            $request->validate([
                'borrower_name' => 'required',
                'borrower_email' => 'required|email',
                'borrower_city' => 'required',
                'borrower_purpose' => 'required',
            ]);

        }
        if ($request->is_co_borrower) {
            $request->validate([
                'co_borrower_name' => 'required',
                'co_borrower_email' => 'required|email',
                'co_borrower_city' => 'required',
                'co_borrower_purpose' => 'required',
            ]);
        }
        if ($request->co_borrower_2) {
            $request->validate([
                'co_borrower_name2' => 'required',
                'co_borrower_email2' => 'required|email',
                'co_borrower_city2' => 'required',
                'co_borrower_purpose2' => 'required',
            ]);
        }
        if ($request->guarantor) {
            $request->validate([
                'guarantor_name' => 'required',
                'guarantor_email' => 'required|email',
                'guarantor_city' => 'required',
                'guarantor_purpose' => 'required',
            ]);
        }
        if ($request->executant) {
            $request->validate([
                'executant_name' => 'required',
                'executant_email' => 'required|email',
                'executant_city' => 'required',
                'executant_purpose' => 'required',
            ]);
        }
        if ($request->is_authorised_signatory) {
            $request->validate([
                'authorised_signatory_name' => 'required',
                'authorised_signatory_email' => 'required|email',
                'authorised_signatory_city' => 'required',
                'authorised_signatory_purpose' => 'required',
            ]);
        }

        // dd('validated');

        if($request->is_borrower){
            //Code to mail to borrower
            if (!empty($request->borrower_name)) {

                $esign1 = new ESignature();
                $esign1->rfqId = $request->rfqId;
                $esign1->unique_id = $uniqid;
                $esign1->encoded_pdf = $converted_doc;
                $esign1->document_info = $document_info;
                $esign1->user_type = "borrower";
                $esign1->user_name = $request->borrower_name;
                $esign1->user_email = $request->borrower_email;
                $esign1->user_city = $request->borrower_city;
                $esign1->user_purpose = $request->borrower_purpose;
                $esign1->save();
            }
            // email data
            // $data = [
            //     'name' => $request->borrower_name,
            //     'subject' => 'HUNDEE - E-signing for borrower',
            //     'email' => $request->borrower_email,
            //     'city' => $request->borrower_city,
            //     'blade_file' => 'esign/index',
            //     'route' => route('zoop.user.mail.click', $uniqid)
            // ];
            // $resp = SendMail($data);

        }
        if($request->is_co_borrower){
            //Code to mail to co-borrower
            if (!empty($request->co_borrower_name)) {

                $esign3 = new ESignature();
                $esign3->rfqId = $request->rfqId;
                $esign3->unique_id = $uniqid;
                $esign3->encoded_pdf = $converted_doc;
                $esign3->document_info = $document_info;
                $esign3->user_type = "co_borrower1";
                $esign3->user_name = $request->co_borrower_name;
                $esign3->user_email = $request->co_borrower_email;
                $esign3->user_city = $request->co_borrower_city;
                $esign3->user_purpose = $request->co_borrower_purpose;
                $esign3->save();
    
                // email data
                // $data = [
                //     'name' => $request->co_borrower_name,
                //     'subject' => 'HUNDEE - E-signing for Co-borrower 1',
                //     'email' => $request->co_borrower_email,
                //     'city' => $request->co_borrower_city,
                //     'blade_file' => 'esign/index',
                //     'route' => route('zoop.user.mail.click', $uniqid)
                // ];
                // $resp = SendMail($data);
            }

        }
        if($request->co_borrower_2){
            //Code to mail to coborrower2
            if (!empty($request->co_borrower_name2)) {
                
                $esign4 = new ESignature();
                $esign4->rfqId = $request->rfqId;
                $esign4->unique_id = $uniqid;
                $esign4->encoded_pdf = $converted_doc;
                $esign4->document_info = $document_info;
                $esign4->user_type = "co_borrower2";
                $esign4->user_name = $request->co_borrower_name2;
                $esign4->user_email = $request->co_borrower_email2;
                $esign4->user_city = $request->co_borrower_city2;
                $esign4->user_purpose = $request->co_borrower_purpose2;
                $esign4->save();
    
                // email data
                // $data = [
                //     'name' => $request->co_borrower_name2,
                //     'subject' => 'HUNDEE - E-signing for Co-borrower 2',
                //     'email' => $request->co_borrower_email2,
                //     'city' => $request->co_borrower_city2,
                //     'blade_file' => 'esign/index',
                //     'route' => route('zoop.user.mail.click', $uniqid)
                // ];
                // $resp = SendMail($data);
            }

        }
        if($request->guarantor){
            //Code to mail to guarantor
            if (!empty($request->guarantor_name)) {

                $esign7 = new ESignature();
                $esign7->rfqId = $request->rfqId;
                $esign7->unique_id = $uniqid;
                $esign7->encoded_pdf = $converted_doc;
                $esign7->document_info = $document_info;
                $esign7->user_type = "guarantor";
                $esign7->user_name = $request->guarantor_name;
                $esign7->user_email = $request->guarantor_email;
                $esign7->user_city = $request->guarantor_city;
                $esign7->user_purpose = $request->guarantor_purpose;
                $esign7->save();
    
                // email data
                // $data = [
                //     'name' => $request->guarantor_name,
                //     'subject' => 'HUNDEE - E-signing for Guarantor',
                //     'email' => $request->guarantor_email,
                //     'city' => $request->guarantor_city,
                //     'blade_file' => 'esign/index',
                //     'route' => route('zoop.user.mail.click', $uniqid)
                // ];
                // $resp = SendMail($data);
            }

        }
        if($request->executant){
            //Code to mail to executant
            if (!empty($executant_name)) {

                $esign6 = new ESignature();
                $esign6->rfqId = $request->rfqId;
                $esign6->unique_id = $uniqid;
                $esign6->encoded_pdf = $converted_doc;
                $esign6->document_info = $document_info;
                $esign6->user_type = "executant";
                $esign6->user_name = $request->executant_name;
                $esign6->user_email = $request->executant_email;
                $esign6->user_city = $request->executant_city;
                $esign6->user_purpose = $request->executant_purpose;
                $esign6->save();
    
                // email data
                // $data = [
                //     'name' => $request->executant_name,
                //     'subject' => 'HUNDEE - E-signing for Executant',
                //     'email' => $request->executant_email,
                //     'city' => $request->executant_city,
                //     'blade_file' => 'esign/index',
                //     'route' => route('zoop.user.mail.click', $uniqid)
                // ];
                // $resp = SendMail($data);
            }

        }
        if($request->is_authorised_signatory){
            //Code to mail to PFSL
            if (!empty($request->authorised_signatory_name)) {

                $esign2 = new ESignature();
                $esign2->rfqId = $request->rfqId;
                $esign2->unique_id = $uniqid;
                $esign2->encoded_pdf = $converted_doc;
                $esign2->document_info = $document_info;
                $esign2->user_type = "authorised_signatory";
                $esign2->user_name = $request->authorised_signatory_name;
                $esign2->user_email = $request->authorised_signatory_email;
                $esign2->user_city = $request->authorised_signatory_city;
                $esign2->user_purpose = $request->authorised_signatory_purpose;
                $esign2->save();
    
                // email data
                // $data = [
                //     'name' => $request->authorised_signatory_name,
                //     'subject' => 'HUNDEE - E-signing for authorised signatory',
                //     'email' => $request->authorised_signatory_email,
                //     'city' => $request->authorised_signatory_city,
                //     'blade_file' => 'esign/index',
                //     'route' => route('zoop.user.mail.click', $uniqid)
                // ];
                // $resp = SendMail($data);
            }

        }

        if($this->MultipleEsign($uniqid) == false){
            return redirect()->back()->with('success','Some Error occured! Try to lower the pdf size less than 15mb.');
        }

        return redirect()->back()->with('success','Mail Sent successfully!');
    }

    public function MultipleEsign($id)
    {
        
        $esigns = ESignature::where('unique_id',$id)->get();

        // dd($esigns);

        $borrower_signer = $coborrower_signer = $guarantor_signer = $executant_signer = $pfsl_signer = 'No';

        $signers = (array)[];
        foreach($esigns as $esign){
            
            // Request signer body for borrower
            if($esign->user_type == 'borrower'){

                $sign_coordinate_array = 
                [   
                    '{
                        "page_num": 5,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 6,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 7,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 8,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 9,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 10,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 11,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 12,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 13,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 14,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 15,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 16,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 17,
                        "x_coord": 50,
                        "y_coord": 660
                    }',
                    '{
                        "page_num": 18,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 19,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 20,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 21,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 22,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 23,
                        "x_coord": 230,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 24,
                        "x_coord": 420,
                        "y_coord": 475
                    }',
                    '{
                        "page_num": 25,
                        "x_coord": 380,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 26,
                        "x_coord": 355,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 27,
                        "x_coord": 355,
                        "y_coord": 140
                    }',
                    '{
                        "page_num": 30,
                        "x_coord": 375,
                        "y_coord": 480
                    }',
                    '{
                        "page_num": 31,
                        "x_coord": 355,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 32,
                        "x_coord": 355,
                        "y_coord": 330
                    }'
                ];

                $last_page = 32;

                $AgreementRfq = AgreementRfq::find($esign->rfqId);
                $borrower_id = $AgreementRfq->borrower_id;
                $agreement_id = $AgreementRfq->agreement_id;
                $BorrowerAgreement = BorrowerAgreement::where('borrower_id',$borrower_id)->where('agreement_id',$agreement_id)->latest('id')->get()[0]->id;

                if(VernaculationData($BorrowerAgreement)['borrower_check'] == 'Yes'){
                    $last_page = $last_page + 1;
                    $barr = (array)[
                        "page_num" => $last_page,
                        "x_coord" => 360,
                        "y_coord" => 490
                    ];
                    array_push($sign_coordinate_array, json_encode($barr));    
                }

                $sign_coordinate_string = implode(',',$sign_coordinate_array);

                // dd($sign_coordinate_string);

                $signer_data = (array)[
                    '{"signer_name":"'.$esign->user_name.'"',
                    '"signer_email":"' . $esign->user_email.'"',
                    '"signer_city":"' . $esign->user_city.'"',
                    '"signer_purpose":"' . $esign->user_purpose.'"',
                    '"sign_coordinates":' . '['.$sign_coordinate_string.']}'
                ];

                array_push($signers,implode(',',$signer_data));

                $borrower_signer = 'Yes';
            }

            // Request signer body for co-borrower
            if($esign->user_type == 'co_borrower1'){

                $sign_coordinate_array = 
                [   
                    '{
                        "page_num": 5,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 6,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 7,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 8,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 9,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 10,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 11,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 12,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 13,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 14,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 15,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 16,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 17,
                        "x_coord": 30,
                        "y_coord": 585
                    }',
                    '{
                        "page_num": 18,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 19,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 20,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 21,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 22,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 23,
                        "x_coord": 30,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 24,
                        "x_coord": 245,
                        "y_coord": 475
                    }',
                    '{
                        "page_num": 25,
                        "x_coord": 70,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 26,
                        "x_coord": 90,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 27,
                        "x_coord": 90,
                        "y_coord": 140
                    }',
                    '{
                        "page_num": 30,
                        "x_coord": 120,
                        "y_coord": 480
                    }',
                    '{
                        "page_num": 31,
                        "x_coord": 100,
                        "y_coord": 25
                    }',
                    '{
                        "page_num": 32,
                        "x_coord": 88,
                        "y_coord": 350
                    }'
                ];

                $last_page = 32;

                $AgreementRfq = AgreementRfq::find($esign->rfqId);
                $borrower_id = $AgreementRfq->borrower_id;
                $agreement_id = $AgreementRfq->agreement_id;
                $BorrowerAgreement = BorrowerAgreement::where('borrower_id',$borrower_id)->where('agreement_id',$agreement_id)->latest('id')->get()[0]->id;

                if(VernaculationData($BorrowerAgreement)['borrower_check'] == 'Yes'){
                    $last_page = $last_page+1;
                }
                if(VernaculationData($BorrowerAgreement)['coborrower_check'] == 'Yes'){
                    $last_page = $last_page + 1;
                    $barr = (array)[
                        "page_num" => $last_page,
                        "x_coord" => 360,
                        "y_coord" => 490
                    ];
                    array_push($sign_coordinate_array, json_encode($barr));    
                }

                $sign_coordinate_string = implode(',',$sign_coordinate_array);

                // dd($sign_coordinate_string);

                $signer_data = (array)[
                    '{"signer_name":"'.$esign->user_name.'"',
                    '"signer_email":"' . $esign->user_email.'"',
                    '"signer_city":"' . $esign->user_city.'"',
                    '"signer_purpose":"' . $esign->user_purpose.'"',
                    '"sign_coordinates":' . '['.$sign_coordinate_string.']}'
                ];

                array_push($signers,implode(',',$signer_data));

                $coborrower_signer = 'Yes';
            }

            // Request signer body for guarantor
            if($esign->user_type == 'guarantor'){

                $sign_coordinate_array = 
                [   
                    '{
                        "page_num": 17,
                        "x_coord": 300,
                        "y_coord": 60
                    }'
                ];

                $last_page = 32;

                $AgreementRfq = AgreementRfq::find($esign->rfqId);
                $borrower_id = $AgreementRfq->borrower_id;
                $agreement_id = $AgreementRfq->agreement_id;
                $BorrowerAgreement = BorrowerAgreement::where('borrower_id',$borrower_id)->where('agreement_id',$agreement_id)->latest('id')->get()[0]->id;

                if(VernaculationData($BorrowerAgreement)['borrower_check'] == 'Yes'){
                    $last_page = $last_page + 1;    
                }
                if(VernaculationData($BorrowerAgreement)['coborrower_check'] == 'Yes'){
                    $last_page = $last_page + 1;   
                }
                if(VernaculationData($BorrowerAgreement)['gurrantor_check'] == 'Yes'){
                    $last_page = $last_page + 1;
                    $barr = (array)[
                        "page_num" => $last_page,
                        "x_coord" => 360,
                        "y_coord" => 505
                    ];
                    array_push($sign_coordinate_array, json_encode($barr));    
                }

                $sign_coordinate_string = implode(',',$sign_coordinate_array);

                // dd($sign_coordinate_string);

                $signer_data = (array)[
                    '{"signer_name":"'.$esign->user_name.'"',
                    '"signer_email":"' . $esign->user_email.'"',
                    '"signer_city":"' . $esign->user_city.'"',
                    '"signer_purpose":"' . $esign->user_purpose.'"',
                    '"sign_coordinates":' . '['.$sign_coordinate_string.']}'
                ];

                array_push($signers,implode(',',$signer_data));

                $guarantor_signer = 'Yes';
            }

            // Request signer body for executant
            if($esign->user_type == 'executant'){

                $sign_coordinate_array = [];

                $last_page = 32;

                $AgreementRfq = AgreementRfq::find($esign->rfqId);
                $borrower_id = $AgreementRfq->borrower_id;
                $agreement_id = $AgreementRfq->agreement_id;
                $BorrowerAgreement = BorrowerAgreement::where('borrower_id',$borrower_id)->where('agreement_id',$agreement_id)->latest('id')->get()[0]->id;

                if(VernaculationData($BorrowerAgreement)['borrower_check'] == 'Yes'){
                    $last_page = $last_page + 1;
                    $barr = (array)[
                        "page_num" => $last_page,
                        "x_coord" => 80,
                        "y_coord" => 490
                    ];
                    array_push($sign_coordinate_array, json_encode($barr));    
                }
                if(VernaculationData($BorrowerAgreement)['coborrower_check'] == 'Yes'){
                    $last_page = $last_page + 1;
                    $barr = (array)[
                        "page_num" => $last_page,
                        "x_coord" => 80,
                        "y_coord" => 490
                    ];
                    array_push($sign_coordinate_array, json_encode($barr));   
                }
                if(VernaculationData($BorrowerAgreement)['gurrantor_check'] == 'Yes'){
                    $last_page = $last_page + 1;
                    $barr = (array)[
                        "page_num" => $last_page,
                        "x_coord" => 80,
                        "y_coord" => 490
                    ];
                    array_push($sign_coordinate_array, json_encode($barr));    
                }

                $sign_coordinate_string = implode(',',$sign_coordinate_array);

                // dd($sign_coordinate_string);

                $signer_data = (array)[
                    '{"signer_name":"'.$esign->user_name.'"',
                    '"signer_email":"' . $esign->user_email.'"',
                    '"signer_city":"' . $esign->user_city.'"',
                    '"signer_purpose":"' . $esign->user_purpose.'"',
                    '"sign_coordinates":' . '['.$sign_coordinate_string.']}'
                ];

                array_push($signers,implode(',',$signer_data));

                $executant_signer = 'Yes';
            }

            // Request signer body for pfsl
            if($esign->user_type == 'authorised_signatory'){

                $signer_data = (array)
                [
                    '{"signer_name":"'.$esign->user_name.'"',
                    '"signer_email":"' . $esign->user_email.'"',
                    '"signer_city":"' . $esign->user_city.'"',
                    '"signer_purpose":"' . $esign->user_purpose.'"',
                    '"sign_coordinates":[
                        {
                            "page_num": 5,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 6,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 7,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 8,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 9,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 10,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 11,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 12,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 13,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 14,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 15,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 16,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 17,
                            "x_coord": 26,
                            "y_coord": 388
                        },
                        {
                            "page_num": 18,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 19,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 20,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 21,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 22,
                            "x_coord": 420,
                            "y_coord": 25
                        },
                        {
                            "page_num": 23,
                            "x_coord": 420,
                            "y_coord": 25
                        }
                    ]}'
                ];

                array_push($signers,implode(',',$signer_data));

                $pfsl_signer = 'Yes';
            }
        }

        // Single Api Call to multi-sign for all types of user!
        $curl = curl_init();

        $curl_request_body = '{
            "document": {
                "data": "' . $esigns[0]->encoded_pdf . '",
                "info": "' . $esigns[0]->document_info . '"
            },
            "signers": ['.implode(',',$signers).'],
            "txn_expiry_min": 2880,
            "response_url": "'. $this->response_url .'",
            "redirect_url": "'. $this->redirect_url .'",
            "email_template": {
                "org_name": "Hundee-Peerless"
            }
        }';

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://test.zoop.one/contract/esign/v5/init',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $curl_request_body,
            CURLOPT_HTTPHEADER => array(
                'app-id: 62a1b8581c552a001fb9113e',
                'api-key: HDY9YMP-8V7MY7P-NJSYZBH-6FSPT46',
                'Content-Type: application/json'
            ),
        ));

        // Response for multiple signing api call;
        $response = curl_exec($curl);
        curl_close($curl);
        $response_arr = json_decode($response);

        if($response == 'Body exceeded 1mb limit'){
            return false;
        }
        // print_r($response);
        // exit();

        // Sending mails to users after getting response.
        foreach ($response_arr->requests as $key => $value) {

            // Inserting new data for each signers in webhook response table
            DB::table('zoop_webhook_response')->insert(['esign_uniq_id'=>$id,'request_id'=>$value->request_id]);

            // email data
            $data = [
                'name' => $value->signer_name,
                'subject' => 'HUNDEE - E-signing for '.$value->signer_name,
                'email' => $value->signer_email,
                'blade_file' => 'esign/index',
                'route' => route('zoop.user.esigning.mail.click', base64_encode($value->request_id))
            ];
            SendMail($data);
        }
        return true;
    }
    
    public function EsignBlade(Request $request, $id) {
        $id = base64_decode($id);
        return view('zoop.borrower', compact('id'));
    }

    public function redirect(Request $request) {
        return view('zoop.redirect');
    }

    public function response(Request $request) {
        // dd($request->all());
        $json_req = json_encode($request->all);
        $file = fopen("public/esign/data.txt","w");
        echo fwrite($file, $json_req);
        fclose($file);
        // return view('zoop.response');
    }
}
