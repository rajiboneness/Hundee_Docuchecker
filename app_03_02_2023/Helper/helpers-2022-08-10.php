<?php

use App\Models\Activity;
use App\Models\AgreementData;
use App\Models\AgreementRfq;
use App\Models\Borrower;
use App\Models\BorrowerAgreement;
use App\Models\Estamp;
use App\Models\Field;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

// generate alpha numeric for usage
function generateUniqueAlphaNumeric($length = 8)
{
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $number = random_int(0, 36);
        $character = base_convert($number, 10, 36);
        $random_string .= $character;
    }
    return $random_string;
}

// limit words in view, no need to show full text
function words($string, $words = 100)
{
    return \Illuminate\Support\Str::limit($string, $words);
}

// random number generate
function randomGenerator()
{
    return uniqid() . '' . date('ymdhis') . '' . uniqid();
}

// empty string check
function emptyCheck($string, $date = false)
{
    if ($date) {
        return !empty($string) ? date('Y-m-d', strtotime($string)) : '0000-00-00';
    }
    return !empty($string) ? $string : '';
}

// file upload from controller function
function fileUpload($file, $folder = 'image')
{
    $random = randomGenerator();
    $file->move('upload/' . $folder . '/', $random . '.' . $file->getClientOriginalExtension());
    $fileurl = 'upload/' . $folder . '/' . $random . '.' . $file->getClientOriginalExtension();
    return $fileurl;
}

function checkStringFileAray($data)
{
    if ($data != '') {
        if (is_array($data)) {
            return ($data ? implode(',', $data) : '');
        } elseif (is_string($data)) {
            return $data;
        } else {
            return fileUpload($data, 'agreementUploads');
        }
    }

    return '';
}

