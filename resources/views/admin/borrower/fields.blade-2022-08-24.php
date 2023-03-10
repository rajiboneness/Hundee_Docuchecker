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
                                <a href="{{ route('user.borrower.list') }}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="font-weight-light text-dark">
                                        <span class="font-weight-normal" title="Borrower's name">
                                            {{ ucwords($data->name_prefix) }}
                                            {{ $data->full_name }}
                                        </span> - <span title="Agreement name">{{ $data->agreement_name }}</span>
                                    </h5>
                                </div>
                                <div class="col-md-4 text-right">
                                    @if ($data->agreementRfq > 0)
                                        {{-- <a href="{{ route('user.borrower.agreement.view', $id) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> View agreement details</a> --}}

                                        <a href="{{route('user.borrower.setAnnexure5doc',[$id])}}"
                                            class="btn btn-sm btn-secondary">Update data for ANEXTURE-V</a>

                                        <a href="{{ route('user.esign.borrower.detail', [$data->borrower_id, $id, $data->rfqDetails->id]) }}"
                                            class="btn btn-sm btn-secondary" target="_blank"><i
                                                class="fas fa-signature"></i> Send for eSignature</a>

                                        {{-- <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id, 'type' => 'esign']) }}" class="btn btn-sm btn-secondary" target="_blank"><i class="fas fa-signature"></i> E-Sign</a> --}}

                                        <a href="#viewPdfModal" data-toggle="modal" class="btn btn-sm btn-primary"
                                            target="_blank"><i class="fas fa-file-pdf"></i> View PDF</a>

                                        <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id, 'status' => 'download']) }}"
                                            class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-download"></i>
                                            Download PDF</a>

                                        {{-- <a href="{{ route('user.borrower.agreement.pdf.view', [$data->borrower_id, $data->agreement_id]) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-file-pdf"></i>View PDF</a> --}}

                                        {{-- <a href="{{ route('user.borrower.agreement.pdf.view', [$data->borrower_id, $data->agreement_id, 'status' => 'download']) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-download"></i> Download PDF</a> --}}
                                    @endif
                                </div>

                                @if ($data->agreementRfq == 0)
                                    <div class="col-md-12 mt-3">
                                        <p class="small text-muted mb-0"><span class="text-danger">*</span> Please fill up the form first to view PDF</p>
                                    </div>
                                @endif
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">

                                    <input type="hidden" name="borrower_id" id="borrower_id" value="{{ request()->id }}">

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
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <table
                                                            class="table table-sm table-bordered table-hover table-striped"
                                                            id="agreementFieldsTable">
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
                                                                            {{-- <button type="button" class="btn btn-sm btn-success" onclick="editAgreementFields()">Edit <i class="fas fa-edit"></i></button> --}}

                                                                            {{-- <button type="button" class="btn btn-sm btn-primary" onclick="stepper.next()">Go to Documents <i class="fas fa-chevron-right"></i></button> --}}

                                                                            <a href="#viewPdfModal" data-toggle="modal"
                                                                                class="btn btn-sm btn-primary"
                                                                                target="_blank"><i
                                                                                    class="fas fa-file-pdf"></i> View
                                                                                PDF</a>

                                                                            {{-- <a href="{{ route('user.borrower.agreement.pdf.view', [$data->borrower_id, $data->agreement_id]) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-file-pdf"></i>
                                                                        View PDF</a> --}}
                                                                        @else
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-primary">Submit data
                                                                                <i class="fas fa-upload"></i></button>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small mb-2">Main agreement</p>
                    <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id, 'status' => 'download']) }}"
                        class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-download"></i> Download PDF</a>
                    <a href="{{ route('user.borrower.agreement.pdf.view.web', [$data->borrower_id, $data->agreement_id, $id]) }}"
                        class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-file-pdf"></i> View PDF</a>

                    <br><br>

                    <p class="small mb-2">Stamp paper</p>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                Page 5-Rs 100 Stamp paper
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                Page 25-Rs 10 Stamp
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                Page 26-Rs 50 Stamp
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="home-tab1" data-toggle="tab" href="#home1" role="tab" aria-controls="home" aria-selected="true">
                                Page 31-Rs 10 Stamp
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="contact" role="tabpanel"
                            aria-labelledby="contact-tab">
                            <h6 class="badge badge-primary">Available Stamp: <span
                                    id="span-contact">{{ availableStampNew(100)->count() }}</span></h6>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title font-weight-bold">All Available 100Rs Estamps</div>
                                </div>
                                <div class="card-body">
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
                            <h6 class="badge badge-primary">Available Stamp: <span
                                    id="span-home">{{ availableStampNew(10)->count() }}</span></h6>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title font-weight-bold">All Available 10Rs Estamps</div>
                                </div>
                                <div class="card-body">
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
                                                                        class="badge badge-dark action-button stamp_{{$stamp->id}}"
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
                            <h6 class="badge badge-primary">Available Stamp: <span
                                    id="span-profile">{{ availableStampNew(50)->count() }}</span></h6>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title font-weight-bold">All Available 50Rs Estamps</div>
                                </div>
                                <div class="card-body">
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
                            <h6 class="badge badge-primary">Available Stamp: <span
                                    id="span-home-tab1">{{ availableStampNew(10)->count() }}</span></h6>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title font-weight-bold">All Available 10Rs Estamps</div>
                                </div>
                                <div class="card-body">
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
                                                                        class="badge badge-dark action-button stamp_{{$stamp->id}}"
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
                                    var availableStampCount = result.availableStampCount;
                                    $('.stamp_' + stamp_id).replaceWith(
                                        `<a href="javascript: void(0)" class="badge badge-danger action-button" title="View" id="stamp_` +
                                        stamp_id + `_` + page_no + `">Used</a>`);
                                    if (page_no == 3) {
                                        $('#span-contact').text(availableStampCount);
                                    } else if (page_no == 24) {
                                        $('#span-home').text(availableStampCount);
                                    } else if (page_no == 25) {
                                        $('#span-profile').text(availableStampCount);
                                    } else if (page_no == 31) {
                                        $('#span-home1').text(availableStampCount);
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
        $('input[name="field_name[placeofagreement]"]').on('change', ()=> {
            setValue($('input[name="field_name[placeofagreement]"]').val());
        });


        function setDate(val) {
            $('input[name="field_name[deedofpersonalguaranteedate]"]').val(val);
            $('input[name="field_name[demandpromissorynoteforborrowerdate]"]').val(val);
            $('input[name="field_name[continuingsecurityletterdate1]"]').val(val);
            $('input[name="field_name[undertakingcumindemnitydate]"]').val(val);
            $('input[name="field_name[sanctionletterdate]"]').val(val);
        }
        $('input[name="field_name[dateofagreement]"]').on('change',()=>{
            setDate($('input[name="field_name[dateofagreement]"]').val());
        });
        
    </script>
@endsection
