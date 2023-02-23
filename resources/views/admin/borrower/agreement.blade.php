@extends('layouts.auth.master')

@section('title', 'View agreement fields')

@section('content')
<section class="content">
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
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <input type="hidden" name="borrower_id" id="borrower_id" value="{{ request()->id }}">

                                @if (count($data->fields) > 0)
                                    <form action="#" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <table class="table table-sm table-bordered table-hover table-striped" id="agreementFieldsTable">
                                            @forelse ($data->parentFields as $indexParent => $parent)
                                                <tr>
                                                    <td colspan="3" class="field-heading">{{ $parent->name }}</td>
                                                </tr>
                                                @foreach ($parent->childRelation as $indexChild => $item)
                                                    <!-- concept of customer id is removed -->
                                                    @if ($item->childField->name == "Customer ID")
                                                    <!-- borrower officially valid documents hidden by default -->
                                                    @elseif ($item->childField->name == "Aadhar card number of the Borrower")
                                                    @elseif ($item->childField->name == "Voter card number of the Borrower")
                                                    @elseif ($item->childField->name == "Bank account number of the Borrower")
                                                    @elseif ($item->childField->name == "Bank name of the Borrower")
                                                    @elseif ($item->childField->name == "Bank IFSC of the Borrower")
                                                    @elseif ($item->childField->name == "Driving license number of the Borrower")
                                                    @elseif ($item->childField->name == "Driving license issue date of the Borrower")
                                                    @elseif ($item->childField->name == "Driving license expiry date of the Borrower")
                                                    @elseif ($item->childField->name == "Electricity bill number of the Borrower")
                                                    @elseif ($item->childField->name == "Passport number of the Borrower")
                                                    @elseif ($item->childField->name == "Passport issue date of the Borrower")
                                                    @elseif ($item->childField->name == "Passport expiry date of the Borrower")

                                                    <!-- co-borrower 1 officially valid documents hidden by default -->
                                                    @elseif ($item->childField->name == "Aadhar card number of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Voter card number of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Bank account number of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Bank name of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Bank IFSC of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Driving license number of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Driving license issue date of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Driving license expiry date of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Electricity bill number of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Passport number of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Passport issue date of the Co-Borrower")
                                                    @elseif ($item->childField->name == "Passport expiry date of the Co-Borrower")

                                                    <!-- co-borrower 2 officially valid documents hidden by default -->
                                                    @elseif ($item->childField->name == "Aadhar card number of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Voter card number of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Bank account number of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Bank name of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Bank IFSC of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Driving license number of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Driving license issue date of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Driving license expiry date of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Electricity bill number of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Passport number of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Passport issue date of the Co-Borrower 2")
                                                    @elseif ($item->childField->name == "Passport expiry date of the Co-Borrower 2")

                                                    <!-- guarantor officially valid documents hidden by default -->
                                                    @elseif ($item->childField->name == "Aadhar card number of the Guarantor")
                                                    @elseif ($item->childField->name == "Voter card number of the Guarantor")
                                                    @elseif ($item->childField->name == "Bank account number of the Guarantor")
                                                    @elseif ($item->childField->name == "Bank name of the Guarantor")
                                                    @elseif ($item->childField->name == "Bank IFSC of the Guarantor")
                                                    @elseif ($item->childField->name == "Driving license number of the Guarantor")
                                                    @elseif ($item->childField->name == "Driving license issue date of the Guarantor")
                                                    @elseif ($item->childField->name == "Driving license expiry date of the Guarantor")
                                                    @elseif ($item->childField->name == "Electricity bill number of the Guarantor")
                                                    @elseif ($item->childField->name == "Passport number of the Guarantor")
                                                    @elseif ($item->childField->name == "Passport issue date of the Guarantor")
                                                    @elseif ($item->childField->name == "Passport expiry date of the Guarantor")

                                                    <!-- other date of emi credit hidden by default -->
                                                    @elseif ($item->childField->name == "Other date of EMI credit")

                                                    <!-- Name of the check-off Company hidden by default -->
                                                    @elseif ($item->childField->name == "Name of the check-off Company")

                                                    <!-- Post-dated cheques hidden by default -->
                                                    @elseif ($item->childField->name == "Post date cheque 1 description")
                                                    @elseif ($item->childField->name == "Post date cheque 1 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 1 date")
                                                    @elseif ($item->childField->name == "Post date cheque 1 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 2 description")
                                                    @elseif ($item->childField->name == "Post date cheque 2 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 2 date")
                                                    @elseif ($item->childField->name == "Post date cheque 2 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 3 description")
                                                    @elseif ($item->childField->name == "Post date cheque 3 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 3 date")
                                                    @elseif ($item->childField->name == "Post date cheque 3 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 4 description")
                                                    @elseif ($item->childField->name == "Post date cheque 4 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 4 date")
                                                    @elseif ($item->childField->name == "Post date cheque 4 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 5 description")
                                                    @elseif ($item->childField->name == "Post date cheque 5 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 5 date")
                                                    @elseif ($item->childField->name == "Post date cheque 5 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 6 description")
                                                    @elseif ($item->childField->name == "Post date cheque 6 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 6 date")
                                                    @elseif ($item->childField->name == "Post date cheque 6 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 7 description")
                                                    @elseif ($item->childField->name == "Post date cheque 7 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 7 date")
                                                    @elseif ($item->childField->name == "Post date cheque 7 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 8 description")
                                                    @elseif ($item->childField->name == "Post date cheque 8 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 8 date")
                                                    @elseif ($item->childField->name == "Post date cheque 8 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 9 description")
                                                    @elseif ($item->childField->name == "Post date cheque 9 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 9 date")
                                                    @elseif ($item->childField->name == "Post date cheque 9 amount")
                                                    @elseif ($item->childField->name == "Post date cheque 10 description")
                                                    @elseif ($item->childField->name == "Post date cheque 10 cheque number")
                                                    @elseif ($item->childField->name == "Post date cheque 10 date")
                                                    @elseif ($item->childField->name == "Post date cheque 10 amount")

                                                    @else
                                                    <tr>
                                                        <td style="width: 50px">{{ $indexChild + 1 }}</td>
                                                        <td class="fields_col-1">
                                                            <label class="font-weight-bold">{!! $item->childField->name !!} {!! $item->childField->required == 1 ? '<span class="text-danger" title="This field is required">*</span>' : '' !!}</label>
                                                        </td>
                                                        <td class="fields_col-2">
                                                            @php
                                                                if ($data->agreementRfq > 0) {
                                                                    $formType = 'show';
                                                                } else {
                                                                    $formType = 'create';
                                                                }
                                                            @endphp

                                                            {!! form3lements($item->childField->id, $item->childField->name, $item->childField->inputType->name, $item->childField->value, $item->childField->key_name, '', $borrowerId = $data->borrower_id, $formType) !!}
                                                        </td>
                                                    </tr>
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
                                                        <input type="hidden" name="borrower_id" value="{{ $data->borrower_id }}">
                                                        <input type="hidden" name="agreement_id" value="{{ $data->agreement_id }}">
                                                        <input type="hidden" name="borrower_agreement_id" value="{{ $id }}">

                                                        @if ($data->agreementRfq > 0)

                                                            {{-- <button type="button" class="btn btn-sm btn-success" onclick="editAgreementFields()">Edit <i class="fas fa-edit"></i></button> --}}

                                                            {{-- <button type="button" class="btn btn-sm btn-primary" onclick="stepper.next()">Go to Documents <i class="fas fa-chevron-right"></i></button> --}}

                                                            <a href="#viewPdfModal" data-toggle="modal" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-file-pdf"></i> View PDF</a>

                                                            {{-- <a href="{{ route('user.borrower.agreement.pdf.view', [$data->borrower_id, $data->agreement_id]) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-file-pdf"></i>
                                                            View PDF</a> --}}

                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-primary">Submit data <i class="fas fa-upload"></i></button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script>

    </script>
@endsection
