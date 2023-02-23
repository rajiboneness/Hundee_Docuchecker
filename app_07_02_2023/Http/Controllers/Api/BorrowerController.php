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
use App\Models\BorrowerAgreement;
use App\Models\AgreementRfq;

class BorrowerController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function borrowerCreate(Request $request) {
        $validate = validator()->make($request->all(), [
            // 'auth_user_id' => 'required|integer|min:1',
            // 'auth_user_emp_id' => 'required|string|min:1|exists:users,emp_id',
            'application_id' => 'required|unique:borrowers',
            'agreement_id' => 'nullable|integer|min:1',
            'name_prefix' => 'nullable|string|min:1|max:50',
            'first_name' => 'required|string|min:1|max:200',
            'middle_name' => 'nullable|string|min:1|max:200',
            'last_name' => 'required|string|min:1|max:200',
            'gender' => 'nullable|string|min:1|max:30',
            'date_of_birth' => 'nullable|date',
            'email' => 'nullable|string|email',
            'mobile' => 'nullable|integer|digits:10',
            //'pan_card_number' => 'required|string|min:10|max:10',
            'occupation' => 'nullable|string|min:1|max:200',
            'marital_status' => 'nullable|string|min:1|max:30',

            'KYC_HOUSE_NO' => 'nullable|string|min:1|max:200',
            'KYC_Street' => 'nullable|string|min:1|max:200',
            'KYC_LOCALITY' => 'nullable|string|min:1|max:200',
            'KYC_CITY' => 'nullable|string|min:1|max:200',
            'KYC_State' => 'nullable|string|min:1|max:200',
            'KYC_PINCODE' => 'nullable|string|min:1|max:200',
            'KYC_Country' => 'nullable|string|min:1|max:200',

            'Aadhar_Number' => 'nullable|integer|digits:12',
        ], [
            // 'auth_user_emp_id.exists' => 'Auth user employee id is invalid',
            'name_prefix.*' => 'The name prefix field is invalid. Please provide the name prefix as Mr, Miss, Mrs, Prof, Dr, CA',
            'gender.*' => 'The gender field is invalid. Please provide the gender as Male, Female, Transgender, Rather not say',
            'marital_status.*' => 'The marital status field is invalid. Please provide the marital status as Married, Unmarried, Single, Divorced, Widowed',
            'pan_card_number.min' => 'Please provide a valid 10 digit Pan Card number',
            'pan_card_number.max' => 'Please provide a valid 10 digit Pan Card number',
            'pan_card_number.unique' => 'The pan card number is already used'
        ]);

