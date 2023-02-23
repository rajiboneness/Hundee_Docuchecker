@extends('layouts.auth.master')

@section('title', 'Setup agreement fields')

@section('content')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .select2-container {
            width: 100% !important;
        }
        .vd-modal h2{
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        .vd-modal h3{
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 0.03em;
        }
        .vd-modal label, .vd-modal h4{
            font-weight: 500;
            font-size: 15px;
            letter-spacing: 0.03em;
        }
    </style>

    <section class="content">
        <link rel="stylesheet" href="{{ asset('admin/plugins/bs-stepper/css/bs-stepper.min.css') }}">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <a href="{{ route('user.borrower.list') }}" class="btn btn-sm btn-primary"> <i
                                        class="fas fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="font-weight-light text-dark">
                                        <span class="font-weight-normal" title="Borrower's name">
                                            {{ ucwords($data->name_prefix) }}
                                            {{ $data->full_name }}
                                        </span> - <span title="Agreement name">{{ $data->agreement_name }}</span>
                                    </h5>
                                </div>
                                @if ($data->agreementRfq > 0)
                                <div class="col-md-6 text-sm-left">
                                    <div class="mb-2">
                                        <a href="{{ route('user.borrower.setAnnexure5doc', [$id]) }}"
                                            class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-{{ $data->annX5dataCount == 0 ? 'danger' : 'success' }}">
                                            {!! $data->annX5dataCount == 0
                                                ? '<i class="fas fa-info-circle mr-2"></i>'
                                                : '<i class="fas fa-check-circle mr-2"></i>' !!}
                                            ANEXTURE-V Content
                                        </a>
                                        <a href="javascript:void(0)" class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-{{CheckVernaculationDataSet($id) ? 'success' : 'danger'}}" data-toggle="modal"
                                            data-target="#vernacularDeclarationModal">
                                            <i class="fas fa-{{CheckVernaculationDataSet($id) ? 'check' : 'info'}}-circle mr-2"></i>
                                            Vernacular Declaration
                                        </a>
                                        <div class="modal fade vd-modal" id="vernacularDeclarationModal" tabindex="-1"
                                           role="dialog" aria-labelledby="vernacularDeclarationModalLabel"
                                           aria-hidden="true">
                                           <div class="modal-dialog modal-lg" role="document">
                                               <form method="POST" action="{{route('user.borrower.storeVernacularDeclaration')}}" class="modal-content">
                                                   @csrf
                                                   <div class="modal-header">
                                                        <h2 class="modal-title" id="vernacularDeclarationModalLabel">
                                                           Set VERNACULAR DECLARATION for Agreement
                                                        </h2>
                                                       <button type="button" class="close" data-dismiss="modal"
                                                           aria-label="Close">
                                                           <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <input type="hidden" name="borrower_agreement_id" value="{{$id}}">
                                                       <label class="d-flex justify-content-between w-100">
                                                           Is Vernacular declaration required?
                                                           <input type="checkbox" name="is_required" onclick="checkvdata()" value="true" {{CheckVernaculationData($id) && VernaculationData($id)['is_required'] == 1 ? 'checked' : ''}}>
                                                       </label>
                                                       <div id="vdata">
                                                            <div class="row justify-content-between align-items-center">
                                                                <div class="col-lg-8 text-left">
                                                                    <div class="form-label">
                                                                        <label>
                                                                            SET VERNACULAR DECLARATION LANGUAGE
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 text-right">
                                                                    <select name="vernacular_language" class="form-control">
                                                                        <option {{CheckVernaculationData($id) && VernaculationData($id)['vernacular_language'] == 'bengali' ? 'selected' : ''}} value="bengali">Bengali</option>
                                                                        <option {{CheckVernaculationData($id) && VernaculationData($id)['vernacular_language'] == 'hindi' ? 'selected' : ''}} value="hindi">Hindi</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <hr class="my-2">
                                                            <div class="col">
                                                                <h3 class="my-1" style="text-align: left;">Set executant's Data</h3>
                                                                <hr class="my-2">
                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Executant Name</label>
                                                                        <input type="text" class="form-control" placeholder="Executant Name" value="{{VernaculationData($id)['executant_name']}}" name="executant_name">
                                                                        @error('executant_name') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Executant Email</label>
                                                                        <input type="text" class="form-control" placeholder="Executant Email" value="{{VernaculationData($id)['executant_email']}}" name="executant_email">
                                                                        @error('executant_email') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-left">
                                                                    <label for="form-label" style="font-weight: 400;">Executant Address</label>
                                                                    <input type="text" class="form-control" placeholder="Executant Address" value="{{VernaculationData($id)['executant_adress']}}" name="executant_adress">
                                                                    @error('executant_adress') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                </div>

                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Executant Fathers/Husband Name</label>
                                                                        <input type="text" value="{{VernaculationData($id)['executant_fathers_or_husband_name']}}" class="form-control" placeholder="Executant Fathers/Husband Name" name="executant_fathers_or_husband_name">
                                                                        @error('executant_fathers_or_husband_name') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Executant Relation (Father Or Husband?)</label>
                                                                        <select class="form-control" name="is_executant_fathers_or_husband">
                                                                            <option {{VernaculationData($id)['is_executant_fathers_or_husband'] == 'Father' ? 'selected' : ''}} value="Father">Father</option>
                                                                            <option {{VernaculationData($id)['is_executant_fathers_or_husband'] == 'Husband' ? 'selected' : ''}} value="Husband">Husband</option>
                                                                        </select>
                                                                        @error('is_executant_fathers_or_husband') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Executant Phone Number</label>
                                                                        <input type="text" class="form-control" value="{{VernaculationData($id)['executant_phone_number']}}" placeholder="Executant Phone Number" name="executant_phone_number">
                                                                        @error('executant_phone_number') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="my-2">
                                                            <div class="col">
                                                                <h3 class="my-1" style="text-align: left;">
                                                                    Set Vernacular Language checks for:
                                                                </h3>
                                                                <hr class="my-2">
                                                                <div>
                                                                    <div class="row mt-3 w-100 align-items-center justify-content-between">
                                                                        <div class="col-6 text-left">
                                                                            <h4>Borrower : </h4>
                                                                        </div>
                                                                        <div class="col-6 text-right">
                                                                            <label class="form-label">
                                                                                <input type="checkbox" onclick="showNextInput(this)" name="borrower_check" value="Yes" {{CheckVernaculationData($id) && VernaculationData($id)['borrower_check'] == 'Yes' ? 'checked' : ''}}>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" value="{{VernaculationData($id)['executant_know_borrower_years']}}" class="form-control {{CheckVernaculationData($id) && VernaculationData($id)['borrower_check'] == 'Yes' ? '' : 'd-none'}}" placeholder="Years Executant know borrower" name="executant_know_borrower_years" id="executant_know_borrower_years">                                                                    
                                                                </div>

                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Borrower Fathers/Husband Name</label>
                                                                        <input type="text" value="{{VernaculationData($id)['borrower_father_or_husband_name']}}" class="form-control" placeholder="Borrower Fathers/Husband Name" name="borrower_father_or_husband_name">
                                                                        @error('borrower_father_or_husband_name') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Borrower Relation (Father Or Husband?)</label>
                                                                        <select class="form-control" name="is_borrower_father_or_husband">
                                                                            <option {{VernaculationData($id)['is_borrower_father_or_husband'] == 'Father' ? 'selected' : ''}} value="Father">Father</option>
                                                                            <option {{VernaculationData($id)['is_borrower_father_or_husband'] == 'Husband' ? 'selected' : ''}} value="Husband">Husband</option>
                                                                        </select>
                                                                        @error('is_borrower_father_or_husband') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            @if (coborrowerAndgurantor($data->rfqDetails->id)['name_cb1'] != '')
                                                                <div>
                                                                    <div class="row mt-3 w-100 align-items-center justify-content-between">
                                                                        <div class="col-6 text-left">
                                                                            <h5>Co-Borrower : </h5>
                                                                        </div>
                                                                        <div class="col-6 text-right">
                                                                            <label class="form-label">
                                                                                <input type="checkbox" onclick="showNextInput(this)" name="coborrower_check" value="Yes" {{CheckVernaculationData($id) && VernaculationData($id)['coborrower_check'] == 'Yes' ? 'checked' : ''}}>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" value="{{VernaculationData($id)['executant_know_coborrower_years']}}" class="form-control {{CheckVernaculationData($id) && VernaculationData($id)['coborrower_check'] == 'Yes' ? '' : 'd-none'}}" placeholder="Years Executant know co-borrower" name="executant_know_coborrower_years" id="executant_know_coborrower_years">
                                                                </div>

                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Co-Borrower Fathers/Husband Name</label>
                                                                        <input type="text" value="{{VernaculationData($id)['coborrower_father_or_husband_name']}}" class="form-control" placeholder="Co-Borrower Fathers/Husband Name" name="coborrower_father_or_husband_name">
                                                                        @error('coborrower_father_or_husband_name') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Co-Borrower Relation (Father Or Husband?)</label>
                                                                        <select class="form-control" name="is_coborrower_father_or_husband">
                                                                            <option {{VernaculationData($id)['is_coborrower_father_or_husband'] == 'Father' ? 'selected' : ''}} value="Father">Father</option>
                                                                            <option {{VernaculationData($id)['is_coborrower_father_or_husband'] == 'Husband' ? 'selected' : ''}} value="Husband">Husband</option>
                                                                        </select>
                                                                        @error('is_coborrower_father_or_husband') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>

                                                            @endif
                                                            @if (coborrowerAndgurantor($data->rfqDetails->id)['name_cb2'] != '')
                                                                <div>
                                                                    <div class="row mt-3 w-100 align-items-center justify-content-between">
                                                                        <div class="col-6 text-left">
                                                                            <h4>Co-borrower-2: </h4>
                                                                        </div>
                                                                        <div class="col-6 text-right">
                                                                            <label class="form-label">
                                                                                <input type="checkbox" onclick="showNextInput(this)" name="co2borrower_check" value="Yes" {{CheckVernaculationData($id) && VernaculationData($id)['co2borrower_check'] == 'Yes' ? 'checked' : ''}}>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" value="{{VernaculationData($id)['executant_know_coborrower2_years']}}" class="form-control {{CheckVernaculationData($id) && VernaculationData($id)['co2borrower_check'] == 'Yes' ? '' : 'd-none'}}" placeholder="Years Executant know co-borrower2" name="executant_know_coborrower2_years" id="executant_know_coborrower2_years">
                                                                </div>

                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Co-Borrower2 Fathers/Husband Name</label>
                                                                        <input type="text" value="{{VernaculationData($id)['co2borrower_father_or_husband_name']}}" class="form-control" placeholder="Co-Borrower2 Fathers/Husband Name" name="co2borrower_father_or_husband_name">
                                                                        @error('co2borrower_father_or_husband_name') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Co-Borrower2 Relation (Father Or Husband?)</label>
                                                                        <select class="form-control" name="is_co2borrower_father_or_husband">
                                                                            <option {{VernaculationData($id)['is_co2borrower_father_or_husband'] == 'Father' ? 'selected' : ''}} value="Father">Father</option>
                                                                            <option {{VernaculationData($id)['is_co2borrower_father_or_husband'] == 'Husband' ? 'selected' : ''}} value="Husband">Husband</option>
                                                                        </select>
                                                                        @error('is_co2borrower_father_or_husband') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>

                                                            @endif
                                                            @if (coborrowerAndgurantor($data->rfqDetails->id)['name_gur'] != '')
                                                                <div>
                                                                    <div class="row mt-3 w-100 align-items-center justify-content-between">
                                                                        <div class="col-6 text-left">
                                                                            <h4>Guarantor : </h4>
                                                                        </div>
                                                                        <div class="col-6 text-right">
                                                                            <label class="form-label">
                                                                                <input type="checkbox" onclick="showNextInput(this)" name="gurrantor_check" value="Yes" {{CheckVernaculationData($id) && VernaculationData($id)['gurrantor_check'] == 'Yes' ? 'checked' : ''}}>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" value="{{VernaculationData($id)['executant_know_gurrantor_years']}}" class="form-control {{CheckVernaculationData($id) && VernaculationData($id)['gurrantor_check'] == 'Yes' ? '' : 'd-none'}}" placeholder="Years Executant know guarantor" name="executant_know_gurrantor_years" id="executant_know_gurrantor_years">
                                                                </div>

                                                                <div class="d-flex">
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Guarantor Fathers/Husband Name</label>
                                                                        <input type="text" value="{{VernaculationData($id)['gurrantor_father_or_husband_name']}}" class="form-control" placeholder="Guarantor Fathers/Husband Name" name="gurrantor_father_or_husband_name">
                                                                        @error('gurrantor_father_or_husband_name') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                    <div class="form-group col-6 text-left">
                                                                        <label for="form-label" style="font-weight: 400;">Guarantor Relation (Father Or Husband?)</label>
                                                                        <select class="form-control" name="is_gurrantor_father_or_husband">
                                                                            <option {{VernaculationData($id)['is_gurrantor_father_or_husband'] == 'Father' ? 'selected' : ''}} value="Father">Father</option>
                                                                            <option {{VernaculationData($id)['is_gurrantor_father_or_husband'] == 'Husband' ? 'selected' : ''}} value="Husband">Husband</option>
                                                                        </select>
                                                                        @error('is_gurrantor_father_or_husband') <p class="text-danger"><small>{{$message}}</small></p> @enderror
                                                                    </div>
                                                                </div>

                                                            @endif
                                                        </div>
                                                   </div>
                                                   <div class="modal-footer">
                                                       <button type="button" class="btn btn-secondary"
                                                           data-dismiss="modal">Close</button>
                                                       <button type="submit" class="btn btn-primary">Save</button>
                                                   </div>
                                               </form>
                                           </div>
                                        </div>

                                        @if ($data->stampPaper1 && $data->stampPaper2 && $data->stampPaper3 && $data->stampPaper4)
                                            {{-- @if ($data->stampPaper1->front_file_path != '' || $data->stampPaper2->front_file_path != '' || $data->stampPaper3->front_file_path != '' || $data->stampPaper4->front_file_path != '') --}}
                                            <a href="#viewPdfModal" data-toggle="modal" class="btn btn-sm btn-success {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}}">
                                                <i class="fas fa-check-circle mr-2"></i> Stamp Paper
                                            </a>
                                        @else
                                            <a href="#viewPdfModal" data-toggle="modal" class="btn btn-sm btn-danger {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}}">
                                                <i class="fas fa-info-circle mr-2"></i> Stamp Paper
                                            </a>
                                        @endif

                                        
                                    </div>

                                    {{-- @if ($data->annX5dataCount > 0 && $data->annX5dataCount > 0)
                                        
                                    @endif --}}

                                    {{-- <a href="{{ route('user.borrower.agreement.pdf.view', [$data->borrower_id, $data->agreement_id]) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-file-pdf"></i>View PDF</a> --}}

                                    {{-- <a href="{{ route('user.borrower.agreement.pdf.view', [$data->borrower_id, $data->agreement_id, 'status' => 'download']) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-download"></i> Download PDF</a> --}}
                                   
                                </div>
                                <div class="col-md-6 text-sm-right d-flex align-items-center justify-content-end">
                                    @if ($data->annX5dataCount > 0 && $data->annX5dataCount > 0 && $data->stampPaper1 && $data->stampPaper2 && $data->stampPaper3 && $data->stampPaper4 && CheckVernaculationDataSet($id))
                                        <div>
                                            <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id]) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-file-pdf"></i> View PDF
                                            </a>

                                            <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id, 'status' => 'download']) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-download"></i> Download PDF
                                            </a>
                                        </div>
                                        @if(auth()->user()->user_type == 1 || auth()->user()->user_type == 4)
                                            <a href="javascript:void(0)"
                                                onclick="return annexureVdata(`{{ route('user.esign.borrower.detail', [$data->borrower_id, $id, $data->rfqDetails->id]) }}`)"
                                                id="eSignatureSendBtn" class="btn btn-sm btn-secondary ml-1 border-0">
                                                <i class="fas fa-signature"></i> Send for eSignature
                                            </a>
                                        @endif
                                    @else
                                        <div>
                                            <button
                                                class="btn btn-sm btn-primary disabled">
                                                <i class="fas fa-file-pdf"></i> View PDF
                                            </button>

                                            <button
                                                class="btn btn-sm btn-primary disabled">
                                                <i class="fas fa-download"></i> Download PDF
                                            </button>
                                        </div>
                                        @if(auth()->user()->user_type == 1 || auth()->user()->user_type == 4)
                                            <button class="btn btn-sm btn-secondary disabled ml-1 border-0">
                                                <i class="fas fa-signature"></i> Send for eSignature
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                @endif
                                

                                @if ($data->agreementRfq == 0)
                                    <div class="col-md-12 mt-3">
                                        <p class="small text-muted mb-0"><span class="text-danger">*</span> Please fill up
                                            the form first to view PDF</p>
                                    </div>
                                @endif
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">

                                    <input type="hidden" name="borrower_id" id="borrower_id"
                                        value="{{ request()->id }}">

                                    <div class="bs-stepper">
                                        <div class="bs-stepper-header" role="tablist">
                                            <div class="step" data-target="#information-part">
                                                <button type="button" class="step-trigger" role="tab"
                                                    aria-controls="information-part" id="information-part-trigger">
                                                    <span class="bs-stepper-circle"><i class="fas fa-user"></i></span>
                                                    <span class="bs-stepper-label">Borrower's information</span>
                                                </button>
                                            </div>
                                            {{-- <div class="line"></div>
                                        <div class="step" data-target="#document-part">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="document-part" id="document-part-trigger">
                                                <span class="bs-stepper-circle"><i
                                                        class="fas fa-file-import"></i></span>
                                                <span class="bs-stepper-label">Documents</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#submit-part">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="submit-part" id="submit-part-trigger">
                                                <span class="bs-stepper-circle"><i class="fas fa-save"></i></span>
                                                <span class="bs-stepper-label">Submit</span>
                                            </button>
                                        </div> --}}
                                        </div>
                                        <div class="bs-stepper-content">
                                            <div id="information-part" class="content" role="tabpanel"
                                                aria-labelledby="information-part-trigger" style="display: block;">
                                                @if (count($data->fields) > 0)
                                                    <form action="{{ route('user.borrower.agreement.store') }}"
                                                        method="POST" id="agreement_form" enctype="multipart/form-data">
                                                        @csrf
                                                        <table class="table table-sm table-bordered table-hover table-striped"
                                                            id="agreementFieldsTable">
                                                            
                                                            {{-- Application Id - New Field Set --}}
                                                            
                                                            <tr>
                                                                <td colspan="3" class="field-heading">Application ID</td>
                                                            </tr>
                                                            <tr>         
                                                                <td style="width: 50px">1
                                                                </td>
                                                                <td class="fields_col-1">
                                                                    @if($data->borrower_agreement[0]->application_id == null)
                                                                        <label class="font-weight-bold">Application Id (Must be of 4 digits)</label>
                                                                    @else
                                                                        <label class="font-weight-bold">Application Id</label>
                                                                    @endif
                                                                </td>
                                                                @if($data->borrower_agreement[0]->application_id == null)
                                                                    <td class="fields_col-2">
                                                                        <div class="w-100 d-flex"><input type="number" max="9999" placeholder="App ID" class="form-control form-control-sm " name="application_id"></div>
                                                                        @error('application_id') <p class="text-danger text-sm">{{$message}}</p> @enderror
                                                                    </td>
                                                                @else
                                                                    <td class="fields_col-2">
                                                                        <div class="w-100 d-flex"><input type="text" readonly class="form-control form-control-sm " value="{{$data->borrower_agreement[0]->application_id}}"></div>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            {{-- ------------------------------ --}}


                                                            @forelse ($data->parentFields as $indexParent => $parent)
                                                                <tr>
                                                                    <td colspan="3" class="field-heading">
                                                                        {{ $parent->name }}</td>
                                                                </tr>
                                                                @php $count = 1; @endphp
                                                                @foreach ($parent->childRelation as $indexChild => $item)
                                                                    {{-- Order/ Position of these fields is maintained in FieldParent modal --}}

                                                                    <!-- concept of customer id is removed -->
                                                                    @if ($item->childField->name == 'Customer ID')
                                                                        <!-- borrower officially valid documents hidden by default -->
                                                                    @elseif ($item->childField->name == 'Aadhar card number of the Borrower')

                                                                    @elseif ($item->childField->name == 'Voter card number of the Borrower')

                                                                    @elseif ($item->childField->name == 'Bank account number of the Borrower')

                                                                    @elseif ($item->childField->name == 'Bank name of the Borrower')

                                                                    @elseif ($item->childField->name == 'Bank IFSC of the Borrower')

                                                                    @elseif ($item->childField->name == 'Driving license number of the Borrower')

                                                                    @elseif ($item->childField->name == 'Driving license issue date of the Borrower')

                                                                    @elseif ($item->childField->name == 'Driving license expiry date of the Borrower')

                                                                    @elseif ($item->childField->name == 'Electricity bill number of the Borrower')

                                                                    @elseif ($item->childField->name == 'Passport number of the Borrower')

                                                                    @elseif ($item->childField->name == 'Passport issue date of the Borrower')

                                                                    @elseif ($item->childField->name == 'Passport expiry date of the Borrower')
                                                                        <!-- co-borrower 1 officially valid documents hidden by default -->
                                                                    @elseif ($item->childField->name == 'Aadhar card number of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Voter card number of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Bank account number of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Bank name of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Bank IFSC of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Driving license number of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Driving license issue date of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Driving license expiry date of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Electricity bill number of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Passport number of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Passport issue date of the Co-Borrower')

                                                                    @elseif ($item->childField->name == 'Passport expiry date of the Co-Borrower')
                                                                        <!-- co-borrower 2 officially valid documents hidden by default -->
                                                                    @elseif ($item->childField->name == 'Aadhar card number of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Voter card number of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Bank account number of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Bank name of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Bank IFSC of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Driving license number of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Driving license issue date of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Driving license expiry date of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Electricity bill number of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Passport number of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Passport issue date of the Co-Borrower 2')

                                                                    @elseif ($item->childField->name == 'Passport expiry date of the Co-Borrower 2')
                                                                        <!-- guarantor officially valid documents hidden by default -->
                                                                    @elseif ($item->childField->name == 'Aadhar card number of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Voter card number of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Bank account number of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Bank name of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Bank IFSC of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Driving license number of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Driving license issue date of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Driving license expiry date of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Electricity bill number of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Passport number of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Passport issue date of the Guarantor')

                                                                    @elseif ($item->childField->name == 'Passport expiry date of the Guarantor')
                                                                        <!-- other date of emi credit hidden by default -->
                                                                    @elseif ($item->childField->name == 'Other date of EMI credit')
                                                                        <!-- Name of the check-off Company hidden by default -->
                                                                    @elseif ($item->childField->name == 'Name of the check-off Company')
                                                                        <!-- Post-dated cheques hidden by default -->
                                                                    @elseif ($item->childField->name == 'Post date cheque 1 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 1 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 1 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 1 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 2 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 2 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 2 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 2 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 3 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 3 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 3 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 3 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 4 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 4 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 4 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 4 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 5 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 5 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 5 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 5 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 6 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 6 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 6 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 6 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 7 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 7 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 7 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 7 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 8 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 8 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 8 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 8 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 9 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 9 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 9 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 9 amount')

                                                                    @elseif ($item->childField->name == 'Post date cheque 10 description')

                                                                    @elseif ($item->childField->name == 'Post date cheque 10 cheque number')

                                                                    @elseif ($item->childField->name == 'Post date cheque 10 date')

                                                                    @elseif ($item->childField->name == 'Post date cheque 10 amount')
                                                                        {{-- this is always permanent address by default --}}
                                                                    {{-- @elseif ($item->childField->name == 'Resident status of the Borrower') --}}

                                                                    {{-- @elseif ($item->childField->name == 'Resident status of the Co-Borrower') --}}

                                                                    @elseif ($item->childField->name == 'Resident status of the Co-Borrower 2')

                                                                    {{-- @elseif ($item->childField->name == 'Resident status of the Guarantor') --}}
                                                                    
                                                                    {{-- Only First 5 post cheque fields will be shown --}}

                                                                    @elseif ($item->childField->name == 'Post date cheque 6')

                                                                    @elseif ($item->childField->name == 'Post date cheque 7')
                                                                    
                                                                    @elseif ($item->childField->name == 'Post date cheque 8')
                                                                    
                                                                    @elseif ($item->childField->name == 'Post date cheque 9')
                                                                    
                                                                    @elseif ($item->childField->name == 'Post date cheque 10')
                                                                    @else
                                                                        <tr>
                                                                            {{-- <td style="width: 50px">{{ $indexChild + 1 }}</td> --}}
                                                                            <td style="width: 50px">{{ $count }}
                                                                            </td>
                                                                            <td class="fields_col-1">
                                                                                <label
                                                                                    class="font-weight-bold">{!! $item->childField->name !!}
                                                                                    {!! $item->childField->required == 1 ? '<span class="text-danger" title="This field is required">*</span>' : '' !!}</label>
                                                                            </td>
                                                                            <td class="fields_col-2">

                                                                                @php
                                                                                    if ($data->agreementRfq > 0) {
                                                                                        $formType = 'show';
                                                                                    } else {
                                                                                        $formType = 'create';
                                                                                    }
                                                                                @endphp

                                                                                {!! form3lements(
                                                                                    $item->childField->id,
                                                                                    $item->childField->name,
                                                                                    $item->childField->inputType->name,
                                                                                    $item->childField->value,
                                                                                    $item->childField->key_name,
                                                                                    '',
                                                                                    $borrowerId = $data->borrower_id,
                                                                                    $formType,
                                                                                ) !!}
                                                                            </td>
                                                                        </tr>
                                                                        @php $count++; @endphp
                                                                    @endif
                                                                @endforeach
                                                            @empty
                                                                <tr>
                                                                    <td colspan="100%"><em>No records found</em></td>
                                                                </tr>
                                                            @endforelse

                                                            {{-- data submit / edit --}}
                                                            @if(auth()->user()->user_type == 1 || auth()->user()->user_type == 3)
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="position: sticky;bottom: -1px;z-index: 99;background-color: #e9e9e9;">
                                                                        <div class="w-100 text-right">
                                                                            <input type="hidden" name="borrower_id"
                                                                                value="{{ $data->borrower_id }}">
                                                                            <input type="hidden" name="agreement_id"
                                                                                value="{{ $data->agreement_id }}">
                                                                            <input type="hidden" name="borrower_agreement_id"
                                                                                value="{{ $id }}">

                                                                            @if ($data->agreementRfq > 0)
                                                                                {{-- <a href="#viewPdfModal" data-toggle="modal" class="btn btn-sm btn-primary" target="_blank">
                                                                                    <i class="fas fa-file-pdf"></i> View
                                                                                    PDF
                                                                                </a> --}}
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-primary form_submit_btn">Update data
                                                                                    <i class="fas fa-upload"></i></button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-primary form_submit_btn">Submit data
                                                                                    <i class="fas fa-upload"></i></button>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </form>
                                                @else
                                                    <div class="w-100">
                                                        <p><em>No fields found for this agreement</em></p>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- <div id="document-part" class="content" role="tabpanel"
                                            aria-labelledby="document-part-trigger">
                                            <div class="card border shadow-none rounded-0">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @forelse ($data->requiredDocuments as $index => $documentHead)
                                                            <div class="col-sm-12">
                                                                <p class="text-dark font-weight-bold">
                                                                    {{ $index + 1 }} {{ $documentHead->name }}
                                                                </p>
                                                            </div>

                                                            @foreach ($documentHead->siblingsDocuments as $childItem)
                                                                <div class="col-sm-2">
                                                                    <div class="card">
                                                                        <div class="card-header p-2">
                                                                            {{ $childItem->name }}</div>
                                                                        <div class="card-body p-2">
                                                                            <div class="image__preview">
                                                                                <img class="card-img-top img-fluid"
                                                                                    src="{{ documentSrc($childItem->id, $data->borrower_id, 'image') }}"
                                                                                    alt="Cover Image"
                                                                                    id="image__preview{{ $childItem->id }}">
                                                                            </div>
                                                                            <div class="row mt-2">
                                                                                <div class="col-12 text-center">
                                                                                    <form class="fileUploadForm"
                                                                                        enctype="multipart/form-data"
                                                                                        id="image_upload_form{{ $childItem->id }}">

                                                                                        <input type="file"
                                                                                            name="document"
                                                                                            id="file_{{ $childItem->id }}"
                                                                                            class="borrower-document-upload d-none"
                                                                                            onchange="docUpload(this, {{ $childItem->id }})">

                                                                                        <input type="hidden"
                                                                                            name="agreement_document_id"
                                                                                            id="agreement_document_id_{{ $childItem->id }}"
                                                                                            value="{{ $childItem->id }}">

                                                                                        {!! documentSrc($childItem->id, $data->borrower_id, 'action') !!}

                                                                                        <button type="submit"
                                                                                            class="btn btn-xs btn-success mb-2"
                                                                                            id="image__upload_label{{ $childItem->id }}"
                                                                                            style="display:none"
                                                                                            onclick="docUpload({{ $childItem->id }})">
                                                                                            Upload <i
                                                                                                class="fas fa-upload"></i></button>

                                                                                        <label
                                                                                            class="btn btn-xs btn-primary mb-2"
                                                                                            id="image_upload_status_{{ $childItem->id }}"
                                                                                            style="display:none"></label>

                                                                                        <div
                                                                                            class="progress progress-sm">
                                                                                            <div class="progress-bar"
                                                                                                id="progress_{{ $childItem->id }}"
                                                                                                role="progressbar"
                                                                                                style="width: 0%;display:none;"
                                                                                                aria-valuenow="0"
                                                                                                aria-valuemin="0"
                                                                                                aria-valuemax="100">
                                                                                            </div>
                                                                                        </div>

                                                                                        <span title="Remove image"
                                                                                            class="remove_selected_file text-danger"
                                                                                            id="remove__image{{ $childItem->id }}"
                                                                                            style="display: none;"
                                                                                            onclick="clearimg({{ $childItem->id }})"><i
                                                                                                class="fas fa-times"></i></span>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @empty
                                                            <div class="col-sm-12 text-center">
                                                                <p class="text-muted"><em>No documents to
                                                                        upload</em></p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="card-footer"
                                                    style="position: sticky;bottom: 0;z-index: 99;background-color: #e9e9e9;padding: 0.3rem;">
                                                    <div class="text-right">
                                                        <button class="btn btn-sm btn-primary"
                                                            onclick="stepper.previous()"><i
                                                                class="fas fa-chevron-left"></i> Back to borrower's
                                                            form</button>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="stepper.next()">Go to Submit <i
                                                                class="fas fa-chevron-right"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                            {{-- <div id="submit-part" class="content" role="tabpanel" aria-labelledby="submit-part-trigger"> --}}
                                            {{-- <button class="btn btn-sm btn-primary" onclick="stepper.previous()"><i class="fas fa-chevron-left"></i> Back to Documents</button> --}}

                                            {{-- <a href="{{ route('user.borrower.list') }}" class="btn btn-sm btn-primary">Submit <i class="fas fa-save"></i></a> --}}
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- view pdf MODAL -->
    <div class="modal" id="viewPdfModal" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stamp paper</h5>
                    <div class="d-flex">
                        <a href="{{route('user.estamp.list')}}" target="_blank" class="btn btn-outline-dark">Add New Stamp</a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    {{-- <p class="small mb-2">Main agreement</p>
                    <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id, 'status' => 'download']) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-download"></i> Download PDF</a>

                    <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id]) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-file-pdf"></i> View PDF</a>

                    <br><br> --}}

                    {{-- <p class="small mb-2">Stamp paper</p> --}}

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact"
                                role="tab" aria-controls="contact" aria-selected="false" style="font-size: 12px;">
                                "PERSONAL LOAN FACILITY AGREEMENT" - Rs 100 Stamp paper
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true" style="font-size: 12px;">
                                "ANNEXURE II" - Rs 10 Stamp
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false" style="font-size: 12px;">
                                "ANNEXURE III" - Rs 50 Stamp
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="home-tab1" data-toggle="tab" href="#home1" role="tab"
                                aria-controls="home" aria-selected="true" style="font-size: 12px;">
                                "ANNEXURE VII" - Rs 10 Stamp
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="contact" role="tabpanel"
                            aria-labelledby="contact-tab">
                            {{-- <h6 class="badge badge-primary">Available Stamp: <span id="span-contact">{{ availableStampNew(100)->count() }}</span></h6> --}}

                            <div class="card" style="box-shadow: 0 0 1px rgb(0 0 0 / 0%), 0 1px 3px rgb(0 0 0 / 0%);">
                                {{-- <div class="card-header">
                                    <div class="card-title font-weight-bold">Available 100Rs Estamps - {{ availableStampNew(100)->count() }}</div>
                                </div> --}}
                                <div class="card-body p-1 py-3">
                                    <div id="100rs_stamp_image">
                                        @if ($data->stampPaper1)
                                            <div class="w-100">
                                                <p class="small text-muted mb-2">Used Stamp</p>
                                                <img src="{{ asset($data->stampPaper1->front_file_path) }}"
                                                    alt="" style="height: 100px">
                                            </div>
                                            <hr>
                                        @endif
                                    </div>

                                    <div class="card-title font-weight-bold mb-3 text-muted">Available 100Rs Estamps -
                                        <span class="text-dark"
                                            id="100rs_available_stamp">{{ availableStampNew(100)->count() }}</span> </div>

                                    <table class="table table-sm table-bordered table-hover table-striped"
                                        id="showRoleTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Unique Stamp Code</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (availableStampNew(100)->count() > 0)
                                                @foreach (availableStampNew(100) as $key => $stamp)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $stamp->unique_stamp_code }}</td>
                                                        <td class="text-right">
                                                            <div class="single-line">
                                                                @if (checkUsedStamp($stamp->id, $data->borrower_id, $data->agreement_id) == 0)
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-dark action-button"
                                                                        title="View"
                                                                        onclick="useStamp({{ $stamp->id }},{{ $data->borrower_id }}, {{ $data->agreement_id }}, 3)"
                                                                        id="stamp_{{ $stamp->id }}_3">Use</a>
                                                                @else
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-danger action-button"
                                                                        title="View"
                                                                        id="stamp_{{ $stamp->id }}_3">Used</a>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%" class="text-center">No data found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            {{-- <h6 class="badge badge-primary">Available Stamp: <span id="span-home">{{ availableStampNew(10)->count() }}</span></h6> --}}
                            <div class="card" style="box-shadow: 0 0 1px rgb(0 0 0 / 0%), 0 1px 3px rgb(0 0 0 / 0%);">
                                {{-- <div class="card-header">
                                    <div class="card-title font-weight-bold">Available 10Rs Estamps</div>
                                </div> --}}
                                <div class="card-body p-1 py-3">
                                    <div id="10rs_stamp_image_page_1">
                                        @if ($data->stampPaper2)
                                            <div class="w-100">
                                                <p class="small text-muted mb-2">Used Stamp</p>
                                                <img src="{{ asset($data->stampPaper2->front_file_path) }}"
                                                    alt="" style="height: 100px">
                                            </div>
                                            <hr>
                                        @endif
                                    </div>
                                    <div class="card-title font-weight-bold mb-3 text-muted">Available 10Rs Estamps - <span
                                            class="text-dark available_10rs_stamp">{{ availableStampNew(10)->count() }}</span>
                                    </div>

                                    <table class="table table-sm table-bordered table-hover table-striped"
                                        id="showRoleTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Unique Stamp Code</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (availableStampNew(10)->count() > 0)
                                                @foreach (availableStampNew(10) as $key => $stamp)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $stamp->unique_stamp_code }}</td>
                                                        <td class="text-right">
                                                            <div class="single-line">
                                                                @if (checkUsedStamp($stamp->id, $data->borrower_id, $data->agreement_id) == 0)
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-dark action-button stamp_{{ $stamp->id }}"
                                                                        title="View"
                                                                        onclick="useStamp({{ $stamp->id }},{{ $data->borrower_id }}, {{ $data->agreement_id }}, 24)"
                                                                        id="stamp_{{ $stamp->id }}_24">Use</a>
                                                                @else
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-danger action-button"
                                                                        title="View"
                                                                        id="stamp_{{ $stamp->id }}_24">Used</a>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%" class="text-center">No data found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                            {{-- <h6 class="badge badge-primary">Available Stamp: <span id="span-profile">{{ availableStampNew(50)->count() }}</span></h6> --}}

                            <div class="card" style="box-shadow: 0 0 1px rgb(0 0 0 / 0%), 0 1px 3px rgb(0 0 0 / 0%);">
                                {{-- <div class="card-header">
                                    <div class="card-title font-weight-bold">Available 50Rs Estamps</div>
                                </div> --}}
                                <div class="card-body p-1 py-3">

                                    <div id="50rs_stamp_image">
                                        @if ($data->stampPaper3)
                                            <div class="w-100">
                                                <p class="small text-muted mb-2">Used Stamp</p>
                                                <img src="{{ asset($data->stampPaper3->front_file_path) }}"
                                                    alt="" style="height: 100px">
                                            </div>
                                            <hr>
                                        @endif
                                    </div>

                                    <div class="card-title font-weight-bold mb-3 text-muted">Available 50Rs Estamps - <span
                                            class="text-dark available_50rs_stamps">{{ availableStampNew(50)->count() }}</span>
                                    </div>

                                    <table class="table table-sm table-bordered table-hover table-striped"
                                        id="showRoleTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Unique Stamp Code</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (availableStampNew(50)->count() > 0)
                                                @foreach (availableStampNew(50) as $key => $stamp)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $stamp->unique_stamp_code }}</td>
                                                        <td class="text-right">
                                                            <div class="single-line">
                                                                @if (checkUsedStamp($stamp->id, $data->borrower_id, $data->agreement_id) == 0)
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-dark action-button"
                                                                        title="View"
                                                                        onclick="useStamp({{ $stamp->id }},{{ $data->borrower_id }}, {{ $data->agreement_id }},25)"
                                                                        id="stamp_{{ $stamp->id }}_25">Use</a>
                                                                @else
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-danger action-button"
                                                                        title="View"
                                                                        id="stamp_{{ $stamp->id }}_25">Used</a>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%" class="text-center">No data found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="home1" role="tabpanel" aria-labelledby="home-tab1">

                            {{-- <h6 class="badge badge-primary">Available Stamp: <span id="span-home-tab1">{{ availableStampNew(10)->count() }}</span></h6> --}}

                            <div class="card" style="box-shadow: 0 0 1px rgb(0 0 0 / 0%), 0 1px 3px rgb(0 0 0 / 0%);">
                                {{-- <div class="card-header">
                                    <div class="card-title font-weight-bold">Available 10Rs Estamps</div>
                                </div> --}}
                                <div class="card-body p-1 py-3">

                                    <div id="100rs_stamp_image_page2">
                                        @if ($data->stampPaper4)
                                            <div class="w-100">
                                                <p class="small text-muted mb-2">Used Stamp</p>
                                                <img src="{{ asset($data->stampPaper4->front_file_path) }}"
                                                    alt="" style="height: 100px">
                                            </div>
                                            <hr>
                                        @endif
                                    </div>

                                    <div class="card-title font-weight-bold mb-3 text-muted">Available 10Rs Estamps - <span
                                            class="text-dark available_10rs_stamp">{{ availableStampNew(10)->count() }}</span>
                                    </div>

                                    <table class="table table-sm table-bordered table-hover table-striped"
                                        id="showRoleTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Unique Stamp Code</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (availableStampNew(10)->count() > 0)
                                                @foreach (availableStampNew(10) as $key => $stamp)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $stamp->unique_stamp_code }}</td>
                                                        <td class="text-right">
                                                            <div class="single-line">
                                                                @if (checkUsedStamp($stamp->id, $data->borrower_id, $data->agreement_id) == 0)
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-dark action-button stamp_{{ $stamp->id }}"
                                                                        title="View"
                                                                        onclick="useStamp({{ $stamp->id }},{{ $data->borrower_id }}, {{ $data->agreement_id }}, 31)"
                                                                        id="stamp_{{ $stamp->id }}_31">Use</a>
                                                                @else
                                                                    <a href="javascript: void(0)"
                                                                        class="badge badge-danger action-button"
                                                                        title="View"
                                                                        id="stamp_{{ $stamp->id }}_31">Used</a>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%" class="text-center">No data found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <!-- bs stepper -->
    <script src="{{ asset('admin/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $('.form_submit_btn').click(function(e){
            if($('input').is('[name="application_id"]')){
                if($('input[name="application_id"]').val() == '' || $('input[name="application_id"]').val().length > 4 ){
                    // Swal.fire(
                    //     'Alert!',
                    //     'Please enter a valid application id first',
                    //     'warning'
                    // );
                    toastFire('danger','Please enter a valid application id first');
                }else{
                    $('#agreement_form').submit();
                }
            }else{
                $('#agreement_form').submit();
            }
        })
    </script>

    <script>
        function checkvdata(){
            if($('input[name="is_required"]').prop('checked') == true){
                $('#vdata').show();
            }
            else{
                $('#vdata').hide();
            }
        }   
        checkvdata();
    </script>
    <script>
        // select tag to select2 plugin
        //$('select').select2();
        // step by step document upload
        var stepper = new Stepper($('.bs-stepper')[0]);

        // document upload
        function docUpload(input, agreement_document_id) {
            // after selecting an image, show preview
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image__preview' + agreement_document_id).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
                $('#image__preview_label' + agreement_document_id).hide();
                $('#image__upload_label' + agreement_document_id).show();
                $('#remove__image' + agreement_document_id).show();
            }

            $('#image_upload_form' + agreement_document_id).submit(function(event) {
                event.preventDefault();
                $('#image_upload_status_' + agreement_document_id).removeClass('btn-primary').removeClass(
                    'btn-success').removeClass('btn-danger');
                var form = $(this);
                $('#progress_' + agreement_document_id).css('width', '0').show();
                $(this).ajaxSubmit({
                    type: 'POST',
                    url: '{{ route('user.borrower.agreement.document.upload') }}',
                    // data: form.serialize(),
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'agreement_document_id': agreement_document_id,
                        'borrower_id': $('#borrower_id').val(),
                        'document': $('#file_' + agreement_document_id).val(),
                    },
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $('#progress_' + agreement_document_id).text(percentComplete +
                                    '%');
                                $('#progress_' + agreement_document_id).css('width',
                                    percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#remove__image' + agreement_document_id).prop('disabled', true).hide();
                        $('#image__upload_label' + agreement_document_id).prop('disabled', true).hide();
                        $('#image_upload_status_' + agreement_document_id).addClass('disabled')
                            .addClass('btn-primary mb-2').html('Uploading...').show();
                    },
                    success: function(data) {
                        // console.log("success");
                        // console.log(data);
                        $('#image_upload_form' + agreement_document_id).trigger("reset");
                        // var obj = $.parseJSON(data);
                        if (data.response_code == 200) {
                            $('#image_upload_status_' + agreement_document_id).hide().html('')
                                .removeClass('disabled').removeClass('btn-primary');
                            $('#image_upload_status_' + agreement_document_id).addClass('btn-success')
                                .html('Complete <i class="uil uil-check"></i>').show();
                            setTimeout(function() {
                                // $('#image_upload_status_'+agreement_document_id).css('visibility', 'hidden');
                                $('#progress_' + agreement_document_id).css('width', '0')
                                    .hide();
                                $('#image_upload_status_' + agreement_document_id).hide();
                                $('#image__preview_label' + agreement_document_id).show();
                                $('#image__upload_label' + agreement_document_id).prop(
                                    'disabled', false);
                            }, 3500);
                        } else {
                            $('#image_upload_status_' + agreement_document_id).hide().html('')
                                .removeClass('disabled').removeClass('btn-primary');
                            $('#image_upload_status_' + agreement_document_id).addClass('btn-danger')
                                .html(data.message).show();
                            setTimeout(function() {
                                // $('#image_upload_status_'+agreement_document_id).css('visibility', 'hidden');
                                $('#progress_' + agreement_document_id).css('width', '0')
                                    .hide();
                                $('#image_upload_status_' + agreement_document_id).hide();
                                $('#image__preview_label' + agreement_document_id).show();
                                $('#image__upload_label' + agreement_document_id).prop(
                                    'disabled', false);
                            }, 5000);
                        }
                    },
                    error: function(data) {
                        console.log("error");
                        console.log(data);
                    }
                });
            });
        }

        // clear image after browse, before upload
        function clearimg(count) {
            // $("#image_"+count).val('');
            $('#image__preview' + count).attr('src', '{{ asset('admin/static-required/blank.png') }}');
            // $('#image__preview_label'+count).removeClass('btn-success').html('Browse <i class="uil uil-camera-plus"></i>');
            $('#image__preview_label' + count).show();
            $('#image__upload_label' + count).hide();
            $('#remove__image' + count).hide();
        }

        // view document
        function viewUploadedDocument(id) {
            $.ajax({
                url: '{{ route('user.borrower.agreement.document.show') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id: id
                },
                success: function(result) {
                    // console.log(result.message);
                    let content = '';
                    if (result.response_code == 200) {
                        content += '<p class="text-muted small mb-1">Borrower</p><h6>' + result.message
                            .agreement_document_upload.borrower_details.name_prefix + ' ' + result.message
                            .agreement_document_upload.borrower_details.full_name + '</h6>';

                        content += '<p class="text-muted small mb-2 mt-3">Agreement</p>';

                        content += '<div class="card"><div class="card-header p-2"><h3 class="card-title">' +
                            result.message.agreement_document_upload.document_details.name +
                            '</h3><div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button><a class="btn btn-tool" href="' +
                            result.file +
                            '" download><i class="fas fa-download"></i></a></div></div><div class="card-body p-0"><img src="' +
                            result.file + '" class="w-100" alt="' + result.message.agreement_document_upload
                            .document_details.name + '"></div></div>';

                        content += '<p class="text-muted small mb-2 mt-3">Verify</p>';
                        if (result.message.agreement_document_upload.verify == 0) {
                            content +=
                                '<div id="verifyDocBigger"><a href="javascript: void(0)" class="btn btn-sm btn-danger" onclick="verifyUploadedDocument(' +
                                result.message.agreement_document_upload.id + ', ' + result.message
                                .agreement_document_upload.verify +
                                ')">Document is unverified. Tap here to verify</a></div>';
                        } else {
                            content +=
                                '<div id="verifyDocBigger"><a href="javascript: void(0)" class="btn btn-sm btn-success" onclick="verifyUploadedDocument(' +
                                result.message.agreement_document_upload.id + ', ' + result.message
                                .agreement_document_upload.verify +
                                ')">Document is verified. Tap here to remove verification</a></div>';
                        }
                    } else {
                        content += '<p class="text-muted small mb-1">No data found. Try again</p>';
                    }
                    $('#appendContent').html(content);
                    $('#userDetailsModalLabel').text('Document details');
                    $('#userDetails').modal('show');
                }
            });
        }

        // verify document
        function verifyUploadedDocument(id, type) {
            let typeShow = '';
            if (type == 1) {
                typeShow = 'un-verify';
            } else {
                typeShow = 'verify';
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + typeShow + ' the record',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f44336',
                cancelButtonColor: '#8b8787',
                customClass: {
                    confirmButton: 'box-shadow-danger',
                },
                confirmButtonText: 'Yes, ' + typeShow + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('user.borrower.agreement.document.verify') }}',
                        method: 'post',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            id: id,
                            type: type
                        },
                        success: function(result) {
                            if (result.response_code == 200) {
                                if (result.updateStatusCode == 1) {
                                    $('#verifyDocBigger').html(
                                        '<a href="javascript: void(0)" class="btn btn-sm btn-success" onclick="verifyUploadedDocument(' +
                                        id +
                                        ', 0)">Document is verified. Tap here to remove verification</a>'
                                    );

                                    $('#verifyDocToggle' + id).removeClass('btn-danger').addClass(
                                        'btn-success').html(
                                        '<i class="fas fa-clipboard-check"></i>');
                                } else {
                                    $('#verifyDocBigger').html(
                                        '<a href="javascript: void(0)" class="btn btn-sm btn-danger" onclick="verifyUploadedDocument(' +
                                        id + ', 1)">Document is unverified. Tap here to verify</a>');

                                    $('#verifyDocToggle' + id).removeClass('btn-success').addClass(
                                        'btn-danger').html('<i class="fas fa-question-circle"></i>');
                                }
                            } else {
                                $('#verifyDocBigger').html(
                                    '<a href="javascript: void(0)" class="btn btn-sm btn-success" onclick="verifyUploadedDocument(' +
                                    id + ')">Something happened. Try again</a>');
                            }
                            // $('#appendContent').html(content);
                            // $('#userDetailsModalLabel').text('Borrower details');
                            // $('#userDetails').modal('show');
                        }
                    });
                }
            });
        }

        // edit agreement fields
        function editAgreementFields() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to edit the agreement fields. All the disabled fields will be active",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, edit it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#agreementFieldsTable input, select, textarea').prop('disabled', false);
                }
            })
        }

        // Officially Valid Documents of the Borrower on click starts
        $('input[name="field_name[officiallyvaliddocumentsoftheborrower]"]').click(function() {
            var inputValue = $(this).attr("value");
            ovdBorrower(inputValue);
        });

        function ovdBorrower(inputValue) {
            if (inputValue == 'Aadhar card') {
                $('input[name="field_name[aadharcardnumberoftheborrower]"]').show();

                $('input[name="field_name[votercardnumberoftheborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').hide();
                $('input[name="field_name[banknameoftheborrower]"]').hide();
                $('input[name="field_name[bankifscoftheborrower]"]').hide();
                $('#borrowerDrivingLicenseHolder').hide();
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').hide();
                $('#borrowerPassportHolder').hide();
            } else if (inputValue == 'Voter card') {
                $('input[name="field_name[votercardnumberoftheborrower]"]').show();

                $('input[name="field_name[aadharcardnumberoftheborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').hide();
                $('input[name="field_name[banknameoftheborrower]"]').hide();
                $('input[name="field_name[bankifscoftheborrower]"]').hide();
                $('#borrowerDrivingLicenseHolder').hide();
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').hide();
                $('#borrowerPassportHolder').hide();
            } else if (inputValue == 'Bank statement') {
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').show();
                $('input[name="field_name[banknameoftheborrower]"]').show();
                $('input[name="field_name[bankifscoftheborrower]"]').show();

                $('input[name="field_name[aadharcardnumberoftheborrower]"]').hide();
                $('input[name="field_name[votercardnumberoftheborrower]"]').hide();
                $('#borrowerDrivingLicenseHolder').hide();
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').hide();
                $('#borrowerPassportHolder').hide();
            } else if (inputValue == 'Driving license') {
                $('#borrowerDrivingLicenseHolder').show();

                $('input[name="field_name[aadharcardnumberoftheborrower]"]').hide();
                $('input[name="field_name[votercardnumberoftheborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').hide();
                $('input[name="field_name[banknameoftheborrower]"]').hide();
                $('input[name="field_name[bankifscoftheborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').hide();
                $('#borrowerPassportHolder').hide();
            } else if (inputValue == 'Electricity bill') {
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').show();

                $('input[name="field_name[aadharcardnumberoftheborrower]"]').hide();
                $('input[name="field_name[votercardnumberoftheborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').hide();
                $('input[name="field_name[banknameoftheborrower]"]').hide();
                $('input[name="field_name[bankifscoftheborrower]"]').hide();
                $('#borrowerDrivingLicenseHolder').hide();
                $('#borrowerPassportHolder').hide();
            } else if (inputValue == 'Passport') {
                $('#borrowerPassportHolder').show();

                $('input[name="field_name[aadharcardnumberoftheborrower]"]').hide();
                $('input[name="field_name[votercardnumberoftheborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').hide();
                $('input[name="field_name[banknameoftheborrower]"]').hide();
                $('input[name="field_name[bankifscoftheborrower]"]').hide();
                $('#borrowerDrivingLicenseHolder').hide();
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').hide();
            } else {
                $('input[name="field_name[aadharcardnumberoftheborrower]"]').hide();
                $('input[name="field_name[votercardnumberoftheborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheborrower]"]').hide();
                $('input[name="field_name[banknameoftheborrower]"]').hide();
                $('input[name="field_name[bankifscoftheborrower]"]').hide();
                $('#borrowerDrivingLicenseHolder').hide();
                $('#borrowerPassportHolder').hide();
                $('input[name="field_name[electricitybillnumberoftheborrower]"]').hide();
            }
        }
        var ovdBorrowerSelected = $('input[name="field_name[officiallyvaliddocumentsoftheborrower]"]:checked').val();
        ovdBorrower(ovdBorrowerSelected);
        // Officially Valid Documents of the Borrower on click ends

        // Officially Valid Documents of the Co-Borrower on click starts
        $('input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]').click(function() {
            var inputValue = $(this).attr("value");
            // console.log(inputValue);
            ovdCoBorrower(inputValue);
        });

        function ovdCoBorrower(inputValue) {
            if (inputValue == 'Aadhar card') {
                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').show();

                $('input[name="field_name[votercardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').hide();
                $('input[name="field_name[banknameofthecoborrower]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').hide();
                $('#coBorrower1DrivingLicenseHolder').hide();
                $('#coBorrower1PassportHolder').hide();
            } else if (inputValue == 'Voter card') {
                $('input[name="field_name[votercardnumberofthecoborrower]"]').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').hide();
                $('input[name="field_name[banknameofthecoborrower]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').hide();
                $('#coBorrower1DrivingLicenseHolder').hide();
                $('#coBorrower1PassportHolder').hide();
            } else if (inputValue == 'Bank statement') {
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').show();
                $('input[name="field_name[banknameofthecoborrower]"]').show();
                $('input[name="field_name[bankifscofthecoborrower]"]').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').hide();
                $('#coBorrower1DrivingLicenseHolder').hide();
                $('#coBorrower1PassportHolder').hide();

            } else if (inputValue == 'Driving license') {
                $('#coBorrower1DrivingLicenseHolder').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').hide();
                $('input[name="field_name[banknameofthecoborrower]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').hide();
                $('#coBorrower1PassportHolder').hide();
            } else if (inputValue == 'Electricity bill') {
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').hide();
                $('input[name="field_name[banknameofthecoborrower]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower]"]').hide();
                $('#coBorrower1DrivingLicenseHolder').hide();
                $('#coBorrower1PassportHolder').hide();
            } else if (inputValue == 'Passport') {
                $('#coBorrower1PassportHolder').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').hide();
                $('input[name="field_name[banknameofthecoborrower]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').hide();
                $('#coBorrower1DrivingLicenseHolder').hide();
            } else {
                $('input[name="field_name[aadharcardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower]"]').hide();
                $('input[name="field_name[banknameofthecoborrower]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower]"]').hide();
                $('#coBorrower1DrivingLicenseHolder').hide();
                $('#coBorrower1PassportHolder').hide();
            }
        }
        var ovdCoBorrowerSelected = $('input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]:checked').val();
        ovdCoBorrower(ovdCoBorrowerSelected);
        // Officially Valid Documents of the Co-Borrower on click ends

        // Officially Valid Documents of the Co-Borrower 2 on click starts
        $('input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]').click(function() {
            var inputValue = $(this).attr("value");
            // console.log(inputValue);
            ovdCoBorrower2(inputValue);
        });

        function ovdCoBorrower2(inputValue) {
            if (inputValue == 'Aadhar card') {
                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').show();

                $('input[name="field_name[votercardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[banknameofthecoborrower2]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower2]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').hide();
                $('#coBorrower2DrivingLicenseHolder').hide();
                $('#coBorrower2PassportHolder').hide();
            } else if (inputValue == 'Voter card') {
                $('input[name="field_name[votercardnumberofthecoborrower2]"]').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[banknameofthecoborrower2]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower2]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').hide();
                $('#coBorrower2DrivingLicenseHolder').hide();
                $('#coBorrower2PassportHolder').hide();
            } else if (inputValue == 'Bank statement') {
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').show();
                $('input[name="field_name[banknameofthecoborrower2]"]').show();
                $('input[name="field_name[bankifscofthecoborrower2]"]').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').hide();
                $('#coBorrower2DrivingLicenseHolder').hide();
                $('#coBorrower2PassportHolder').hide();
            } else if (inputValue == 'Driving license') {
                $('#coBorrower2DrivingLicenseHolder').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[banknameofthecoborrower2]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower2]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').hide();
                $('#coBorrower2PassportHolder').hide();
            } else if (inputValue == 'Electricity bill') {
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[banknameofthecoborrower2]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower2]"]').hide();
                $('#coBorrower2DrivingLicenseHolder').hide();
                $('#coBorrower2PassportHolder').hide();
            } else if (inputValue == 'Passport') {
                $('#coBorrower2PassportHolder').show();

                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[banknameofthecoborrower2]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower2]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').hide();
                $('#coBorrower2DrivingLicenseHolder').hide();
            } else {
                $('input[name="field_name[aadharcardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[votercardnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[bankaccountnumberofthecoborrower2]"]').hide();
                $('input[name="field_name[banknameofthecoborrower2]"]').hide();
                $('input[name="field_name[bankifscofthecoborrower2]"]').hide();
                $('input[name="field_name[electricitybillnumberofthecoborrower2]"]').hide();
                $('#coBorrower2DrivingLicenseHolder').hide();
                $('#coBorrower2PassportHolder').hide();
            }
        }
        var ovdCoBorrower2Selected = $('input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]:checked').val();
        ovdCoBorrower2(ovdCoBorrower2Selected);
        // Officially Valid Documents of the Co-Borrower 2 on click ends

        // Officially Valid Documents of the Guarantor on click starts
        $('input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]').click(function() {
            var inputValue = $(this).attr("value");
            // console.log(inputValue);
            ovdGuarantor(inputValue);
        });

        function ovdGuarantor(inputValue) {
            if (inputValue == 'Aadhar card') {
                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').show();

                $('input[name="field_name[votercardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').hide();
                $('input[name="field_name[banknameoftheguarantor]"]').hide();
                $('input[name="field_name[bankifscoftheguarantor]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').hide();
                $('#guarantorDrivingLicenseHolder').hide();
                $('#guarantorPassportHolder').hide();
            } else if (inputValue == 'Voter card') {
                $('input[name="field_name[votercardnumberoftheguarantor]"]').show();

                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').hide();
                $('input[name="field_name[banknameoftheguarantor]"]').hide();
                $('input[name="field_name[bankifscoftheguarantor]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').hide();
                $('#guarantorDrivingLicenseHolder').hide();
                $('#guarantorPassportHolder').hide();
            } else if (inputValue == 'Bank statement') {
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').show();
                $('input[name="field_name[banknameoftheguarantor]"]').show();
                $('input[name="field_name[bankifscoftheguarantor]"]').show();

                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[votercardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').hide();
                $('#guarantorDrivingLicenseHolder').hide();
                $('#guarantorPassportHolder').hide();
            } else if (inputValue == 'Driving license') {
                $('#guarantorDrivingLicenseHolder').show();

                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[votercardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').hide();
                $('input[name="field_name[banknameoftheguarantor]"]').hide();
                $('input[name="field_name[bankifscoftheguarantor]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').hide();
                $('#guarantorPassportHolder').hide();
            } else if (inputValue == 'Electricity bill') {
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').show();

                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[votercardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').hide();
                $('input[name="field_name[banknameoftheguarantor]"]').hide();
                $('input[name="field_name[bankifscoftheguarantor]"]').hide();
                $('#guarantorDrivingLicenseHolder').hide();
                $('#guarantorPassportHolder').hide();
            } else if (inputValue == 'Passport') {
                $('#guarantorPassportHolder').show();

                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[votercardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').hide();
                $('input[name="field_name[banknameoftheguarantor]"]').hide();
                $('input[name="field_name[bankifscoftheguarantor]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').hide();
                $('#guarantorDrivingLicenseHolder').hide();
            } else {
                $('input[name="field_name[aadharcardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[votercardnumberoftheguarantor]"]').hide();
                $('input[name="field_name[bankaccountnumberoftheguarantor]"]').hide();
                $('input[name="field_name[banknameoftheguarantor]"]').hide();
                $('input[name="field_name[bankifscoftheguarantor]"]').hide();
                $('input[name="field_name[electricitybillnumberoftheguarantor]"]').hide();
                $('#guarantorDrivingLicenseHolder').hide();
                $('#guarantorPassportHolder').hide();
            }
        }
        var ovdGuarantorSelected = $('input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]:checked').val();
        ovdGuarantor(ovdGuarantorSelected);
        // Officially Valid Documents of the Guarantor on click ends

        // Date of credit of EMI into Lender's Bank Account on click starts
        $('input[name="field_name[dateofcreditofemiintolendersbankaccount]"]').click(function() {
            var inputValue = $(this).attr("value");
            // console.log(inputValue);
            otherdateEmiCheck(inputValue);
        });

        function otherdateEmiCheck(val) {
            if (val == 'Others') {
                $('select[name="field_name[otherdateofemicredit]"]').show();
            } else {
                $('select[name="field_name[otherdateofemicredit]"]').hide();
            }
        }
        var otherDateEmiSelected = $('input[name="field_name[dateofcreditofemiintolendersbankaccount]"]:checked').val();
        otherdateEmiCheck(otherDateEmiSelected);
        // Date of credit of EMI into Lender's Bank Account on click ends

        // Nature of Loan on click starts
        $('select[name="field_name[natureofloan]"]').on('change', function() {
            var inputValue = $(this).val();
            // console.log(inputValue);
            natureOfLoanCheck(inputValue);
        });

        function natureOfLoanCheck(val) {
            if (val == 'Loan against salary with check-off') {
                console.log(val);
                $('select[name="field_name[nameofthecheckoffcompany]"]').show();
            } else {
                $('select[name="field_name[nameofthecheckoffcompany]"]').hide();
            }
        }
        var natureOfLoanSelected = $('select[name="field_name[natureofloan]"]').val();
        natureOfLoanCheck(natureOfLoanSelected);
        // Nature of Loan on click ends

        function annexureVdata(x) {
            if ($("#eSignatureSendBtn").attr('data-disable') == 'disable') {
                Swal.fire(
                    'Alert!',
                    'Please upload data for annexure V first',
                    'warning'
                )
                return false;
            } else {
                window.open(x, '_blank');
            }
        }

        $('input[name="field_name[pancardnumberofthecoborrower]"]').on('keyup', function() {
            if ($('input[name="field_name[pancardnumberofthecoborrower]"]').val().length >= 10) {
                // console.log($('input[name="field_name[pancardnumberofthecoborrower]"]').val());
                $.ajax({
                    url: "{{ route('autofill.data.bypan') }}",
                    method: "GET",
                    data: {
                        pan_id: this.value,
                    },
                    success: function(res) {
                        if (res != "No-data") {
                            const details = JSON.parse(res);
                            // console.log(details);
                            $('select[name="field_name[prefixofthecoborrower]"]').val(details.prefix)
                                .change();
                            $('input[name="field_name[nameofthecoborrower]"]').val(details.name);

                            $cb_vd_len = $(
                                    'input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]')
                                .length
                            for (i = 0; i < $cb_vd_len; i++) {
                                if ($(
                                        'input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]')
                                    .eq(i).val() == details.validdoc) {
                                    $('input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]')
                                        .eq(i).attr('checked', true);
                                }
                            }

                            $cb_rs_len = $('input[name="field_name[residentstatusofthecoborrower]"]')
                                .length
                            for (i = 0; i < $cb_rs_len; i++) {
                                if ($('input[name="field_name[residentstatusofthecoborrower]"]').eq(i)
                                    .val() == details.resident_status) {
                                    $('input[name="field_name[residentstatusofthecoborrower]"]').eq(i)
                                        .attr('checked', true);
                                }
                            }

                            $('input[name="field_name[streetaddressofthecoborrower]"]').val(details
                                .streetadress);
                            $('input[name="field_name[pincodeofthecoborrower]"]').val(details.pincode);
                            $('input[name="field_name[cityofthecoborrower]"]').val(details.city);
                            $('input[name="field_name[stateofthecoborrower]"]').val(details.state);

                            $('input[name="field_name[dateofbirthofthecoborrower]"]').val(details.dob);
                            $('input[name="field_name[occupationofthecoborrower]"]').val(details
                                .occupation);
                            $('select[name="field_name[maritalstatusofthecoborrower]"]').val(details
                                .material_status).change();
                            $('select[name="field_name[highesteducationofthecoborrower]"]').val(details
                                .highest_ed).change();
                            $('input[name="field_name[mobilenumberofthecoborrower]"]').val(details
                                .mobile);
                            $('input[name="field_name[emailidofthecoborrower]"]').val(details.email);

                            ovdCoBorrower(details.validdoc);
                        } else {
                            $('select[name="field_name[prefixofthecoborrower]"]').val('');
                            $('input[name="field_name[nameofthecoborrower]"]').val('');

                            $cb_vd_len = $(
                                    'input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]')
                                .length
                            for (i = 0; i < $cb_vd_len; i++) {
                                $('input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]')
                                    .eq(i).attr('checked', false);
                            }

                            $cb_rs_len = $('input[name="field_name[residentstatusofthecoborrower]"]')
                                .length
                            for (i = 0; i < $cb_rs_len; i++) {
                                $('input[name="field_name[residentstatusofthecoborrower]"]').eq(i).attr(
                                    'checked', false);
                            }

                            $('input[name="field_name[streetaddressofthecoborrower]"]').val('');
                            $('input[name="field_name[pincodeofthecoborrower]"]').val('');
                            $('input[name="field_name[cityofthecoborrower]"]').val('');
                            $('input[name="field_name[stateofthecoborrower]"]').val('');

                            $('input[name="field_name[dateofbirthofthecoborrower]"]').val('');
                            $('input[name="field_name[occupationofthecoborrower]"]').val('');
                            $('select[name="field_name[maritalstatusofthecoborrower]"]').val('');
                            $('select[name="field_name[highesteducationofthecoborrower]"]').val('');
                            $('input[name="field_name[mobilenumberofthecoborrower]"]').val('');
                            $('input[name="field_name[emailidofthecoborrower]"]').val('');

                        }
                    }
                })
            } else {
                $('select[name="field_name[prefixofthecoborrower]"]').val('');
                $('input[name="field_name[nameofthecoborrower]"]').val('');

                $cb_vd_len = $('input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]').length
                for (i = 0; i < $cb_vd_len; i++) {
                    $('input[name="field_name[officiallyvaliddocumentsofthecoborrower]"]').eq(i).attr('checked',
                        false);
                }

                $cb_rs_len = $('input[name="field_name[residentstatusofthecoborrower]"]').length
                for (i = 0; i < $cb_rs_len; i++) {
                    $('input[name="field_name[residentstatusofthecoborrower]"]').eq(i).attr('checked', false);
                }

                $('input[name="field_name[streetaddressofthecoborrower]"]').val('');
                $('input[name="field_name[pincodeofthecoborrower]"]').val('');
                $('input[name="field_name[cityofthecoborrower]"]').val('');
                $('input[name="field_name[stateofthecoborrower]"]').val('');

                $('input[name="field_name[dateofbirthofthecoborrower]"]').val('');
                $('input[name="field_name[occupationofthecoborrower]"]').val('');
                $('select[name="field_name[maritalstatusofthecoborrower]"]').val('');
                $('select[name="field_name[highesteducationofthecoborrower]"]').val('');
                $('input[name="field_name[mobilenumberofthecoborrower]"]').val('');
                $('input[name="field_name[emailidofthecoborrower]"]').val('');
            }
        })

        $('input[name="field_name[pancardnumberofthecoborrower2]"]').on('keyup', function() {
            if ($('input[name="field_name[pancardnumberofthecoborrower2]"]').val().length >= 10) {
                // console.log($('input[name="field_name[pancardnumberofthecoborrower2]"]').val());
                $.ajax({
                    url: "{{ route('autofill.data.bypan') }}",
                    method: "GET",
                    data: {
                        pan_id: this.value,
                    },
                    success: function(res) {
                        if (res != "No-data") {
                            const details = JSON.parse(res);
                            // console.log(details);
                            $('select[name="field_name[prefixofthecoborrower2]"]').val(details.prefix)
                                .change();
                            $('input[name="field_name[nameofthecoborrower2]"]').val(details.name);

                            $cb_vd_len = $(
                                'input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]'
                                ).length
                            for (i = 0; i < $cb_vd_len; i++) {
                                if ($(
                                        'input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]')
                                    .eq(i).val() == details.validdoc) {
                                    $('input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]')
                                        .eq(i).attr('checked', true);
                                }
                            }

                            $cb_rs_len = $('input[name="field_name[residentstatusofthecoborrower2]"]')
                                .length
                            for (i = 0; i < $cb_rs_len; i++) {
                                if ($('input[name="field_name[residentstatusofthecoborrower2]"]').eq(i)
                                    .val() == details.resident_status) {
                                    $('input[name="field_name[residentstatusofthecoborrower2]"]').eq(i)
                                        .attr('checked', true);
                                }
                            }

                            $('input[name="field_name[streetaddressofthecoborrower2]"]').val(details
                                .streetadress);
                            $('input[name="field_name[pincodeofthecoborrower2]"]').val(details.pincode);
                            $('input[name="field_name[cityofthecoborrower2]"]').val(details.city);
                            $('input[name="field_name[stateofthecoborrower2]"]').val(details.state);

                            $('input[name="field_name[dateofbirthofthecoborrower2]"]').val(details.dob);
                            $('input[name="field_name[occupationofthecoborrower2]"]').val(details
                                .occupation);
                            $('select[name="field_name[maritalstatusofthecoborrower2]"]').val(details
                                .material_status).change();
                            $('select[name="field_name[highesteducationofthecoborrower2]"]').val(details
                                .highest_ed).change();
                            $('input[name="field_name[mobilenumberofthecoborrower2]"]').val(details
                                .mobile);
                            $('input[name="field_name[emailidofthecoborrower2]"]').val(details.email);

                            ovdCoBorrower2(details.validdoc);
                        } else {
                            $('select[name="field_name[prefixofthecoborrower2]"]').val('');
                            $('input[name="field_name[nameofthecoborrower2]"]').val('');

                            $cb_vd_len = $(
                                'input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]'
                                ).length
                            for (i = 0; i < $cb_vd_len; i++) {
                                $('input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]')
                                    .eq(i).attr('checked', false);
                            }

                            $cb_rs_len = $('input[name="field_name[residentstatusofthecoborrower2]"]')
                                .length
                            for (i = 0; i < $cb_rs_len; i++) {
                                $('input[name="field_name[residentstatusofthecoborrower2]"]').eq(i)
                                    .attr('checked', false);
                            }

                            $('input[name="field_name[streetaddressofthecoborrower2]"]').val('');
                            $('input[name="field_name[pincodeofthecoborrower2]"]').val('');
                            $('input[name="field_name[cityofthecoborrower2]"]').val('');
                            $('input[name="field_name[stateofthecoborrower2]"]').val('');

                            $('input[name="field_name[dateofbirthofthecoborrower2]"]').val('');
                            $('input[name="field_name[occupationofthecoborrower2]"]').val('');
                            $('select[name="field_name[maritalstatusofthecoborrower2]"]').val('');
                            $('select[name="field_name[highesteducationofthecoborrower2]"]').val('');
                            $('input[name="field_name[mobilenumberofthecoborrower2]"]').val('');
                            $('input[name="field_name[emailidofthecoborrower2]"]').val('');

                        }
                    }
                })
            } else {
                $('select[name="field_name[prefixofthecoborrower2]"]').val('');
                $('input[name="field_name[nameofthecoborrower2]"]').val('');

                $cb_vd_len = $('input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]').length
                for (i = 0; i < $cb_vd_len; i++) {
                    $('input[name="field_name[officiallyvaliddocumentsofthecoborrower2]"]').eq(i).attr('checked',
                        false);
                }

                $cb_rs_len = $('input[name="field_name[residentstatusofthecoborrower2]"]').length
                for (i = 0; i < $cb_rs_len; i++) {
                    $('input[name="field_name[residentstatusofthecoborrower2]"]').eq(i).attr('checked', false);
                }

                $('input[name="field_name[streetaddressofthecoborrower2]"]').val('');
                $('input[name="field_name[pincodeofthecoborrower2]"]').val('');
                $('input[name="field_name[cityofthecoborrower2]"]').val('');
                $('input[name="field_name[stateofthecoborrower2]"]').val('');

                $('input[name="field_name[dateofbirthofthecoborrower2]"]').val('');
                $('input[name="field_name[occupationofthecoborrower2]"]').val('');
                $('select[name="field_name[maritalstatusofthecoborrower2]"]').val('');
                $('select[name="field_name[highesteducationofthecoborrower2]"]').val('');
                $('input[name="field_name[mobilenumberofthecoborrower2]"]').val('');
                $('input[name="field_name[emailidofthecoborrower2]"]').val('');
            }
        })

        $('input[name="field_name[pancardnumberoftheguarantor]"]').on('keyup', function() {
            if ($('input[name="field_name[pancardnumberoftheguarantor]"]').val().length >= 10) {
                // console.log($('input[name="field_name[pancardnumberofthecoborrower]"]').val());
                $.ajax({
                    url: "{{ route('autofill.data.bypan') }}",
                    method: "GET",
                    data: {
                        pan_id: this.value,
                    },
                    success: function(res) {
                        if (res != "No-data") {
                            const details = JSON.parse(res);
                            // console.log(details);
                            $('select[name="field_name[prefixoftheguarantor]"]').val(details.prefix)
                                .change();
                            $('input[name="field_name[nameoftheguarantor]"]').val(details.name);

                            $cb_vd_len = $(
                                    'input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]')
                                .length
                            for (i = 0; i < $cb_vd_len; i++) {
                                if ($(
                                        'input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]')
                                    .eq(i).val() == details.validdoc) {
                                    $('input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]')
                                        .eq(i).attr('checked', true);
                                }
                            }

                            $cb_rs_len = $('input[name="field_name[residentstatusoftheguarantor]"]')
                                .length
                            for (i = 0; i < $cb_rs_len; i++) {
                                if ($('input[name="field_name[residentstatusoftheguarantor]"]').eq(i)
                                    .val() == details.resident_status) {
                                    $('input[name="field_name[residentstatusoftheguarantor]"]').eq(i)
                                        .attr('checked', true);
                                }
                            }

                            $('input[name="field_name[streetaddressoftheguarantor]"]').val(details
                                .streetadress);
                            $('input[name="field_name[pincodeoftheguarantor]"]').val(details.pincode);
                            $('input[name="field_name[cityoftheguarantor]"]').val(details.city);
                            $('input[name="field_name[stateoftheguarantor]"]').val(details.state);

                            $('input[name="field_name[dateofbirthoftheguarantor]"]').val(details.dob);
                            $('input[name="field_name[occupationoftheguarantor]"]').val(details
                                .occupation);
                            $('select[name="field_name[maritalstatusoftheguarantor]"]').val(details
                                .material_status).change();
                            $('select[name="field_name[highesteducationoftheguarantor]"]').val(details
                                .highest_ed).change();
                            $('input[name="field_name[mobilenumberoftheguarantor]"]').val(details
                                .mobile);
                            $('input[name="field_name[emailidoftheguarantor]"]').val(details.email);

                            ovdGuarantor(details.validdoc);
                        } else {
                            $('select[name="field_name[prefixoftheguarantor]"]').val('');
                            $('input[name="field_name[nameoftheguarantor]"]').val('');

                            $cb_vd_len = $(
                                    'input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]')
                                .length
                            for (i = 0; i < $cb_vd_len; i++) {
                                $('input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]')
                                    .eq(i).attr('checked', false);
                            }

                            $cb_rs_len = $('input[name="field_name[residentstatusoftheguarantor]"]')
                                .length
                            for (i = 0; i < $cb_rs_len; i++) {
                                $('input[name="field_name[residentstatusoftheguarantor]"]').eq(i).attr(
                                    'checked', false);
                            }

                            $('input[name="field_name[streetaddressoftheguarantor]"]').val('');
                            $('input[name="field_name[pincodeoftheguarantor]"]').val('');
                            $('input[name="field_name[cityoftheguarantor]"]').val('');
                            $('input[name="field_name[stateoftheguarantor]"]').val('');

                            $('input[name="field_name[dateofbirthoftheguarantor]"]').val('');
                            $('input[name="field_name[occupationoftheguarantor]"]').val('');
                            $('select[name="field_name[maritalstatusoftheguarantor]"]').val('');
                            $('select[name="field_name[highesteducationoftheguarantor]"]').val('');
                            $('input[name="field_name[mobilenumberoftheguarantor]"]').val('');
                            $('input[name="field_name[emailidoftheguarantor]"]').val('');

                        }
                    }
                })
            } else {
                $('select[name="field_name[prefixoftheguarantor]"]').val('');
                $('input[name="field_name[nameoftheguarantor]"]').val('');

                $cb_vd_len = $('input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]').length
                for (i = 0; i < $cb_vd_len; i++) {
                    $('input[name="field_name[officiallyvaliddocumentsoftheguarantor]"]').eq(i).attr('checked',
                        false);
                }

                $cb_rs_len = $('input[name="field_name[residentstatusoftheguarantor]"]').length
                for (i = 0; i < $cb_rs_len; i++) {
                    $('input[name="field_name[residentstatusoftheguarantor]"]').eq(i).attr('checked', false);
                }

                $('input[name="field_name[streetaddressoftheguarantor]"]').val('');
                $('input[name="field_name[pincodeoftheguarantor]"]').val('');
                $('input[name="field_name[cityoftheguarantor]"]').val('');
                $('input[name="field_name[stateoftheguarantor]"]').val('');

                $('input[name="field_name[dateofbirthoftheguarantor]"]').val('');
                $('input[name="field_name[occupationoftheguarantor]"]').val('');
                $('select[name="field_name[maritalstatusoftheguarantor]"]').val('');
                $('select[name="field_name[highesteducationoftheguarantor]"]').val('');
                $('input[name="field_name[mobilenumberoftheguarantor]"]').val('');
                $('input[name="field_name[emailidoftheguarantor]"]').val('');
            }
        })

        function useStamp(stamp_id, borrower_id, agreement_id, page_no) {
            // console.log('stamp_id:- ' + stamp_id);
            // console.log('borrower_id:- ' + borrower_id);
            // console.log('agreement_id:- ' + agreement_id);
            // console.log('page_no:- ' + page_no);
            // return true;

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to use this stamp',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f44336',
                cancelButtonColor: '#8b8787',
                customClass: {
                    confirmButton: 'box-shadow-danger',
                },
                confirmButtonText: 'Yes, use it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('user.borrower.agreement.stamp.use') }}',
                        method: 'post',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            stamp_id: stamp_id,
                            borrower_id: borrower_id,
                            agreement_id: agreement_id,
                            page_no: page_no
                        },
                        success: function(result) {
                            if (result.response_code == 200) {
                                if (result.tile == 'success') {
                                    // console.log(result);
                                    var availableStampCount = result.availableStampCount;
                                    // $('.stamp_' + stamp_id).replaceWith(
                                    //     `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                    //     stamp_id + `_` + page_no + `">Used</a>`);

                                    if (page_no == 3) {
                                        $('#span-contact').text(availableStampCount);

                                        $('#100rs_stamp_image').html('<div class="w-100">' +
                                            '<p class="small text-muted mb-2">Used Stamp</p>' +
                                            '<img src="' + window.location.origin + '/' + result
                                            .usedStampImage +
                                            '" alt="" style="height: 100px"></div><hr>');

                                        $('#100rs_available_stamp').html(availableStampCount)

                                        $('#stamp_' + stamp_id + '_' + page_no).replaceWith(
                                            `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                            stamp_id + `_` + page_no + `">Used</a>`);

                                    } else if (page_no == 24) {
                                        $('#span-home').text(availableStampCount);

                                        $('.available_10rs_stamp').text(availableStampCount);

                                        $('#10rs_stamp_image_page_1').html('<div class="w-100">' +
                                            '<p class="small text-muted mb-2">Used Stamp</p>' +
                                            '<img src="' + window.location.origin + '/' + result
                                            .usedStampImage +
                                            '" alt="" style="height: 100px"></div><hr>');

                                        $('#stamp_' + stamp_id + '_' + 24).replaceWith(
                                            `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                            stamp_id + `_` + 24 + `">Used</a>`);

                                        $('#stamp_' + stamp_id + '_' + 31).replaceWith(
                                            `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                            stamp_id + `_` + 31 + `">Used</a>`);

                                    } else if (page_no == 25) {
                                        $('#span-profile').text(availableStampCount);

                                        $('#50rs_stamp_image').html('<div class="w-100">' +
                                            '<p class="small text-muted mb-2">Used Stamp</p>' +
                                            '<img src="' + window.location.origin + '/' + result
                                            .usedStampImage +
                                            '" alt="" style="height: 100px"></div><hr>');

                                        $('.available_50rs_stamps').text(availableStampCount);

                                        $('#stamp_' + stamp_id + '_' + page_no).replaceWith(
                                            `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                            stamp_id + `_` + page_no + `">Used</a>`);

                                    } else if (page_no == 31) {
                                        $('#span-home1').text(availableStampCount);

                                        $('.available_10rs_stamp').text(availableStampCount);

                                        $('#100rs_stamp_image_page2').html('<div class="w-100">' +
                                            '<p class="small text-muted mb-2">Used Stamp</p>' +
                                            '<img src="' + window.location.origin + '/' + result
                                            .usedStampImage +
                                            '" alt="" style="height: 100px"></div><hr>');

                                        $('#stamp_' + stamp_id + '_' + 24).replaceWith(
                                            `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                            stamp_id + `_` + 24 + `">Used</a>`);

                                        $('#stamp_' + stamp_id + '_' + 31).replaceWith(
                                            `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                            stamp_id + `_` + 31 + `">Used</a>`);
                                    }

                                    Swal.fire(
                                        'Used!',
                                        'This stamp is successfully used.',
                                        'success'
                                    )
                                } else {
                                    $('#stamp_' + stamp_id + '_' + page_no).replaceWith(
                                        `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                        stamp_id + `_` + page_no + `">Used</a>`);
                                    Swal.fire(
                                        'Used!',
                                        'This stamp is already used.',
                                        'error'
                                    )
                                }

                            }
                        }
                    });
                }
            });
        }


        function setValue(val) {
            $('input[name="field_name[demandpromissorynoteforborrowerplace]"]').val(val);
        }
        $('input[name="field_name[placeofagreement]"]').on('change', () => {
            setValue($('input[name="field_name[placeofagreement]"]').val());
        });


        function setDate(val) {
            $('input[name="field_name[deedofpersonalguaranteedate]"]').val(val);
            $('input[name="field_name[demandpromissorynoteforborrowerdate]"]').val(val);
            $('input[name="field_name[continuingsecurityletterdate1]"]').val(val);
            $('input[name="field_name[undertakingcumindemnitydate]"]').val(val);
            $('input[name="field_name[sanctionletterdate]"]').val(val);
        }
        $('input[name="field_name[dateofagreement]"]').on('change', () => {
            setDate($('input[name="field_name[dateofagreement]"]').val());
        });

        function showNextInput(x){
            if($(x).prop('checked')){
                $(x).parent().parent().parent().parent().children().eq(1).show();
                $(x).parent().parent().parent().parent().children().eq(1).removeClass('d-none');
            }else{
                $(x).parent().parent().parent().parent().children().eq(1).hide();
            }
        }

        $('input[name="field_name[loanaccountnumber]"]').on('keyup',function(){
            let val = $(this).val();
            $('input[name="field_name[loanreferencenumber]"]').val(val);
            $('input[name="field_name[sanctionletternumber]"]').val(val);
            $('input[name="field_name[loanapplicationnumber]"]').val(val);
        })

    </script>

    @if($errors->any())
        <script>
            $('#vernacularDeclarationModal').modal('show');
        </script>
    @endif
@endsection
