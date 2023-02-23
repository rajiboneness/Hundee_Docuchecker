<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\AgreementData;
use App\Models\User;
use App\Models\ESignature;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function __construct() {
        $this->aadhar_init_url = "https://test.zoop.one/contract/esign/v4/aadhaar/init";
        $this->app_id = "62a1b8581c552a001fb9113e";
        $this->api_key = "HDY9YMP-8V7MY7P-NJSYZBH-6FSPT46";
        $this->response_url = url('/').'/zoop/response';
        $this->redirect_url = url('/').'/zoop/redirect';

        
    }

    public function check(Request $request)
    {
        // echo env('MAIL_FROM_ADDRESS') ;
        $data = DB::table('zoop_webhook_response')->find(1);
        $response = $data->response;
        $params = json_decode($response);
        echo '<pre>'; echo "request_id:- ".$params->request_id;
        echo '<pre>'; echo "signed_url:- ".$params->result->document->signed_url;
        echo '<pre>'; echo "postal_code:- ".$params->result->signer->postal_code;

        echo '<pre>'; print_r($params);

    }

    public function index(Request $request)
    {
        # code...
        // die('Hi');
        // $url =  url('/callback/test.txt');
        $json_body = json_encode($request->all());
        // $json_body = "Hello World. Testing!";
        $url = public_path("/esign/data.txt");
        // echo $url; die;
        $file = fopen($url,"w");
        fwrite($file, $json_body);
        fclose($file);
    }

}