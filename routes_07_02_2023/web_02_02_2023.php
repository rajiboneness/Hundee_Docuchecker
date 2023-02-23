<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TestController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('home');
})->name('welcome');

Route::any('test/index', [TestController::class, 'index'])->name('test.index');
Route::any('test/check', [TestController::class, 'check'])->name('test.check');

Route::any('webhook/esign', [WebhookController::class, 'esign'])->name('webhook.esign');


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// API ROUTE PDF DOWNLOAD - no auth needed - public for ZOOP access
Route::get('user/borrower/{borrowerId}/agreement/{agreementId}/pdf/view', [PDFController::class, 'showDynamicPdf'])->name('user.borrower.agreement.pdf.view');
Route::get('user/borrower/{borrowerId}/agreement/{agreementId}/pdf/{borrowerAgreementsId}/view/web', [PDFController::class, 'showDynamicPdf'])->name('user.borrower.agreement.pdf.view.web');

// ZOOP for e-signing - public URL
// Route::get('/zoop/borrower', [ZoopController::class, 'borrower'])->name('zoop.borrower');
// Route::get('/zoop/co-borrower', [ZoopController::class, 'coBorrower'])->name('zoop.coborrower');
// Route::get('/zoop/authorised-signatory', [ZoopController::class, 'authorisedSignatory'])->name('zoop.authorised-signatory');

Route::get('/zoop/borrower/{id}', [ZoopController::class, 'eSigningAll'])->name('zoop.borrower');

// link updated for Multiple Esigning simultaneously
Route::get('/zoop/user/esigning/{id}',[ZoopController::class,'EsignBlade'])->name('zoop.user.esigning.mail.click');

// after esign redirection
Route::any('zoop/redirect', [ZoopController::class, 'redirect'])->name('zoop.redirect');
Route::any('zoop/response', [ZoopController::class, 'response'])->name('zoop.response');