// form elements check & show values
/*** fields blade & admin.borrower.fields blade ***/
// parameters fields id, field name - placeholder, field type - text/email, value - select/radio, 
function form3lements($field_id, $name, $type, $value=null, $key_name, $required=null, $borrowerId=null, $form_type=null)
{
    $respValue = '';
    $disabledField = '';
    $optionalFieldsData = ''; // optional for officially valid documents
    $extraClass = ''; // extra class name for filtering
    if (!empty($borrowerId)) {
        // in case of adding agreement data, auto-fill borrower details starts
        if (isset($form_type) == 'create') {
            // fetching borrower details
            $borrower = \App\Models\Borrower::findOrFail($borrowerId);
            switch($key_name){
                // borrower id
                case 'customerid' :
                    $disabledField = '';
                    $respValue = $borrower->CUSTOMER_ID;
                    break;
                // borrower name prefix
                case 'prefixoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->name_prefix;
                    break;
                // borrower full name
                case 'nameoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->full_name;
                    break;
                // Officially Valid Documents of the Borrower
                case 'officiallyvaliddocumentsoftheborrower' :
                    // 	Officially Valid Documents entry fields

                    // 1 borrower - aadhar card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataAadharNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'aadharcardnumberoftheborrower')->first();
                            if ($agreementDataAadharNo) {
                                // if data is filled & watching only
                                $respValueAadharCardNo = $agreementDataAadharNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueAadharCardNo = $borrower->Aadhar_Number;
                    }

                    $optionalFieldsInsideData = '<input type="text" placeholder="Aadhar card number of the Borrower" class="form-control form-control-sm text-uppercase" name="field_name[aadharcardnumberoftheborrower]" value="'.$respValueAadharCardNo.'" style="display:none;"><input type="hidden" value="96" name="field_id[96]">';

                    // 2 borrower - voter card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataVoterCardNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'votercardnumberoftheborrower')->first();
                            if ($agreementDataVoterCardNo) {
                                // if data is filled & watching only
                                $respValueVoterCardNo = $agreementDataVoterCardNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueVoterCardNo = $borrower->Voter_ID;
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Voter card number of the Borrower" class="form-control form-control-sm text-uppercase" name="field_name[votercardnumberoftheborrower]" value="'.$respValueVoterCardNo.'" style="display:none;"><input type="hidden" value="97" name="field_id[97]">';

                    // 3 borrower - bank acc no, name, ifsc
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataBankAccNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankaccountnumberoftheborrower')->first();
                            $agreementDataBankName = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'banknameoftheborrower')->first();
                            $agreementDataBankIfsc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankifscoftheborrower')->first();
                            if ($agreementDataBankAccNo) {
                                // if data is filled & watching only
                                $respValueBankAccNo = $agreementDataBankAccNo->field_value;
                            }
                            if ($agreementDataBankName) {
                                // if data is filled & watching only
                                $respValueBankName = $agreementDataBankName->field_value;
                            }
                            if ($agreementDataBankIfsc) {
                                // if data is filled & watching only
                                $respValueBankIfsc = $agreementDataBankIfsc->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueBankAccNo = '';
                        $respValueBankName = '';
                        $respValueBankIfsc = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row"> <div class="col-4"> <input type="text" placeholder="Bank account number of the Borrower" class="form-control form-control-sm" name="field_name[bankaccountnumberoftheborrower]" value="'.$respValueBankAccNo.'" style="display:none;"><input type="hidden" value="98" name="field_id[98]"> </div><div class="col-4"> <input type="text" placeholder="Bank name of the Borrower" class="form-control form-control-sm" name="field_name[banknameoftheborrower]" value="'.$respValueBankName.'" style="display:none;"><input type="hidden" value="99" name="field_id[99]"> </div><div class="col-4"> <input type="text" placeholder="Bank IFSC of the Borrower" class="form-control form-control-sm text-uppercase" name="field_name[bankifscoftheborrower]" value="'.$respValueBankIfsc.'" style="display:none;"><input type="hidden" value="100" name="field_id[100]"> </div> </div>';

                    // 4 borrower - driving license, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataLicenseNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicensenumberoftheborrower')->first();
                            $agreementDataLicenseIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseissuedateoftheborrower')->first();
                            $agreementDataLicenseExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseexpirydateoftheborrower')->first();
                            if ($agreementDataLicenseNo) {
                                // if data is filled & watching only
                                $respValueLicenseNo = $agreementDataLicenseNo->field_value;
                            }
                            if ($agreementDataLicenseIssue) {
                                // if data is filled & watching only
                                $respValueLicenseIssue = $agreementDataLicenseIssue->field_value;
                            }
                            if ($agreementDataLicenseExpiry) {
                                // if data is filled & watching only
                                $respValueLicenseExpiry = $agreementDataLicenseExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueLicenseNo = $borrower->DRIVING_LINC;
                        $respValueLicenseIssue = '';
                        $respValueLicenseExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="borrowerDrivingLicenseHolder" style="display:none"> <div class="col-4"> <input type="text" placeholder="Driving license number of the Borrower" class="form-control form-control-sm text-uppercase" name="field_name[drivinglicensenumberoftheborrower]" value="'.$respValueLicenseNo.'"><input type="hidden" value="101" name="field_id[101]"> <p class="small text-muted my-1">Driving license number</p> </div><div class="col-4"> <input type="date" placeholder="Driving license issue date of the Borrower" class="form-control form-control-sm" name="field_name[drivinglicenseissuedateoftheborrower]" value="'.$respValueLicenseIssue.'"><input type="hidden" value="102" name="field_id[102]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Driving license expiry date of the Borrower" class="form-control form-control-sm" name="field_name[drivinglicenseexpirydateoftheborrower]" value="'.$respValueLicenseExpiry.'"><input type="hidden" value="103" name="field_id[103]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    // 5 borrower - electricity bill
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataElecBill = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'electricitybillnumberoftheborrower')->first();
                            if ($agreementDataElecBill) {
                                // if data is filled & watching only
                                $respValueElecBill = $agreementDataElecBill->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueElecBill = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Electricity bill number of the Borrower" class="form-control form-control-sm text-uppercase" name="field_name[electricitybillnumberoftheborrower]" value="'.$respValueElecBill.'" style="display:none;"><input type="hidden" value="104" name="field_id[104]">';

                    // 6 borrower - passport, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataPassportNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportnumberoftheborrower')->first();
                            $agreementDataPassportIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportissuedateoftheborrower')->first();
                            $agreementDataPassportExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportexpirydateoftheborrower')->first();
                            if ($agreementDataPassportNo) {
                                // if data is filled & watching only
                                $respValuePassportNo = $agreementDataPassportNo->field_value;
                            }
                            if ($agreementDataPassportIssue) {
                                // if data is filled & watching only
                                $respValuePassportIssue = $agreementDataPassportIssue->field_value;
                            }
                            if ($agreementDataPassportExpiry) {
                                // if data is filled & watching only
                                $respValuePassportExpiry = $agreementDataPassportExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValuePassportNo = $borrower->PASSPORT_NO;
                        $respValuePassportIssue = '';
                        $respValuePassportExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="borrowerPassportHolder" style="display: none;"> <div class="col-4"> <input type="text" placeholder="Passport number of the Borrower" class="form-control form-control-sm text-uppercase" name="field_name[passportnumberoftheborrower]" value="'.$respValuePassportNo.'"><input type="hidden" value="105" name="field_id[105]"> <p class="small text-muted my-1">Passport number</p> </div><div class="col-4"> <input type="date" placeholder="Passport issue date of the Borrower" class="form-control form-control-sm" name="field_name[passportissuedateoftheborrower]" value="'.$respValuePassportIssue.'"><input type="hidden" value="106" name="field_id[106]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Passport expiry date of the Borrower" class="form-control form-control-sm" name="field_name[passportexpirydateoftheborrower]" value="'.$respValuePassportExpiry.'"><input type="hidden" value="107" name="field_id[107]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100 mt-3">'.$optionalFieldsInsideData.'</div>';

                break;


                // Officially Valid Documents of the Co-Borrower 1
                case 'officiallyvaliddocumentsofthecoborrower' :
                    // 	Officially Valid Documents entry fields

                    // 1 coborrower - aadhar card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataAadharNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'aadharcardnumberofthecoborrower')->first();
                            if ($agreementDataAadharNo) {
                                // if data is filled & watching only
                                $respValueAadharCardNo = $agreementDataAadharNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueAadharCardNo = '';
                    }

                    $optionalFieldsInsideData = '<input type="text" placeholder="Aadhar card number of the Co-Borrower" class="form-control form-control-sm text-uppercase" name="field_name[aadharcardnumberofthecoborrower]" value="'.$respValueAadharCardNo.'" style="display:none;"><input type="hidden" value="108" name="field_id[108]">';

                    // 2 coborrower - voter card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataVoterCardNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'votercardnumberofthecoborrower')->first();
                            if ($agreementDataVoterCardNo) {
                                // if data is filled & watching only
                                $respValueVoterCardNo = $agreementDataVoterCardNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueVoterCardNo = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Voter card number of the Co-Borrower" class="form-control form-control-sm text-uppercase" name="field_name[votercardnumberofthecoborrower]" value="'.$respValueVoterCardNo.'" style="display:none;"><input type="hidden" value="109" name="field_id[109]">';

                    // 3 coborrower - bank acc no, name, ifsc
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataBankAccNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankaccountnumberofthecoborrower')->first();
                            $agreementDataBankName = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'banknameofthecoborrower')->first();
                            $agreementDataBankIfsc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankifscofthecoborrower')->first();
                            if ($agreementDataBankAccNo) {
                                // if data is filled & watching only
                                $respValueBankAccNo = $agreementDataBankAccNo->field_value;
                            }
                            if ($agreementDataBankName) {
                                // if data is filled & watching only
                                $respValueBankName = $agreementDataBankName->field_value;
                            }
                            if ($agreementDataBankIfsc) {
                                // if data is filled & watching only
                                $respValueBankIfsc = $agreementDataBankIfsc->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueBankAccNo = '';
                        $respValueBankName = '';
                        $respValueBankIfsc = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row"> <div class="col-4"> <input type="text" placeholder="Bank account number of the Co-Borrower" class="form-control form-control-sm" name="field_name[bankaccountnumberofthecoborrower]" value="'.$respValueBankAccNo.'" style="display:none;"><input type="hidden" value="110" name="field_id[110]"> </div><div class="col-4"> <input type="text" placeholder="Bank name of the Co-Borrower" class="form-control form-control-sm" name="field_name[banknameofthecoborrower]" value="'.$respValueBankName.'" style="display:none;"><input type="hidden" value="111" name="field_id[111]"> </div><div class="col-4"> <input type="text" placeholder="Bank IFSC of the Co-Borrower" class="form-control form-control-sm text-uppercase" name="field_name[bankifscofthecoborrower]" value="'.$respValueBankIfsc.'" style="display:none;"><input type="hidden" value="112" name="field_id[112]"> </div> </div>';

                    // 4 coborrower - driving license, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataLicenseNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicensenumberofthecoborrower')->first();
                            $agreementDataLicenseIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseissuedateofthecoborrower')->first();
                            $agreementDataLicenseExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseexpirydateofthecoborrower')->first();
                            if ($agreementDataLicenseNo) {
                                // if data is filled & watching only
                                $respValueLicenseNo = $agreementDataLicenseNo->field_value;
                            }
                            if ($agreementDataLicenseIssue) {
                                // if data is filled & watching only
                                $respValueLicenseIssue = $agreementDataLicenseIssue->field_value;
                            }
                            if ($agreementDataLicenseExpiry) {
                                // if data is filled & watching only
                                $respValueLicenseExpiry = $agreementDataLicenseExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueLicenseNo = '';
                        $respValueLicenseIssue = '';
                        $respValueLicenseExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="coBorrower1DrivingLicenseHolder" style="display: none;"> <div class="col-4"> <input type="text" placeholder="Driving license number of the Co-Borrower" class="form-control form-control-sm text-uppercase" name="field_name[drivinglicensenumberofthecoborrower]" value="'.$respValueLicenseNo.'"><input type="hidden" value="113" name="field_id[113]"> <p class="small text-muted my-1">Driving license number</p> </div><div class="col-4"> <input type="date" placeholder="Driving license issue date of the Co-Borrower" class="form-control form-control-sm" name="field_name[drivinglicenseissuedateofthecoborrower]" value="'.$respValueLicenseIssue.'"><input type="hidden" value="114" name="field_id[114]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Driving license expiry date of the Co-Borrower" class="form-control form-control-sm" name="field_name[drivinglicenseexpirydateofthecoborrower]" value="'.$respValueLicenseExpiry.'"><input type="hidden" value="115" name="field_id[115]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    // 5 coborrower - electricity bill
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataElecBill = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'electricitybillnumberofthecoborrower')->first();
                            if ($agreementDataElecBill) {
                                // if data is filled & watching only
                                $respValueElecBill = $agreementDataElecBill->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueElecBill = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Electricity bill number of the Co-Borrower" class="form-control form-control-sm text-uppercase" name="field_name[electricitybillnumberofthecoborrower]" value="'.$respValueElecBill.'" style="display:none;"><input type="hidden" value="116" name="field_id[116]">';

                    // 6 coborrower - passport, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataPassportNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportnumberofthecoborrower')->first();
                            $agreementDataPassportIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportissuedateofthecoborrower')->first();
                            $agreementDataPassportExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportexpirydateofthecoborrower')->first();
                            if ($agreementDataPassportNo) {
                                // if data is filled & watching only
                                $respValuePassportNo = $agreementDataPassportNo->field_value;
                            }
                            if ($agreementDataPassportIssue) {
                                // if data is filled & watching only
                                $respValuePassportIssue = $agreementDataPassportIssue->field_value;
                            }
                            if ($agreementDataPassportExpiry) {
                                // if data is filled & watching only
                                $respValuePassportExpiry = $agreementDataPassportExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValuePassportNo = '';
                        $respValuePassportIssue = '';
                        $respValuePassportExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="coBorrower1PassportHolder" style="display: none;"> <div class="col-4"> <input type="text" placeholder="Passport number of the Co-Borrower" class="form-control form-control-sm text-uppercase" name="field_name[passportnumberofthecoborrower]" value="'.$respValuePassportNo.'"><input type="hidden" value="117" name="field_id[117]"> <p class="small text-muted my-1">Passport number</p> </div><div class="col-4"> <input type="date" placeholder="Passport issue date of the Co-Borrower" class="form-control form-control-sm" name="field_name[passportissuedateofthecoborrower]" value="'.$respValuePassportIssue.'"><input type="hidden" value="118" name="field_id[118]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Passport expiry date of the Co-Borrower" class="form-control form-control-sm" name="field_name[passportexpirydateofthecoborrower]" value="'.$respValuePassportExpiry.'"><input type="hidden" value="119" name="field_id[119]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100 mt-3">'.$optionalFieldsInsideData.'</div>';

                break;


                // Officially Valid Documents of the Co-Borrower 2
                case 'officiallyvaliddocumentsofthecoborrower2' :
                    // 	Officially Valid Documents entry fields

                    // 1 coborrower - aadhar card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataAadharNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'aadharcardnumberofthecoborrower2')->first();
                            if ($agreementDataAadharNo) {
                                // if data is filled & watching only
                                $respValueAadharCardNo = $agreementDataAadharNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueAadharCardNo = '';
                    }

                    $optionalFieldsInsideData = '<input type="text" placeholder="Aadhar card number of the Co-Borrower 2" class="form-control form-control-sm text-uppercase" name="field_name[aadharcardnumberofthecoborrower2]" value="'.$respValueAadharCardNo.'" style="display:none;"><input type="hidden" value="108" name="field_id[108]">';

                    // 2 coborrower - voter card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataVoterCardNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'votercardnumberofthecoborrower2')->first();
                            if ($agreementDataVoterCardNo) {
                                // if data is filled & watching only
                                $respValueVoterCardNo = $agreementDataVoterCardNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueVoterCardNo = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Voter card number of the Co-Borrower 2" class="form-control form-control-sm text-uppercase" name="field_name[votercardnumberofthecoborrower2]" value="'.$respValueVoterCardNo.'" style="display:none;"><input type="hidden" value="109" name="field_id[109]">';

                    // 3 coborrower - bank acc no, name, ifsc
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataBankAccNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankaccountnumberofthecoborrower2')->first();
                            $agreementDataBankName = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'banknameofthecoborrower2')->first();
                            $agreementDataBankIfsc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankifscofthecoborrower2')->first();
                            if ($agreementDataBankAccNo) {
                                // if data is filled & watching only
                                $respValueBankAccNo = $agreementDataBankAccNo->field_value;
                            }
                            if ($agreementDataBankName) {
                                // if data is filled & watching only
                                $respValueBankName = $agreementDataBankName->field_value;
                            }
                            if ($agreementDataBankIfsc) {
                                // if data is filled & watching only
                                $respValueBankIfsc = $agreementDataBankIfsc->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueBankAccNo = '';
                        $respValueBankName = '';
                        $respValueBankIfsc = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row"> <div class="col-4"> <input type="text" placeholder="Bank account number of the Co-Borrower 2" class="form-control form-control-sm" name="field_name[bankaccountnumberofthecoborrower2]" value="'.$respValueBankAccNo.'" style="display:none;"><input type="hidden" value="110" name="field_id[110]"> </div><div class="col-4"> <input type="text" placeholder="Bank name of the Co-Borrower 2" class="form-control form-control-sm" name="field_name[banknameofthecoborrower2]" value="'.$respValueBankName.'" style="display:none;"><input type="hidden" value="111" name="field_id[111]"> </div><div class="col-4"> <input type="text" placeholder="Bank IFSC of the Co-Borrower 2" class="form-control form-control-sm text-uppercase" name="field_name[bankifscofthecoborrower2]" value="'.$respValueBankIfsc.'" style="display:none;"><input type="hidden" value="112" name="field_id[112]"> </div> </div>';

                    // 4 coborrower - driving license, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataLicenseNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicensenumberofthecoborrower2')->first();
                            $agreementDataLicenseIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseissuedateofthecoborrower2')->first();
                            $agreementDataLicenseExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseexpirydateofthecoborrower2')->first();
                            if ($agreementDataLicenseNo) {
                                // if data is filled & watching only
                                $respValueLicenseNo = $agreementDataLicenseNo->field_value;
                            }
                            if ($agreementDataLicenseIssue) {
                                // if data is filled & watching only
                                $respValueLicenseIssue = $agreementDataLicenseIssue->field_value;
                            }
                            if ($agreementDataLicenseExpiry) {
                                // if data is filled & watching only
                                $respValueLicenseExpiry = $agreementDataLicenseExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueLicenseNo = '';
                        $respValueLicenseIssue = '';
                        $respValueLicenseExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="coBorrower2DrivingLicenseHolder" style="display:none"> <div class="col-4"> <input type="text" placeholder="Driving license number of the Co-Borrower 2" class="form-control form-control-sm text-uppercase" name="field_name[drivinglicensenumberofthecoborrower2]" value="'.$respValueLicenseNo.'"><input type="hidden" value="113" name="field_id[113]"> <p class="small text-muted my-1">Driving license number</p> </div><div class="col-4"> <input type="date" placeholder="Driving license issue date of the Co-Borrower 2" class="form-control form-control-sm" name="field_name[drivinglicenseissuedateofthecoborrower2]" value="'.$respValueLicenseIssue.'"><input type="hidden" value="114" name="field_id[114]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Driving license expiry date of the Co-Borrower 2" class="form-control form-control-sm" name="field_name[drivinglicenseexpirydateofthecoborrower2]" value="'.$respValueLicenseExpiry.'"><input type="hidden" value="115" name="field_id[115]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    // 5 coborrower - electricity bill
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataElecBill = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'electricitybillnumberofthecoborrower2')->first();
                            if ($agreementDataElecBill) {
                                // if data is filled & watching only
                                $respValueElecBill = $agreementDataElecBill->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueElecBill = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Electricity bill number of the Co-Borrower 2" class="form-control form-control-sm text-uppercase" name="field_name[electricitybillnumberofthecoborrower2]" value="'.$respValueElecBill.'" style="display:none;"><input type="hidden" value="116" name="field_id[116]">';

                    // 6 coborrower - passport, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataPassportNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportnumberofthecoborrower2')->first();
                            $agreementDataPassportIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportissuedateofthecoborrower2')->first();
                            $agreementDataPassportExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportexpirydateofthecoborrower2')->first();
                            if ($agreementDataPassportNo) {
                                // if data is filled & watching only
                                $respValuePassportNo = $agreementDataPassportNo->field_value;
                            }
                            if ($agreementDataPassportIssue) {
                                // if data is filled & watching only
                                $respValuePassportIssue = $agreementDataPassportIssue->field_value;
                            }
                            if ($agreementDataPassportExpiry) {
                                // if data is filled & watching only
                                $respValuePassportExpiry = $agreementDataPassportExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValuePassportNo = '';
                        $respValuePassportIssue = '';
                        $respValuePassportExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="coBorrower2PassportHolder" style="display:none"> <div class="col-4"> <input type="text" placeholder="Passport number of the Co-Borrower 2" class="form-control form-control-sm text-uppercase" name="field_name[passportnumberofthecoborrower2]" value="'.$respValuePassportNo.'"><input type="hidden" value="117" name="field_id[117]"> <p class="small text-muted my-1">Passport number</p> </div><div class="col-4"> <input type="date" placeholder="Passport issue date of the Co-Borrower 2" class="form-control form-control-sm" name="field_name[passportissuedateofthecoborrower2]" value="'.$respValuePassportIssue.'"><input type="hidden" value="118" name="field_id[118]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Passport expiry date of the Co-Borrower 2" class="form-control form-control-sm" name="field_name[passportexpirydateofthecoborrower2]" value="'.$respValuePassportExpiry.'"><input type="hidden" value="119" name="field_id[119]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100 mt-3">'.$optionalFieldsInsideData.'</div>';

                break;


                // Officially Valid Documents of the Guarantor
                case 'officiallyvaliddocumentsoftheguarantor' :
                    // 	Officially Valid Documents entry fields

                    // 1 guarantor - aadhar card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataAadharNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'aadharcardnumberoftheguarantor')->first();
                            if ($agreementDataAadharNo) {
                                // if data is filled & watching only
                                $respValueAadharCardNo = $agreementDataAadharNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueAadharCardNo = '';
                    }

                    $optionalFieldsInsideData = '<input type="text" placeholder="Aadhar card number of the Guarantor" class="form-control form-control-sm text-uppercase" name="field_name[aadharcardnumberoftheguarantor]" value="'.$respValueAadharCardNo.'" style="display:none;"><input type="hidden" value="120" name="field_id[120]">';

                    // 2 guarantor - voter card
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataVoterCardNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'votercardnumberoftheguarantor')->first();
                            if ($agreementDataVoterCardNo) {
                                // if data is filled & watching only
                                $respValueVoterCardNo = $agreementDataVoterCardNo->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueVoterCardNo = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Voter card number of the Guarantor" class="form-control form-control-sm text-uppercase" name="field_name[votercardnumberoftheguarantor]" value="'.$respValueVoterCardNo.'" style="display:none;"><input type="hidden" value="121" name="field_id[121]">';

                    // 3 guarantor - bank acc no, name, ifsc
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataBankAccNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankaccountnumberoftheguarantor')->first();
                            $agreementDataBankName = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'banknameoftheguarantor')->first();
                            $agreementDataBankIfsc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'bankifscoftheguarantor')->first();
                            if ($agreementDataBankAccNo) {
                                // if data is filled & watching only
                                $respValueBankAccNo = $agreementDataBankAccNo->field_value;
                            }
                            if ($agreementDataBankName) {
                                // if data is filled & watching only
                                $respValueBankName = $agreementDataBankName->field_value;
                            }
                            if ($agreementDataBankIfsc) {
                                // if data is filled & watching only
                                $respValueBankIfsc = $agreementDataBankIfsc->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueBankAccNo = '';
                        $respValueBankName = '';
                        $respValueBankIfsc = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row"> <div class="col-4"> <input type="text" placeholder="Bank account number of the Guarantor" class="form-control form-control-sm" name="field_name[bankaccountnumberoftheguarantor]" value="'.$respValueBankAccNo.'" style="display:none;"><input type="hidden" value="122" name="field_id[122]"> </div><div class="col-4"> <input type="text" placeholder="Bank name of the Guarantor" class="form-control form-control-sm" name="field_name[banknameoftheguarantor]" value="'.$respValueBankName.'" style="display:none;"><input type="hidden" value="123" name="field_id[123]"> </div><div class="col-4"> <input type="text" placeholder="Bank IFSC of the Guarantor" class="form-control form-control-sm text-uppercase" name="field_name[bankifscoftheguarantor]" value="'.$respValueBankIfsc.'" style="display:none;"><input type="hidden" value="124" name="field_id[124]"> </div> </div>';

                    // 4 guarantor - driving license, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataLicenseNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicensenumberoftheguarantor')->first();
                            $agreementDataLicenseIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseissuedateoftheguarantor')->first();
                            $agreementDataLicenseExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'drivinglicenseexpirydateoftheguarantor')->first();
                            if ($agreementDataLicenseNo) {
                                // if data is filled & watching only
                                $respValueLicenseNo = $agreementDataLicenseNo->field_value;
                            }
                            if ($agreementDataLicenseIssue) {
                                // if data is filled & watching only
                                $respValueLicenseIssue = $agreementDataLicenseIssue->field_value;
                            }
                            if ($agreementDataLicenseExpiry) {
                                // if data is filled & watching only
                                $respValueLicenseExpiry = $agreementDataLicenseExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueLicenseNo = '';
                        $respValueLicenseIssue = '';
                        $respValueLicenseExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="guarantorDrivingLicenseHolder" style="display:none"> <div class="col-4"> <input type="text" placeholder="Driving license number of the Guarantor" class="form-control form-control-sm text-uppercase" name="field_name[drivinglicensenumberoftheguarantor]" value="'.$respValueLicenseNo.'"><input type="hidden" value="125" name="field_id[125]"> <p class="small text-muted my-1">Driving license number</p> </div><div class="col-4"> <input type="date" placeholder="Driving license issue date of the Guarantor" class="form-control form-control-sm" name="field_name[drivinglicenseissuedateoftheguarantor]" value="'.$respValueLicenseIssue.'"><input type="hidden" value="126" name="field_id[126]"> <p class="small text-muted my-1">Expiry date</p> </div><div class="col-4"> <input type="date" placeholder="Driving license expiry date of the Guarantor" class="form-control form-control-sm" name="field_name[drivinglicenseexpirydateoftheguarantor]" value="'.$respValueLicenseExpiry.'"><input type="hidden" value="127" name="field_id[127]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    // 5 guarantor - electricity bill
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataElecBill = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'electricitybillnumberoftheguarantor')->first();
                            if ($agreementDataElecBill) {
                                // if data is filled & watching only
                                $respValueElecBill = $agreementDataElecBill->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueElecBill = '';
                    }

                    $optionalFieldsInsideData .= '<input type="text" placeholder="Electricity bill number of the Guarantor" class="form-control form-control-sm text-uppercase" name="field_name[electricitybillnumberoftheguarantor]" value="'.$respValueElecBill.'" style="display:none;"><input type="hidden" value="128" name="field_id[128]">';

                    // 6 guarantor - passport, issue, expiry
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataPassportNo = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportnumberoftheguarantor')->first();
                            $agreementDataPassportIssue = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportissuedateoftheguarantor')->first();
                            $agreementDataPassportExpiry = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'passportexpirydateoftheguarantor')->first();
                            if ($agreementDataPassportNo) {
                                // if data is filled & watching only
                                $respValuePassportNo = $agreementDataPassportNo->field_value;
                            }
                            if ($agreementDataPassportIssue) {
                                // if data is filled & watching only
                                $respValuePassportIssue = $agreementDataPassportIssue->field_value;
                            }
                            if ($agreementDataPassportExpiry) {
                                // if data is filled & watching only
                                $respValuePassportExpiry = $agreementDataPassportExpiry->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValuePassportNo = '';
                        $respValuePassportIssue = '';
                        $respValuePassportExpiry = '';
                    }

                    $optionalFieldsInsideData .= '<div class="row" id="guarantorPassportHolder" style="display:none"> <div class="col-4"> <input type="text" placeholder="Passport number of the Guarantor" class="form-control form-control-sm text-uppercase" name="field_name[passportnumberoftheguarantor]" value="'.$respValuePassportNo.'"><input type="hidden" value="129" name="field_id[129]"> <p class="small text-muted my-1">Passport number</p> </div><div class="col-4"> <input type="date" placeholder="Passport issue date of the Guarantor" class="form-control form-control-sm" name="field_name[passportissuedateoftheguarantor]" value="'.$respValuePassportIssue.'"><input type="hidden" value="130" name="field_id[130]"> <p class="small text-muted my-1">Issue date</p> </div><div class="col-4"> <input type="date" placeholder="Passport expiry date of the Guarantor" class="form-control form-control-sm" name="field_name[passportexpirydateoftheguarantor]" value="'.$respValuePassportExpiry.'"><input type="hidden" value="131" name="field_id[131]"> <p class="small text-muted my-1">Expiry date</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100 mt-3">'.$optionalFieldsInsideData.'</div>';

                break;


                // Nature of loan
                case 'natureofloan' :
                    $checkoffCompanyNames = Field::select('value')->where('key_name', 'nameofthecheckoffcompany')->first();

                    $explodedNames = explode(', ', $checkoffCompanyNames->value);

                    $optionalFieldsInsideData = '<select class="form-control form-control-sm" name="field_name[nameofthecheckoffcompany]" style="display: none;"><option value="" selected="" hidden="">Select Name of the check-off Company</option>';

                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $checkOffCompanyName = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'nameofthecheckoffcompany')->first();
                            if ($checkOffCompanyName) {
                                // if data is filled & watching only
                                $checkOffCompanyName = $checkOffCompanyName->field_value;
                            }
                        }
                    } else {
                        $checkOffCompanyName = '';
                    }

                    foreach ($explodedNames as $key => $checkoffCompanyItem) {
                        ($checkOffCompanyName == $checkoffCompanyItem) ? $checkoffCompanyChecked = 'selected' : $checkoffCompanyChecked = '';

                        $optionalFieldsInsideData .= '<option value="'.$checkoffCompanyItem.'" '.$checkoffCompanyChecked.'>'.$checkoffCompanyItem.'</option>';
                    }

                    $optionalFieldsInsideData .= '</select>';

                    $optionalFieldsData = '<div class="w-100 mt-3">'.$optionalFieldsInsideData.'</div>';

                break;

                // Post date cheque 1
                case 'postdatecheque1' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque1desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque1description')->first();
                            $cheque1numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque1chequenumber')->first();
                            $cheque1date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque1date')->first();
                            $cheque1amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque1amount')->first();

                            $value1 = $cheque1desc->field_value;
                            $value2 = $cheque1numb->field_value;
                            $value3 = $cheque1date->field_value;
                            $value4 = $cheque1amount->field_value;
                        }
                    } else {
                        $value1 = $value2 = $value3 = $value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 1 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque1description]">'.$value1.'</textarea> <input type="hidden" value="159" name="field_id[159]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 1 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque1chequenumber]" value="'.$value2.'"><input type="hidden" value="160" name="field_id[160]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 1 date" class="form-control form-control-sm" name="field_name[postdatecheque1date]" value="'.$value3.'"><input type="hidden" value="161" name="field_id[161]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 1 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque1amount]" value="'.$value4.'"><input type="hidden" value="162" name="field_id[162]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 2
                case 'postdatecheque2' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque2desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque2description')->first();
                            $cheque2numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque2chequenumber')->first();
                            $cheque2date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque2date')->first();
                            $cheque2amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque2amount')->first();

                            ($cheque2desc) ? $cheque2value1 = $cheque2desc->field_value : $cheque2value1 = '';
                            ($cheque2numb) ? $cheque2value2 = $cheque2numb->field_value : $cheque2value2 = '';
                            ($cheque2date) ? $cheque2value3 = $cheque2date->field_value : $cheque2value3 = '';
                            ($cheque2amount) ? $cheque2value4 = $cheque2amount->field_value : $cheque2value4 = '';
                        }
                    } else {
                        $cheque2value1 = $cheque2value2 = $cheque2value3 = $cheque2value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 2 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque2description]">'.$cheque2value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 2 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque2chequenumber]" value="'.$cheque2value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 2 date" class="form-control form-control-sm" name="field_name[postdatecheque2date]" value="'.$cheque2value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 2 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque2amount]" value="'.$cheque2value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 3
                case 'postdatecheque3' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque3desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque3description')->first();
                            $cheque3numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque3chequenumber')->first();
                            $cheque3date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque3date')->first();
                            $cheque3amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque3amount')->first();

                            ($cheque3desc) ? $cheque3value1 = $cheque3desc->field_value : $cheque3value1 = '';
                            ($cheque3numb) ? $cheque3value2 = $cheque3numb->field_value : $cheque3value2 = '';
                            ($cheque3date) ? $cheque3value3 = $cheque3date->field_value : $cheque3value3 = '';
                            ($cheque3amount) ? $cheque3value4 = $cheque3amount->field_value : $cheque3value4 = '';
                        }
                    } else {
                        $cheque3value1 = $cheque3value2 = $cheque3value3 = $cheque3value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 3 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque3description]">'.$cheque3value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 3 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque3chequenumber]" value="'.$cheque3value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 3 date" class="form-control form-control-sm" name="field_name[postdatecheque3date]" value="'.$cheque3value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 3 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque3amount]" value="'.$cheque3value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 4
                case 'postdatecheque4' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque4desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque4description')->first();
                            $cheque4numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque4chequenumber')->first();
                            $cheque4date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque4date')->first();
                            $cheque4amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque4amount')->first();

                            ($cheque4desc) ? $cheque4value1 = $cheque4desc->field_value : $cheque4value1 = '';
                            ($cheque4numb) ? $cheque4value2 = $cheque4numb->field_value : $cheque4value2 = '';
                            ($cheque4date) ? $cheque4value3 = $cheque4date->field_value : $cheque4value3 = '';
                            ($cheque4amount) ? $cheque4value4 = $cheque4amount->field_value : $cheque4value4 = '';
                        }
                    } else {
                        $cheque4value1 = $cheque4value2 = $cheque4value3 = $cheque4value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque4description]">'.$cheque4value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque4chequenumber]" value="'.$cheque4value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque4date]" value="'.$cheque4value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque4amount]" value="'.$cheque4value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 5
                case 'postdatecheque5' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque5desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque5description')->first();
                            $cheque5numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque5chequenumber')->first();
                            $cheque5date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque5date')->first();
                            $cheque5amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque5amount')->first();

                            ($cheque5desc) ? $cheque5value1 = $cheque5desc->field_value : $cheque5value1 = '';
                            ($cheque5numb) ? $cheque5value2 = $cheque5numb->field_value : $cheque5value2 = '';
                            ($cheque5date) ? $cheque5value3 = $cheque5date->field_value : $cheque5value3 = '';
                            ($cheque5amount) ? $cheque5value4 = $cheque5amount->field_value : $cheque5value4 = '';
                        }
                    } else {
                        $cheque5value1 = $cheque5value2 = $cheque5value3 = $cheque5value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque5description]">'.$cheque5value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque5chequenumber]" value="'.$cheque5value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque5date]" value="'.$cheque5value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque5amount]" value="'.$cheque5value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 6
                case 'postdatecheque6' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque6desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque6description')->first();
                            $cheque6numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque6chequenumber')->first();
                            $cheque6date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque6date')->first();
                            $cheque6amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque6amount')->first();

                            ($cheque6desc) ? $cheque6value1 = $cheque6desc->field_value : $cheque6value1 = '';
                            ($cheque6numb) ? $cheque6value2 = $cheque6numb->field_value : $cheque6value2 = '';
                            ($cheque6date) ? $cheque6value3 = $cheque6date->field_value : $cheque6value3 = '';
                            ($cheque6amount) ? $cheque6value4 = $cheque6amount->field_value : $cheque6value4 = '';
                        }
                    } else {
                        $cheque6value1 = $cheque6value2 = $cheque6value3 = $cheque6value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque6description]">'.$cheque6value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque6chequenumber]" value="'.$cheque6value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque6date]" value="'.$cheque6value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque6amount]" value="'.$cheque6value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 7
                case 'postdatecheque7' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque7desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque7description')->first();
                            $cheque7numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque7chequenumber')->first();
                            $cheque7date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque7date')->first();
                            $cheque7amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque7amount')->first();

                            ($cheque7desc) ? $cheque7value1 = $cheque7desc->field_value : $cheque7value1 = '';
                            ($cheque7numb) ? $cheque7value2 = $cheque7numb->field_value : $cheque7value2 = '';
                            ($cheque7date) ? $cheque7value3 = $cheque7date->field_value : $cheque7value3 = '';
                            ($cheque7amount) ? $cheque7value4 = $cheque7amount->field_value : $cheque7value4 = '';
                        }
                    } else {
                        $cheque7value1 = $cheque7value2 = $cheque7value3 = $cheque7value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque7description]">'.$cheque7value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque7chequenumber]" value="'.$cheque7value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque7date]" value="'.$cheque7value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque7amount]" value="'.$cheque7value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 8
                case 'postdatecheque8' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque8desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque8description')->first();
                            $cheque8numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque8chequenumber')->first();
                            $cheque8date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque8date')->first();
                            $cheque8amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque8amount')->first();

                            ($cheque8desc) ? $cheque8value1 = $cheque8desc->field_value : $cheque8value1 = '';
                            ($cheque8numb) ? $cheque8value2 = $cheque8numb->field_value : $cheque8value2 = '';
                            ($cheque8date) ? $cheque8value3 = $cheque8date->field_value : $cheque8value3 = '';
                            ($cheque8amount) ? $cheque8value4 = $cheque8amount->field_value : $cheque8value4 = '';
                        }
                    } else {
                        $cheque8value1 = $cheque8value2 = $cheque8value3 = $cheque8value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque8description]">'.$cheque8value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque8chequenumber]" value="'.$cheque8value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque8date]" value="'.$cheque8value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque8amount]" value="'.$cheque8value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 9
                case 'postdatecheque9' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque9desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque9description')->first();
                            $cheque9numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque9chequenumber')->first();
                            $cheque9date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque9date')->first();
                            $cheque9amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque9amount')->first();

                            ($cheque9desc) ? $cheque9value1 = $cheque9desc->field_value : $cheque9value1 = '';
                            ($cheque9numb) ? $cheque9value2 = $cheque9numb->field_value : $cheque9value2 = '';
                            ($cheque9date) ? $cheque9value3 = $cheque9date->field_value : $cheque9value3 = '';
                            ($cheque9amount) ? $cheque9value4 = $cheque9amount->field_value : $cheque9value4 = '';
                        }
                    } else {
                        $cheque9value1 = $cheque9value2 = $cheque9value3 = $cheque9value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque9description]">'.$cheque9value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque9chequenumber]" value="'.$cheque9value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque9date]" value="'.$cheque9value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque9amount]" value="'.$cheque9value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // Post date cheque 10
                case 'postdatecheque10' :
                    if ($form_type == 'show') {
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $cheque10desc = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque10description')->first();
                            $cheque10numb = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque10chequenumber')->first();
                            $cheque10date = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque10date')->first();
                            $cheque10amount = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'postdatecheque10amount')->first();

                            ($cheque10desc) ? $cheque10value1 = $cheque10desc->field_value : $cheque10value1 = '';
                            ($cheque10numb) ? $cheque10value2 = $cheque10numb->field_value : $cheque10value2 = '';
                            ($cheque10date) ? $cheque10value3 = $cheque10date->field_value : $cheque10value3 = '';
                            ($cheque10amount) ? $cheque10value4 = $cheque10amount->field_value : $cheque10value4 = '';
                        }
                    } else {
                        $cheque10value1 = $cheque10value2 = $cheque10value3 = $cheque10value4 = '';
                    }

                    $optionalFieldsInsideData = '<div class="row"> <div class="col-12"> <textarea placeholder="Post date cheque 4 description" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[postdatecheque10description]">'.$cheque10value1.'</textarea> <input type="hidden" value="163" name="field_id[163]"> <p class="small text-muted my-1">Description</p> </div><div class="col-4"> <input type="text" placeholder="Post date cheque 4 cheque number" class="form-control form-control-sm" name="field_name[postdatecheque10chequenumber]" value="'.$cheque10value2.'"><input type="hidden" value="164" name="field_id[164]"> <p class="small text-muted my-1">Cheque number</p> </div><div class="col-4"> <input type="date" placeholder="Post date cheque 4 date" class="form-control form-control-sm" name="field_name[postdatecheque10date]" value="'.$cheque10value3.'"><input type="hidden" value="165" name="field_id[165]"> <p class="small text-muted my-1">Date</p> </div> <div class="col-4"> <input type="text" placeholder="Post date cheque 4 amount" class="form-control form-control-sm numberField" name="field_name[postdatecheque10amount]" value="'.$cheque10value4.'"><input type="hidden" value="166" name="field_id[166]"> <p class="small text-muted my-1">Amount</p> </div> </div>';

                    $optionalFieldsData = '<div class="w-100">'.$optionalFieldsInsideData.'</div>';
                break;

                // borrower date of birth
                case 'dateofbirthoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->date_of_birth;
                    break;
                // borrower email id
                case 'emailidoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->email;
                    break;
                // borrower mobile number
                case 'mobilenumberoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->mobile;
                    break;
                // borrower pan card number
                case 'pancardnumberoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->pan_card_number;
                    break;
                // borrower occupation
                case 'occupationoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->occupation;
                    break;
                // borrower marital status
                case 'maritalstatusoftheborrower' :
                    $disabledField = '';
                    $respValue = $borrower->marital_status;
                    break;
                // borrower street address
                case 'streetaddressoftheborrower' :
                    $disabledField = '';

                    $display_house_no = (strtolower($borrower->KYC_HOUSE_NO) != 'na' && $borrower->KYC_HOUSE_NO != '') ? $borrower->KYC_HOUSE_NO.' ' : '';
                    $display_street = (strtolower($borrower->KYC_Street) != 'na' || $borrower->KYC_Street != '') ? $borrower->KYC_Street.' ' : '';
                    $display_locality = (strtolower($borrower->KYC_LOCALITY) != 'na' || $borrower->KYC_LOCALITY != '') ? $borrower->KYC_LOCALITY.' ' : '';
                    $display_city = (strtolower($borrower->KYC_CITY) != 'na' || $borrower->KYC_CITY != '') ? $borrower->KYC_CITY.' ' : '';
                    $display_state = (strtolower($borrower->KYC_State) != 'na' || $borrower->KYC_State != '') ? $borrower->KYC_State.' ' : '';
                    $display_pincode = (strtolower($borrower->KYC_PINCODE) != 'na' || $borrower->KYC_PINCODE != '') ? $borrower->KYC_PINCODE.' ' : '';
                    $display_country = (strtolower($borrower->KYC_Country) != 'na' || $borrower->KYC_Country != '') ? $borrower->KYC_Country.' ' : '';

                    $respValue = $display_house_no.$display_street.$display_locality.$display_city.$display_state.$display_pincode.$display_country;

                    break;
                // IFS code fetch API
                case 'ifscodeofborrower' :
                    $disabledField = '';
                    $extraClass = 'ifsCodeFetch text-uppercase';
                    break;
                // Rate of interest/ processing charge/ documentation fee/ monthly EMI/ Penal Interest percentage
                case 'rateofinterest' :
                    $disabledField = '';
                    $extraClass = 'numberField';
                    break;
                // processing charge
                case 'processingchargeinpercentage' :
                    $disabledField = '';
                    $extraClass = 'numberField';
                    break;
                // documentation fee
                case 'documentationfee' :
                    $disabledField = '';
                    $extraClass = 'numberField';
                    break;
                // monthly EMI
                case 'monthlyemiindigits' :
                    $disabledField = '';
                    $extraClass = 'numberField';
                    break;
                // Penal Interest percentage
                case 'penalinterestpercentage' :
                    $disabledField = '';
                    $extraClass = 'numberField';
                    break;
                // Date of credit of EMI into Lender's Bank Account
                case 'dateofcreditofemiintolendersbankaccount' :
                    // value
                    if ($form_type == 'show') {
                        // if rfq found, fetch filled data
                        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
                        if ($rfq) {
                            $agreementDataOtherDate = AgreementData::where('rfq_id', $rfq->id)->where('field_name', 'otherdateofemicredit')->first();
                            if ($agreementDataOtherDate) {
                                // if data is filled & watching only
                                $respValueOtherDate = $agreementDataOtherDate->field_value;
                            }
                        }
                    } else {
                        // if data is not filled
                        $respValueOtherDate = '';
                    }

                    $valueOtherDateEmiCredit = Field::select('value')->where('key_name', 'otherdateofemicredit')->first();

                    $expValue = explode(', ', $valueOtherDateEmiCredit->value);
                    $option = '<option value="" selected hidden>Select Other date of EMI credit</option>';
                    foreach ($expValue as $index => $val) {
                        $selected = '';
                        if (strtolower($respValueOtherDate) == strtolower($val)) $selected = 'selected';

                        $option .= '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                    }

                    $optionalFieldsInsideData = '<select class="form-control form-control-sm" name="field_name[otherdateofemicredit]" style="display:none;">'.$option.'</select><input type="hidden" value="75" name="field_id[75]">';

                    // $optionalFieldsInsideData = '<input type="text" placeholder="Other date of EMI credit" class="form-control form-control-sm" name="field_name[otherdateofemicredit]" value="'.$respValueOtherDate.'" style="display:none;"><input type="hidden" value="120" name="field_id[120]">';

                    $optionalFieldsData = '<div class="w-100 mt-3">'.$optionalFieldsInsideData.'</div>';

                break;
                default :
                    $disabledField = '';
                    $respValue = '';
                    $extraClass = '';
                    break;
            }
        }
        // in case of adding agreement data, auto-fill borrower details ends

        // if rfq found, fetch filled data
        $rfq = AgreementRfq::select('id')->where('borrower_id', $borrowerId)->first();
        if ($rfq) {
            $agreementData = AgreementData::where('rfq_id', $rfq->id)->where('field_name', $key_name)->first();
            if ($agreementData) $respValue = $agreementData->field_value;

            $disabledField = '';
        }
    }

    // adding extra text after fields
    $extraPostField = '';
    $extraPreField = '';
    if ($key_name == 'loanamountindigits') $extraPreField = '<span class="post-text">Rs.</span>';
    if ($key_name == 'processingchargeinpercentage') $extraPostField = '<span class="post-text">+ Taxes</span>';
    if ($key_name == 'documentationfee') {
        $extraPreField = '<span class="post-text">Rs.</span>';
        $extraPostField = '<span class="post-text">+ Taxes</span>';
    }

    // working with required/ optional fields
    if ($key_name == 'otherdateofemicredit') $required = '';
    if ($key_name == 'demandpromissorynoteforcoborrowerdate') $required = '';
    if ($key_name == 'continuingsecurityletterdate2') $required = '';
    if ($key_name == 'otherdocumentstobeattachedwithapplicationforloan') $required = '';
    // if ($key_name == 'otherdateofemicredit' && $key_name = 'demandpromissorynoteforcoborrowerdate' && $key_name = 'continuingsecurityletterdate2') {
    //     $required = '';
    // }

    switch ($type) {
        case 'text':
            if ($key_name == 'postdatecheque1' || $key_name == 'postdatecheque2' || $key_name == 'postdatecheque3' || $key_name == 'postdatecheque4' || $key_name == 'postdatecheque5' || $key_name == 'postdatecheque6' || $key_name == 'postdatecheque7' || $key_name == 'postdatecheque8' || $key_name == 'postdatecheque9' || $key_name == 'postdatecheque10') {
                $response = $optionalFieldsData;
            } else {
                $response = '<div class="w-100 d-flex">'.$extraPreField.'<input type="text" placeholder="' . $name . '" class="form-control form-control-sm '.$extraClass.'" name="field_name[' . $key_name . ']" ' . $required . ' value="' . $respValue . '" '.$disabledField.' ><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">'.$extraPostField.$optionalFieldsData.'</div>';
            }

            break;
        case 'email':
            $response = $extraPreField.'<input type="email" placeholder="' . $name . '" class="form-control form-control-sm" name="field_name[' . $key_name . ']" ' . $required . ' value="' . $respValue . '" '.$disabledField.'><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">'.$extraPostField;
            break;
        case 'number':
            $response = $extraPreField.'<input type="number" placeholder="' . $name . '" class="form-control form-control-sm" name="field_name[' . $key_name . ']" ' . $required . ' value="' . $respValue . '" '.$disabledField.'><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">'.$extraPostField;
            break;
        case 'date':
            // if (isset($form_type) == 'show') {
            //     $respValue = date('Y-m-d', strtotime($respValue));
            // } else {
            //     $respValue = '';
            // }

            $response = $extraPreField.'<input type="date" placeholder="' . $name . '" class="form-control form-control-sm" name="field_name[' . $key_name . ']" ' . $required . ' value="' . $respValue . '" '.$disabledField.' '.$respValue.'><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">'.$extraPostField;
            break;
        case 'time':
            $response = $extraPreField.'<input type="time" placeholder="' . $name . '" class="form-control form-control-sm" name="field_name[' . $key_name . ']" ' . $required . ' value="' . $respValue . '" '.$disabledField.'><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">'.$extraPostField;
            break;
        case 'file':
            $response = '<div class="custom-file custom-file-sm"><input type="file" class="custom-file-input" id="customFile" name="field_name[' . $key_name . ']" ' . $required . ' '.$disabledField.'><label class="custom-file-label" for="customFile">Choose ' . $name . '</label></div><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">';
            break;
        case 'select':
            $expValue = explode(', ', $value);
            $option = '<option value="" selected hidden>Select ' . $name . '</option>';
            foreach ($expValue as $index => $val) {
                $selected = '';
                if (strtolower($respValue) == strtolower($val)) $selected = 'selected';

                $option .= '<option value="' . $val . '" ' . $selected . '>' . $val . '</option>';
            }
            $response = '<div class="w-100"><select class="form-control form-control-sm" name="field_name[' . $key_name . ']" ' . $required . ' '.$disabledField.'>' . $option . '</select><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">'.$optionalFieldsData.'</div>';
            break;
        case 'checkbox':
            // all values, showing properly
            $expValue = explode(', ', $value);

            // response/ selected values
            $explodedRespValues = explode(',', strtolower($respValue));

            foreach ($explodedRespValues as $key => $singleRespValue) {
                $newCheckedValues[] = generateKeyForForm($singleRespValue);
            }

            $option = '';
            foreach ($expValue as $index => $val) {
                $checked = '';
                if(in_array(generateKeyForForm(strReplace($val)), $newCheckedValues)) $checked = 'checked';

                $option .= '<div class="single-checkbox-holder"><input class="form-check-input" type="checkbox" name="field_name[' . $key_name . '][]" id="' . $key_name . '-' . $index . '" value="' . $val . '" '.$checked.' '.$disabledField.'> <label for="' . $key_name . '-' . $index . '" class="form-check-label mr-1">' . $val.' </label></div>';
            }
            $response = '<div class="form-check">' . $option . '</div><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">';
            break;
        case 'radio':
            $expValue = explode(', ', $value);
            $option = '';
            foreach ($expValue as $index => $val) {
                $checked = '';
                if ($respValue == $val) $checked = 'checked';
                $option .= '<input class="form-check-input" type="radio" name="field_name[' . $key_name . ']" id="' . $key_name . '-' . $index . '" value="' . $val . '" ' . $required . ' ' . $checked . ' '.$disabledField.'> <label for="' . $key_name . '-' . $index . '" class="form-check-label mr-1">' . $val . '</label><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">';
            }
            $response = '<div class="w-100"><div class="form-check form-check-inline nowrap">' . $option . '</div>'.$optionalFieldsData.'</div>';
            break;
        case 'textarea':
            $response = '<textarea placeholder="' . $name . '" class="form-control form-control-sm" style="min-height:100px;max-height:200px" name="field_name[' . $key_name . ']" ' . $required . ' '.$disabledField.'>' . $respValue . '</textarea><input type="hidden" value="' . $field_id . '" name="field_id[' . $field_id . ']">';
            break;
        default:
            $response = '<input type="text">';
            break;
    }

    return $response;
}