        if (!$validate->fails()) {
            DB::beginTransaction();

            try {
                $user = new Borrower;
                $user->CUSTOMER_ID = 0;
                $user->application_id = $request->application_id;
                $user->name_prefix = $request->name_prefix;
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->full_name = $request->first_name . ($request->middle_name ? ' ' . $request->middle_name : null) . ' ' . $request->last_name;
                $user->gender = $request->gender;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->occupation = $request->occupation;
                $user->date_of_birth = $request->date_of_birth;
                $user->marital_status = $request->marital_status;
                $user->image_path = $request->image_path ? $request->image_path : null;
                $user->signature_path = $request->signature_path ? $request->signature_path : null;
                $user->street_address = $request->street_address ? $request->street_address : null;
                $user->city = $request->city ? $request->city : null;
                $user->pincode = $request->pincode ? $request->pincode : null;
                $user->state = $request->state ? $request->state : null;
                $user->pan_card_number = strtoupper($request->pan_card_number);

                // $user->uploaded_by = $request->auth_user_id;

                $user->Customer_Type = $request->Customer_Type ? $request->Customer_Type : null;
                $user->Resident_Status = $request->Resident_Status ? $request->Resident_Status : null;
                $user->Aadhar_Number = $request->Aadhar_Number ? $request->Aadhar_Number : null;
                $user->Main_Constitution = $request->Main_Constitution ? $request->Main_Constitution : null;

                $user->Sub_Constitution = $request->Sub_Constitution ? $request->Sub_Constitution : null;
                $user->KYC_Date = $request->KYC_Date ? $request->KYC_Date : null;
                $user->Re_KYC_Due_Date = $request->Re_KYC_Due_Date ? $request->Re_KYC_Due_Date : null;
                $user->Minor = $request->Minor ? $request->Minor : null;
                $user->Customer_Category = $request->Customer_Category ? $request->Customer_Category : null;
                $user->Alternate_Mobile_No = $request->Alternate_Mobile_No ? $request->Alternate_Mobile_No : null;
                $user->Telephone_No = $request->Telephone_No ? $request->Telephone_No : null;

                $user->Office_Telephone_No = $request->Office_Telephone_No ? $request->Office_Telephone_No: null;
                $user->FAX_No = $request->FAX_No ? $request->FAX_No: null;
                $user->Preferred_Language = $request->Preferred_Language ? $request->Preferred_Language: null;
                $user->REMARKS = $request->REMARKS ? $request->REMARKS: null;
                $user->KYC_Care_of = $request->KYC_Care_of ? $request->KYC_Care_of: null;
                $user->KYC_HOUSE_NO = $request->KYC_HOUSE_NO ? $request->KYC_HOUSE_NO: null;
                $user->KYC_LANDMARK = $request->KYC_LANDMARK ? $request->KYC_LANDMARK: null;
                $user->KYC_Street = $request->KYC_Street ? $request->KYC_Street: null;
                $user->KYC_LOCALITY = $request->KYC_LOCALITY ? $request->KYC_LOCALITY: null;
                $user->KYC_PINCODE = $request->KYC_PINCODE ? $request->KYC_PINCODE: null;
                $user->KYC_Country = $request->KYC_Country ? $request->KYC_Country: null;
                $user->KYC_State = $request->KYC_State ? $request->KYC_State: null;
                $user->KYC_District = $request->KYC_District ? $request->KYC_District: null;
                $user->KYC_POST_OFFICE = $request->KYC_POST_OFFICE ? $request->KYC_POST_OFFICE: null;
                $user->KYC_CITY = $request->KYC_CITY ? $request->KYC_CITY: null;
                $user->KYC_Taluka = $request->KYC_Taluka ? $request->KYC_Taluka: null;
                $user->KYC_Population_Group = $request->KYC_Population_Group ? $request->KYC_Population_Group: null;


                $user->COMM_Care_of = $request->COMM_Care_of ? $request->COMM_Care_of : null;
                $user->COMM_HOUSE_NO = $request->COMM_HOUSE_NO ? $request->COMM_HOUSE_NO : null;
                $user->COMM_LANDMARK = $request->COMM_LANDMARK ? $request->COMM_LANDMARK : null;
                $user->COMM_Street = $request->COMM_Street ? $request->COMM_Street : null;
                $user->COMM_LOCALITY = $request->COMM_LOCALITY ? $request->COMM_LOCALITY : null;
                $user->COMM_PINCODE = $request->COMM_PINCODE ? $request->COMM_PINCODE : null;
                $user->COMM_Country = $request->COMM_Country ? $request->COMM_Country : null;
                $user->COMM_State = $request->COMM_State ? $request->COMM_State : null;
                $user->COMM_District = $request->COMM_District ? $request->COMM_District : null;
                $user->COMM_POST_OFFICE = $request->COMM_POST_OFFICE ? $request->COMM_POST_OFFICE : null;
                $user->COMM_CITY = $request->COMM_CITY ? $request->COMM_CITY : null;
                $user->COMM_Taluka = $request->COMM_Taluka ? $request->COMM_Taluka : null;
                $user->COMM_Population_Group = $request->COMM_Population_Group ? $request->COMM_Population_Group : null;
                $user->Social_Media = $request->Social_Media ? $request->Social_Media : null;
                $user->Social_Media_ID = $request->Social_Media_ID ? $request->Social_Media_ID : null;
                $user->PROFESSION = $request->PROFESSION ? $request->PROFESSION : null;
                $user->EDUCATION = $request->EDUCATION ? $request->EDUCATION : null;
                $user->ORGANISATION_NAME = $request->ORGANISATION_NAME ? $request->ORGANISATION_NAME : null;
                $user->NET_INCOME = $request->NET_INCOME ? $request->NET_INCOME : null;

                $user->NET_EXPENSE = $request->NET_EXPENSE ? $request->NET_EXPENSE : null;
                $user->NET_SAVINGS = $request->NET_SAVINGS ? $request->NET_SAVINGS : null;
                $user->Years_in_Organization = $request->Years_in_Organization ? $request->Years_in_Organization : null;
                $user->CIBIL_SCORE = $request->CIBIL_SCORE ? $request->CIBIL_SCORE : null;
                $user->PERSONAL_LOAN_SCORE = $request->PERSONAL_LOAN_SCORE ? $request->PERSONAL_LOAN_SCORE : null;
                $user->GST_EXEMPTED = $request->GST_EXEMPTED ? $request->GST_EXEMPTED : null;
                $user->RM_EMP_ID = $request->RM_EMP_ID ? $request->RM_EMP_ID : null;
                $user->RM_Designation = $request->RM_Designation ? $request->RM_Designation : null;
                $user->RM_TITLE = $request->RM_TITLE ? $request->RM_TITLE : null;
                $user->RM_NAME = $request->RM_NAME ? $request->RM_NAME : null;
                $user->RM_Landline_No = $request->RM_Landline_No ? $request->RM_Landline_No : null;
                $user->RM_MOBILE_NO = $request->RM_MOBILE_NO ? $request->RM_MOBILE_NO : null;
                $user->RM_EMAIL_ID = $request->RM_EMAIL_ID ? $request->RM_EMAIL_ID : null;

                $user->DSA_ID = $request->DSA_ID ? $request->DSA_ID : null;
                $user->DSA_NAME = $request->DSA_NAME ? $request->DSA_NAME : null;
                $user->DSA_LANDLINE_NO = $request->DSA_LANDLINE_NO ? $request->DSA_LANDLINE_NO : null;
                $user->DSA_MOBILE_NO = $request->DSA_MOBILE_NO ? $request->DSA_MOBILE_NO : null;
                $user->DSA_EMAIL_ID = $request->DSA_EMAIL_ID ? $request->DSA_EMAIL_ID : null;
                $user->GIR_NO = $request->GIR_NO ? $request->GIR_NO : null;
                $user->RATION_CARD_NO = $request->RATION_CARD_NO ? $request->RATION_CARD_NO : null;
                $user->DRIVING_LINC = $request->DRIVING_LINC ? $request->DRIVING_LINC : null;
                $user->NPR_NO = $request->NPR_NO ? $request->NPR_NO : null;
                $user->PASSPORT_NO = $request->PASSPORT_NO ? $request->PASSPORT_NO : null;
                $user->EXPORTER_CODE = $request->EXPORTER_CODE ? $request->EXPORTER_CODE : null;
                $user->GST_NO = $request->GST_NO ? $request->GST_NO : null;
                $user->Voter_ID = $request->Voter_ID ? $request->Voter_ID : null;
                $user->CUSTM_2 = $request->CUSTM_2 ? $request->CUSTM_2 : null;
                $user->CATEGORY = $request->CATEGORY ? $request->CATEGORY : null;
                $user->RELIGION = $request->RELIGION ? $request->RELIGION : null;
                $user->MINORITY_STATUS = $request->MINORITY_STATUS ? $request->MINORITY_STATUS : null;
                $user->CASTE = $request->CASTE ? $request->CASTE : null;
                $user->SUB_CAST = $request->SUB_CAST ? $request->SUB_CAST : null;
                $user->RESERVATION_TYP = $request->RESERVATION_TYP ? $request->RESERVATION_TYP : null;
                $user->Physically_Challenged = $request->Physically_Challenged ? $request->Physically_Challenged : null;
                $user->Weaker_Section = $request->Weaker_Section ? $request->Weaker_Section : null;
                $user->Valued_Customer = $request->Valued_Customer ? $request->Valued_Customer : null;
                $user->Special_Category_1 = $request->Special_Category_1 ? $request->Special_Category_1 : null;
                $user->Vip_Category = $request->Vip_Category ? $request->Vip_Category : null;
                $user->Special_Category_2 = $request->Special_Category_2 ? $request->Special_Category_2 : null;
                $user->Senior_Citizen = $request->Senior_Citizen ? $request->Senior_Citizen : null;
                $user->Senior_Citizen_From = $request->Senior_Citizen_From ? $request->Senior_Citizen_From : null;
                $user->NO_OF_DEPEND = $request->NO_OF_DEPEND ? $request->NO_OF_DEPEND : null;
                $user->SPOUSE = $request->SPOUSE ? $request->SPOUSE : null;
                $user->CHILDREN = $request->CHILDREN ? $request->CHILDREN : null;

                $user->PARENTS = $request->PARENTS ? $request->PARENTS : null;
                $user->Employee_Staus = $request->Employee_Staus ? $request->Employee_Staus : null;
                $user->Employee_No = $request->Employee_No ? $request->Employee_No : null;
                $user->EMP_Date = $request->EMP_Date ? $request->EMP_Date : null;
                $user->Nature_of_Occupation = $request->Nature_of_Occupation ? $request->Nature_of_Occupation : null;
                $user->EMPLYEER_NAME = $request->EMPLYEER_NAME ? $request->EMPLYEER_NAME : null;
                $user->Role = $request->Role ? $request->Role : null;
                $user->SPECIALIZATION = $request->SPECIALIZATION ? $request->SPECIALIZATION : null;
                $user->EMP_GRADE = $request->EMP_GRADE ? $request->EMP_GRADE : null;
                $user->DESIGNATION = $request->DESIGNATION ? $request->DESIGNATION : null;
                $user->Office_Address = $request->Office_Address ? $request->Office_Address : null;
                $user->Office_Phone = $request->Office_Phone ? $request->Office_Phone : null;
                $user->Office_EXTENSION = $request->Office_EXTENSION ? $request->Office_EXTENSION : null;
                $user->Office_Fax = $request->Office_Fax ? $request->Office_Fax : null;
                $user->Office_MOBILE = $request->Office_MOBILE ? $request->Office_MOBILE : null;
                $user->Office_PINCODE = $request->Office_PINCODE ? $request->Office_PINCODE : null;
                $user->Office_CITY = $request->Office_CITY ? $request->Office_CITY : null;
                $user->Working_Since = $request->Working_Since ? $request->Working_Since : null;
                $user->Working_in_Current_company_Yrs = $request->Working_in_Current_company_Yrs ? $request->Working_in_Current_company_Yrs : null;
                $user->RETIRE_AGE = $request->RETIRE_AGE ? $request->RETIRE_AGE : null;
                $user->Nature_of_Business = $request->Nature_of_Business ? $request->Nature_of_Business : null;

                $user->Annual_Income = $request->Annual_Income ? $request->Annual_Income : null;
                $user->Prof_Self_Employed = $request->Prof_Self_Employed ? $request->Prof_Self_Employed : null;
                $user->Prof_Self_Annual_Income = $request->Prof_Self_Annual_Income ? $request->Prof_Self_Annual_Income : null;
                $user->IT_RETURN_YR1 = $request->IT_RETURN_YR1 ? $request->IT_RETURN_YR1 : null;
                $user->INCOME_DECLARED1 = $request->INCOME_DECLARED1 ? $request->INCOME_DECLARED1 : null;
                $user->TAX_PAID = $request->TAX_PAID ? $request->TAX_PAID : null;
                $user->REFUND_CLAIMED1 = $request->REFUND_CLAIMED1 ? $request->REFUND_CLAIMED1 : null;
                $user->IT_RETURN_YR2 = $request->IT_RETURN_YR2 ? $request->IT_RETURN_YR2 : null;
                $user->INCOME_DECLARED2 = $request->INCOME_DECLARED2 ? $request->INCOME_DECLARED2 : null;
                $user->TAX_PAID2 = $request->TAX_PAID2 ? $request->TAX_PAID2 : null;
                $user->REFUND_CLAIMED2 = $request->REFUND_CLAIMED2 ? $request->REFUND_CLAIMED2 : null;
                $user->IT_RETURN_YR3 = $request->IT_RETURN_YR3 ? $request->IT_RETURN_YR3 : null;
                $user->INCOME_DECLARED3 = $request->INCOME_DECLARED3 ? $request->INCOME_DECLARED3 : null;
                $user->TAX_PAID3 = $request->TAX_PAID3 ? $request->TAX_PAID3 : null;
                $user->REFUND_CLAIMED3 = $request->REFUND_CLAIMED3 ? $request->REFUND_CLAIMED3 : null;
                $user->Maiden_Title = $request->Maiden_Title ? $request->Maiden_Title : null;
                $user->Maiden_First_Name = $request->Maiden_First_Name ? $request->Maiden_First_Name : null;
                $user->Maiden_Middle_Name = $request->Maiden_Middle_Name ? $request->Maiden_Middle_Name : null;
                $user->Maiden_Last_Name = $request->Maiden_Last_Name ? $request->Maiden_Last_Name : null;
                $user->Father_Title = $request->Father_Title ? $request->Father_Title : null;
                $user->Father_First_Name = $request->Father_First_Name ? $request->Father_First_Name : null;

                $user->Father_Middle_Name = $request->Father_Middle_Name ? $request->Father_Middle_Name : null;
                $user->Father_Last_Name = $request->Father_Last_Name ? $request->Father_Last_Name : null;
                $user->Mother_Title = $request->Mother_Title ? $request->Mother_Title : null;
                $user->Mother_First_Name = $request->Mother_First_Name ? $request->Mother_First_Name : null;
                $user->Mothers_Maiden_Name = $request->Mothers_Maiden_Name ? $request->Mothers_Maiden_Name : null;
                $user->Generic_Surname = $request->Generic_Surname ? $request->Generic_Surname : null;
                $user->Spouse_Title = $request->Spouse_Title ? $request->Spouse_Title : null;
                $user->Spouse_First_Name = $request->Spouse_First_Name ? $request->Spouse_First_Name : null;
                $user->Spouse_Family_Name = $request->Spouse_Family_Name ? $request->Spouse_Family_Name : null;
                $user->Identification_Mark = $request->Identification_Mark ? $request->Identification_Mark : null;
                $user->Country_of_Domicile = $request->Country_of_Domicile ? $request->Country_of_Domicile : null;
                $user->Qualification = $request->Qualification ? $request->Qualification : null;
                $user->Nationality = $request->Nationality ? $request->Nationality : null;
                $user->Blood_Group = $request->Blood_Group ? $request->Blood_Group : null;
                $user->Offences = $request->Offences ? $request->Offences : null;
                $user->Politically_Exposed = $request->Politically_Exposed ? $request->Politically_Exposed : null;
                $user->Residence_Type = $request->Residence_Type ? $request->Residence_Type : null;
                $user->AREA = $request->AREA ? $request->AREA : null;
                $user->land_mark = $request->land_mark ? $request->land_mark : null;
                $user->Owned = $request->Owned ? $request->Owned : null;
                $user->Rented = $request->Rented ? $request->Rented : null;
                $user->Rent_Per_Month = $request->Rent_Per_Month ? $request->Rent_Per_Month : null;
                $user->Ancestral = $request->Ancestral ? $request->Ancestral : null;
                $user->Staying_Since = $request->Staying_Since ? $request->Staying_Since : null;
                $user->EMPLOYERRS = $request->EMPLOYERRS ? $request->EMPLOYERRS : null;

                $data = $user->save();


                $borrower_agreement = new BorrowerAgreement();
                $borrower_agreement->borrower_id = $user->id;
                $borrower_agreement->agreement_id = 1;
                $borrower_agreement->application_id = $request->application_id;
                $borrower_agreement->save();

                // borrower agreement
                /* if (!empty($request->agreement_id)) {
                    $borrower_agreement = new BorrowerAgreement();
                    $borrower_agreement->borrower_id = $user->id;
                    $borrower_agreement->agreement_id = $request->agreement_id;
                    $borrower_agreement->uploaded_by = $request->auth_user_id;
                    $borrower_agreement->save();
                } */

                // notification fire
                // createNotification($request->auth_user_id, 1, 'new_borrower', 'New borrower, ' . $request->name_prefix . ' ' . $request->full_name . ' added by ' . $request->auth_user_emp_id);

                // activity log
                /* $logData = [
                    'type' => 'new_borrower',
                    'title' => 'New borrower created',
                    'desc' => 'New borrower, ' . $request->full_name . ' created by ' . $request->auth_user_emp_id
                ]; */
                // activityLog($logData);

                DB::commit();
                // return redirect()->route('user.borrower.list')->with('success', 'Borrower created');
                return response()->json(['status' => 200, 'message' => 'Borrower created', 'data' => $user], 200);
            } catch (Exception $e) {
                DB::rollback();
                // $error['email'] = 'Something went wrong';
                // return redirect(route('user.borrower.create'))->withErrors($error)->withInput($request->all());
                return response()->json(['status' => 400, 'message' => 'Something happened'], 400);
            }
        } else {
            return response()->json(['status' => 400, 'message' => $validate->errors()->first()], 400);
        }
    }

    public function borrowerList() {
        $borrowerData = Borrower::with('agreement', 'borrowerAgreementRfq')->latest('id')->get();

        if ($borrowerData->count() > 0) {
            $data = [];
            foreach($borrowerData as $borrowerKey => $borrowerValue) {
                $agreement = [];

                foreach ($borrowerValue->agreement as $key => $value) {
                    $agreement[] = [
                        'id' => $value->agreementDetails->id,
                        'name' => $value->agreementDetails->name
                    ];
                }

                $data[] = [
                    'application_id' => $borrowerValue->application_id,
                    'borrower_id' => $borrowerValue->id,
                    'customer_id' => $borrowerValue->CUSTOMER_ID,
                    'name_prefix' => $borrowerValue->name_prefix,
                    'full_name' => $borrowerValue->full_name,
                    'first_name' => $borrowerValue->first_name,
                    'middle_name' => $borrowerValue->middle_name,
                    'last_name' => $borrowerValue->last_name,
                    'gender' => $borrowerValue->gender,
                    'email' => $borrowerValue->email,
                    'mobile' => $borrowerValue->mobile,
                    'occupation' => $borrowerValue->occupation,
                    'date_of_birth' => $borrowerValue->date_of_birth,
                    'marital_status' => $borrowerValue->marital_status,
                    'image_path' => asset($borrowerValue->image_path),
                    'signature_path' => $borrowerValue->signature_path,
                    'street_address' => $borrowerValue->street_address,
                    'city' => $borrowerValue->city,
                    'pincode' => $borrowerValue->pincode,
                    'state' => $borrowerValue->state,
                    'block' => $borrowerValue->block,
                    'pan_card_number' => $borrowerValue->pan_card_number,
                    'Customer_Type' => $borrowerValue->Customer_Type,
                    'Resident_Status' => $borrowerValue->Resident_Status,
                    'Aadhar_Number' => $borrowerValue->Aadhar_Number,
                    'Main_Constitution' => $borrowerValue->Main_Constitution,
                    'Sub_Constitution' => $borrowerValue->Sub_Constitution,
                    'KYC_Date' => $borrowerValue->KYC_Date,
                    'Re_KYC_Due_Date' => $borrowerValue->Re_KYC_Due_Date,
                    'Minor' => $borrowerValue->Minor,
                    'Customer_Category' => $borrowerValue->Customer_Category,
                    'Alternate_Mobile_No' => $borrowerValue->Alternate_Mobile_No,
                    'Telephone_No' => $borrowerValue->Telephone_No,
                    'Office_Telephone_No' => $borrowerValue->Office_Telephone_No,
                    'FAX_No' => $borrowerValue->FAX_No,
                    'Preferred_Language' => $borrowerValue->Preferred_Language,
                    'REMARKS' => $borrowerValue->REMARKS,
                    'KYC_Care_of' => $borrowerValue->KYC_Care_of,
                    'KYC_HOUSE_NO' => $borrowerValue->KYC_HOUSE_NO,
                    'KYC_LANDMARK' => $borrowerValue->KYC_LANDMARK,
                    'KYC_Street' => $borrowerValue->KYC_Street,
                    'KYC_LOCALITY' => $borrowerValue->KYC_LOCALITY,
                    'KYC_PINCODE' => $borrowerValue->KYC_PINCODE,
                    'KYC_Country' => $borrowerValue->KYC_Country,
                    'KYC_State' => $borrowerValue->KYC_State,
                    'KYC_District' => $borrowerValue->KYC_District,
                    'KYC_POST_OFFICE' => $borrowerValue->KYC_POST_OFFICE,
                    'KYC_CITY' => $borrowerValue->KYC_CITY,
                    'KYC_Taluka' => $borrowerValue->KYC_Taluka,
                    'KYC_Population_Group' => $borrowerValue->KYC_Population_Group,
                    'COMM_Care_of' => $borrowerValue->COMM_Care_of,
                    'COMM_HOUSE_NO' => $borrowerValue->COMM_HOUSE_NO,
                    'COMM_LANDMARK' => $borrowerValue->COMM_LANDMARK,
                    'COMM_Street' => $borrowerValue->COMM_Street,
                    'COMM_LOCALITY' => $borrowerValue->COMM_LOCALITY,
                    'COMM_PINCODE' => $borrowerValue->COMM_PINCODE,
                    'COMM_Country' => $borrowerValue->COMM_Country,
                    'COMM_State' => $borrowerValue->COMM_State,
                    'COMM_District' => $borrowerValue->COMM_District,
                    'COMM_POST_OFFICE' => $borrowerValue->COMM_POST_OFFICE,
                    'COMM_CITY' => $borrowerValue->COMM_CITY,
                    'COMM_Taluka' => $borrowerValue->COMM_Taluka,
                    'COMM_Population_Group' => $borrowerValue->COMM_Population_Group,
                    'Social_Media' => $borrowerValue->Social_Media,
                    'Social_Media_ID' => $borrowerValue->Social_Media_ID,
                    'PROFESSION' => $borrowerValue->PROFESSION,
                    'EDUCATION' => $borrowerValue->EDUCATION,
                    'ORGANISATION_NAME' => $borrowerValue->ORGANISATION_NAME,
                    'NET_INCOME' => $borrowerValue->NET_INCOME,
                    'NET_EXPENSE' => $borrowerValue->NET_EXPENSE,
                    'NET_SAVINGS' => $borrowerValue->NET_SAVINGS,
                    'Years_in_Organization' => $borrowerValue->Years_in_Organization,
                    'CIBIL_SCORE' => $borrowerValue->CIBIL_SCORE,
                    'PERSONAL_LOAN_SCORE' => $borrowerValue->PERSONAL_LOAN_SCORE,
                    'GST_EXEMPTED' => $borrowerValue->GST_EXEMPTED,
                    'RM_EMP_ID' => $borrowerValue->RM_EMP_ID,
                    'RM_Designation' => $borrowerValue->RM_Designation,
                    'RM_TITLE' => $borrowerValue->RM_TITLE,
                    'RM_NAME' => $borrowerValue->RM_NAME,
                    'RM_Landline_No' => $borrowerValue->RM_Landline_No,
                    'RM_MOBILE_NO' => $borrowerValue->RM_MOBILE_NO,
                    'RM_EMAIL_ID' => $borrowerValue->RM_EMAIL_ID,
                    'DSA_ID' => $borrowerValue->DSA_ID,
                    'DSA_NAME' => $borrowerValue->DSA_NAME,
                    'DSA_LANDLINE_NO' => $borrowerValue->DSA_LANDLINE_NO,
                    'DSA_MOBILE_NO' => $borrowerValue->DSA_MOBILE_NO,
                    'DSA_EMAIL_ID' => $borrowerValue->DSA_EMAIL_ID,
                    'GIR_NO' => $borrowerValue->GIR_NO,
                    'RATION_CARD_NO' => $borrowerValue->RATION_CARD_NO,
                    'DRIVING_LINC' => $borrowerValue->DRIVING_LINC,
                    'NPR_NO' => $borrowerValue->NPR_NO,
                    'PASSPORT_NO' => $borrowerValue->PASSPORT_NO,
                    'EXPORTER_CODE' => $borrowerValue->EXPORTER_CODE,
                    'GST_NO' => $borrowerValue->GST_NO,
                    'Voter_ID' => $borrowerValue->Voter_ID,
                    'CUSTM_2' => $borrowerValue->CUSTM_2,
                    'CATEGORY' => $borrowerValue->CATEGORY,
                    'RELIGION' => $borrowerValue->RELIGION,
                    'MINORITY_STATUS' => $borrowerValue->MINORITY_STATUS,
                    'CASTE' => $borrowerValue->CASTE,
                    'SUB_CAST' => $borrowerValue->SUB_CAST,
                    'RESERVATION_TYP' => $borrowerValue->RESERVATION_TYP,
                    'Physically_Challenged' => $borrowerValue->Physically_Challenged,
                    'Weaker_Section' => $borrowerValue->Weaker_Section,
                    'Valued_Customer' => $borrowerValue->Valued_Customer,
                    'Special_Category_1' => $borrowerValue->Special_Category_1,
                    'Vip_Category' => $borrowerValue->Vip_Category,
                    'Special_Category_2' => $borrowerValue->Special_Category_2,
                    'Senior_Citizen' => $borrowerValue->Senior_Citizen,
                    'Senior_Citizen_From' => $borrowerValue->Senior_Citizen_From,
                    'NO_OF_DEPEND' => $borrowerValue->NO_OF_DEPEND,
                    'SPOUSE' => $borrowerValue->SPOUSE,
                    'CHILDREN' => $borrowerValue->CHILDREN,
                    'PARENTS' => $borrowerValue->PARENTS,
                    'Employee_Staus' => $borrowerValue->Employee_Staus,
                    'Employee_No' => $borrowerValue->Employee_No,
                    'EMP_Date' => $borrowerValue->EMP_Date,
                    'Nature_of_Occupation' => $borrowerValue->Nature_of_Occupation,
                    'EMPLYEER_NAME' => $borrowerValue->EMPLYEER_NAME,
                    'Role' => $borrowerValue->Role,
                    'SPECIALIZATION' => $borrowerValue->SPECIALIZATION,
                    'EMP_GRADE' => $borrowerValue->EMP_GRADE,
                    'DESIGNATION' => $borrowerValue->DESIGNATION,
                    'Office_Address' => $borrowerValue->Office_Address,
                    'Office_Phone' => $borrowerValue->Office_Phone,
                    'Office_EXTENSION' => $borrowerValue->Office_EXTENSION,
                    'Office_Fax' => $borrowerValue->Office_Fax,
                    'Office_MOBILE' => $borrowerValue->Office_MOBILE,
                    'Office_PINCODE' => $borrowerValue->Office_PINCODE,
                    'Office_CITY' => $borrowerValue->Office_CITY,
                    'Working_Since' => $borrowerValue->Working_Since,
                    'Working_in_Current_company_Yrs' => $borrowerValue->Working_in_Current_company_Yrs,
                    'RETIRE_AGE' => $borrowerValue->RETIRE_AGE,
                    'Nature_of_Business' => $borrowerValue->Nature_of_Business,
                    'Annual_Income' => $borrowerValue->Annual_Income,
                    'Prof_Self_Employed' => $borrowerValue->Prof_Self_Employed,
                    'Prof_Self_Annual_Income' => $borrowerValue->Prof_Self_Annual_Income,
                    'IT_RETURN_YR1' => $borrowerValue->IT_RETURN_YR1,
                    'INCOME_DECLARED1' => $borrowerValue->INCOME_DECLARED1,
                    'TAX_PAID' => $borrowerValue->TAX_PAID,
                    'REFUND_CLAIMED1' => $borrowerValue->REFUND_CLAIMED1,
                    'IT_RETURN_YR2' => $borrowerValue->IT_RETURN_YR2,
                    'INCOME_DECLARED2' => $borrowerValue->INCOME_DECLARED2,
                    'TAX_PAID2' => $borrowerValue->TAX_PAID2,
                    'REFUND_CLAIMED2' => $borrowerValue->REFUND_CLAIMED2,
                    'IT_RETURN_YR3' => $borrowerValue->IT_RETURN_YR3,
                    'INCOME_DECLARED3' => $borrowerValue->INCOME_DECLARED3,
                    'TAX_PAID3' => $borrowerValue->TAX_PAID3,
                    'REFUND_CLAIMED3' => $borrowerValue->REFUND_CLAIMED3,
                    'Maiden_Title' => $borrowerValue->Maiden_Title,
                    'Maiden_First_Name' => $borrowerValue->Maiden_First_Name,
                    'Maiden_Middle_Name' => $borrowerValue->Maiden_Middle_Name,
                    'Maiden_Last_Name' => $borrowerValue->Maiden_Last_Name,
                    'Father_Title' => $borrowerValue->Father_Title,
                    'Father_First_Name' => $borrowerValue->Father_First_Name,
                    'Father_Middle_Name' => $borrowerValue->Father_Middle_Name,
                    'Father_Last_Name' => $borrowerValue->Father_Last_Name,
                    'Mother_Title' => $borrowerValue->Mother_Title,
                    'Mother_First_Name' => $borrowerValue->Mother_First_Name,
                    'Mothers_Maiden_Name' => $borrowerValue->Mothers_Maiden_Name,
                    'Generic_Surname' => $borrowerValue->Generic_Surname,
                    'Spouse_Title' => $borrowerValue->Spouse_Title,
                    'Spouse_First_Name' => $borrowerValue->Spouse_First_Name,
                    'Spouse_Family_Name' => $borrowerValue->Spouse_Family_Name,
                    'Identification_Mark' => $borrowerValue->Identification_Mark,
                    'Country_of_Domicile' => $borrowerValue->Country_of_Domicile,
                    'Qualification' => $borrowerValue->Qualification,
                    'Nationality' => $borrowerValue->Nationality,
                    'Blood_Group' => $borrowerValue->Blood_Group,
                    'Offences' => $borrowerValue->Offences,
                    'Politically_Exposed' => $borrowerValue->Politically_Exposed,
                    'Residence_Type' => $borrowerValue->Residence_Type,
                    'AREA' => $borrowerValue->AREA,
                    'land_mark' => $borrowerValue->land_mark,
                    'Owned' => $borrowerValue->Owned,
                    'Rented' => $borrowerValue->Rented,
                    'Rent_Per_Month' => $borrowerValue->Rent_Per_Month,
                    'Ancestral' => $borrowerValue->Ancestral,
                    'Staying_Since' => $borrowerValue->Staying_Since,
                    'EMPLOYERRS' => $borrowerValue->EMPLOYERRS,
                    'agreement_details' => $agreement
                ];
            }

            return response()->json(['status' => 200, 'message' => 'Borrower List', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'No borrower data found'], 400);
        }
    }

    public function borrowerDetail(Request $request, $appId) {

        $borrowerData = Borrower::where('application_id', $appId)->first();

        if(!empty($borrowerData)) {
            return response()->json(['status' => 200, 'message' => 'Borrower detail found', 'data' => $borrowerData], 200);
        } else {
            return response()->json(['status' => 404, 'data' => 'Invalid application ID'], 404);
        }

        /*
        $borrowerData = Borrower::where('id', $id)->get();

        if (count($borrowerData) > 0) {
            $data = [];
            foreach($borrowerData as $borrowerKey => $borrowerValue) {
                $agreement = [];

                // if($borrowerValue->agreement) {
                //     foreach ($borrowerValue->agreement as $key => $value) {
                //         $agreement[] = [
                //             'id' => $value->agreementDetails->id,
                //             'name' => $value->agreementDetails->name
                //         ];
                //     }
                // }

                $data[] = [
                    'application_id' => $borrowerValue->application_id,
                    'borrower_id' => $borrowerValue->id,
                    'customer_id' => $borrowerValue->CUSTOMER_ID,
                    'name_prefix' => $borrowerValue->name_prefix,
                    'full_name' => $borrowerValue->full_name,
                    'first_name' => $borrowerValue->first_name,
                    'middle_name' => $borrowerValue->middle_name,
                    'last_name' => $borrowerValue->last_name,
                    'gender' => $borrowerValue->gender,
                    'email' => $borrowerValue->email,
                    'mobile' => $borrowerValue->mobile,
                    'occupation' => $borrowerValue->occupation,
                    'date_of_birth' => $borrowerValue->date_of_birth,
                    'marital_status' => $borrowerValue->marital_status,
                    'image_path' => asset($borrowerValue->image_path),
                    'signature_path' => $borrowerValue->signature_path,
                    'street_address' => $borrowerValue->street_address,
                    'city' => $borrowerValue->city,
                    'pincode' => $borrowerValue->pincode,
                    'state' => $borrowerValue->state,
                    'block' => $borrowerValue->block,
                    'pan_card_number' => $borrowerValue->pan_card_number,
                    'Customer_Type' => $borrowerValue->Customer_Type,
                    'Resident_Status' => $borrowerValue->Resident_Status,
                    'Aadhar_Number' => $borrowerValue->Aadhar_Number,
                    'Main_Constitution' => $borrowerValue->Main_Constitution,
                    'Sub_Constitution' => $borrowerValue->Sub_Constitution,
                    'KYC_Date' => $borrowerValue->KYC_Date,
                    'Re_KYC_Due_Date' => $borrowerValue->Re_KYC_Due_Date,
                    'Minor' => $borrowerValue->Minor,
                    'Customer_Category' => $borrowerValue->Customer_Category,
                    'Alternate_Mobile_No' => $borrowerValue->Alternate_Mobile_No,
                    'Telephone_No' => $borrowerValue->Telephone_No,
                    'Office_Telephone_No' => $borrowerValue->Office_Telephone_No,
                    'FAX_No' => $borrowerValue->FAX_No,
                    'Preferred_Language' => $borrowerValue->Preferred_Language,
                    'REMARKS' => $borrowerValue->REMARKS,
                    'KYC_Care_of' => $borrowerValue->KYC_Care_of,
                    'KYC_HOUSE_NO' => $borrowerValue->KYC_HOUSE_NO,
                    'KYC_LANDMARK' => $borrowerValue->KYC_LANDMARK,
                    'KYC_Street' => $borrowerValue->KYC_Street,
                    'KYC_LOCALITY' => $borrowerValue->KYC_LOCALITY,
                    'KYC_PINCODE' => $borrowerValue->KYC_PINCODE,
                    'KYC_Country' => $borrowerValue->KYC_Country,
                    'KYC_State' => $borrowerValue->KYC_State,
                    'KYC_District' => $borrowerValue->KYC_District,
                    'KYC_POST_OFFICE' => $borrowerValue->KYC_POST_OFFICE,
                    'KYC_CITY' => $borrowerValue->KYC_CITY,
                    'KYC_Taluka' => $borrowerValue->KYC_Taluka,
                    'KYC_Population_Group' => $borrowerValue->KYC_Population_Group,
                    'COMM_Care_of' => $borrowerValue->COMM_Care_of,
                    'COMM_HOUSE_NO' => $borrowerValue->COMM_HOUSE_NO,
                    'COMM_LANDMARK' => $borrowerValue->COMM_LANDMARK,
                    'COMM_Street' => $borrowerValue->COMM_Street,
                    'COMM_LOCALITY' => $borrowerValue->COMM_LOCALITY,
                    'COMM_PINCODE' => $borrowerValue->COMM_PINCODE,
                    'COMM_Country' => $borrowerValue->COMM_Country,
                    'COMM_State' => $borrowerValue->COMM_State,
                    'COMM_District' => $borrowerValue->COMM_District,
                    'COMM_POST_OFFICE' => $borrowerValue->COMM_POST_OFFICE,
                    'COMM_CITY' => $borrowerValue->COMM_CITY,
                    'COMM_Taluka' => $borrowerValue->COMM_Taluka,
                    'COMM_Population_Group' => $borrowerValue->COMM_Population_Group,
                    'Social_Media' => $borrowerValue->Social_Media,
                    'Social_Media_ID' => $borrowerValue->Social_Media_ID,
                    'PROFESSION' => $borrowerValue->PROFESSION,
                    'EDUCATION' => $borrowerValue->EDUCATION,
                    'ORGANISATION_NAME' => $borrowerValue->ORGANISATION_NAME,
                    'NET_INCOME' => $borrowerValue->NET_INCOME,
                    'NET_EXPENSE' => $borrowerValue->NET_EXPENSE,
                    'NET_SAVINGS' => $borrowerValue->NET_SAVINGS,
                    'Years_in_Organization' => $borrowerValue->Years_in_Organization,
                    'CIBIL_SCORE' => $borrowerValue->CIBIL_SCORE,
                    'PERSONAL_LOAN_SCORE' => $borrowerValue->PERSONAL_LOAN_SCORE,
                    'GST_EXEMPTED' => $borrowerValue->GST_EXEMPTED,
                    'RM_EMP_ID' => $borrowerValue->RM_EMP_ID,
                    'RM_Designation' => $borrowerValue->RM_Designation,
                    'RM_TITLE' => $borrowerValue->RM_TITLE,
                    'RM_NAME' => $borrowerValue->RM_NAME,
                    'RM_Landline_No' => $borrowerValue->RM_Landline_No,
                    'RM_MOBILE_NO' => $borrowerValue->RM_MOBILE_NO,
                    'RM_EMAIL_ID' => $borrowerValue->RM_EMAIL_ID,
                    'DSA_ID' => $borrowerValue->DSA_ID,
                    'DSA_NAME' => $borrowerValue->DSA_NAME,
                    'DSA_LANDLINE_NO' => $borrowerValue->DSA_LANDLINE_NO,
                    'DSA_MOBILE_NO' => $borrowerValue->DSA_MOBILE_NO,
                    'DSA_EMAIL_ID' => $borrowerValue->DSA_EMAIL_ID,
                    'GIR_NO' => $borrowerValue->GIR_NO,
                    'RATION_CARD_NO' => $borrowerValue->RATION_CARD_NO,
                    'DRIVING_LINC' => $borrowerValue->DRIVING_LINC,
                    'NPR_NO' => $borrowerValue->NPR_NO,
                    'PASSPORT_NO' => $borrowerValue->PASSPORT_NO,
                    'EXPORTER_CODE' => $borrowerValue->EXPORTER_CODE,
                    'GST_NO' => $borrowerValue->GST_NO,
                    'Voter_ID' => $borrowerValue->Voter_ID,
                    'CUSTM_2' => $borrowerValue->CUSTM_2,
                    'CATEGORY' => $borrowerValue->CATEGORY,
                    'RELIGION' => $borrowerValue->RELIGION,
                    'MINORITY_STATUS' => $borrowerValue->MINORITY_STATUS,
                    'CASTE' => $borrowerValue->CASTE,
                    'SUB_CAST' => $borrowerValue->SUB_CAST,
                    'RESERVATION_TYP' => $borrowerValue->RESERVATION_TYP,
                    'Physically_Challenged' => $borrowerValue->Physically_Challenged,
                    'Weaker_Section' => $borrowerValue->Weaker_Section,
                    'Valued_Customer' => $borrowerValue->Valued_Customer,
                    'Special_Category_1' => $borrowerValue->Special_Category_1,
                    'Vip_Category' => $borrowerValue->Vip_Category,
                    'Special_Category_2' => $borrowerValue->Special_Category_2,
                    'Senior_Citizen' => $borrowerValue->Senior_Citizen,
                    'Senior_Citizen_From' => $borrowerValue->Senior_Citizen_From,
                    'NO_OF_DEPEND' => $borrowerValue->NO_OF_DEPEND,
                    'SPOUSE' => $borrowerValue->SPOUSE,
                    'CHILDREN' => $borrowerValue->CHILDREN,
                    'PARENTS' => $borrowerValue->PARENTS,
                    'Employee_Staus' => $borrowerValue->Employee_Staus,
                    'Employee_No' => $borrowerValue->Employee_No,
                    'EMP_Date' => $borrowerValue->EMP_Date,
                    'Nature_of_Occupation' => $borrowerValue->Nature_of_Occupation,
                    'EMPLYEER_NAME' => $borrowerValue->EMPLYEER_NAME,
                    'Role' => $borrowerValue->Role,
                    'SPECIALIZATION' => $borrowerValue->SPECIALIZATION,
                    'EMP_GRADE' => $borrowerValue->EMP_GRADE,
                    'DESIGNATION' => $borrowerValue->DESIGNATION,
                    'Office_Address' => $borrowerValue->Office_Address,
                    'Office_Phone' => $borrowerValue->Office_Phone,
                    'Office_EXTENSION' => $borrowerValue->Office_EXTENSION,
                    'Office_Fax' => $borrowerValue->Office_Fax,
                    'Office_MOBILE' => $borrowerValue->Office_MOBILE,
                    'Office_PINCODE' => $borrowerValue->Office_PINCODE,
                    'Office_CITY' => $borrowerValue->Office_CITY,
                    'Working_Since' => $borrowerValue->Working_Since,
                    'Working_in_Current_company_Yrs' => $borrowerValue->Working_in_Current_company_Yrs,
                    'RETIRE_AGE' => $borrowerValue->RETIRE_AGE,
                    'Nature_of_Business' => $borrowerValue->Nature_of_Business,
                    'Annual_Income' => $borrowerValue->Annual_Income,
                    'Prof_Self_Employed' => $borrowerValue->Prof_Self_Employed,
                    'Prof_Self_Annual_Income' => $borrowerValue->Prof_Self_Annual_Income,
                    'IT_RETURN_YR1' => $borrowerValue->IT_RETURN_YR1,
                    'INCOME_DECLARED1' => $borrowerValue->INCOME_DECLARED1,
                    'TAX_PAID' => $borrowerValue->TAX_PAID,
                    'REFUND_CLAIMED1' => $borrowerValue->REFUND_CLAIMED1,
                    'IT_RETURN_YR2' => $borrowerValue->IT_RETURN_YR2,
                    'INCOME_DECLARED2' => $borrowerValue->INCOME_DECLARED2,
                    'TAX_PAID2' => $borrowerValue->TAX_PAID2,
                    'REFUND_CLAIMED2' => $borrowerValue->REFUND_CLAIMED2,
                    'IT_RETURN_YR3' => $borrowerValue->IT_RETURN_YR3,
                    'INCOME_DECLARED3' => $borrowerValue->INCOME_DECLARED3,
                    'TAX_PAID3' => $borrowerValue->TAX_PAID3,
                    'REFUND_CLAIMED3' => $borrowerValue->REFUND_CLAIMED3,
                    'Maiden_Title' => $borrowerValue->Maiden_Title,
                    'Maiden_First_Name' => $borrowerValue->Maiden_First_Name,
                    'Maiden_Middle_Name' => $borrowerValue->Maiden_Middle_Name,
                    'Maiden_Last_Name' => $borrowerValue->Maiden_Last_Name,
                    'Father_Title' => $borrowerValue->Father_Title,
                    'Father_First_Name' => $borrowerValue->Father_First_Name,
                    'Father_Middle_Name' => $borrowerValue->Father_Middle_Name,
                    'Father_Last_Name' => $borrowerValue->Father_Last_Name,
                    'Mother_Title' => $borrowerValue->Mother_Title,
                    'Mother_First_Name' => $borrowerValue->Mother_First_Name,
                    'Mothers_Maiden_Name' => $borrowerValue->Mothers_Maiden_Name,
                    'Generic_Surname' => $borrowerValue->Generic_Surname,
                    'Spouse_Title' => $borrowerValue->Spouse_Title,
                    'Spouse_First_Name' => $borrowerValue->Spouse_First_Name,
                    'Spouse_Family_Name' => $borrowerValue->Spouse_Family_Name,
                    'Identification_Mark' => $borrowerValue->Identification_Mark,
                    'Country_of_Domicile' => $borrowerValue->Country_of_Domicile,
                    'Qualification' => $borrowerValue->Qualification,
                    'Nationality' => $borrowerValue->Nationality,
                    'Blood_Group' => $borrowerValue->Blood_Group,
                    'Offences' => $borrowerValue->Offences,
                    'Politically_Exposed' => $borrowerValue->Politically_Exposed,
                    'Residence_Type' => $borrowerValue->Residence_Type,
                    'AREA' => $borrowerValue->AREA,
                    'land_mark' => $borrowerValue->land_mark,
                    'Owned' => $borrowerValue->Owned,
                    'Rented' => $borrowerValue->Rented,
                    'Rent_Per_Month' => $borrowerValue->Rent_Per_Month,
                    'Ancestral' => $borrowerValue->Ancestral,
                    'Staying_Since' => $borrowerValue->Staying_Since,
                    'EMPLOYERRS' => $borrowerValue->EMPLOYERRS,
                    // 'agreement_details' => $agreement
                ];
            }

            return response()->json(['status' => 200, 'message' => 'Borrower data found', 'data' => $data], 200);
            // return response()->json(['status' => 200, 'data' => $borrowerData]);
        } else {
            return response()->json(['status' => 404, 'data' => 'not found'], 404);
        }
        */

        /* $borrowerData = Borrower::with('agreement', 'borrowerAgreementRfq')->findOrFail($id);
        // $borrowerData = Borrower::where()->with('agreement', 'borrowerAgreementRfq')->first();

        if ($borrowerData->count() > 0) {
            $data = [];
            foreach($borrowerData as $borrowerKey => $borrowerValue) {
                $agreement = [];

                foreach ($borrowerValue->agreement as $key => $value) {
                    $agreement[] = [
                        'id' => $value->agreementDetails->id,
                        'name' => $value->agreementDetails->name
                    ];
                }

                $data[] = [
                    'borrower_id' => $borrowerValue->id,
                    'customer_id' => $borrowerValue->CUSTOMER_ID,
                    'name_prefix' => $borrowerValue->name_prefix,
                    'full_name' => $borrowerValue->full_name,
                    'first_name' => $borrowerValue->first_name,
                    'middle_name' => $borrowerValue->middle_name,
                    'last_name' => $borrowerValue->last_name,
                    'gender' => $borrowerValue->gender,
                    'email' => $borrowerValue->email,
                    'mobile' => $borrowerValue->mobile,
                    'occupation' => $borrowerValue->occupation,
                    'date_of_birth' => $borrowerValue->date_of_birth,
                    'marital_status' => $borrowerValue->marital_status,
                    'image_path' => asset($borrowerValue->image_path),
                    'signature_path' => $borrowerValue->signature_path,
                    'street_address' => $borrowerValue->street_address,
                    'city' => $borrowerValue->city,
                    'pincode' => $borrowerValue->pincode,
                    'state' => $borrowerValue->state,
                    'block' => $borrowerValue->block,
                    'pan_card_number' => $borrowerValue->pan_card_number,
                    'Customer_Type' => $borrowerValue->Customer_Type,
                    'Resident_Status' => $borrowerValue->Resident_Status,
                    'Aadhar_Number' => $borrowerValue->Aadhar_Number,
                    'Main_Constitution' => $borrowerValue->Main_Constitution,
                    'Sub_Constitution' => $borrowerValue->Sub_Constitution,
                    'KYC_Date' => $borrowerValue->KYC_Date,
                    'Re_KYC_Due_Date' => $borrowerValue->Re_KYC_Due_Date,
                    'Minor' => $borrowerValue->Minor,
                    'Customer_Category' => $borrowerValue->Customer_Category,
                    'Alternate_Mobile_No' => $borrowerValue->Alternate_Mobile_No,
                    'Telephone_No' => $borrowerValue->Telephone_No,
                    'Office_Telephone_No' => $borrowerValue->Office_Telephone_No,
                    'FAX_No' => $borrowerValue->FAX_No,
                    'Preferred_Language' => $borrowerValue->Preferred_Language,
                    'REMARKS' => $borrowerValue->REMARKS,
                    'KYC_Care_of' => $borrowerValue->KYC_Care_of,
                    'KYC_HOUSE_NO' => $borrowerValue->KYC_HOUSE_NO,
                    'KYC_LANDMARK' => $borrowerValue->KYC_LANDMARK,
                    'KYC_Street' => $borrowerValue->KYC_Street,
                    'KYC_LOCALITY' => $borrowerValue->KYC_LOCALITY,
                    'KYC_PINCODE' => $borrowerValue->KYC_PINCODE,
                    'KYC_Country' => $borrowerValue->KYC_Country,
                    'KYC_State' => $borrowerValue->KYC_State,
                    'KYC_District' => $borrowerValue->KYC_District,
                    'KYC_POST_OFFICE' => $borrowerValue->KYC_POST_OFFICE,
                    'KYC_CITY' => $borrowerValue->KYC_CITY,
                    'KYC_Taluka' => $borrowerValue->KYC_Taluka,
                    'KYC_Population_Group' => $borrowerValue->KYC_Population_Group,
                    'COMM_Care_of' => $borrowerValue->COMM_Care_of,
                    'COMM_HOUSE_NO' => $borrowerValue->COMM_HOUSE_NO,
                    'COMM_LANDMARK' => $borrowerValue->COMM_LANDMARK,
                    'COMM_Street' => $borrowerValue->COMM_Street,
                    'COMM_LOCALITY' => $borrowerValue->COMM_LOCALITY,
                    'COMM_PINCODE' => $borrowerValue->COMM_PINCODE,
                    'COMM_Country' => $borrowerValue->COMM_Country,
                    'COMM_State' => $borrowerValue->COMM_State,
                    'COMM_District' => $borrowerValue->COMM_District,
                    'COMM_POST_OFFICE' => $borrowerValue->COMM_POST_OFFICE,
                    'COMM_CITY' => $borrowerValue->COMM_CITY,
                    'COMM_Taluka' => $borrowerValue->COMM_Taluka,
                    'COMM_Population_Group' => $borrowerValue->COMM_Population_Group,
                    'Social_Media' => $borrowerValue->Social_Media,
                    'Social_Media_ID' => $borrowerValue->Social_Media_ID,
                    'PROFESSION' => $borrowerValue->PROFESSION,
                    'EDUCATION' => $borrowerValue->EDUCATION,
                    'ORGANISATION_NAME' => $borrowerValue->ORGANISATION_NAME,
                    'NET_INCOME' => $borrowerValue->NET_INCOME,
                    'NET_EXPENSE' => $borrowerValue->NET_EXPENSE,
                    'NET_SAVINGS' => $borrowerValue->NET_SAVINGS,
                    'Years_in_Organization' => $borrowerValue->Years_in_Organization,
                    'CIBIL_SCORE' => $borrowerValue->CIBIL_SCORE,
                    'PERSONAL_LOAN_SCORE' => $borrowerValue->PERSONAL_LOAN_SCORE,
                    'GST_EXEMPTED' => $borrowerValue->GST_EXEMPTED,
                    'RM_EMP_ID' => $borrowerValue->RM_EMP_ID,
                    'RM_Designation' => $borrowerValue->RM_Designation,
                    'RM_TITLE' => $borrowerValue->RM_TITLE,
                    'RM_NAME' => $borrowerValue->RM_NAME,
                    'RM_Landline_No' => $borrowerValue->RM_Landline_No,
                    'RM_MOBILE_NO' => $borrowerValue->RM_MOBILE_NO,
                    'RM_EMAIL_ID' => $borrowerValue->RM_EMAIL_ID,
                    'DSA_ID' => $borrowerValue->DSA_ID,
                    'DSA_NAME' => $borrowerValue->DSA_NAME,
                    'DSA_LANDLINE_NO' => $borrowerValue->DSA_LANDLINE_NO,
                    'DSA_MOBILE_NO' => $borrowerValue->DSA_MOBILE_NO,
                    'DSA_EMAIL_ID' => $borrowerValue->DSA_EMAIL_ID,
                    'GIR_NO' => $borrowerValue->GIR_NO,
                    'RATION_CARD_NO' => $borrowerValue->RATION_CARD_NO,
                    'DRIVING_LINC' => $borrowerValue->DRIVING_LINC,
                    'NPR_NO' => $borrowerValue->NPR_NO,
                    'PASSPORT_NO' => $borrowerValue->PASSPORT_NO,
                    'EXPORTER_CODE' => $borrowerValue->EXPORTER_CODE,
                    'GST_NO' => $borrowerValue->GST_NO,
                    'Voter_ID' => $borrowerValue->Voter_ID,
                    'CUSTM_2' => $borrowerValue->CUSTM_2,
                    'CATEGORY' => $borrowerValue->CATEGORY,
                    'RELIGION' => $borrowerValue->RELIGION,
                    'MINORITY_STATUS' => $borrowerValue->MINORITY_STATUS,
                    'CASTE' => $borrowerValue->CASTE,
                    'SUB_CAST' => $borrowerValue->SUB_CAST,
                    'RESERVATION_TYP' => $borrowerValue->RESERVATION_TYP,
                    'Physically_Challenged' => $borrowerValue->Physically_Challenged,
                    'Weaker_Section' => $borrowerValue->Weaker_Section,
                    'Valued_Customer' => $borrowerValue->Valued_Customer,
                    'Special_Category_1' => $borrowerValue->Special_Category_1,
                    'Vip_Category' => $borrowerValue->Vip_Category,
                    'Special_Category_2' => $borrowerValue->Special_Category_2,
                    'Senior_Citizen' => $borrowerValue->Senior_Citizen,
                    'Senior_Citizen_From' => $borrowerValue->Senior_Citizen_From,
                    'NO_OF_DEPEND' => $borrowerValue->NO_OF_DEPEND,
                    'SPOUSE' => $borrowerValue->SPOUSE,
                    'CHILDREN' => $borrowerValue->CHILDREN,
                    'PARENTS' => $borrowerValue->PARENTS,
                    'Employee_Staus' => $borrowerValue->Employee_Staus,
                    'Employee_No' => $borrowerValue->Employee_No,
                    'EMP_Date' => $borrowerValue->EMP_Date,
                    'Nature_of_Occupation' => $borrowerValue->Nature_of_Occupation,
                    'EMPLYEER_NAME' => $borrowerValue->EMPLYEER_NAME,
                    'Role' => $borrowerValue->Role,
                    'SPECIALIZATION' => $borrowerValue->SPECIALIZATION,
                    'EMP_GRADE' => $borrowerValue->EMP_GRADE,
                    'DESIGNATION' => $borrowerValue->DESIGNATION,
                    'Office_Address' => $borrowerValue->Office_Address,
                    'Office_Phone' => $borrowerValue->Office_Phone,
                    'Office_EXTENSION' => $borrowerValue->Office_EXTENSION,
                    'Office_Fax' => $borrowerValue->Office_Fax,
                    'Office_MOBILE' => $borrowerValue->Office_MOBILE,
                    'Office_PINCODE' => $borrowerValue->Office_PINCODE,
                    'Office_CITY' => $borrowerValue->Office_CITY,
                    'Working_Since' => $borrowerValue->Working_Since,
                    'Working_in_Current_company_Yrs' => $borrowerValue->Working_in_Current_company_Yrs,
                    'RETIRE_AGE' => $borrowerValue->RETIRE_AGE,
                    'Nature_of_Business' => $borrowerValue->Nature_of_Business,
                    'Annual_Income' => $borrowerValue->Annual_Income,
                    'Prof_Self_Employed' => $borrowerValue->Prof_Self_Employed,
                    'Prof_Self_Annual_Income' => $borrowerValue->Prof_Self_Annual_Income,
                    'IT_RETURN_YR1' => $borrowerValue->IT_RETURN_YR1,
                    'INCOME_DECLARED1' => $borrowerValue->INCOME_DECLARED1,
                    'TAX_PAID' => $borrowerValue->TAX_PAID,
                    'REFUND_CLAIMED1' => $borrowerValue->REFUND_CLAIMED1,
                    'IT_RETURN_YR2' => $borrowerValue->IT_RETURN_YR2,
                    'INCOME_DECLARED2' => $borrowerValue->INCOME_DECLARED2,
                    'TAX_PAID2' => $borrowerValue->TAX_PAID2,
                    'REFUND_CLAIMED2' => $borrowerValue->REFUND_CLAIMED2,
                    'IT_RETURN_YR3' => $borrowerValue->IT_RETURN_YR3,
                    'INCOME_DECLARED3' => $borrowerValue->INCOME_DECLARED3,
                    'TAX_PAID3' => $borrowerValue->TAX_PAID3,
                    'REFUND_CLAIMED3' => $borrowerValue->REFUND_CLAIMED3,
                    'Maiden_Title' => $borrowerValue->Maiden_Title,
                    'Maiden_First_Name' => $borrowerValue->Maiden_First_Name,
                    'Maiden_Middle_Name' => $borrowerValue->Maiden_Middle_Name,
                    'Maiden_Last_Name' => $borrowerValue->Maiden_Last_Name,
                    'Father_Title' => $borrowerValue->Father_Title,
                    'Father_First_Name' => $borrowerValue->Father_First_Name,
                    'Father_Middle_Name' => $borrowerValue->Father_Middle_Name,
                    'Father_Last_Name' => $borrowerValue->Father_Last_Name,
                    'Mother_Title' => $borrowerValue->Mother_Title,
                    'Mother_First_Name' => $borrowerValue->Mother_First_Name,
                    'Mothers_Maiden_Name' => $borrowerValue->Mothers_Maiden_Name,
                    'Generic_Surname' => $borrowerValue->Generic_Surname,
                    'Spouse_Title' => $borrowerValue->Spouse_Title,
                    'Spouse_First_Name' => $borrowerValue->Spouse_First_Name,
                    'Spouse_Family_Name' => $borrowerValue->Spouse_Family_Name,
                    'Identification_Mark' => $borrowerValue->Identification_Mark,
                    'Country_of_Domicile' => $borrowerValue->Country_of_Domicile,
                    'Qualification' => $borrowerValue->Qualification,
                    'Nationality' => $borrowerValue->Nationality,
                    'Blood_Group' => $borrowerValue->Blood_Group,
                    'Offences' => $borrowerValue->Offences,
                    'Politically_Exposed' => $borrowerValue->Politically_Exposed,
                    'Residence_Type' => $borrowerValue->Residence_Type,
                    'AREA' => $borrowerValue->AREA,
                    'land_mark' => $borrowerValue->land_mark,
                    'Owned' => $borrowerValue->Owned,
                    'Rented' => $borrowerValue->Rented,
                    'Rent_Per_Month' => $borrowerValue->Rent_Per_Month,
                    'Ancestral' => $borrowerValue->Ancestral,
                    'Staying_Since' => $borrowerValue->Staying_Since,
                    'EMPLOYERRS' => $borrowerValue->EMPLOYERRS,
                    'agreement_details' => $agreement
                ];
            }

            return response()->json(['status' => 200, 'message' => 'Borrower List', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'No borrower detail found'], 400);
        } */
    }
}
