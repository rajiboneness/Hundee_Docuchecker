<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Borrower;
use App\Models\Agreement;
use App\Models\AgreementRfq;
use App\Models\BorrowerAgreement;

class AgreementController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function agreementList() {
        $agreement = Agreement::get();
        $data = [];
        foreach($agreement as $agreementKey => $agreementVal) {
            $data[] = [
                'agreement_id' => $agreementVal->id,
                'name' => $agreementVal->name,
            ];
        }
        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function agreementDownload(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'application_id' => 'required'
        ]);

        if (!$validate->fails()) {
            $agreementData = BorrowerAgreement::where('application_id', $request->application_id)->first();

            if ($agreementData) {
                $borrowerAgreementDataExists = AgreementRfq::where('borrower_id', $agreementData->borrower_id)->where('agreement_id', $agreementData->agreement_id)->get();

                if (count($borrowerAgreementDataExists) > 0) {
                    return response()->json([
                        'status' => 200, 
                        // 'data' => $borrowerAgreementDataExists->id, 
                        // 'borrowerAgreementDataExists' => count($borrowerAgreementDataExists)
                        // 'Agreement download url' => url('/').'/user/borrower/'.$agreementData->borrower_id.'/agreement/'.$agreementData->agreement_id.'/pdf/view'
                        'Agreement download url' => url('/').'/user/borrower/'.$agreementData->borrower_id.'/agreement/'.$agreementData->agreement_id.'/pdf/'.$agreementData->id.'/view/web'
                    ], 200);
                } else {
                    return response()->json(['status' => 400, 'message' => 'No document found'], 400);
                }
            } else {
                return response()->json(['status' => 400, 'message' => 'No agreement found for this application id'], 400);
            }

        } else {
            return response()->json(['status' => 400, 'message' => $validate->errors()->first()], 400);
        }

        /* $validate = Validator::make($request->all(), [
            'auth_user_id' => 'required|integer|min:1',
            'auth_user_emp_id' => 'required|string|min:1|exists:users,emp_id',
            'application_id'=> 'required',
            // 'borrower_id'=> 'required|integer|min:1',
            // 'agreement_id'=> 'required|integer|min:1',
        ]);

        if (!$validate->fails()) {
            // $borrowerData = Borrower::where('application_id', $request->application_id)->first();
            $agreementData = BorrowerAgreement::where('application_id', $request->application_id)->first();

            $borrowerAgreementDataExists = AgreementRfq::where('application_id', $request->application_id)->count();
            // $borrowerAgreementDataExists = AgreementRfq::where('borrower_id', $request->borrower_id)->where('agreement_id', $request->agreement_id)->count();

            if ($borrowerAgreementDataExists > 0) {
                // activity log
                $logData = [
                    'type' => 'agreement_download_request',
                    'title' => 'Agreement download request',
                    'desc' => 'Agreement download request generated for Application id: '.$request->application_id.' by EMP ID '.$request->auth_user_emp_id
                    // 'desc' => 'Agreement download request generated for Agreement id: '.$request->agreement_id.' and Borrower id: '.$request->borrower_id.' by EMP ID '.$request->auth_user_emp_id
                ];
                activityLog($logData);

                return response()->json(['status' => 200, 'Agreement download url' => url('/').'/user/borrower/'.$agreementData->borrower_id.'/agreement/'.$agreementData->agreement_id.'/pdf/view'], 200);
            } else {
                return response()->json(['status' => 400, 'message' => 'No document found'], 400);
            }
        } else {
            return response()->json(['status' => 400, 'message' => $validate->errors()->first()], 400);
        } */
    }
}
