<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->tinyInteger('type')->default(0)->comment('0 means header for fields, others means input types');
            $table->longText('value')->nullable()->comment('comma separated value of fields');
            $table->text('key_name')->nullable();
            $table->tinyInteger('required')->default(0)->comment('1 is required, 0 is not');
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        //name prefix, genders list, marital status
        $namePrefixList = 'Mr, Miss, Mrs, Prof, Dr, CA';
        $gendersList = 'Male, Female, Transgender, Rather not say';
        $maritalStatusList = 'Married, Unmarried, Single, Divorced, Widowed';
        // highest qualification list
        $highestQualificationList = 'AAS, BAS, B Arch, BA, BBA, MBA, DDS, DBA, MD, Pharm D, PhD, B Tech, M Tech, BCA, MCA';

        // valid document lists for borrower & co-borrower
        $validDocumentsList = 'Aadhar card, Voter card, Bank statement, Driving license, Electricity bill, Passport';

        // nature/ type of loan
        $natureOfLoan = 'Loan against salary, Loan against salary with check-off, Loan to Prefessional, Loan to Professional RLOC, Loan to Professional RLOC with check-off, Loan to Professional RLOC without check-off';

        // loan application documents to attach
        $loanApplicationDocumentsToAttach = 'Salary Certificate from current Employer, Proof of identity, Proof of current residential & official address, Latest three months&apos; Bank Statement (where salary / income is credited or accumulated), Salary slips for last three months preceding application date, Two passport size photographs, Certified copy of standing Instructions/ Signed ECS / ACH mandate/other relevant mandate to designated bank of the Borrower(s) to transfer to the Lender on the Due Dates the amounts which are required to be paid by the Borrower(s) as specified in terms of Repayment in Schedule II, Copies of last 2 years&apos; ITR, Signature Verification by banker (as per PFSL format), Proof of other income, Proof of assets (copy of registered deed of house property / statement of accounts of mutual fund / insurance policy / statement of demat account), Guarantor&apos;s net worth certificate (as per PFSL format)';

        // $loanApplicationDocumentsToAttach = 'Salary Certificate from current Employer, Proof of identity, Proof of current residential & official address, Latest three months&apos; Bank Statement (where salary / income is credited or accumulated), Salary slips for last three months preceding application date, Two passport size photographs, Certified copy of standing Instructions/ Signed ECS / ACH mandate/other relevant mandate to designated bank&#44; of the Borrower(s) to transfer to the Lender on the Due Dates&#44; the amounts which are required to be paid by the Borrower(s)&#44; as specified in terms of Repayment in Schedule II, Copies of last 2 years&apos; ITR, Signature Verification by banker (as per PFSL format), Proof of other income, Proof of assets (copy of registered deed of house property / statement of accounts of mutual fund / insurance policy / statement of demat account), Guarantor&apos;s net worth certificate (as per PFSL format)';

        // repatyment tenure in months
        $repaymentTenureValue = '';
        for($i = 1; $i < 61; $i++) {
            if ($i != 60) $repaymentTenureValue .= $i.', ';
            else $repaymentTenureValue .= $i;
        }

        // other date of emi credit
        $otherDateOfEmiCreditValue = '1st, 2nd, 3rd, 4th, 5th, 6th, 7th, 8th, 9th, 10th, 11th, 12th, 13th, 14th, 15th, 16th, 17th, 18th, 19th, 20th, 21st, 22nd, 23rd, 24th, 25th, 26th, 27th, 28th, 29th, 30th, 31st';

        // check of company lists
        $checkOffCompanyList = 'Peerless Sec, Peerless Hosp, Peerless Hotel, DIC Nadia, Peerless FPDL, Bengal Peerless, Peerless Gen & Fin, PS Group, Peerless Finance, Pathfinder, Smart Stainless, Indus Nursing Home, Shree Automative, United eServices, South Calcutta Girls&apos; Academy, Bichitra Holding, Sikha Holding';

        $data = [
            ['name' => 'Name of the authorised signatory', 'type' => 1, 'value' => '', 'key_name' => 'nameoftheauthorisedsignatory'],

            // borrower details
            ['name' => 'Customer ID', 'type' => 1, 'value' => '', 'key_name' => 'customerid'],
            ['name' => 'Prefix of the Borrower', 'type' => 7, 'value' => $namePrefixList, 'key_name' => 'prefixoftheborrower'],
            ['name' => 'Name of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'nameoftheborrower'],
            ['name' => 'Street address of the Borrower', 'type' => 10, 'value' => '', 'key_name' => 'streetaddressoftheborrower'],
            ['name' => 'PAN card number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'pancardnumberoftheborrower'],
            ['name' => 'Officially Valid Documents of the Borrower', 'type' => 9, 'value' => $validDocumentsList, 'key_name' => 'officiallyvaliddocumentsoftheborrower'],
            ['name' => 'Occupation of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'occupationoftheborrower'],
            ['name' => 'Resident status of the Borrower', 'type' => 9, 'value' => 'Permanent address, Communication address', 'key_name' => 'residentstatusoftheborrower'],
            ['name' => 'Date of birth of the Borrower', 'type' => 4, 'value' => '', 'key_name' => 'dateofbirthoftheborrower'],
            ['name' => 'Marital status of the Borrower', 'type' => 7, 'value' => $maritalStatusList, 'key_name' => 'maritalstatusoftheborrower'],
            ['name' => 'Highest education of the Borrower', 'type' => 7, 'value' => $highestQualificationList, 'key_name' => 'highesteducationoftheborrower'],
            ['name' => 'Mobile number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'mobilenumberoftheborrower'],
            ['name' => 'Email ID of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'emailidoftheborrower'],

            // co borrower 1 details
            ['name' => 'Prefix of the Co-Borrower', 'type' => 7, 'value' => $namePrefixList, 'key_name' => 'prefixofthecoborrower'],
            ['name' => 'Name of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'nameofthecoborrower'],
            ['name' => 'Street address of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'streetaddressofthecoborrower'],
            ['name' => 'Pincode of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'pincodeofthecoborrower'],
            ['name' => 'City of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'cityofthecoborrower'],
            ['name' => 'State of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'stateofthecoborrower'],
            ['name' => 'PAN card number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'pancardnumberofthecoborrower'],
            ['name' => 'Officially Valid Documents of the Co-Borrower', 'type' => 9, 'value' => $validDocumentsList, 'key_name' => 'officiallyvaliddocumentsofthecoborrower'],
            ['name' => 'Occupation of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'occupationofthecoborrower'],
            ['name' => 'Resident status of the Co-Borrower', 'type' => 9, 'value' => 'Permanent address, Communication address', 'key_name' => 'residentstatusofthecoborrower'],
            ['name' => 'Date of birth of the Co-Borrower', 'type' => 4, 'value' => '', 'key_name' => 'dateofbirthofthecoborrower'],
            ['name' => 'Marital status of the Co-Borrower', 'type' => 7, 'value' => $maritalStatusList, 'key_name' => 'maritalstatusofthecoborrower'],
            ['name' => 'Highest education of the Co-Borrower', 'type' => 7, 'value' => $highestQualificationList, 'key_name' => 'highesteducationofthecoborrower'],
            ['name' => 'Mobile number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'mobilenumberofthecoborrower'],
            ['name' => 'Email ID of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'emailidofthecoborrower'],

            // guarantor details
            ['name' => 'Prefix of the Guarantor', 'type' => 7, 'value' => $namePrefixList, 'key_name' => 'prefixoftheguarantor'],
            ['name' => 'Name of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'nameoftheguarantor'],
            ['name' => 'Street address of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'streetaddressoftheguarantor'],
            ['name' => 'Pincode of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'pincodeoftheguarantor'],
            ['name' => 'City of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'cityoftheguarantor'],
            ['name' => 'State of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'stateoftheguarantor'],
            ['name' => 'PAN card number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'pancardnumberoftheguarantor'],
            ['name' => 'Officially Valid Documents of the Guarantor', 'type' => 9, 'value' => $validDocumentsList, 'key_name' => 'officiallyvaliddocumentsoftheguarantor'],
            ['name' => 'Occupation of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'occupationoftheguarantor'],
            ['name' => 'Resident status of the Guarantor', 'type' => 9, 'value' => 'Permanent address, Communication address', 'key_name' => 'residentstatusoftheguarantor'],
            ['name' => 'Date of birth of the Guarantor', 'type' => 4, 'value' => '', 'key_name' => 'dateofbirthoftheguarantor'],
            ['name' => 'Marital status of the Guarantor', 'type' => 7, 'value' => $maritalStatusList, 'key_name' => 'maritalstatusoftheguarantor'],
            ['name' => 'Highest education of the Guarantor', 'type' => 7, 'value' => $highestQualificationList, 'key_name' => 'highesteducationoftheguarantor'],
            ['name' => 'Mobile number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'mobilenumberoftheguarantor'],
            ['name' => 'Email ID of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'emailidoftheguarantor'],

            // witness 1 details
            ['name' => 'Witness 1 Full name', 'type' => 1, 'value' => '', 'key_name' => 'witness1fullname'],
            ['name' => 'Witness 1 Street address', 'type' => 1, 'value' => '', 'key_name' => 'witness1streetaddress'],
            ['name' => 'Witness 1 City', 'type' => 1, 'value' => '', 'key_name' => 'witness1city'],
            ['name' => 'Witness 1 Pincode', 'type' => 1, 'value' => '', 'key_name' => 'witness1pincode'],
            ['name' => 'Witness 1 State', 'type' => 1, 'value' => '', 'key_name' => 'witness1state'],

            // witness 2 details
            ['name' => 'Witness 2 Full name', 'type' => 1, 'value' => '', 'key_name' => 'witness2fullname'],
            ['name' => 'Witness 2 Street address', 'type' => 1, 'value' => '', 'key_name' => 'witness2streetaddress'],
            ['name' => 'Witness 2 City', 'type' => 1, 'value' => '', 'key_name' => 'witness2city'],
            ['name' => 'Witness 2 Pincode', 'type' => 1, 'value' => '', 'key_name' => 'witness2pincode'],
            ['name' => 'Witness 2 State', 'type' => 1, 'value' => '', 'key_name' => 'witness2state'],

            // loan details
            // loan application number is loan reference number
            ['name' => 'Loan Account Number', 'type' => 1, 'value' => '', 'key_name' => 'loanaccountnumber'],
            ['name' => 'Letter of Intent Number', 'type' => 1, 'value' => '', 'key_name' => 'letterofintentnumber'],

            // agreement details
            ['name' => 'Place of agreement', 'type' => 1, 'value' => '', 'key_name' => 'placeofagreement'],
            ['name' => 'Date of agreement', 'type' => 4, 'value' => '', 'key_name' => 'dateofagreement'],

            // key facts of the loan
            ['name' => 'Nature of Loan', 'type' => 7, 'value' => $natureOfLoan, 'key_name' => 'natureofloan'],
            ['name' => 'Loan amount in digits', 'type' => 1, 'value' => '', 'key_name' => 'loanamountindigits'],
            ['name' => 'Loan amount in digits in words', 'type' => 1, 'value' => '', 'key_name' => 'loanamountindigitsinwords'],
            ['name' => 'Loan reference number', 'type' => 1, 'value' => '', 'key_name' => 'loanreferencenumber'],
            ['name' => 'Purpose of Loan', 'type' => 1, 'value' => '', 'key_name' => 'purposeofloan'],
            ['name' => 'Repayment tenure (in months)', 'type' => 7, 'value' => $repaymentTenureValue, 'key_name' => 'repaymenttenureinmonths'],
            ['name' => 'Rate of Interest', 'type' => 1, 'value' => '', 'key_name' => 'rateofinterest'],
            ['name' => 'Processing charge (in percentage)', 'type' => 1, 'value' => '', 'key_name' => 'processingchargeinpercentage'],
            ['name' => 'Documentation fee', 'type' => 1, 'value' => '', 'key_name' => 'documentationfee'],
            ['name' => 'Security & Margin', 'type' => 10, 'value' => '', 'key_name' => 'securitymargin'],
            ['name' => 'Guarantee', 'type' => 1, 'value' => '', 'key_name' => 'guarantee'],
            ['name' => 'Monthly instalments number', 'type' => 1, 'value' => '', 'key_name' => 'monthlyinstalmentsnumber'],
            ['name' => 'Monthly EMI', 'type' => 1, 'value' => '', 'key_name' => 'monthlyemiindigits'],
            ['name' => 'Monthly EMI in words', 'type' => 1, 'value' => '', 'key_name' => 'monthlyemiinwords'],
            ['name' => 'Payment deduction from', 'type' => 8, 'value' => 'deducted from the Borrower&apos;s salary by the Borrower&apos;s employer on monthly basis and credited into the Lender&apos;s bank Account, directly debited from the Borrower&apos;s bank Account and credited into lender&apos;s bank Account', 'key_name' => 'paymentdeductionfrom'],
            ['name' => 'Date of credit of EMI into Lender&apos;s Bank Account', 'type' => 9, 'value' => '5th of every month, 2nd of every month, Others', 'key_name' => 'dateofcreditofemiintolendersbankaccount'],
            ['name' => 'Other date of EMI credit', 'type' => 7, 'value' => $otherDateOfEmiCreditValue, 'key_name' => 'otherdateofemicredit'],
            ['name' => 'Penal Interest percentage', 'type' => 1, 'value' => '', 'key_name' => 'penalinterestpercentage'],
            ['name' => 'Beneficiary Name of Borrower&apos;s bank', 'type' => 1, 'value' => '', 'key_name' => 'beneficiarynameofborrowersbank'],
            ['name' => 'Savings account number of Borrower', 'type' => 1, 'value' => '', 'key_name' => 'savingsaccountnumberofborrower'],
            ['name' => 'Bank Name of Borrower', 'type' => 1, 'value' => '', 'key_name' => 'banknameofborrower'],
            ['name' => 'Branch Name of Borrower', 'type' => 1, 'value' => '', 'key_name' => 'branchnameofborrower'],
            ['name' => 'Bank address of Borrower', 'type' => 1, 'value' => '', 'key_name' => 'bankaddressofborrower'],
            ['name' => 'IFS code of Borrower', 'type' => 1, 'value' => '', 'key_name' => 'ifscodeofborrower'],
            ['name' => 'Insurance of Borrower', 'type' => 10, 'value' => '', 'key_name' => 'insuranceofborrower'],
            ['name' => 'Documents to be attached with application for loan', 'type' => 8, 'value' => $loanApplicationDocumentsToAttach, 'key_name' => 'documentstobeattachedwithapplicationforloan'],
            ['name' => 'Other documents to be attached with application for loan', 'type' => 1, 'value' => $loanApplicationDocumentsToAttach, 'key_name' => 'otherdocumentstobeattachedwithapplicationforloan'],
            // Personal loan facility agreement dated


            ['name' => 'Deed of Personal Guarantee date', 'type' => 4, 'value' => '', 'key_name' => 'deedofpersonalguaranteedate'],

            // DEMAND PROMISSORY NOTE
            ['name' => 'Demand Promissory Note for borrower Place', 'type' => 1, 'value' => '', 'key_name' => 'demandpromissorynoteforborrowerplace'],
            ['name' => 'Demand Promissory Note for borrower Date', 'type' => 4, 'value' => '', 'key_name' => 'demandpromissorynoteforborrowerdate'],
            ['name' => 'Demand Promissory Note for borrower Amount', 'type' => 1, 'value' => '', 'key_name' => 'demandpromissorynoteforborroweramount'],

            // CONTINUING SECURITY LETTER
            ['name' => 'Continuing Security Letter Date 1', 'type' => 4, 'value' => '', 'key_name' => 'continuingsecurityletterdate1'],

            // UNDERTAKING CUM INDEMNITY
            ['name' => 'Undertaking Cum Indemnity Date', 'type' => 4, 'value' => '', 'key_name' => 'undertakingcumindemnitydate'],



            // OVDs : Aadhar card, Voter card, Bank statement, Driving license, Electricity bill, Passport
            // borrower valid documents information starts
            ['name' => 'Aadhar card number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'aadharcardnumberoftheborrower'],
            ['name' => 'Voter card number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'votercardnumberoftheborrower'],
            ['name' => 'Bank account number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'bankaccountnumberoftheborrower'],
            ['name' => 'Bank name of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'banknameoftheborrower'],
            ['name' => 'Bank IFSC of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'bankifscoftheborrower'],
            ['name' => 'Driving license number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'drivinglicensenumberoftheborrower'],
            ['name' => 'Driving license issue date of the Borrower', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseissuedateoftheborrower'],
            ['name' => 'Driving license expiry date of the Borrower', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseexpirydateoftheborrower'],
            ['name' => 'Electricity bill number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'electricitybillnumberoftheborrower'],
            ['name' => 'Passport number of the Borrower', 'type' => 1, 'value' => '', 'key_name' => 'passportnumberoftheborrower'],
            ['name' => 'Passport issue date of the Borrower', 'type' => 4, 'value' => '', 'key_name' => 'passportissuedateoftheborrower'],
            ['name' => 'Passport expiry date of the Borrower', 'type' => 4, 'value' => '', 'key_name' => 'passportexpirydateoftheborrower'],
            // borrower valid documents information ends

            // co-borrower valid documents information starts
            ['name' => 'Aadhar card number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'aadharcardnumberofthecoborrower'],
            ['name' => 'Voter card number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'votercardnumberofthecoborrower'],
            ['name' => 'Bank account number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'bankaccountnumberofthecoborrower'],
            ['name' => 'Bank name of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'banknameofthecoborrower'],
            ['name' => 'Bank IFSC of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'bankifscofthecoborrower'],
            ['name' => 'Driving license number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'drivinglicensenumberofthecoborrower'],
            ['name' => 'Driving license issue date of the Co-Borrower', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseissuedateofthecoborrower'],
            ['name' => 'Driving license expiry date of the Co-Borrower', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseexpirydateofthecoborrower'],
            ['name' => 'Electricity bill number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'electricitybillnumberofthecoborrower'],
            ['name' => 'Passport number of the Co-Borrower', 'type' => 1, 'value' => '', 'key_name' => 'passportnumberofthecoborrower'],
            ['name' => 'Passport issue date of the Co-Borrower', 'type' => 4, 'value' => '', 'key_name' => 'passportissuedateofthecoborrower'],
            ['name' => 'Passport expiry date of the Co-Borrower', 'type' => 4, 'value' => '', 'key_name' => 'passportexpirydateofthecoborrower'],
            // co-borrower valid documents information ends

            // guarantor valid documents information starts
            ['name' => 'Aadhar card number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'aadharcardnumberoftheguarantor'],
            ['name' => 'Voter card number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'votercardnumberoftheguarantor'],
            ['name' => 'Bank account number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'bankaccountnumberoftheguarantor'],
            ['name' => 'Bank name of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'banknameoftheguarantor'],
            ['name' => 'Bank IFSC of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'bankifscoftheguarantor'],
            ['name' => 'Driving license number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'drivinglicensenumberoftheguarantor'],
            ['name' => 'Driving license issue date of the Guarantor', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseissuedateoftheguarantor'],
            ['name' => 'Driving license expiry date of the Guarantor', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseexpirydateoftheguarantor'],
            ['name' => 'Electricity bill number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'electricitybillnumberoftheguarantor'],
            ['name' => 'Passport number of the Guarantor', 'type' => 1, 'value' => '', 'key_name' => 'passportnumberoftheguarantor'],
            ['name' => 'Passport issue date of the Guarantor', 'type' => 4, 'value' => '', 'key_name' => 'passportissuedateoftheguarantor'],
            ['name' => 'Passport expiry date of the Guarantor', 'type' => 4, 'value' => '', 'key_name' => 'passportexpirydateoftheguarantor'],
            // guarantor valid documents information ends

            // co borrower 2 details
            ['name' => 'Prefix of the Co-Borrower 2', 'type' => 7, 'value' => $namePrefixList, 'key_name' => 'prefixofthecoborrower2'],
            ['name' => 'Name of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'nameofthecoborrower2'],
            ['name' => 'Street address of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'streetaddressofthecoborrower2'],
            ['name' => 'Pincode of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'pincodeofthecoborrower2'],
            ['name' => 'City of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'cityofthecoborrower2'],
            ['name' => 'State of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'stateofthecoborrower2'],
            ['name' => 'PAN card number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'pancardnumberofthecoborrower2'],
            ['name' => 'Officially Valid Documents of the Co-Borrower 2', 'type' => 9, 'value' => $validDocumentsList, 'key_name' => 'officiallyvaliddocumentsofthecoborrower2'],
            ['name' => 'Occupation of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'occupationofthecoborrower2'],
            ['name' => 'Resident status of the Co-Borrower 2', 'type' =>  9, 'value' => 'Permanent address, Communication address', 'key_name' => 'residentstatusofthecoborrower2'],
            ['name' => 'Date of birth of the Co-Borrower 2', 'type' => 4, 'value' => '', 'key_name' => 'dateofbirthofthecoborrower2'],
            ['name' => 'Marital status of the Co-Borrower 2', 'type' => 7, 'value' => $maritalStatusList, 'key_name' => 'maritalstatusofthecoborrower2'],
            ['name' => 'Highest education of the Co-Borrower 2', 'type' => 7, 'value' => $highestQualificationList, 'key_name' => 'highesteducationofthecoborrower2'],
            ['name' => 'Mobile number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'mobilenumberofthecoborrower2'],
            ['name' => 'Email ID of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'emailidofthecoborrower2'],

            // co-borrower 2 valid documents information starts
            ['name' => 'Aadhar card number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'aadharcardnumberofthecoborrower2'],
            ['name' => 'Voter card number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'votercardnumberofthecoborrower2'],
            ['name' => 'Bank account number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'bankaccountnumberofthecoborrower2'],
            ['name' => 'Bank name of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'banknameofthecoborrower2'],
            ['name' => 'Bank IFSC of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'bankifscofthecoborrower2'],
            ['name' => 'Driving license number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'drivinglicensenumberofthecoborrower2'],
            ['name' => 'Driving license issue date of the Co-Borrower 2', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseissuedateofthecoborrower2'],
            ['name' => 'Driving license expiry date of the Co-Borrower 2', 'type' => 4, 'value' => '', 'key_name' => 'drivinglicenseexpirydateofthecoborrower2'],
            ['name' => 'Electricity bill number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'electricitybillnumberofthecoborrower2'],
            ['name' => 'Passport number of the Co-Borrower 2', 'type' => 1, 'value' => '', 'key_name' => 'passportnumberofthecoborrower2'],
            ['name' => 'Passport issue date of the Co-Borrower 2', 'type' => 4, 'value' => '', 'key_name' => 'passportissuedateofthecoborrower2'],
            ['name' => 'Passport expiry date of the Co-Borrower 2', 'type' => 4, 'value' => '', 'key_name' => 'passportexpirydateofthecoborrower2'],
            // co-borrower valid documents information ends

            // id starts from 155
            ['name' => 'Prefix of the authorised signatory', 'type' => 7, 'value' => $namePrefixList, 'key_name' => 'prefixoftheauthorisedsignatory'],
            ['name' => 'Sanction letter date', 'type' => 4, 'value' => '', 'key_name' => 'sanctionletterdate'],
            // this field is used, only if nature of loan is Loan against salary with check -off selected
            ['name' => 'Name of the check-off Company', 'type' => 7, 'value' => $checkOffCompanyList, 'key_name' => 'nameofthecheckoffcompany'],

            // post dates cheques information starts
            ['name' => 'Post date cheque 1', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque1'],
            ['name' => 'Post date cheque 2', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque2'],
            ['name' => 'Post date cheque 3', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque3'],
            ['name' => 'Post date cheque 4', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque4'],
            ['name' => 'Post date cheque 5', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque5'],
            ['name' => 'Post date cheque 6', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque6'],
            ['name' => 'Post date cheque 7', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque7'],
            ['name' => 'Post date cheque 8', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque8'],
            ['name' => 'Post date cheque 9', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque9'],
            ['name' => 'Post date cheque 10', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque10'],

            ['name' => 'Post date cheque 1 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque1description'],
            ['name' => 'Post date cheque 1 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque1chequenumber'],
            ['name' => 'Post date cheque 1 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque1date'],
            ['name' => 'Post date cheque 1 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque1amount'],

            ['name' => 'Post date cheque 2 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque2description'],
            ['name' => 'Post date cheque 2 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque2chequenumber'],
            ['name' => 'Post date cheque 2 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque2date'],
            ['name' => 'Post date cheque 2 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque2amount'],

            ['name' => 'Post date cheque 3 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque3description'],
            ['name' => 'Post date cheque 3 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque3chequenumber'],
            ['name' => 'Post date cheque 3 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque3date'],
            ['name' => 'Post date cheque 3 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque3amount'],

            ['name' => 'Post date cheque 4 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque4description'],
            ['name' => 'Post date cheque 4 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque4chequenumber'],
            ['name' => 'Post date cheque 4 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque4date'],
            ['name' => 'Post date cheque 4 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque4amount'],

            ['name' => 'Post date cheque 5 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque5description'],
            ['name' => 'Post date cheque 5 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque5chequenumber'],
            ['name' => 'Post date cheque 5 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque5date'],
            ['name' => 'Post date cheque 5 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque5amount'],

            ['name' => 'Post date cheque 6 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque6description'],
            ['name' => 'Post date cheque 6 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque6chequenumber'],
            ['name' => 'Post date cheque 6 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque6date'],
            ['name' => 'Post date cheque 6 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque6amount'],

            ['name' => 'Post date cheque 7 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque7description'],
            ['name' => 'Post date cheque 7 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque7chequenumber'],
            ['name' => 'Post date cheque 7 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque7date'],
            ['name' => 'Post date cheque 7 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque7amount'],

            ['name' => 'Post date cheque 8 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque8description'],
            ['name' => 'Post date cheque 8 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque8chequenumber'],
            ['name' => 'Post date cheque 8 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque8date'],
            ['name' => 'Post date cheque 8 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque8amount'],

            ['name' => 'Post date cheque 9 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque9description'],
            ['name' => 'Post date cheque 9 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque9chequenumber'],
            ['name' => 'Post date cheque 9 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque9date'],
            ['name' => 'Post date cheque 9 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque9amount'],

            ['name' => 'Post date cheque 10 description', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque10description'],
            ['name' => 'Post date cheque 10 cheque number', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque10chequenumber'],
            ['name' => 'Post date cheque 10 date', 'type' => 4, 'value' => '', 'key_name' => 'postdatecheque10date'],
            ['name' => 'Post date cheque 10 amount', 'type' => 1, 'value' => '', 'key_name' => 'postdatecheque10amount'],
            // post dates cheques information ends

            // id starts from 208
            ['name' => 'Sanction letter number', 'type' => 1, 'value' => '', 'key_name' => 'sanctionletternumber'],
            ['name' => 'Loan application number', 'type' => 1, 'value' => '', 'key_name' => 'loanapplicationnumber'],
        ];

        DB::table('fields')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
