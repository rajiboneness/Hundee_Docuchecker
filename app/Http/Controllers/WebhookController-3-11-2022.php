<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function __construct(Request $request)
    {
        # code...
    }

    public function esign(Request $request)
    {
        # write form body to a file...
        $json_body = json_encode($request->all());
        $url = public_path("/esign/data.txt");

        

        $id = DB::table('zoop_webhook_response')->insertGetId([
            'response' => $json_body
        ]);

        $data = DB::table('zoop_webhook_response')->find($id);
        $response = $data->response;
        $params = json_decode($response);

        // # get params

        $request_id = $params->request_id;
        $success = $params->success;
        $issued_by = $params->result->document->issued_by;
        $signed_at = $params->result->document->signed_at;
        $signed_url = $params->result->document->signed_url;
        $signer_name = $params->result->signer->fetched_name;
        $signer_city = $params->result->signer->city;
        $signer_postal_code = $params->result->signer->postal_code;
        $signer_email = $params->result->signer->email;

        DB::table('zoop_webhook_response')->where('id',$id)->update([
            'request_id' => $request_id,
            'issued_by' => $issued_by,
            'signed_at' => $signed_at,
            'signed_url' => $signed_url,
            'signer_name' => $signer_name,
            'signer_city' => $signer_city,
            'signer_postal_code' => $signer_postal_code,
            'signer_email' => $signer_email
        ]);

        $check_e_signature = DB::table('e_signatures')->where('user_email', $signer_email)->where('signed_pdf', '=', null)->first();
        if(!empty($check_e_signature)){
            DB::table('e_signatures')->where('id',$check_e_signature->id)->update([
                'signed_pdf' => $signed_url,
                'signed_at' => $signed_at,
                'signer_name' => $signer_name,
                'signer_city' => $signer_city,
                'signer_postal_code' => $signer_postal_code,
                'signer_email' => $signer_email
            ]);
        }

        $file = fopen($url,"w");
        fwrite($file, $json_body);
        fclose($file);

        

    }
}