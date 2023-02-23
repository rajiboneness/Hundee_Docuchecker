<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsOfBorrowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->bigInteger('CUSTOMER_ID')->after('id')->nullable();
            $table->TEXT('Customer_Type', 50)->nullable();
            $table->TEXT('Resident_Status', 50)->nullable();
            $table->TEXT('Aadhar_Number', 12)->nullable();
            $table->TEXT('Main_Constitution', 30)->nullable();
            $table->TEXT('Sub_Constitution', 30)->nullable();
            $table->TEXT('KYC_Date', 30)->nullable();
            $table->TEXT('Re_KYC_Due_Date', 30)->nullable();

            $table->TEXT('Minor', 5)->nullable();
            $table->TEXT('Customer_Category', 10)->nullable();
            $table->TEXT('Alternate_Mobile_No', 20)->nullable();
            $table->TEXT('Telephone_No', 20)->nullable();
            $table->TEXT('Office_Telephone_No', 20)->nullable();
            $table->TEXT('FAX_No', 20)->nullable();
            $table->TEXT('Preferred_Language', 20)->nullable();
            $table->longText('REMARKS')->nullable();
            $table->TEXT('KYC_Care_of')->nullable();
            $table->TEXT('KYC_HOUSE_NO')->nullable();
            $table->TEXT('KYC_LANDMARK')->nullable();
            $table->TEXT('KYC_Street')->nullable();
            $table->TEXT('KYC_LOCALITY')->nullable();
            $table->TEXT('KYC_PINCODE', 6)->nullable();
            $table->TEXT('KYC_Country', 100)->nullable();
            $table->TEXT('KYC_State', 100)->nullable();
            $table->TEXT('KYC_District', 200)->nullable();
            $table->TEXT('KYC_POST_OFFICE')->nullable();
            $table->TEXT('KYC_CITY')->nullable();
            $table->TEXT('KYC_Taluka')->nullable();
            $table->TEXT('KYC_Population_Group')->nullable();
            $table->TEXT('COMM_Care_of')->nullable(); // COMM_Care of -> COMM_Care_of
            $table->TEXT('COMM_HOUSE_NO')->nullable(); // COMM_HOUSE NO -> COMM_HOUSE_NO
            $table->TEXT('COMM_LANDMARK')->nullable();
            $table->TEXT('COMM_Street')->nullable();
            $table->TEXT('COMM_LOCALITY')->nullable();
            $table->TEXT('COMM_PINCODE', 6)->nullable();
            $table->TEXT('COMM_Country', 100)->nullable();
            $table->TEXT('COMM_State', 100)->nullable();
            $table->TEXT('COMM_District')->nullable();
            $table->TEXT('COMM_POST_OFFICE')->nullable();
            $table->TEXT('COMM_CITY')->nullable();
            $table->TEXT('COMM_Taluka')->nullable();
            $table->TEXT('COMM_Population_Group')->nullable();
            $table->TEXT('Social_Media')->nullable();
            $table->TEXT('Social_Media_ID')->nullable();
            $table->TEXT('PROFESSION')->nullable();
            $table->TEXT('EDUCATION')->nullable();
            $table->TEXT('ORGANISATION_NAME')->nullable();
            $table->TEXT('NET_INCOME', 20)->nullable();
            $table->TEXT('NET_EXPENSE', 20)->nullable();
            $table->TEXT('NET_SAVINGS', 20)->nullable();
            $table->TEXT('Years_in_Organization', 5)->nullable();
            $table->TEXT('CIBIL_SCORE', 30)->nullable();
            $table->TEXT('PERSONAL_LOAN_SCORE', 30)->nullable();
            $table->TEXT('GST_EXEMPTED')->nullable();

            $table->TEXT('RM_EMP_ID')->nullable();
            $table->TEXT('RM_Designation')->nullable();
            $table->TEXT('RM_TITLE')->nullable();
            $table->TEXT('RM_NAME')->nullable();
            $table->TEXT('RM_Landline_No', 20)->nullable();
            $table->TEXT('RM_MOBILE_NO', 20)->nullable();
            $table->TEXT('RM_EMAIL_ID', 200)->nullable();
            $table->TEXT('DSA_ID')->nullable();
            $table->TEXT('DSA_NAME')->nullable();
            $table->TEXT('DSA_LANDLINE_NO', 20)->nullable();
            $table->TEXT('DSA_MOBILE_NO', 20)->nullable();
            $table->TEXT('DSA_EMAIL_ID', 200)->nullable();
            $table->TEXT('GIR_NO')->nullable();
            $table->TEXT('RATION_CARD_NO')->nullable();
            $table->TEXT('DRIVING_LINC')->nullable();
            $table->TEXT('NPR_NO')->nullable();
            $table->TEXT('PASSPORT_NO')->nullable();
            $table->TEXT('EXPORTER_CODE')->nullable();
            $table->TEXT('GST_NO')->nullable();
            $table->TEXT('Voter_ID', 100)->nullable();
            $table->TEXT('CUSTM_2')->nullable();
            $table->TEXT('CATEGORY')->nullable();
            $table->TEXT('RELIGION', 50)->nullable();
            $table->TEXT('MINORITY_STATUS')->nullable();

            $table->TEXT('CASTE', 30)->nullable();
            $table->TEXT('SUB_CAST')->nullable();
            $table->TEXT('RESERVATION_TYP')->nullable();
            $table->TEXT('Physically_Challenged', 10)->nullable();
            $table->TEXT('Weaker_Section')->nullable();
            $table->TEXT('Valued_Customer')->nullable();
            $table->TEXT('Special_Category_1')->nullable();
            $table->TEXT('Vip_Category')->nullable();
            $table->TEXT('Special_Category_2')->nullable();
            $table->TEXT('Senior_Citizen', 10)->nullable();
            $table->TEXT('Senior_Citizen_From', 30)->nullable();
            $table->TEXT('NO_OF_DEPEND', 30)->nullable();
            $table->TEXT('SPOUSE', 10)->nullable();
            $table->TEXT('CHILDREN', 10)->nullable();
            $table->TEXT('PARENTS', 10)->nullable();
            $table->TEXT('Employee_Staus', 50)->nullable();
            $table->TEXT('Employee_No', 30)->nullable();
            $table->TEXT('EMP_Date', 30)->nullable();
            $table->TEXT('Nature_of_Occupation')->nullable();
            $table->TEXT('EMPLYEER_NAME')->nullable();
            $table->TEXT('Role')->nullable();
            $table->TEXT('SPECIALIZATION')->nullable();
            $table->TEXT('EMP_GRADE')->nullable();
            $table->TEXT('DESIGNATION')->nullable();
            $table->TEXT('Office_Address')->nullable();
            $table->TEXT('Office_Phone', 20)->nullable();
            $table->TEXT('Office_EXTENSION', 20)->nullable();
            $table->TEXT('Office_Fax', 20)->nullable();
            $table->TEXT('Office_MOBILE', 20)->nullable();
            $table->TEXT('Office_PINCODE', 6)->nullable();
            $table->TEXT('Office_CITY', 6)->nullable(); // CITY -> Office_CITY

            $table->TEXT('Working_Since', 10)->nullable();
            $table->TEXT('Working_in_Current_company_Yrs', 10)->nullable();
            $table->TEXT('RETIRE_AGE', 10)->nullable();
            $table->TEXT('Nature_of_Business')->nullable();
            $table->TEXT('Annual_Income', 30)->nullable();
            $table->TEXT('Prof_Self_Employed')->nullable();
            $table->TEXT('Prof_Self_Annual_Income', 30)->nullable();
            $table->TEXT('IT_RETURN_YR1')->nullable();
            $table->TEXT('INCOME_DECLARED1', 30)->nullable();
            $table->TEXT('TAX_PAID', 30)->nullable();
            $table->TEXT('REFUND_CLAIMED1', 30)->nullable();

            $table->TEXT('IT_RETURN_YR2')->nullable();
            $table->TEXT('INCOME_DECLARED2', 30)->nullable();
            $table->TEXT('TAX_PAID2', 30)->nullable();
            $table->TEXT('REFUND_CLAIMED2', 30)->nullable();

            $table->TEXT('IT_RETURN_YR3')->nullable();
            $table->TEXT('INCOME_DECLARED3', 30)->nullable();
            $table->TEXT('TAX_PAID3', 30)->nullable();
            $table->TEXT('REFUND_CLAIMED3', 30)->nullable();

            $table->TEXT('Maiden_Title')->nullable();
            $table->TEXT('Maiden_First_Name')->nullable();
            $table->TEXT('Maiden_Middle_Name')->nullable();
            $table->TEXT('Maiden_Last_Name')->nullable();
            $table->TEXT('Father_Title')->nullable();
            $table->TEXT('Father_First_Name')->nullable();
            $table->TEXT('Father_Middle_Name')->nullable();
            $table->TEXT('Father_Last_Name')->nullable();
            $table->TEXT('Mother_Title')->nullable();
            $table->TEXT('Mother_First_Name')->nullable();
            $table->TEXT('Mothers_Maiden_Name')->nullable();
            $table->TEXT('Generic_Surname')->nullable();
            $table->TEXT('Spouse_Title')->nullable();
            $table->TEXT('Spouse_First_Name')->nullable();
            $table->TEXT('Spouse_Family_Name')->nullable();
            $table->TEXT('Identification_Mark')->nullable();
            $table->TEXT('Country_of_Domicile')->nullable();
            $table->TEXT('Qualification')->nullable();
            $table->TEXT('Nationality', 50)->nullable();
            $table->TEXT('Blood_Group', 20)->nullable();
            $table->TEXT('Offences')->nullable();
            $table->TEXT('Politically_Exposed')->nullable();

            $table->TEXT('Residence_Type')->nullable();
            $table->TEXT('AREA')->nullable();
            $table->TEXT('land_mark')->nullable(); // Land Mark -> land_mark
            $table->TEXT('Owned')->nullable();
            $table->TEXT('Rented')->nullable();
            $table->TEXT('Rent_Per_Month', 30)->nullable();
            $table->TEXT('Ancestral')->nullable();
            $table->TEXT('Staying_Since', 30)->nullable();
            $table->TEXT('EMPLOYERRS')->nullable();
        });

        // DB::statement("ALTER TABLE `borrowers` CHANGE `name_prefix` `Title` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mr., Ms., Mrs.'");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `first_name` `FirstName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `middle_name` `MiddleName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `last_name` `LastName` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `gender` `Gender` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `date_of_birth` `Birth_Date` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `mobile` `Mobile` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `email` `Email_Id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `marital_status` `Marital_Status` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrowers', function(Blueprint $table) {
            // $table->dropColumn('CUSTOMER_ID');
            $table->dropColumn('Customer_Type');
            $table->dropColumn('Resident_Status');
            $table->dropColumn('Aadhar_Number');
            $table->dropColumn('Main_Constitution');
            $table->dropColumn('Sub_Constitution');
            $table->dropColumn('KYC_Date');
            $table->dropColumn('Re_KYC_Due_Date');

            $table->dropColumn('Minor');
            $table->dropColumn('Customer_Category');
            $table->dropColumn('Alternate_Mobile_No');
            $table->dropColumn('Telephone_No');
            $table->dropColumn('Office_Telephone_No');
            $table->dropColumn('FAX_No');
            $table->dropColumn('Preferred_Language');
            $table->dropColumn('REMARKS');
            $table->dropColumn('KYC_Care_of');
            $table->dropColumn('KYC_HOUSE_NO');
            $table->dropColumn('KYC_LANDMARK');
            $table->dropColumn('KYC_Street');
            $table->dropColumn('KYC_LOCALITY');
            $table->dropColumn('KYC_PINCODE');
            $table->dropColumn('KYC_Country');
            $table->dropColumn('KYC_State');
            $table->dropColumn('KYC_District');
            $table->dropColumn('KYC_POST_OFFICE');
            $table->dropColumn('KYC_CITY');
            $table->dropColumn('KYC_Taluka');
            $table->dropColumn('KYC_Population_Group');
            $table->dropColumn('COMM_Care_of'); // COMM_Care of -> COMM_Care_of
            $table->dropColumn('COMM_HOUSE_NO'); // COMM_HOUSE NO -> COMM_HOUSE_NO
            $table->dropColumn('COMM_LANDMARK');
            $table->dropColumn('COMM_Street');
            $table->dropColumn('COMM_LOCALITY');
            $table->dropColumn('COMM_PINCODE');
            $table->dropColumn('COMM_Country');
            $table->dropColumn('COMM_State');
            $table->dropColumn('COMM_District');
            $table->dropColumn('COMM_POST_OFFICE');
            $table->dropColumn('COMM_CITY');
            $table->dropColumn('COMM_Taluka');
            $table->dropColumn('COMM_Population_Group');
            $table->dropColumn('Social_Media');
            $table->dropColumn('Social_Media_ID');
            $table->dropColumn('PROFESSION');
            $table->dropColumn('EDUCATION');
            $table->dropColumn('ORGANISATION_NAME');
            $table->dropColumn('NET_INCOME');
            $table->dropColumn('NET_EXPENSE');
            $table->dropColumn('NET_SAVINGS');
            $table->dropColumn('Years_in_Organization');
            $table->dropColumn('CIBIL_SCORE');
            $table->dropColumn('PERSONAL_LOAN_SCORE');
            $table->dropColumn('GST_EXEMPTED');

            $table->dropColumn('RM_EMP_ID');
            $table->dropColumn('RM_Designation');
            $table->dropColumn('RM_TITLE');
            $table->dropColumn('RM_NAME');
            $table->dropColumn('RM_Landline_No');
            $table->dropColumn('RM_MOBILE_NO');
            $table->dropColumn('RM_EMAIL_ID');
            $table->dropColumn('DSA_ID');
            $table->dropColumn('DSA_NAME');
            $table->dropColumn('DSA_LANDLINE_NO');
            $table->dropColumn('DSA_MOBILE_NO');
            $table->dropColumn('DSA_EMAIL_ID');
            $table->dropColumn('GIR_NO');
            $table->dropColumn('RATION_CARD_NO');
            $table->dropColumn('DRIVING_LINC');
            $table->dropColumn('NPR_NO');
            $table->dropColumn('PASSPORT_NO');
            $table->dropColumn('EXPORTER_CODE');
            $table->dropColumn('GST_NO');
            $table->dropColumn('Voter_ID');
            $table->dropColumn('CUSTM_2');
            $table->dropColumn('CATEGORY');
            $table->dropColumn('RELIGION');
            $table->dropColumn('MINORITY_STATUS');

            $table->dropColumn('CASTE');
            $table->dropColumn('SUB_CAST');
            $table->dropColumn('RESERVATION_TYP');
            $table->dropColumn('Physically_Challenged');
            $table->dropColumn('Weaker_Section');
            $table->dropColumn('Valued_Customer');
            $table->dropColumn('Special_Category_1');
            $table->dropColumn('Vip_Category');
            $table->dropColumn('Special_Category_2');
            $table->dropColumn('Senior_Citizen');
            $table->dropColumn('Senior_Citizen_From');
            $table->dropColumn('NO_OF_DEPEND');
            $table->dropColumn('SPOUSE');
            $table->dropColumn('CHILDREN');
            $table->dropColumn('PARENTS');
            $table->dropColumn('Employee_Staus');
            $table->dropColumn('Employee_No');
            $table->dropColumn('EMP_Date');
            $table->dropColumn('Nature_of_Occupation');
            $table->dropColumn('EMPLYEER_NAME');
            $table->dropColumn('Role');
            $table->dropColumn('SPECIALIZATION');
            $table->dropColumn('EMP_GRADE');
            $table->dropColumn('DESIGNATION');
            $table->dropColumn('Office_Address');
            $table->dropColumn('Office_Phone');
            $table->dropColumn('Office_EXTENSION');
            $table->dropColumn('Office_Fax');
            $table->dropColumn('Office_MOBILE');
            $table->dropColumn('Office_PINCODE');
            $table->dropColumn('Office_CITY'); // CITY -> Office_CITY

            $table->dropColumn('Working_Since');
            $table->dropColumn('Working_in_Current_company_Yrs');
            $table->dropColumn('RETIRE_AGE');
            $table->dropColumn('Nature_of_Business');
            $table->dropColumn('Annual_Income');
            $table->dropColumn('Prof_Self_Employed');
            $table->dropColumn('Prof_Self_Annual_Income');
            $table->dropColumn('IT_RETURN_YR1');
            $table->dropColumn('INCOME_DECLARED1');
            $table->dropColumn('TAX_PAID');
            $table->dropColumn('REFUND_CLAIMED1');

            $table->dropColumn('IT_RETURN_YR2');
            $table->dropColumn('INCOME_DECLARED2');
            $table->dropColumn('TAX_PAID2');
            $table->dropColumn('REFUND_CLAIMED2');

            $table->dropColumn('IT_RETURN_YR3');
            $table->dropColumn('INCOME_DECLARED3');
            $table->dropColumn('TAX_PAID3');
            $table->dropColumn('REFUND_CLAIMED3');

            $table->dropColumn('Maiden_Title');
            $table->dropColumn('Maiden_First_Name');
            $table->dropColumn('Maiden_Middle_Name');
            $table->dropColumn('Maiden_Last_Name');
            $table->dropColumn('Father_Title');
            $table->dropColumn('Father_First_Name');
            $table->dropColumn('Father_Middle_Name');
            $table->dropColumn('Father_Last_Name');
            $table->dropColumn('Mother_Title');
            $table->dropColumn('Mother_First_Name');
            $table->dropColumn('Mothers_Maiden_Name');
            $table->dropColumn('Generic_Surname');
            $table->dropColumn('Spouse_Title');
            $table->dropColumn('Spouse_First_Name');
            $table->dropColumn('Spouse_Family_Name');
            $table->dropColumn('Identification_Mark');
            $table->dropColumn('Country_of_Domicile');
            $table->dropColumn('Qualification');
            $table->dropColumn('Nationality');
            $table->dropColumn('Blood_Group');
            $table->dropColumn('Offences');
            $table->dropColumn('Politically_Exposed');

            $table->dropColumn('Residence_Type');
            $table->dropColumn('AREA');
            $table->dropColumn('land_mark');
            $table->dropColumn('Owned');
            $table->dropColumn('Rented');
            $table->dropColumn('Rent_Per_Month');
            $table->dropColumn('Ancestral');
            $table->dropColumn('Staying_Since');
            $table->dropColumn('EMPLOYERRS');
        });

        // DB::statement("ALTER TABLE `borrowers` CHANGE `Title` `name_prefix` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mr., Ms., Mrs.'");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `FirstName` `first_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `MiddleName` `middle_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `LastName` `last_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `Gender` `gender` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `Birth_Date` `date_of_birth` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `Mobile` `mobile` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `Email_Id` `email` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
        // DB::statement("ALTER TABLE `borrowers` CHANGE `Marital_Status` `marital_status` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''");
    }
}
