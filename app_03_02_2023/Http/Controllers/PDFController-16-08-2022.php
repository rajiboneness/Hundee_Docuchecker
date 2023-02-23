<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\AgreementData;
use App\Models\BorrowerAgreement;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class PDFController extends Controller
{
    public function showPdf(Request $request, $id)
    {
        $agreement = Agreement::select('html')->findOrFail($id);
        $data = [
            'fileName' => 'personal_loan_pdf',
            'title' => 'personal loan pdf',
            'date' => date('Y-m-d'),
            'html' => $agreement->html
        ];

        // show in browser
        $viewhtml = View::make('admin.pdf.index', $data)->render();
        // $viewhtml = View::make('admin.agreement.pdf', $data)->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($viewhtml)->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function generatePdf(Request $request, $id)
    {
        $agreement = Agreement::select('html')->findOrFail($id);
        $data = [
            'fileName' => 'personal_loan_pdf',
            'title' => 'personal loan pdf',
            'date' => date('Y-m-d'),
            'html' => $agreement->html
        ];

        // download
        $pdf = PDF::loadView('admin.agreement.pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download($data['fileName'].'.pdf');
    }

    public function getData($agreement, $checkFeld,$image = false){
        $response = '';
        foreach ($agreement as $key => $value) {
            if($value->field_name == $checkFeld){
                $response = $value->field_value;
                break;
            }
        }

        return $response;
    }

    // dynamic PDF after filling up data
    // public function showDynamicPdf($borrowerId, $agreementId, $borrowerAgreementsId)
    public function showDynamicPdf(Request $request, $borrowerId, $agreementId, $borrowerAgreementsId)
    {
        // dd($request->all());
        $data = (object)[];
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();

        // dd($agreement); 

        $data->borrowerId = $borrowerId;
        $data->agreementId = $agreementId;
        $data->borrowerAgreementsId = $borrowerAgreementsId;
        $data->date = date('d-m-Y');
        $customBorrowerName = str_replace(' ', '-', strtolower($this->getData($agreement, 'nameoftheborrower')));
        $data->fileName = $customBorrowerName.'-personal-loan-agreement-'.$data->date;
        $data->title = 'personal loan agreement';
        $data->customerid = $this->getData($agreement, 'customerid');
        $data->prefixoftheborrower = $this->getData($agreement, 'prefixoftheborrower');
        $data->nameoftheborrower = $this->getData($agreement, 'nameoftheborrower');
        $data->prefixofthecoborrower = $this->getData($agreement, 'prefixofthecoborrower');
        $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        $data->prefixoftheguarantor = $this->getData($agreement, 'prefixoftheguarantor');
        $data->nameoftheguarantor = $this->getData($agreement, 'nameoftheguarantor');
        // loan application number is loan reference number, application number is removed
        $data->loanreferencenumber = $this->getData($agreement, 'loanreferencenumber');
        // added again
        $data->loanapplicationnumber = $this->getData($agreement, 'loanapplicationnumber');
        $data->loanaccountnumber = $this->getData($agreement, 'loanaccountnumber');
        $data->sanctionletternumber = $this->getData($agreement, 'sanctionletternumber');

        // witness 1
        $data->witness1fullname = $this->getData($agreement, 'witness1fullname');
        $data->witness1streetaddress = $this->getData($agreement, 'witness1streetaddress');
        $data->witness1city = $this->getData($agreement, 'witness1city');
        $data->witness1pincode = $this->getData($agreement, 'witness1pincode');
        $data->witness1state = $this->getData($agreement, 'witness1state');

        // witness 2
        $data->witness2fullname = $this->getData($agreement, 'witness2fullname');
        $data->witness2streetaddress = $this->getData($agreement, 'witness2streetaddress');
        $data->witness2city = $this->getData($agreement, 'witness2city');
        $data->witness2pincode = $this->getData($agreement, 'witness2pincode');
        $data->witness2state = $this->getData($agreement, 'witness2state');

        // guarantor
        // $data->prefixoftheguarantor = $this->getData($agreement, 'prefixoftheguarantor');
        $data->guarantorfullname = $this->getData($agreement, 'guarantorfullname');
        $data->streetaddressoftheguarantor = $this->getData($agreement, 'streetaddressoftheguarantor');
        $data->cityoftheguarantor = $this->getData($agreement, 'cityoftheguarantor');
        $data->pincodeoftheguarantor = $this->getData($agreement, 'pincodeoftheguarantor');
        $data->stateoftheguarantor = $this->getData($agreement, 'stateoftheguarantor');
        $data->pancardnumberoftheguarantor = $this->getData($agreement, 'pancardnumberoftheguarantor');
        $data->officiallyvaliddocumentsoftheguarantor = $this->getData($agreement, 'officiallyvaliddocumentsoftheguarantor');
        $data->occupationoftheguarantor = $this->getData($agreement, 'occupationoftheguarantor');
        $data->residentstatusoftheguarantor = $this->getData($agreement, 'residentstatusoftheguarantor');
        $data->dateofbirthoftheguarantor = ($this->getData($agreement, 'dateofbirthoftheguarantor') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofbirthoftheguarantor'))) : '';
        $data->maritalstatusoftheguarantor = $this->getData($agreement, 'maritalstatusoftheguarantor');
        $data->highesteducationoftheguarantor = $this->getData($agreement, 'highesteducationoftheguarantor');
        $data->mobilenumberoftheguarantor = $this->getData($agreement, 'mobilenumberoftheguarantor');
        $data->emailidoftheguarantor = $this->getData($agreement, 'emailidoftheguarantor');

        // borrower
        $data->streetaddressoftheborrower = $this->getData($agreement, 'streetaddressoftheborrower');
        $data->pancardnumberoftheborrower = $this->getData($agreement, 'pancardnumberoftheborrower');
        $data->officiallyvaliddocumentsoftheborrower = $this->getData($agreement, 'officiallyvaliddocumentsoftheborrower');
        $data->occupationoftheborrower = $this->getData($agreement, 'occupationoftheborrower');
        $data->residentstatusoftheborrower = $this->getData($agreement, 'residentstatusoftheborrower');
        $data->dateofbirthoftheborrower = ($this->getData($agreement, 'dateofbirthoftheborrower') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofbirthoftheborrower'))) : '';
        $data->maritalstatusoftheborrower = $this->getData($agreement, 'maritalstatusoftheborrower');
        $data->highesteducationoftheborrower = $this->getData($agreement, 'highesteducationoftheborrower');
        $data->mobilenumberoftheborrower = $this->getData($agreement, 'mobilenumberoftheborrower');
        $data->emailidoftheborrower = $this->getData($agreement, 'emailidoftheborrower');

        // co-borrower 1
        // $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        $data->streetaddressofthecoborrower = $this->getData($agreement, 'streetaddressofthecoborrower');
        $data->pancardnumberofthecoborrower = $this->getData($agreement, 'pancardnumberofthecoborrower');
        $data->officiallyvaliddocumentsofthecoborrower = $this->getData($agreement, 'officiallyvaliddocumentsofthecoborrower');
        $data->occupationofthecoborrower = $this->getData($agreement, 'occupationofthecoborrower');
        $data->residentstatusofthecoborrower = $this->getData($agreement, 'residentstatusofthecoborrower');
        $data->dateofbirthofthecoborrower = ($this->getData($agreement, 'dateofbirthofthecoborrower') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofbirthofthecoborrower'))) : '';
        $data->maritalstatusofthecoborrower = $this->getData($agreement, 'maritalstatusofthecoborrower');
        $data->highesteducationofthecoborrower = $this->getData($agreement, 'highesteducationofthecoborrower');
        $data->mobilenumberofthecoborrower = $this->getData($agreement, 'mobilenumberofthecoborrower');
        $data->emailidofthecoborrower = $this->getData($agreement, 'emailidofthecoborrower');

        // co-borrower 2
        $data->prefixofthecoborrower2 = $this->getData($agreement, 'prefixofthecoborrower2');
        $data->nameofthecoborrower2 = $this->getData($agreement, 'nameofthecoborrower2');
        $data->streetaddressofthecoborrower2 = $this->getData($agreement, 'streetaddressofthecoborrower2');
        $data->pancardnumberofthecoborrower2 = $this->getData($agreement, 'pancardnumberofthecoborrower2');
        $data->officiallyvaliddocumentsofthecoborrower2 = $this->getData($agreement, 'officiallyvaliddocumentsofthecoborrower2');
        $data->occupationofthecoborrower2 = $this->getData($agreement, 'occupationofthecoborrower2');
        $data->residentstatusofthecoborrower2 = $this->getData($agreement, 'residentstatusofthecoborrower2');
        $data->dateofbirthofthecoborrower2 = ($this->getData($agreement, 'dateofbirthofthecoborrower2') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofbirthofthecoborrower2'))) : '';
        $data->maritalstatusofthecoborrower2 = $this->getData($agreement, 'maritalstatusofthecoborrower2');
        $data->highesteducationofthecoborrower2 = $this->getData($agreement, 'highesteducationofthecoborrower2');
        $data->mobilenumberofthecoborrower2 = $this->getData($agreement, 'mobilenumberofthecoborrower2');
        $data->emailidofthecoborrower2 = $this->getData($agreement, 'emailidofthecoborrower2');

        // agreement place & date
        $data->placeofagreement = $this->getData($agreement, 'placeofagreement');
        $data->dateofagreement = ($this->getData($agreement, 'dateofagreement') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofagreement'))) : '';

        // page 16
        $data->prefixoftheauthorisedsignatory = $this->getData($agreement, 'prefixoftheauthorisedsignatory');
        $data->nameoftheauthorisedsignatory = $this->getData($agreement, 'nameoftheauthorisedsignatory');

        // page 17
        $data->aadharcardnumberoftheborrower = $this->getData($agreement, 'aadharcardnumberoftheborrower');
        $data->votercardnumberoftheborrower = $this->getData($agreement, 'votercardnumberoftheborrower');
        $data->bankaccountnumberoftheborrower = $this->getData($agreement, 'bankaccountnumberoftheborrower');
        $data->drivinglicensenumberoftheborrower = $this->getData($agreement, 'drivinglicensenumberoftheborrower');
        $data->electricitybillnumberoftheborrower = $this->getData($agreement, 'electricitybillnumberoftheborrower');
        $data->passportnumberoftheborrower = $this->getData($agreement, 'passportnumberoftheborrower');

        $data->aadharcardnumberofthecoborrower = $this->getData($agreement, 'aadharcardnumberofthecoborrower');
        $data->votercardnumberofthecoborrower = $this->getData($agreement, 'votercardnumberofthecoborrower');
        $data->bankaccountnumberofthecoborrower = $this->getData($agreement, 'bankaccountnumberofthecoborrower');
        $data->drivinglicensenumberofthecoborrower = $this->getData($agreement, 'drivinglicensenumberofthecoborrower');
        $data->electricitybillnumberofthecoborrower = $this->getData($agreement, 'electricitybillnumberofthecoborrower');
        $data->passportnumberofthecoborrower = $this->getData($agreement, 'passportnumberofthecoborrower');

        $data->aadharcardnumberofthecoborrower2 = $this->getData($agreement, 'aadharcardnumberofthecoborrower2');
        $data->votercardnumberofthecoborrower2 = $this->getData($agreement, 'votercardnumberofthecoborrower2');
        $data->bankaccountnumberofthecoborrower2 = $this->getData($agreement, 'bankaccountnumberofthecoborrower2');
        $data->drivinglicensenumberofthecoborrower2 = $this->getData($agreement, 'drivinglicensenumberofthecoborrower2');
        $data->electricitybillnumberofthecoborrower2 = $this->getData($agreement, 'electricitybillnumberofthecoborrower2');
        $data->passportnumberofthecoborrower2 = $this->getData($agreement, 'passportnumberofthecoborrower2');

        $data->aadharcardnumberoftheguarantor = $this->getData($agreement, 'aadharcardnumberoftheguarantor');
        $data->votercardnumberoftheguarantor = $this->getData($agreement, 'votercardnumberoftheguarantor');
        $data->bankaccountnumberoftheguarantor = $this->getData($agreement, 'bankaccountnumberoftheguarantor');
        $data->drivinglicensenumberoftheguarantor = $this->getData($agreement, 'drivinglicensenumberoftheguarantor');
        $data->electricitybillnumberoftheguarantor = $this->getData($agreement, 'electricitybillnumberoftheguarantor');
        $data->passportnumberoftheguarantor = $this->getData($agreement, 'passportnumberoftheguarantor');

        // page 18
        $data->natureofloan = $this->getData($agreement, 'natureofloan');
        $data->nameofthecheckoffcompany = $this->getData($agreement, 'nameofthecheckoffcompany');
        $data->loanamountindigits = $this->getData($agreement, 'loanamountindigits');
        $data->loanamountindigitsinwords = $this->getData($agreement, 'loanamountindigitsinwords');
        $data->loanamountinlakh = $this->getData($agreement, 'loanamountinlakh');
        $data->loanamountinlakhinwords = $this->getData($agreement, 'loanamountinlakhinwords');
        $data->loanreferencenumber = $this->getData($agreement, 'loanreferencenumber');
        $data->purposeofloan = $this->getData($agreement, 'purposeofloan');
        $data->repaymenttenureinmonths = $this->getData($agreement, 'repaymenttenureinmonths');
        $data->rateofinterest = $this->getData($agreement, 'rateofinterest');
        $data->processingchargeinpercentage = $this->getData($agreement, 'processingchargeinpercentage');
        $data->documentationfee = $this->getData($agreement, 'documentationfee');
        $data->securitymargin = $this->getData($agreement, 'securitymargin');
        $data->guarantee = $this->getData($agreement, 'guarantee');
        $data->monthlyinstalmentsnumber = $this->getData($agreement, 'monthlyinstalmentsnumber');
        $data->monthlyemiindigits = $this->getData($agreement, 'monthlyemiindigits');
        $data->monthlyemiinwords = $this->getData($agreement, 'monthlyemiinwords');
        $data->paymentdeductionfrom = $this->getData($agreement, 'paymentdeductionfrom');

        // page 19
        $data->dateofcreditofemiintolendersbankaccount = $this->getData($agreement, 'dateofcreditofemiintolendersbankaccount');
        $data->otherdateofemicredit = $this->getData($agreement, 'otherdateofemicredit');
        $data->penalinterestpercentage = $this->getData($agreement, 'penalinterestpercentage');
        $data->savingsaccountnumberofborrower = $this->getData($agreement, 'savingsaccountnumberofborrower');
        $data->bankaddressofborrower = $this->getData($agreement, 'bankaddressofborrower');
        $data->beneficiarynameofborrowersbank = $this->getData($agreement, 'beneficiarynameofborrowersbank');
        $data->banknameofborrower = $this->getData($agreement, 'banknameofborrower');
        $data->branchnameofborrower = $this->getData($agreement, 'branchnameofborrower');
        $data->ifscodeofborrower = $this->getData($agreement, 'ifscodeofborrower');
        $data->insuranceofborrower = $this->getData($agreement, 'insuranceofborrower');

        // page 21
        $data->documentstobeattachedwithapplicationforloan = $this->getData($agreement, 'documentstobeattachedwithapplicationforloan');
        $data->otherdocumentstobeattachedwithapplicationforloan = $this->getData($agreement, 'otherdocumentstobeattachedwithapplicationforloan');

        // page 22
        $data->deedofpersonalguaranteedate = ($this->getData($agreement, 'deedofpersonalguaranteedate') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'deedofpersonalguaranteedate'))) : '';
        $data->deedofpledgeofmoveablepropertiessharesbondsdebenturesmutualfundsdate = $this->getData($agreement, 'deedofpledgeofmoveablepropertiessharesbondsdebenturesmutualfundsdate');
        $data->deedofmortgageofinmoveablepropertieslandhousewarehousedate = $this->getData($agreement, 'deedofmortgageofinmoveablepropertieslandhousewarehousedate');
        $data->powerofattorneydate = $this->getData($agreement, 'powerofattorneydate');
        $data->deedofassignmentinsurancepolicyfixeddepositdate = $this->getData($agreement, 'deedofassignmentinsurancepolicyfixeddepositdate');

        // page 23
        // for borrower
        $data->demandpromissorynoteforborrowerplace = $this->getData($agreement, 'demandpromissorynoteforborrowerplace');
        $data->demandpromissorynoteforborrowerdate = ($this->getData($agreement, 'demandpromissorynoteforborrowerdate') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'demandpromissorynoteforborrowerdate'))) : '';
        $data->demandpromissorynoteforborroweramount = $this->getData($agreement, 'demandpromissorynoteforborroweramount');

        // page 24
        $data->continuingsecurityletterdate1 = ($this->getData($agreement, 'continuingsecurityletterdate1') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'continuingsecurityletterdate1'))) : '';

        // page 25
        $data->undertakingcumindemnitydate = ($this->getData($agreement, 'undertakingcumindemnitydate') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'undertakingcumindemnitydate'))) : '';

        // page 27
        $data->sanctionletterdate = ($this->getData($agreement, 'sanctionletterdate') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'sanctionletterdate'))) : '';
        $data->letterofintentnumber = $this->getData($agreement, 'letterofintentnumber');

        // page 30
        $data->nachdeclarationforandonbehalfof = $this->getData($agreement, 'nachdeclarationforandonbehalfof');
        $data->nachdeclarationname1 = $this->getData($agreement, 'nachdeclarationname1');
        $data->nachdeclarationname2 = $this->getData($agreement, 'nachdeclarationname2');
        $data->nachdeclarationname3 = $this->getData($agreement, 'nachdeclarationname3');
        $data->nachdeclarationname4 = $this->getData($agreement, 'nachdeclarationname4');

        // page 31
        $data->postdatecheque1description = $this->getData($agreement, 'postdatecheque1description');
        $data->postdatecheque1chequenumber = $this->getData($agreement, 'postdatecheque1chequenumber');
        $data->postdatecheque1date = ($this->getData($agreement, 'postdatecheque1date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque1date'))) : '';
        $data->postdatecheque1amount = $this->getData($agreement, 'postdatecheque1amount');

        $data->postdatecheque2description = $this->getData($agreement, 'postdatecheque2description');
        $data->postdatecheque2chequenumber = $this->getData($agreement, 'postdatecheque2chequenumber');
        $data->postdatecheque2date = ($this->getData($agreement, 'postdatecheque2date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque2date'))) : '';
        $data->postdatecheque2amount = $this->getData($agreement, 'postdatecheque2amount');

        $data->postdatecheque3description = $this->getData($agreement, 'postdatecheque3description');
        $data->postdatecheque3chequenumber = $this->getData($agreement, 'postdatecheque3chequenumber');
        $data->postdatecheque3date = ($this->getData($agreement, 'postdatecheque3date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque3date'))) : '';
        $data->postdatecheque3amount = $this->getData($agreement, 'postdatecheque3amount');

        $data->postdatecheque4description = $this->getData($agreement, 'postdatecheque4description');
        $data->postdatecheque4chequenumber = $this->getData($agreement, 'postdatecheque4chequenumber');
        $data->postdatecheque4date = ($this->getData($agreement, 'postdatecheque4date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque4date'))) : '';
        $data->postdatecheque4amount = $this->getData($agreement, 'postdatecheque4amount');

        $data->postdatecheque5description = $this->getData($agreement, 'postdatecheque5description');
        $data->postdatecheque5chequenumber = $this->getData($agreement, 'postdatecheque5chequenumber');
        $data->postdatecheque5date = ($this->getData($agreement, 'postdatecheque5date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque5date'))) : '';
        $data->postdatecheque5amount = $this->getData($agreement, 'postdatecheque5amount');

        $data->postdatecheque6description = $this->getData($agreement, 'postdatecheque6description');
        $data->postdatecheque6chequenumber = $this->getData($agreement, 'postdatecheque6chequenumber');
        $data->postdatecheque6date = ($this->getData($agreement, 'postdatecheque6date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque6date'))) : '';
        $data->postdatecheque6amount = $this->getData($agreement, 'postdatecheque6amount');

        $data->postdatecheque7description = $this->getData($agreement, 'postdatecheque7description');
        $data->postdatecheque7chequenumber = $this->getData($agreement, 'postdatecheque7chequenumber');
        $data->postdatecheque7date = ($this->getData($agreement, 'postdatecheque7date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque7date'))) : '';
        $data->postdatecheque7amount = $this->getData($agreement, 'postdatecheque7amount');

        $data->postdatecheque8description = $this->getData($agreement, 'postdatecheque8description');
        $data->postdatecheque8chequenumber = $this->getData($agreement, 'postdatecheque8chequenumber');
        $data->postdatecheque8date = ($this->getData($agreement, 'postdatecheque8date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque8date'))) : '';
        $data->postdatecheque8amount = $this->getData($agreement, 'postdatecheque8amount');

        $data->postdatecheque9description = $this->getData($agreement, 'postdatecheque9description');
        $data->postdatecheque9chequenumber = $this->getData($agreement, 'postdatecheque9chequenumber');
        $data->postdatecheque9date = ($this->getData($agreement, 'postdatecheque9date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque9date'))) : '';
        $data->postdatecheque9amount = $this->getData($agreement, 'postdatecheque9amount');

        $data->postdatecheque10description = $this->getData($agreement, 'postdatecheque10description');
        $data->postdatecheque10chequenumber = $this->getData($agreement, 'postdatecheque10chequenumber');
        $data->postdatecheque10date = ($this->getData($agreement, 'postdatecheque10date') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'postdatecheque10date'))) : '';
        $data->postdatecheque10amount = $this->getData($agreement, 'postdatecheque10amount');

        // dd($data);

        return view('admin.agreement.dynamic.personal-loan-agreement', compact('data'));
    }

    // dynamic PDF after filling up data page 3
    public function showDynamicPdfPage3(Request $request, $borrowerId, $agreementId)
    {
        $data = (object)[];
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();

        $data->date = date('d-m-Y');
        $customBorrowerName = str_replace(' ', '-', strtolower($this->getData($agreement, 'nameoftheborrower')));
        $data->fileName = $customBorrowerName.'-personal-loan-agreement-page-3-rs-100-'.$data->date;
        $data->title = 'personal loan agreement';

        $data->dateofagreement = ($this->getData($agreement, 'dateofagreement') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofagreement'))) : '';
        $data->prefixoftheborrower = $this->getData($agreement, 'prefixoftheborrower');
        $data->nameoftheborrower = $this->getData($agreement, 'nameoftheborrower');
        $data->prefixofthecoborrower = $this->getData($agreement, 'prefixofthecoborrower');
        $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        $data->prefixoftheguarantor = $this->getData($agreement, 'prefixoftheguarantor');
        $data->nameoftheguarantor = $this->getData($agreement, 'nameoftheguarantor');
        $data->loanaccountnumber = $this->getData($agreement, 'loanaccountnumber');

        return view('admin.agreement.dynamic.personal-loan-agreement-page-3', compact('data'));
    }

    // dynamic PDF after filling up data page 24
    public function showDynamicPdfPage24(Request $request, $borrowerId, $agreementId)
    {
        $data = (object)[];
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();

        $data->date = date('d-m-Y');
        $customBorrowerName = str_replace(' ', '-', strtolower($this->getData($agreement, 'nameoftheborrower')));
        $data->fileName = $customBorrowerName.'-personal-loan-agreement-page-24-rs-10-'.$data->date;
        $data->title = 'personal loan agreement';

        $data->dateofagreement = ($this->getData($agreement, 'dateofagreement') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofagreement'))) : '';
        $data->prefixoftheborrower = $this->getData($agreement, 'prefixoftheborrower');
        $data->nameoftheborrower = $this->getData($agreement, 'nameoftheborrower');
        $data->prefixofthecoborrower = $this->getData($agreement, 'prefixofthecoborrower');
        $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        $data->prefixoftheguarantor = $this->getData($agreement, 'prefixoftheguarantor');
        $data->nameoftheguarantor = $this->getData($agreement, 'nameoftheguarantor');
        $data->loanaccountnumber = $this->getData($agreement, 'loanaccountnumber');

        return view('admin.agreement.dynamic.personal-loan-agreement-page-24', compact('data'));
    }

    // dynamic PDF after filling up data page 25
    public function showDynamicPdfPage25(Request $request, $borrowerId, $agreementId)
    {
        $data = (object)[];
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();

        $data->date = date('d-m-Y');
        $customBorrowerName = str_replace(' ', '-', strtolower($this->getData($agreement, 'nameoftheborrower')));
        $data->fileName = $customBorrowerName.'-personal-loan-agreement-page-25-rs-50-'.$data->date;
        $data->title = 'personal loan agreement';

        $data->dateofagreement = ($this->getData($agreement, 'dateofagreement') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofagreement'))) : '';
        $data->prefixoftheborrower = $this->getData($agreement, 'prefixoftheborrower');
        $data->nameoftheborrower = $this->getData($agreement, 'nameoftheborrower');
        $data->prefixofthecoborrower = $this->getData($agreement, 'prefixofthecoborrower');
        $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        $data->prefixoftheguarantor = $this->getData($agreement, 'prefixoftheguarantor');
        $data->nameoftheguarantor = $this->getData($agreement, 'nameoftheguarantor');
        $data->loanaccountnumber = $this->getData($agreement, 'loanaccountnumber');

        return view('admin.agreement.dynamic.personal-loan-agreement-page-25', compact('data'));
    }

    // dynamic PDF after filling up data page 31
    public function showDynamicPdfPage31(Request $request, $borrowerId, $agreementId)
    {
        $data = (object)[];
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();

        $data->date = date('d-m-Y');
        $customBorrowerName = str_replace(' ', '-', strtolower($this->getData($agreement, 'nameoftheborrower')));
        $data->fileName = $customBorrowerName.'-personal-loan-agreement-page-31-rs-10-'.$data->date;
        $data->title = 'personal loan agreement';

        $data->dateofagreement = ($this->getData($agreement, 'dateofagreement') != null) ? date('d-m-Y', strtotime($this->getData($agreement, 'dateofagreement'))) : '';
        $data->prefixoftheborrower = $this->getData($agreement, 'prefixoftheborrower');
        $data->nameoftheborrower = $this->getData($agreement, 'nameoftheborrower');
        $data->prefixofthecoborrower = $this->getData($agreement, 'prefixofthecoborrower');
        $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        $data->prefixoftheguarantor = $this->getData($agreement, 'prefixoftheguarantor');
        $data->nameoftheguarantor = $this->getData($agreement, 'nameoftheguarantor');
        $data->loanaccountnumber = $this->getData($agreement, 'loanaccountnumber');

        return view('admin.agreement.dynamic.personal-loan-agreement-page-31', compact('data'));
    }

    // dynamic PDF after filling up data
    public function showDynamicDOMPdf(Request $request, $borrowerId, $agreementId)
    {
        // $data = (object)[];
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();

        // $data->fileName = 'personal_loan_pdf';
        // $data->title = 'personal loan pdf';
        // $data->date = date('Y-m-d');
        // $data->customerid = $this->getData($agreement, 'customerid');
        // $data->nameoftheborrower = $this->getData($agreement, 'nameoftheborrower');
        // $data->nameofthecoborrower = $this->getData($agreement, 'nameofthecoborrower');
        // $data->nameoftheguarantor = $this->getData($agreement, 'nameoftheguarantor');
        // $data->loanapplicationnumber = $this->getData($agreement, 'loanapplicationnumber');
        // $data->loanaccountnumber = $this->getData($agreement, 'loanaccountnumber');
        // $data->signatureoftheauthorisedsignatory = $this->getData($agreement, 'signatureoftheauthorisedsignatory');
        // $data->signatureoftheborrower = $this->getData($agreement, 'signatureoftheborrower');
        // $data->signatureofthecoborrower = $this->getData($agreement, 'signatureofthecoborrower');

        $data = [
            'fileName' => 'personal_loan_pdf',
            'title' => 'personal loan pdf',
            'date' => date('Y-m-d'),
            'customerid' => $this->getData($agreement, 'customerid'),
            'nameoftheborrower' => $this->getData($agreement, 'nameoftheborrower'),
            'nameofthecoborrower' => $this->getData($agreement, 'nameofthecoborrower'),
            'nameoftheguarantor' => $this->getData($agreement, 'nameoftheguarantor'),
            'loanapplicationnumber' => $this->getData($agreement, 'loanapplicationnumber'),
            'loanaccountnumber' => $this->getData($agreement, 'loanaccountnumber'),
            'signatureoftheauthorisedsignatory' => $this->getData($agreement, 'signatureoftheauthorisedsignatory',true),
            'signatureoftheborrower' => $this->getData($agreement, 'signatureoftheborrower'),
            'signatureofthecoborrower' => $this->getData($agreement, 'signatureofthecoborrower'),
            // 'value' => $agreement
        ];

        // show in browser
        $viewhtml = View::make('admin.agreement.dynamic.personal-loan-agreement-old', $data)->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($viewhtml)->setPaper('a4', 'portrait');
        return $pdf->stream();
        // return view('admin.agreement.dynamic.personal-loan-agreement', compact('data'));
    }

    public function generateDynamicPdf(Request $request, $borrowerId, $agreementId)
    {
        $agreement = AgreementData::join('agreement_rfqs', 'agreement_data.rfq_id', '=', 'agreement_rfqs.id')->where('borrower_id', $borrowerId)->where('agreement_id', $agreementId)->get();
        $data = [
            'fileName' => 'personal_loan_pdf',
            'title' => 'personal loan pdf',
            'date' => date('Y-m-d'),
            'customerid' => $this->getData($agreement, 'customerid'),
            'nameoftheborrower' => $this->getData($agreement, 'nameoftheborrower'),
            'nameofthecoborrower' => $this->getData($agreement, 'nameofthecoborrower'),
            'nameoftheguarantor' => $this->getData($agreement, 'nameoftheguarantor'),
            'loanapplicationnumber' => $this->getData($agreement, 'loanapplicationnumber'),
            'loanaccountnumber' => $this->getData($agreement, 'loanaccountnumber'),
            'signatureoftheauthorisedsignatory' => $this->getData($agreement, 'signatureoftheauthorisedsignatory'),
            'signatureoftheborrower' => $this->getData($agreement, 'signatureoftheborrower'),
            'signatureofthecoborrower' => $this->getData($agreement, 'signatureofthecoborrower'),
            // 'value' => $agreement
        ];

        // download
        $pdf = PDF::loadView('admin.agreement.dynamic.personal-loan-agreement', $data)->setPaper('a4', 'portrait');
        return $pdf->download($data['fileName'].'.pdf');
    }
}
