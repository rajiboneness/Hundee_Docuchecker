@extends('layouts.auth.master')

@section('title', 'Enquiry List')
@section('content')
<style>
    .select2-container {
        width: 100% !important;
    }

    .vd-modal h2 {
        font-size: 24px;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    .vd-modal h3 {
        font-size: 18px;
        font-weight: 500;
        letter-spacing: 0.03em;
    }

    .vd-modal label,
    .vd-modal h4 {
        font-weight: 500;
        font-size: 15px;
        letter-spacing: 0.03em;
    }

    .dataTables_filter {
        text-align: right;
    }

    .dataTables_filter label {
        display: inline-flex;
    }

    .dataTables_length label {
        display: flex;
        align-items: center;
    }

    .dataTables_length label select {
        margin: 0 15px;
    }

    .dataTables_paginate {
        text-align: right;
    }

    .dataTables_paginate .pagination {
        display: inline-flex;
    }
    .custom_width{
        max-width: 378px;
    }
    #docuchecker_blade .form-flex {
        display: flex;
        align-items: flex-end;
        justify-content: flex-start;
    }
    #docuchecker_blade .form-flex input {
        padding-left: 0;
    }
    #docuchecker_blade .form-flex span {
        background: #ddd;
        padding: 6px;
        white-space: nowrap;
    }

