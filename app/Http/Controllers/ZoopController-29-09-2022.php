<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\AgreementData;
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

        $data->authorised_signatory_name = $this->getData($data->agreementData, 'nameoftheauthorisedsignatory');
        // $data->co_borrower_email = $this->getData($data->agreementData, 'emailidofthecoborrower');
        // $data->co_borrower_city = $this->getData($data->agreementData, 'cityofthecoborrower');

        $all_auth_sig = User::where('user_type', 1)->where('id', '!=', 1)->get();

        $e_signatures = ESignature::select('id','rfqId','unique_id','document_info','user_type','user_name','user_email','user_city','user_purpose','signed_pdf','signed_at','created_at')->where('rfqId',$rfqId)->orderBy('id','desc')->get()->toarray();

        // dd($e_signatures);

        $b_checked = $cb_checked = $cb2_checked = $as_checked = "";
        $b_disabled = $cb_disabled = $cb2_disabled = $as_disabled = $submit_disabled =  "";

        // first stage/ no signature
        if(empty($e_signatures)){
            // die('empty');
            $b_checked = "checked";
            $cb_disabled = "disabled";
            $cb2_disabled = "disabled";
            $as_disabled = "disabled";
        } else {
            
            if(!empty($data->co_borrower_name) && !empty($data->co_borrower_name2)){
                
            } else{
                if(!empty($data->co_borrower_name) && empty($data->co_borrower_name2)){
                    
                }
                if(empty($data->co_borrower_name) && !empty($data->co_borrower_name2)){
                    
                }
                if(empty($data->co_borrower_name) && empty($data->co_borrower_name2)){
                    
                }
            }
            $check_borrower_done = DB::table('e_signatures')->where('rfqId',$rfqId)->where('user_type', 'borrower')->first();

            // dd($check_borrower_done);
        
            if(!empty($check_borrower_done)){
                if(empty($check_borrower_done->signed_pdf)){  
                    // die('Hi');                  
                    $b_checked = "checked";
                    $cb_disabled = "disabled";
                    $cb2_disabled = "disabled";
                    $as_disabled = "disabled";
                } else {
                    if(!empty($data->co_borrower_name) && !empty($data->co_borrower_name2)){
                        $cb_checked = "checked";
                        $b_disabled = "disabled";
                        $cb2_disabled = "disabled";
                        $as_disabled = "disabled";
                    } else {
                        if(!empty($data->co_borrower_name) && empty($data->co_borrower_name2)){
                            $cb_checked = "checked";
                            $b_disabled = "disabled";
                            $cb2_disabled = "disabled";
                            $as_disabled = "disabled";
                        }
                        if(empty($data->co_borrower_name) && !empty($data->co_borrower_name2)){
                            $cb_disabled = "disabled";
                            $b_disabled = "disabled";
                            $cb_checked = "checked";
                            $as_disabled = "disabled";
                        }
                         if(empty($data->co_borrower_name) && empty($data->co_borrower_name2)){
                            $cb_disabled = "disabled";
                            $b_disabled = "disabled";
                            $cb2_disabled = "disabled";
                            $as_checked = "checked";   
                        }
                    }
                    
                    
                }
            }
            $check_co_borrower_done = DB::table('e_signatures')->where('rfqId',$rfqId)->where('user_type', 'co_borrower')->first();
            
            if(!empty($check_co_borrower_done)){
                if(empty($check_co_borrower_done->signed_pdf)){
                    
                    $cb_checked = "checked";
                    $b_disabled = "disabled";
                    $cb2_disabled = "disabled";
                    $as_disabled = "disabled";
                } else {
                    $cb2_checked = "checked";
                    $b_disabled = "disabled";
                    $cb_disabled = "disabled";
                    $as_disabled = "disabled";
                }
            }
            $check_co_borrower2_done = DB::table('e_signatures')->where('rfqId',$rfqId)->where('user_type', 'co_borrower2')->first();
            
            if(!empty($check_co_borrower2_done)){
                if(empty($check_co_borrower2_done->signed_pdf)){                    
                    $cb2_checked = "checked";
                    $b_disabled = "disabled";
                    $cb_disabled = "disabled";
                    $as_disabled = "disabled";
                } else {
                    $as_checked = "checked";
                    $b_disabled = "disabled";
                    $cb_disabled = "disabled";
                    $cb2_disabled = "disabled";
                }
            }
            $check_authorised_signatory_done = DB::table('e_signatures')->where('rfqId',$rfqId)->where('user_type', 'authorised_signatory')->first();
            
            if(!empty($check_authorised_signatory_done)){
                if(empty($check_authorised_signatory_done->signed_pdf)){
                    $as_checked = "checked";
                    $b_disabled = "disabled";
                    $cb_disabled = "disabled";
                    $cb2_diabled = "disabled";
                } else {
                    $b_disabled = "disabled";
                    $cb_disabled = "disabled";
                    $cb2_diabled = "disabled";
                    $as_disabled = "disabled";
                    $submit_disabled = "disabled";
                }
            }
        }

        

        return view('admin.borrower.esign', compact('data', 'all_auth_sig', 'rfqId', 'e_signatures','b_checked','cb_checked','cb2_checked','as_checked','b_disabled','cb_disabled','cb2_disabled','as_disabled','submit_disabled'));
    }

    public function esignSubmit(Request $request) {
        // dd($request->all());
        $request->validate([
            'file' => 'required|max:14000',
            'documentInformation' => 'required',
            'rfqId' => 'required',

            'borrower_name' => 'required',
            'borrower_email' => 'required|email',
            'borrower_city' => 'required',
            'borrower_purpose' => 'required',

            'co_borrower_name' => 'nullable',
            'co_borrower_email' => 'nullable|email',
            'co_borrower_city' => 'nullable',
            'co_borrower_purpose' => 'nullable',

            'co_borrower_name2' => 'nullable',
            'co_borrower_email2' => 'nullable|email',
            'co_borrower_city2' => 'nullable',
            'co_borrower_purpose2' => 'nullable',

            'authorised_signatory_name' => 'nullable',
            'authorised_signatory_email' => 'nullable|email',
            'authorised_signatory_city' => 'nullable',
            'authorised_signatory_purpose' => 'nullable'
        ], [
            'file.*' => 'Upload e-signing pdf upto 14 MB'
        ]);

        // dd($request->all);

        // encode file to base 64
        $converted_doc = base64_encode(file_get_contents($request->file('file')));
        $document_info = $request->documentInformation;

        $borrower_name = $request->borrower_name;
        $borrower_email = $request->borrower_email;
        $borrower_city = $request->borrower_city;
        $borrower_purpose = $request->borrower_purpose;

        $co_borrower_name = $request->co_borrower_name ?? '';
        $co_borrower_email = $request->co_borrower_email ?? '';
        $co_borrower_city = $request->co_borrower_city ?? '';
        $co_borrower_purpose = $request->co_borrower_purpose ?? '';

        $co_borrower_name2 = $request->co_borrower_name2 ?? '';
        $co_borrower_email2 = $request->co_borrower_email2 ?? '';
        $co_borrower_city2 = $request->co_borrower_city2 ?? '';
        $co_borrower_purpose2 = $request->co_borrower_purpose2 ?? '';

        $authorised_signatory_name = $request->authorised_signatory_name;
        $authorised_signatory_email = $request->authorised_signatory_email;
        $authorised_signatory_city = $request->authorised_signatory_city;
        $authorised_signatory_purpose = $request->authorised_signatory_purpose;

        // Not calling ZOOP APIs - sending email first

        /** Borrower */
        if(!empty($request->is_borrower)){
            if (!empty($borrower_name)) {
                $esign1 = new ESignature();
                $esign1->rfqId = $request->rfqId;
                $esign1->unique_id = $request->unique_id;
                $esign1->encoded_pdf = $converted_doc;
                $esign1->document_info = $document_info;
                $esign1->user_type = "borrower";
                $esign1->user_name = $borrower_name;
                $esign1->user_email = $borrower_email;
                $esign1->user_city = $borrower_city;
                $esign1->user_purpose = $borrower_purpose;
                $esign1->save();
    
                // email data
                $data = [
                    'name' => $borrower_name,
                    'subject' => 'HUNDEE - E-signing for borrower',
                    'email' => $borrower_email,
                    'city' => $borrower_city,
                    'blade_file' => 'esign/index',
                    'route' => route('zoop.borrower.mail.click', $esign1->id)
                ];
                $resp = SendMail($data);
            }
        }
        
        /** Co-Borrower 1 */
        if(!empty($request->is_co_borrower)){
            if (!empty($co_borrower_name)) {
                $esign3 = new ESignature();
                $esign3->rfqId = $request->rfqId;
                $esign3->unique_id = $request->unique_id;
                $esign3->encoded_pdf = $converted_doc;
                $esign3->document_info = $document_info;
                $esign3->user_type = "co_borrower1";
                $esign3->user_name = $co_borrower_name;
                $esign3->user_email = $co_borrower_email;
                $esign3->user_city = $co_borrower_city;
                $esign3->user_purpose = $co_borrower_purpose;
                $esign3->save();
    
                // email data
                $data = [
                    'name' => $co_borrower_name,
                    'subject' => 'HUNDEE - E-signing for Co-borrower 1',
                    'email' => $co_borrower_email,
                    'city' => $co_borrower_city,
                    'blade_file' => 'esign/index',
                    'route' => route('zoop.coborrower1.mail.click', $esign3->id)
                ];
                $resp = SendMail($data);
            }
        }        

        /** Co-Borrower 2 */
        if(!empty($request->co_borrower_2)){
            if (!empty($co_borrower_name2)) {
                $esign4 = new ESignature();
                $esign4->rfqId = $request->rfqId;
                $esign4->unique_id = $request->unique_id;
                $esign4->encoded_pdf = $converted_doc;
                $esign4->document_info = $document_info;
                $esign4->user_type = "co_borrower2";
                $esign4->user_name = $co_borrower_name2;
                $esign4->user_email = $co_borrower_email2;
                $esign4->user_city = $co_borrower_city2;
                $esign4->user_purpose = $co_borrower_purpose2;
                $esign4->save();
    
                // email data
                $data = [
                    'name' => $co_borrower_name2,
                    'subject' => 'HUNDEE - E-signing for Co-borrower 2',
                    'email' => $co_borrower_email2,
                    'city' => $co_borrower_city2,
                    'blade_file' => 'esign/index',
                    'route' => route('zoop.coborrower2.mail.click', $esign4->id)
                ];
                $resp = SendMail($data);
            }
        }        

        /** Authorised Signatory */
        if(!empty($request->is_authorised_signatory)){
            if (!empty($authorised_signatory_name)) {
                $esign2 = new ESignature();
                $esign2->rfqId = $request->rfqId;
                $esign2->unique_id = $request->unique_id;
                $esign2->encoded_pdf = $converted_doc;
                $esign2->document_info = $document_info;
                $esign2->user_type = "authorised_signatory";
                $esign2->user_name = $authorised_signatory_name;
                $esign2->user_email = $authorised_signatory_email;
                $esign2->user_city = $authorised_signatory_city;
                $esign2->user_purpose = $authorised_signatory_purpose;
                $esign2->save();
    
                // email data
                $data = [
                    'name' => $authorised_signatory_name,
                    'subject' => 'HUNDEE - E-signing for authorised signatory',
                    'email' => $authorised_signatory_email,
                    'city' => $authorised_signatory_city,
                    'blade_file' => 'esign/index',
                    'route' => route('zoop.authorised.signatory.mail.click', $esign2->id)
                ];
                $resp = SendMail($data);
            }
        }

        // $this->borrowerSign($converted_doc, $document_info, $borrower_name, $borrower_email, $borrower_city, $borrower_purpose);

        // if ($co_borrower_name) {
        //     $this->coBorrowerSign($converted_doc, $document_info, $co_borrower_name, $co_borrower_email, $co_borrower_city, $co_borrower_purpose);
        // }
        // $this->authorisedSignatorySign($converted_doc, $document_info, $authorised_signatory_name, $authorised_signatory_email, $authorised_signatory_city, $authorised_signatory_purpose);

        return redirect()->back()->with('success', 'Mail sent successfully.');
    }

    // public function borrowerSign($doc, $info, $name, $email, $city, $purpose) {
    public function borrowerSign(Request $request, $id) {
        // dd($request->all());
        $esigning = ESignature::findOrFail($id);
        $doc = $esigning->encoded_pdf;
        $info = $esigning->document_info;
        $name = $esigning->user_name;
        $email = $esigning->user_email;
        $city = $esigning->user_city;
        $purpose = $esigning->user_purpose;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        	CURLOPT_URL => $this->aadhar_init_url,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => '',
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 0,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => 'POST',
        	CURLOPT_POSTFIELDS =>'{
                "document": {
                    "data": "'.$doc.'",
                    "info": "'.$info.'",
                    "sign": [
                        {
                            "page_num": 5,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 6,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 7,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 7,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 8,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 9,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 10,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 11,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 12,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 13,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 14,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 15,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 16,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 17,
                            "x_coord": 30,
                            "y_coord": 730
                        },
                        {
                            "page_num": 18,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 19,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 20,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 21,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 22,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 23,
                            "x_coord": 230,
                            "y_coord": 60
                        },
                        {
                            "page_num": 24,
                            "x_coord": 435,
                            "y_coord": 540
                        },
                        {
                            "page_num": 25,
                            "x_coord": 380,
                            "y_coord": 60
                        },
                        {
                            "page_num": 26,
                            "x_coord": 355,
                            "y_coord": 46
                        },
                        {
                            "page_num": 27,
                            "x_coord": 355,
                            "y_coord": 165
                        },
                        {
                            "page_num": 30,
                            "x_coord": 375,
                            "y_coord": 540
                        },
                        {
                            "page_num": 31,
                            "x_coord": 355,
                            "y_coord": 60
                        },
                        {
                            "page_num": 32,
                            "x_coord": 355,
                            "y_coord": 352
                        },
                        {
                            "page_num": 33,
                            "x_coord": 400,
                            "y_coord": 298
                        }
                    ]
                },
                "signer_name": "'.$name.'",
                "signer_email": "'.$email.'",
                "signer_city": "'.$city.'",
                "purpose": "'.$purpose.'",
                "response_url": "'.$this->response_url.'",
                "redirect_url": "'.$this->redirect_url.'",
                "white_label": "Y"
            }',
            CURLOPT_HTTPHEADER => array(
        		'app-id: '.$this->app_id,
        		'api-key: '.$this->api_key,
        		'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);

        // dd($response->success);

        if($response->success === true) {
            $id = $response->request_id;
            return view('zoop.borrower', compact('id'));
            // $data = [
            //     'name' => $name,
            //     'subject' => 'E-signing for borrower',
            //     'email' => $email,
            //     'city' => $city,
            //     'blade_file' => 'esign/borrower',
            //     'request_id' => $response->request_id,
            // ];

            // $resp = SendMail($data);
        } else {
            // dd($response);
            return view('zoop.failure', compact('response'));
        }
    }

    // public function coBorrowerSign($doc, $info, $name, $email, $city, $purpose) {
    public function coBorrowerSign(Request $request, $id) {
        // dd($request->all());
        $esigning = ESignature::findOrFail($id);
        $doc = $esigning->encoded_pdf;
        $info = $esigning->document_info;
        $name = $esigning->user_name;
        $email = $esigning->user_email;
        $city = $esigning->user_city;
        $purpose = $esigning->user_purpose;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        	CURLOPT_URL => $this->aadhar_init_url,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => '',
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 0,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => 'POST',
        	CURLOPT_POSTFIELDS =>'{
                "document": {
                    "data": "'.$doc.'",
                    "info": "'.$info.'",
                    "sign": [
                        {
                            "page_num": 5,
                            "x_coord": 30,
                            "y_coord": 55
                        },
                        {
                            "page_num": 6,
                            "x_coord": 30,
                            "y_coord": 65
                        },
                        {
                            "page_num": 7,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 8,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 9,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 10,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 11,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 12,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 13,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 14,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 15,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 16,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 17,
                            "x_coord": 30,
                            "y_coord": 688
                        },
                        {
                            "page_num": 18,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 19,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 20,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 21,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 22,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 23,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 24,
                            "x_coord": 425,
                            "y_coord": 467
                        },
                        {
                            "page_num": 25,
                            "x_coord": 70,
                            "y_coord": 60
                        },
                        {
                            "page_num": 26,
                            "x_coord": 90,
                            "y_coord": 45
                        },
                        {
                            "page_num": 27,
                            "x_coord": 90,
                            "y_coord": 170
                        },
                        {
                            "page_num": 30,
                            "x_coord": 375,
                            "y_coord": 540
                        },
                        {
                            "page_num": 31,
                            "x_coord": 355,
                            "y_coord": 60
                        },
                        {
                            "page_num": 32,
                            "x_coord": 88,
                            "y_coord": 370
                        },
                        {
                            "page_num": 33,
                            "x_coord": 220,
                            "y_coord": 290
                        }
                    ]
                },
                "signer_name": "'.$name.'",
                "signer_email": "'.$email.'",
                "signer_city": "'.$city.'",
                "purpose": "'.$purpose.'",
                "response_url": "'.$this->response_url.'",
                "redirect_url": "'.$this->redirect_url.'",
                "white_label": "Y"
            }',
            CURLOPT_HTTPHEADER => array(
        		'app-id: '.$this->app_id,
        		'api-key: '.$this->api_key,
        		'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        
        if($response->success === true) {
            $id = $response->request_id;
            return view('zoop.borrower', compact('id'));
            // $data = [
            //     'name' => $name,
            //     'subject' => 'E-signing for co-borrower',
            //     'email' => $email,
            //     'city' => $city,
            //     'blade_file' => 'esign/borrower',
            //     'request_id' => $response->request_id,
            // ];
            // SendMail($data);
            // return view('zoop.borrower', compact('response'));
        } else {
            return view('zoop.failure', compact('response'));
        }
    }

    public function coBorrowerSign2(Request $request, $id) {
        // dd($request->all());
        $esigning = ESignature::findOrFail($id);
        $doc = $esigning->encoded_pdf;
        $info = $esigning->document_info;
        $name = $esigning->user_name;
        $email = $esigning->user_email;
        $city = $esigning->user_city;
        $purpose = $esigning->user_purpose;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        	CURLOPT_URL => $this->aadhar_init_url,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => '',
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 0,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => 'POST',
        	CURLOPT_POSTFIELDS =>'{
                "document": {
                    "data": "'.$doc.'",
                    "info": "'.$info.'",
                    "sign": [
                        {
                            "page_num": 5,
                            "x_coord": 30,
                            "y_coord": 55
                        },
                        {
                            "page_num": 6,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 7,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 8,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 9,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 10,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 11,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 12,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 13,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 14,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 15,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 16,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 17,
                            "x_coord": 30,
                            "y_coord": 688
                        },
                        {
                            "page_num": 18,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 19,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 20,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 21,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 22,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 23,
                            "x_coord": 30,
                            "y_coord": 60
                        },
                        {
                            "page_num": 24,
                            "x_coord": 425,
                            "y_coord": 467
                        },
                        {
                            "page_num": 25,
                            "x_coord": 70,
                            "y_coord": 60
                        },
                        {
                            "page_num": 26,
                            "x_coord": 90,
                            "y_coord": 45
                        },
                        {
                            "page_num": 27,
                            "x_coord": 90,
                            "y_coord": 170
                        },
                        {
                            "page_num": 30,
                            "x_coord": 375,
                            "y_coord": 540
                        },
                        {
                            "page_num": 31,
                            "x_coord": 355,
                            "y_coord": 60
                        },
                        {
                            "page_num": 32,
                            "x_coord": 88,
                            "y_coord": 370
                        },
                        {
                            "page_num": 33,
                            "x_coord": 220,
                            "y_coord": 290
                        }
                    ]
                },
                "signer_name": "'.$name.'",
                "signer_email": "'.$email.'",
                "signer_city": "'.$city.'",
                "purpose": "'.$purpose.'",
                "response_url": "'.$this->response_url.'",
                "redirect_url": "'.$this->redirect_url.'",
                "white_label": "Y"
            }',
            CURLOPT_HTTPHEADER => array(
        		'app-id: '.$this->app_id,
        		'api-key: '.$this->api_key,
        		'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        
        if($response->success === true) {
            $id = $response->request_id;
            return view('zoop.borrower', compact('id'));
            // $data = [
            //     'name' => $name,
            //     'subject' => 'E-signing for co-borrower',
            //     'email' => $email,
            //     'city' => $city,
            //     'blade_file' => 'esign/borrower',
            //     'request_id' => $response->request_id,
            // ];
            // SendMail($data);
            // return view('zoop.borrower', compact('response'));
        } else {
            return view('zoop.failure', compact('response'));
        }
    }

    // public function authorisedSignatorySign($doc, $info, $name, $email, $city, $purpose) {
    public function authorisedSignatorySign(Request $request, $id) {
        // dd($request->all());
        $esigning = ESignature::findOrFail($id);
        $doc = $esigning->encoded_pdf;
        $info = $esigning->document_info;
        $name = $esigning->user_name;
        $email = $esigning->user_email;
        $city = $esigning->user_city;
        $purpose = $esigning->user_purpose;
        $curl = curl_init();

        curl_setopt_array($curl, array(
        	CURLOPT_URL => $this->aadhar_init_url,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => '',
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 0,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => 'POST',
        	CURLOPT_POSTFIELDS =>'{
                "document": {
                    "data": "'.$doc.'",
                    "info": "'.$info.'",
                    "sign": [
                        {
                            "page_num": 5,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 6,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 7,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 8,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 9,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 10,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 11,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 12,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 13,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 14,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 15,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 16,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 17,
                            "x_coord": 160,
                            "y_coord": 506
                        },
                        {
                            "page_num": 18,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 19,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 20,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 21,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 22,
                            "x_coord": 420,
                            "y_coord": 60
                        },
                        {
                            "page_num": 23,
                            "x_coord": 420,
                            "y_coord": 60
                        }
                    ]
                },
                "signer_name": "'.$name.'",
                "signer_email": "'.$email.'",
                "signer_city": "'.$city.'",
                "purpose": "'.$purpose.'",
                "response_url": "'.$this->response_url.'",
                "redirect_url": "'.$this->redirect_url.'",
                "white_label": "Y"
            }',
            CURLOPT_HTTPHEADER => array(
        		'app-id: '.$this->app_id,
        		'api-key: '.$this->api_key,
        		'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        
        // dd($response);

        if($response->success === true) {
            $id = $response->request_id;
            return view('zoop.borrower', compact('id'));
            // $data = [
            //     'name' => $name,
            //     'subject' => 'E-signing for authorised signatory',
            //     'email' => $email,
            //     'city' => $city,
            //     'blade_file' => 'esign/borrower',
            //     'request_id' => $response->request_id,
            // ];
            // SendMail($data);
            // return view('zoop.borrower', compact('response'));
        } else {
            return view('zoop.failure', compact('response'));
        }
    }

    public function eSigningAll(Request $request, $id) {
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