// generate key name from field name
function generateKeyForForm(string $string)
{
    $key = '';
    for ($i = 0; $i < strlen($string); $i++) {
        if (!preg_match('/[^A-Za-z]+/', $string[$i])) {
            $key .= strtolower($string[$i]);
        }
    }
    return $key;
}

// str replace for apos
function strReplace(string $string) {
    return str_replace(['apos', '('], '', $string);
}

// send mail helper
function SendMail($data)
{
    // mail log
    $newMail = new \App\Models\MailLog();
    $newMail->from = env('MAIL_FROM_ADDRESS');
    $newMail->to = $data['email'];
    $newMail->subject = $data['subject'];
    $newMail->blade_file = $data['blade_file'];
    $newMail->payload = json_encode($data);
    $newMail->save();

    // send mail
    Mail::send('mail/' . $data['blade_file'], $data, function ($message) use ($data) {
        $message->to($data['email'], $data['name'])
            ->subject($data['subject'])
            ->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
    });
}

// notification create helper
function createNotification(int $sender, int $receiver, string $type, string $message = null, string $route = null)
{
    switch ($type) {
        case 'user_registration':
            $title = 'Registration successfull';
            $message = 'Please check & update your profile as needed';
            $route = 'user.profile';
            break;
        case 'new_borrower':
            $title = 'New borrower created';
            $message = $message;
            $route = 'user.borrower.list';
            break;
        case 'agreement_data_upload':
            $title = 'Agreement data uploaded';
            $message = $message;
            $route = $route;
            break;
        default:
            $title = '';
            $message = '';
            $route = '';
            break;
    }

    $notification = new App\Models\Notification;
    $notification->sender_id = $sender;
    $notification->receiver_id = $receiver;
    $notification->type = $type;
    $notification->title = $title;
    $notification->message = $message;
    $notification->route = $route;
    $notification->save();
}