</style>
<section class="content" id="docuchecker_blade">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="text-right">
                            <a href="{{route('user.docu-checker.customer')}}" class="btn btn-sm btn-primary"> <i
                                    class="fas fa-chevron-left"></i> Back</a>
                            <a href="#assignRM" class="btn  btn-sm btn-primary" data-toggle="modal"> <i
                                    class="fas fa-plus"></i> Create New Enquiry</a>
                        </div>
                        {{--Create  Modal --}}
                        <div class="modal fade pr-0" id="assignRM" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-slideout modal-sm custom_width" role="document">
                                <div class="modal-content">
                                    <form method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">New Enquiry</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @csrf
                                            <div id="alertSms"></div>
                                            <div class="mb-3">
                                                <label class="form-label">Agreement Type</label>
                                                <select name="agrType" id="agrType" class="form-control" onchange="FetchLoanNo(this.value)">
                                                    <option value="">Select agreement type...</option>
                                                    @foreach($agreement as $agrData)
                                                    <option value="{{$agrData->id}}">{{$agrData->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label class="form-label">Branch Name</label>
                                                <select name="Branch" id="Branch" class="form-control"
                                                    onchange="OnlyFetchRM(this.value, this.id)">
                                                    <option value="">Select Branch...</option>
                                                    @foreach($Office as $OfficeKey => $Officedata)
                                                    <option value="{{ $Officedata->id }}">
                                                        {{ $Officedata->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="form-label">Relationship Manager</label>
                                                <select name="rm_name" id="RM" class="form-control" required>
                                                    <option value="">Select Relationship Manager...
                                                    </option>
                                                </select>
                                                <label class="form-label">Loan Ac No</label>
                                                <div class="form-flex">
                                                    <span class="default_loan"></span><input type="text" name="loanAcNo" id="loanAcNo" class="form-control" required>
                                                </div>
                                                <label class="form-label">Loan Amount</label>
                                                <input type="number" name="loanAmount" id="loanAmount"
                                                    class="form-control">
                                                <input type="hidden" name="borrower_id" id="borrower_id"
                                                    value="{{$id}}">
                                            </div>
                                        </div>
                                        <div class="modal-footer text-right">
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="CreateDocuEnquiry(event)">Save changes
                                                <i class="fas fa-upload"></i> </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <h5 class="font-weight-light text-dark">
                            <span class="font-weight-normal" title="Borrower's name">
                                {{ $customer }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr class="text-center">
                                    <th width="4%">SR</th>
                                    <th>Agreement Type</th>
                                    <th>Loan Acc No</th>
                                    <th>Request Amount</th>
                                    <th>RM(branch)</th>
                                    <th>Sanction Amount & Date</th>
                                    <th>First Submission Date</th>
                                    <th>Initiation Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Docuchecker as $DKey => $DValue)
                                @php
                                if($DValue->status == 1){
                                $status = "Pending";
                                $color = "danger";
                                }else{
                                $status = "Sanction Agreement";
                                $color = "success";
                                }
                                $onlydate = array();
                                $firstSubDate = App\Models\CustomerLoanDocument::where('docuchecker_id',
                                $DValue->id)->get();
                                $firstDate="";
                                if(count($firstSubDate)>0){
                                    foreach($firstSubDate as $subDate){
                                    $onlydate[] = date('Y-m-d', strtotime($subDate->created_at));
                                    }
                                    $uniquedate = array_unique($onlydate);
                                $firstDate = min($uniquedate);

                                }
                                
                                @endphp
                                <tr>
                                    <td>{{$DKey+1}}</td>
                                    <td>{{$DValue->agreementdata->name}}</td>
                                    <td>{{$DValue->loan_ac_no}}</td>
                                    <td>{{$DValue->loanAmount ? "₹".number_format($DValue->loanAmount) : ""}}</td>
                                    <td><strong>{{$DValue->RMData->name}}</strong><br>
                                        <span>({{$DValue->OfficeData->name}})</span>
                                        <a href="#assignRM{{ $DKey+1 }}" class="badge badge-dark action-button"
                                            data-toggle="modal" title="assign">Update RM</a>
                                        <div class="modal fade pr-0" id="assignRM{{ $DKey+1 }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"></h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div id="alertSms{{ $DKey+1 }}"></div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Branch Name</label>
                                                                {{-- {{ dd($userData) }} --}}
                                                                <select id="OFF{{ $DKey+1 }}" class="form-control"
                                                                    onchange="FetchRM(this.value, this.id)">
                                                                    <option value="">Select Branch...</option>
                                                                    @foreach($Office as $OfficeKey => $OfficeData)
                                                                    <option value="{{ $OfficeData->id }}">
                                                                        {{ $OfficeData->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label class="form-label">Relationship Manager</label>
                                                                <select name="rm_name" id="RM{{ $DKey+1 }}"
                                                                    class="form-control" required>
                                                                    <option value="">Select Relationship Manager...
                                                                    </option>
                                                                </select>
                                                                <input type="hidden" name="borrower_id"
                                                                    id="agreement_id{{ $DKey+1 }}"
                                                                    value="{{ $DValue->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="RMDeta1ls(event,{{ $DKey+1 }})">Save changes <i
                                                                    class="fas fa-upload"></i> </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td></td>
                                    <td>{{ $DValue->first_submission_date ? date('d M Y', strtotime($DValue->first_submission_date)) : "NA" }}</td>
                                    <td>
                                        {{ !empty($firstDate) ? date('d M Y', strtotime($firstDate)) : "NA" }}
                                    </td>
                                    <td><a href="#" class="badge badge-{{$color}} action-button" data-toggle="modal"
                                            title="Status">
                                            {{$status}}</a></td>
                                    <td width="10%">
                                        <a href="{{route('user.docu-checker.details', $DValue->id)}}"
                                            class="badge badge-primary action-button">
                                            Details Check</a>
                                        <a href="#EditEnquiry{{$DKey+1}}"
                                            class="badge badge-primary action-button" data-toggle="modal">
                                            Edit</a>
                                        </td>
                                         {{--Edit  Modal --}}
                                        <div class="modal fade pr-0" id="EditEnquiry{{$DKey+1}}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-slideout modal-sm custom_width" role="document">
                                                <div class="modal-content">
                                                    <form method="post" action="{{ route('user.borrower.update-enquiry') }}">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Enquiry</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div id="alertSms"></div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Agreement Type</label>
                                                                <select name="updateAgrType" class="form-control" onchange="FetchLoanNo(this.value)">
                                                                    <option value="">Select agreement type...</option>
                                                                    @foreach($agreement as $agrData)
                                                                    <option value="{{$agrData->id}}" {{ $DValue->agreement_id ==$agrData->id ? "Selected": ""}}>{{$agrData->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label class="form-label">Loan Ac No</label>
                                                                <div class="form-flex">
                                                                    <span class="default_loan"></span><input type="text" name="loanAcNo" class="form-control" value="">
                                                                </div>
                                                                <label class="form-label">Loan Amount</label>
                                                                <input type="number" name="loanAmount"
                                                                    class="form-control" value="{{ $DValue->loanAmount }}">
                                                                <input type="hidden" name="borrower_id"
                                                                    value="{{$id}}">
                                                                <input type="hidden" name="EnquiryId"
                                                                    value="{{$DValue->id}}">
                                                                    <label class="form-label">First Submission Date</label>
                                                                <input type="date" name="firstSdate"
                                                                    class="form-control" value="{{ $DValue->first_submission_date }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="submit" class="btn btn-sm btn-success">Save changes
                                                                <i class="fas fa-upload"></i> </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#showOfficeTable').DataTable();
    });

</script>
<script>
    function FetchLoanNo(id){
        $.ajax({
            url: "{{ route('user.document.default-loan-number')}}",
            method: 'post',
            data: {
                id: id,
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status == 200) {
                    $(`.default_loan`).text(response.data);
                } 
            }
        });
    }
    function OnlyFetchRM(value, id) {
        // var lastChar = id.substring(3);
        var office_id = value;
        $.ajax({
            url: "{{ route('user.borrower.fetch-office-name')}}",
            method: 'post',
            data: {
                office_id: office_id,
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status == 200) {
                    var array = response.data;
                    $(`#RM option`).remove();
                    $(`#RM`).append('<option value="">Select Relationship Manager...</option>');
                    $.each(array, function (index, value) {
                        $(`#RM`).append('<option value="' + value[0] + '">' + value[1] +
                            "</option>");
                    });
                } else {
                    $(`#RM option`).remove();
                    $(`#RM`).append('<option value="">Select Another Branch...</option>');
                }
            }
        });
    }

    function FetchRM(value, id) {
        var lastChar = id.substring(3);
        var office_id = value;
        $.ajax({
            url: "{{ route('user.borrower.fetch-office-name')}}",
            method: 'post',
            data: {
                office_id: office_id,
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status == 200) {
                    var array = response.data;
                    $(`#RM${lastChar} option`).remove();
                    $(`#RM${lastChar}`).append('<option value="">Select Relationship Manager...</option>');
                    $.each(array, function (index, value) {
                        $(`#RM${lastChar}`).append('<option value="' + value[0] + '">' + value[1] +
                            "</option>");
                    });
                } else {
                    $(`#RM${lastChar} option`).remove();
                    $(`#RM${lastChar}`).append('<option value="">Select Another Branch...</option>');
                }
            }
        });
    }

    function RMDeta1ls(e, id) {
        e.preventDefault();
        var agreement_id = $('#agreement_id' + id + '').val();
        var rm_name = $('#RM' + id + '').val();
        var branch = $('#OFF' + id + '').val();
        // alert(id);
        if (rm_name.length == 0) {
            $('#alertSms' + id + '').addClass('badge badge-danger').html("Please select relationship manager !");
            setTimeout(function () {
                $('#alertSms' + id + '').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{ route('user.borrower.add-relationship-manager') }}",
            data: {
                '_token': '{{ csrf_token() }}',
                rm_name: rm_name,
                agreement_id: agreement_id,
                branch: branch
            },
            success: function (result) {
                if (result.status == 200) {
                    $('#alertSms' + id + '').addClass('badge badge-success').html(
                    "RM Updated Successfully");
                    setTimeout(function () {
                        $('#alertSms' + id + '').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 2000);
                } else {
                    $('#alertSms' + id + '').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms' + id + '').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);

                }
            }
        });

    }

    function CreateDocuEnquiry(e) {
        e.preventDefault();
        var loanAcNo = $('#loanAcNo').val();
        var loanAmount = $('#loanAmount').val();
        var agrType = $('#agrType').val();
        var Branch = $('#Branch').val();
        var RM = $('#RM').val();
        var borrower_id = $('#borrower_id').val();
        if (RM.length == 0) {
            $('#alertSms').addClass('badge badge-danger').html("Please select relationship manager !");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }else if(loanAcNo.length == 0){
            $('#alertSms').addClass('badge badge-danger').html("Please enter loan account number !");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }else if(loanAmount.length == 0){
            $('#alertSms').addClass('badge badge-danger').html("Please enter amount!");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{ route('user.borrower.createnewenquiry') }}",
            data: {
                '_token': '{{ csrf_token() }}',
                loanAmount: loanAmount,
                loanAcNo: loanAcNo,
                agrType: agrType,
                Branch: Branch,
                RM: RM,
                borrower_id: borrower_id
            },
            success: function (result) {
                if (result.status == 200) {
                    $('#alertSms').addClass('badge badge-success').html("New Enquiry Successfully Created");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 2000);
                } else if (result.status == 100) {
                    $('#alertSms').addClass('badge badge-danger').html("Loan Acc. no already exist !");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 2000);
                } else {
                    $('#alertSms').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);

                }
            }
        });

    }

</script>
@endsection