// user common routes
Route::group(['prefix' => 'user', 'middleware' => ['auth', 'permission']], function () {
    
    // esign
    Route::get('{borrowerId}/borrowerAgreement/{borrowerAgreementId}/rfq/{rfqId}/esign', [ZoopController::class, 'detail'])->name('user.esign.borrower.detail');
    // Route::post('esign/submit', [ZoopController::class, 'esignSubmit'])->name('user.esign.submit');
    
    // New Esign Mail to All user simultaneously!
    Route::post('esign/submit', [ZoopController::class, 'MultipleMail'])->name('user.esign.submit');
    
    Route::post('submit/final/agreement',[ZoopController::class, 'submitFinalAgreement'])->name('user.esign.submitAgreement');

    // profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [HomeController::class, 'profile'])->name('user.profile');
        Route::post('update', [HomeController::class, 'profileUpdate'])->name('user.profile.update');
        Route::post('password/update', [HomeController::class, 'passwordUpdate'])->name('user.password.update');
        Route::post('image/update', [HomeController::class, 'imageUpdate'])->name('user.profile.image.update');
    });

    // employee
    Route::group(['prefix' => 'employee'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.employee.list');
        Route::get('/create', [UserController::class, 'create'])->name('user.employee.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.employee.store');
        Route::post('/show', [UserController::class, 'show'])->name('user.employee.show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.employee.edit');
        Route::post('/{id}/update', [UserController::class, 'update'])->name('user.employee.update');
        Route::post('/destroy', [UserController::class, 'destroy'])->name('user.employee.destroy');
        Route::post('/block', [UserController::class, 'block'])->name('user.employee.block');
    });

   // borrower
    Route::group(['prefix' => 'borrower'], function () {
        Route::get('/', [BorrowerController::class, 'index'])->name('user.borrower.list');
        Route::post('/load', [BorrowerController::class, 'indexLoad'])->name('user.borrower.load');
        // Route::get('/old', [BorrowerController::class, 'indexOld'])->name('user.borrower.oldlist');
        Route::get('/create', [BorrowerController::class, 'create'])->name('user.borrower.create');
        Route::post('/store', [BorrowerController::class, 'store'])->name('user.borrower.store');
        Route::post('/show', [BorrowerController::class, 'show'])->name('user.borrower.show');
        Route::get('/{id}/view', [BorrowerController::class, 'details'])->name('user.borrower.details');
        Route::get('/{id}/edit', [BorrowerController::class, 'edit'])->name('user.borrower.edit');
        Route::post('/{id}/update', [BorrowerController::class, 'update'])->name('user.borrower.update');
        Route::post('/destroy', [BorrowerController::class, 'destroy'])->name('user.borrower.destroy');
        Route::post('/csv/upload', [BorrowerController::class, 'upload'])->name('user.borrower.csv.upload');
        Route::get('/csv/export', [BorrowerController::class, 'export'])->name('user.borrower.csv.export');
        Route::post('/csv/upload/test', [BorrowerController::class, 'uploadTest'])->name('user.borrower.csv.upload.test');
        Route::post('/fetch-office-name',[BorrowerController::class, 'FetchOfficeName'])->name('user.borrower.fetch-office-name');
        Route::post('/add-relationship-manager',[BorrowerController::class, 'AddRelationshipManager'])->name('user.borrower.add-relationship-manager');

        //set rm
        Route::get('/set-relationship-manager/{borrowerid}', [BorrowerController::class, 'setRm'])->name('user.borrower.setRm');
        Route::post('/set-relationship-manager', [BorrowerController::class, 'storeRm'])->name('user.borrower.storeRm');
        Route::post('/update-rm-comments/{id}', [BorrowerController::class, 'storeRmComments'])->name('user.borrower.storeRmComments');

        //Update Annexture 5 doc
        Route::get('/set-annexure-V-document/borrower-agreement/{baid}', [BorrowerController::class, 'setAnnexure5doc'])->name('user.borrower.setAnnexure5doc');
        Route::post('/set-annexure-V-document/borrower-agreement/{baid}', [BorrowerController::class, 'storeAnnexure5doc'])->name('user.borrower.storeAnnexure5doc');

        //Store Vernacular Declaration
        Route::post('/set-vernacular-declaration/borrower-agreement',[BorrowerController::class, 'storeVernacularDeclaration'])->name('user.borrower.storeVernacularDeclaration');

        // borrwoer agreement setup
        Route::get('/{id}/agreement/setup', [BorrowerController::class, 'agreementSetups'])->name('user.borrower.agreement.setup');
        Route::post('/agreement/create', [BorrowerController::class, 'agreementCreate'])->name('user.borrower.agreement.create');
        Route::post('/agreement/destroy', [BorrowerController::class, 'agreementDestroy'])->name('user.borrower.agreement.destroy');

        // Docu Checker
        Route::get('/{id}/docu-checker', [BorrowerController::class,'DocucheckerIndex'])->name('user.borrower.docu-checker-index');
        Route::post('/docuchecker-status', [BorrowerController::class,'DocucheckerStatus'])->name('user.borrower.docuchecker-status');
        Route::post('/docuchecker-expirydate', [BorrowerController::class,'DocucheckerExpiryDate'])->name('user.borrower.docuchecker-expirydate');

        


        // agreement
        Route::get('/{id}/agreement', [BorrowerController::class, 'agreementFields'])->name('user.borrower.agreement');
        Route::post('/agreement/store', [BorrowerController::class, 'agreementStore'])->name('user.borrower.agreement.store');
        Route::post('/agreement/document/upload', [BorrowerController::class, 'uploadToServer'])->name('user.borrower.agreement.document.upload');
        Route::post('/agreement/document/show', [BorrowerController::class, 'showDocument'])->name('user.borrower.agreement.document.show');
        Route::post('/agreement/document/verify', [BorrowerController::class, 'verifyDocument'])->name('user.borrower.agreement.document.verify');
        Route::post('/agreement/stamp/use/', [BorrowerController::class, 'stampUseInAgreement'])->name('user.borrower.agreement.stamp.use');


        // pdf
        // Route::get('/{borrowerId}/agreement/{agreementId}/pdf/{borrowerAgreementsId}/view/web', [PDFController::class, 'showDynamicPdf'])->name('user.borrower.agreement.pdf.view.web');
        // Route::get('/{borrowerId}/agreement/{agreementId}/pdf/view/{borrowerAgreementsId}', [PDFController::class, 'showDynamicPdf'])->name('user.borrower.agreement.pdf.view');
        Route::get('/{borrowerId}/agreement/{agreementId}/pdf/page-3/view', [PDFController::class, 'showDynamicPdfPage3'])->name('user.borrower.agreement.pdf.page3.view');
        Route::get('/{borrowerId}/agreement/{agreementId}/pdf/page-24/view', [PDFController::class, 'showDynamicPdfPage24'])->name('user.borrower.agreement.pdf.page24.view');
        Route::get('/{borrowerId}/agreement/{agreementId}/pdf/page-25/view', [PDFController::class, 'showDynamicPdfPage25'])->name('user.borrower.agreement.pdf.page25.view');
        Route::get('/{borrowerId}/agreement/{agreementId}/pdf/page-31/view', [PDFController::class, 'showDynamicPdfPage31'])->name('user.borrower.agreement.pdf.page31.view');
        // Route::get('/{borrowerId}/agreement/{agreementId}/pdf/view', [PDFController::class, 'generateDynamicPdf'])->name('user.borrower.agreement.pdf.download');

        // after agreement is filled
        Route::get('/{id}/agreement/view', [BorrowerController::class, 'agreementFieldsView'])->name('user.borrower.agreement.view');


        // html to pdf convert
        Route::post('agreement/html/to/pdf', [BorrowerController::class, 'pdfConversion'])->name('agreement.html.to.pdf');
    
        //Auto fill co borrower data using pan number
        Route::get('auto-fill-data-using-panid', [BorrowerController::class,'autofillDataPan'])->name('autofill.data.bypan');

    });

    // role
    Route::group(['prefix' => 'employee/role'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('user.role.list');
        Route::post('/store', [RoleController::class, 'store'])->name('user.role.store');
        Route::post('/show', [RoleController::class, 'show'])->name('user.role.show');
        Route::post('/destroy', [RoleController::class, 'destroy'])->name('user.role.destroy');
    });

    // agreement
    Route::group(['prefix' => 'agreement'], function () {
        Route::get('/', [AgreementController::class, 'index'])->name('user.agreement.list');
        Route::get('/create', [AgreementController::class, 'create'])->name('user.agreement.create');
        Route::post('/store', [AgreementController::class, 'store'])->name('user.agreement.store');
        Route::post('/show', [AgreementController::class, 'show'])->name('user.agreement.show');
        Route::get('/{id}/view', [AgreementController::class, 'details'])->name('user.agreement.details');
        Route::get('/{id}/edit', [AgreementController::class, 'edit'])->name('user.agreement.edit');
        Route::post('/{id}/update', [AgreementController::class, 'update'])->name('user.agreement.update');
        Route::post('/destroy', [AgreementController::class, 'destroy'])->name('user.agreement.destroy');
        Route::get('/{id}/fields', [AgreementController::class, 'fieldsIndex'])->name('user.agreement.fields');
        Route::post('/fields/store', [AgreementController::class, 'fieldsStore'])->name('user.agreement.fields.store');
        // pdf
        Route::get('/{id}/pdf/view', [PDFController::class, 'showPdf'])->name('user.agreement.pdf.view');
        Route::get('/{id}/pdf/download', [PDFController::class, 'generatePdf'])->name('user.agreement.pdf.download');
        
         // documents
        Route::get('/{id}/documents', [AgreementController::class, 'documentsIndex'])->name('user.agreement.documents.list');
        Route::post('/documents/store', [AgreementController::class, 'documentsStore'])->name('user.agreement.documents.store');
        Route::post('/documents/show', [AgreementController::class, 'documentsShow'])->name('user.agreement.documents.show');
        Route::post('/documents/destroy', [AgreementController::class, 'documentsDestroy'])->name('user.agreement.documents.destroy');
        
        // New Document 
        Route::post('/document/new-store', [AgreementController::class,'NewDocumentsStore'])->name('user.agreement.documents.new-store');
        Route::post('/document/new-delete', [AgreementController::class,'NewDocumentsDelete'])->name('user.agreement.documents.new-delete');
    
        //Agreement FSD
        Route::post('/fsd/store',[AgreementController::class,'AgreementFsdStore'])->name('user.borrower.agreement.fsd.store');
        Route::get('{id}/fsd/{fsd}/delete',[AgreementController::class,'AgreementFsdDelete'])->name('user.borrower.agreement.fsd.delete');
    });
    // Ducument Management
    Route::group(['prefix' => 'document'], function(){
        Route::get('/', [DocumentController::class, 'index'])->name('user.document.list');
        Route::post('/create', [DocumentController::class, 'create'])->name('user.document.create');
        Route::post('/edit', [DocumentController::class, 'edit'])->name('user.document.edit');
        Route::post('/delete', [DocumentController::class, 'delete'])->name('user.document.delete');
        Route::post('/view', [DocumentController::class, 'view'])->name('user.document.view');
        Route::post('/sub_delete', [DocumentController::class, 'sub_delete'])->name('user.document.sub_delete');
    });
    Route::group(['prefix' => 'docu-checker'], function(){
        // Customer Docu Checker
        Route::get('/customer', [DocumentController::class, 'CustomerList'])->name('user.docu-checker.customer');
        Route::post('/add-loan-type', [DocumentController::class, 'AddLoanType'])->name('user.docu-checker.add-loan-type');
        Route::get('/index/{id}', [DocumentController::class, 'Customerchecker'])->name('user.docu-checker.index');
        Route::post('/docuchecker-status', [DocumentController::class,'DocucheckerStatus'])->name('user.borrower.docuchecker-status');
        Route::post('/docuchecker-expirydate', [DocumentController::class,'DocucheckerExpiryDate'])->name('user.borrower.docuchecker-expirydate');
    });

    

    // field
    Route::group(['prefix' => 'field'], function () {
        Route::get('/', [FieldController::class, 'index'])->name('user.field.list');
        Route::get('/create', [FieldController::class, 'create'])->name('user.field.create');
        Route::post('/store', [FieldController::class, 'store'])->name('user.field.store');
        Route::post('/show', [FieldController::class, 'show'])->name('user.field.show');
        Route::get('/{id}/view', [FieldController::class, 'details'])->name('user.field.details');
        Route::get('/{id}/edit', [FieldController::class, 'edit'])->name('user.field.edit');
        Route::post('/{id}/update', [FieldController::class, 'update'])->name('user.field.update');
        Route::post('/destroy', [FieldController::class, 'destroy'])->name('user.field.destroy');
    });

    // logs
    Route::group(['prefix' => 'logs'], function () {
        Route::get('/', [LogController::class, 'logsIndex'])->name('user.logs');
        Route::get('/mail', [LogController::class, 'logsMail'])->name('user.logs.mail');
        Route::get('/notification', [LogController::class, 'logsNotification'])->name('user.logs.notification');
        Route::post('/notification/readall', [LogController::class, 'notificationReadAll'])->name('user.logs.notification.readall');
        Route::get('/activity', [LogController::class, 'activityIndex'])->name('user.logs.activity');
    });

    // office management
    Route::group(['prefix' => 'office'], function () {
        Route::get('/', [OfficeController::class, 'index'])->name('user.office.list');
        Route::post('/store', [OfficeController::class, 'store'])->name('user.office.store');
        Route::post('/show', [OfficeController::class, 'show'])->name('user.office.show');
        Route::post('/update', [OfficeController::class, 'update'])->name('user.office.update');
        Route::post('/destroy', [OfficeController::class, 'destroy'])->name('user.office.destroy');
    });

    // department management
    Route::group(['prefix' => 'employee/department'], function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('user.department.list');
        Route::post('/store', [DepartmentController::class, 'store'])->name('user.department.store');
        Route::post('/show', [DepartmentController::class, 'show'])->name('user.department.show');
        Route::patch('/update', [DepartmentController::class, 'update'])->name('user.department.update');
        Route::post('/destroy', [DepartmentController::class, 'destroy'])->name('user.department.destroy');
    });

    // designation management
    Route::group(['prefix' => 'employee/designation'], function () {
        Route::get('/', [DesignationController::class, 'index'])->name('user.designation.list');
        Route::post('/store', [DesignationController::class, 'store'])->name('user.designation.store');
        Route::post('/show', [DesignationController::class, 'show'])->name('user.designation.show');
        Route::patch('/update', [DesignationController::class, 'update'])->name('user.designation.update');
        Route::post('/destroy', [DesignationController::class, 'destroy'])->name('user.designation.destroy');
    });

    // Route::prefix(['prefix' => 'qualification'])->group(function () {
    //     Route::get('/', [QualificationController::class, 'index'])->name('user.highest.qualification.list');
    //     Route::post('/store', [QualificationController::class, 'store'])->name('user.highest.qualification.store');
    //     Route::post('/show', [QualificationController::class, 'show'])->name('user.highest.qualification.show');
    //     Route::patch('/update', [QualificationController::class, 'update'])->name('user.highest.qualification.update');
    //     Route::post('/destroy', [QualificationController::class, 'destroy'])->name('user.highest.qualification.destroy');
    // });

    // Qualifiaction
    Route::group(['prefix' => 'qualification'], function () {
        Route::get('/', [QualificationController::class, 'index'])->name('user.qualification.list');
        Route::post('/store', [QualificationController::class, 'store'])->name('user.qualification.store');
        Route::post('/show', [QualificationController::class, 'show'])->name('user.qualification.show');
        Route::patch('/update', [QualificationController::class, 'update'])->name('user.qualification.update');
        Route::post('/destroy', [QualificationController::class, 'destroy'])->name('user.qualification.destroy');
    });

    // FSD MANAGEMENT
    Route::group(['prefix' => 'fsd'], function () {
        Route::get('/', [FSDController::class, 'index'])->name('user.fsd.list');
        Route::post('/store', [FSDController::class, 'store'])->name('user.fsd.store');
        Route::post('/show', [FSDController::class, 'show'])->name('user.fsd.show');
        Route::patch('/update', [FSDController::class, 'update'])->name('user.fsd.update');
        Route::post('/destroy', [FSDController::class, 'destroy'])->name('user.fsd.destroy');
    });

    // notification
    Route::post('/read', [HomeController::class, 'notificationRead'])->name('user.notification.read');

    // Estamp
    Route::group(['prefix' => 'estamp'], function () {
        Route::get('/', [EstampController::class, 'index'])->name('user.estamp.list');
        // Route::get('/create', [FieldController::class, 'create'])->name('user.field.create');
        Route::post('/store', [EstampController::class, 'store'])->name('user.estamp.store');
        Route::post('/show', [EstampController::class, 'show'])->name('user.estamp.show');
        Route::get('/{id}/view', [EstampController::class, 'details'])->name('user.estamp.details');
        Route::get('/{id}/edit', [EstampController::class, 'edit'])->name('user.estamp.edit');
        Route::put('/{id}/update', [EstampController::class, 'update'])->name('user.estamp.update');
        Route::get('/ExportEstamp', [EstampController::class, 'ExportEstamp'])->name('user.estamp.ExportEstamp');
        Route::post('/destroy', [EstampController::class, 'destroy'])->name('user.estamp.destroy');
    });
});