// activity log helper
function activityLog(array $data)
{
    $activity = new Activity;
    $activity->user_id = auth()->user()->id;
    $activity->user_device = '';
    $activity->ip_address = Request::ip();
    $activity->latitude = '';
    $activity->longitude = '';
    $activity->type = $data['type'];
    $activity->title = $data['title'];
    $activity->description = $data['desc'];
    $activity->save();
}

// check if agreement related document is uploaded or not
function documentSrc(int $agreement_document_id, int $borrower_id, string $type)
{
    $image = asset('admin/static-required/blank.png');
    $detailsShow = '<label for="file_' . $agreement_document_id . '" class="btn btn-xs btn-primary" id="image__preview_label' . $agreement_document_id . '">Browse <i class="fas fa-camera"></i></label>';

    $document = \App\Models\AgreementDocumentUpload::where('agreement_document_id', $agreement_document_id)->where('borrower_id', $borrower_id)->where('status', 1)->latest()->first();
    if ($document) {
        $image = asset($document->file_path);

        $verifyShow = '<a href="javascript: void(0)" class="btn btn-xs btn-success mb-2" title="Document verified" onclick="viewUploadedDocument(' . $document->id . ')" id="verifyDocToggle' . $document->id . '"> <i class="fas fa-clipboard-check"></i> </a>';
        if ($document->verify == 0) {
            $verifyShow = '<a href="javascript: void(0)" class="btn btn-xs btn-danger mb-2" title="Document unverified" onclick="viewUploadedDocument(' . $document->id . ')" id="verifyDocToggle' . $document->id . '"> <i class="fas fa-question-circle"></i> </a>';
        }

        $detailsShow = '<a href="javascript: void(0)" class="btn btn-xs btn-primary mb-2" onclick="viewUploadedDocument(' . $document->id . ')"><i class="fas fa-eye"></i></a> <label for="file_' . $agreement_document_id . '" class="btn btn-xs btn-dark" id="image__preview_label' . $agreement_document_id . '">Browse <i class="fas fa-camera"></i></label> ' . $verifyShow;
    }

    if ($type == 'image') {
        return $image;
    } else {
        return $detailsShow;
    }
}

//Check available stamp count
function availableStamp($amount){
    $availableStamp = Estamp::where('amount',$amount)->latest()->get();
    return $availableStamp;
}

function availableStampNew($amount){
    $availableStamp = Estamp::where('amount',$amount)->where('used_flag', 0)->get();
    return $availableStamp;
}

//Check borrower used the specific stamp or not
function checkUsedStamp($stamp_id,$borrower_id,$agreement_id){
    $borrower_agreement_details = BorrowerAgreement::where('borrower_id',$borrower_id)->where('agreement_id',$agreement_id)->first();
    $borrower_agreement_id = $borrower_agreement_details->id;

    $borrower_stamp = Estamp::where('used_flag',1)->where('used_in_agreement',$borrower_agreement_id)->where('id',$stamp_id)->count();
    if ($borrower_stamp > 0) {
       return 1;
    } else {
       return 0;
    }
}

// Check avaialble stamp agreement & amount wise
function avaialbleStampsAgreementWise($agreementId, $amount,$page_no) {
    $data = Estamp::where('used_in_agreement', $agreementId)->where('amount', $amount)->where('pdf_page_no',$page_no)->first();
    return $data;
}


// Check specific stamp is used or not
function specificStampWiseBorrowerDetails($stamp_id){
    $estamp_details = Estamp::find($stamp_id);
    if ($estamp_details->used_flag == 1) {
        $borrower_agreement_details = BorrowerAgreement::find($estamp_details->used_in_agreement);
        $borrower_details = Borrower::find($borrower_agreement_details->borrower_id);
        $borrower_name = $borrower_details->full_name;
        return $borrower_name;
    }else{
        return null;
    }
}

function specificStampWiseBorrowerDetailedLink($stamp_id){
    $estamp_details = Estamp::find($stamp_id);
    if ($estamp_details->used_flag == 1) {
        $borrower_agreement_details = BorrowerAgreement::find($estamp_details->used_in_agreement);
        $borrower_details = Borrower::find($borrower_agreement_details->borrower_id);
        $borrower_agreements_id = $borrower_agreement_details->id;
        $borrower_name = $borrower_details->full_name;
        return array('borrower_agreements_id' => $borrower_agreements_id , 'borrower_name' => $borrower_name);
        // return '<a href="'.url('/user/borrower/'.$borrower_agreements_id.'/agreement').'">'.$borrower_name.'</a>';
    }else{
        return null;
    }
}
