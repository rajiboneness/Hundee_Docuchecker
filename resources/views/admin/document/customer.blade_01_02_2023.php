@extends('layouts.auth.master')

@section('title', 'Customer List')

@section('content')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

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

</style>

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
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="agreement_id" id="agreement_id" value="1">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="30%">Customer Name</th>
                                    <th>Branch Name</th>
                                    <th>RM Name</th>
                                    <th>Loan Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowers as $key => $value)
                                @php
                                     $CustomerLoan = App\Models\CustomerLoanDocument::where('borrower_id', $value->id)->get()->count();
                                     $dataCount = App\Models\CustomerLoanDocument::where('borrower_id', $value->id)->where('status', 1)->get()->count();
                                @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->full_name }}
                                    <br>
                                    <span class="small text-muted font-weight-bold mb-0">{{ $value->mobile }}</span>
                                    <br>
                                    <span class="small text-muted font-weight-bold mb-0">{{ $value->email }}</span>
                                    </td>
                                    <td>
                                        @if($value->RM_ID)
                                            @php
                                            $Branch = App\Models\Office::where('id', $value->Users->office_id)->first();
                                            @endphp
                                        @endif
                                        
                                        <p class="small text-dark font-weight-bold mb-0">
                                            {{ $value->RM_ID ? $Branch->name : '' }}</p>
                                        <a href="#assignRM{{ $key+1 }}"
                                            class="pl-3 pr-3 badge badge-{{ $value->RM_ID ? "dark" : "danger" }}"
                                            data-toggle="modal"
                                            title="assign">{{ $value->RM_ID ? "Update RM" : "Assign RM" }}</a>
                                        <div class="modal fade pr-0" id="assignRM{{ $key+1 }}" tabindex="-1"
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
                                                            <div id="alertSms{{ $key+1 }}"></div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Branch Name</label>
                                                                {{-- {{ dd($userData) }} --}}
                                                                <select id="OFF{{ $key+1 }}" class="form-control"
                                                                    onchange="FetchRM(this.value, this.id)">
                                                                    <option value="">Select Branch...</option>
                                                                    @foreach($officeData as $OfficeKey => $Office)
                                                                    <option value="{{ $Office->id }}">
                                                                        {{ $Office->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label class="form-label">Relationship Manager</label>
                                                                <select name="rm_name" id="RM{{ $key+1 }}"
                                                                    class="form-control" required>
                                                                    <option value="">Select Relationship Manager...
                                                                    </option>
                                                                </select>
                                                                <input type="hidden" name="borrower_id"
                                                                    id="borrower_id{{ $key+1 }}"
                                                                    value="{{ $value->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="RMDeta1ls2(event,{{ $key+1 }})">Save changes
                                                                <i class="fas fa-upload"></i> </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="small text-dark font-weight-bold mb-0">
                                        {{ $value->RM_ID ? $value->Users->name : '' }}</p>
                                    </td>
                                    <td>{{ $value->Loan_Type ? $value->Loan_Type : '' }}</td>
                                    <td>
                                        @if($value->Loan_Type)
                                            <a href=" {{ route('user.docu-checker.index', [$value->id]) }}"
                                            class="pl-3 pr-3 badge badge-dark text-light">Docu Checker</a>
                                            @if($CustomerLoan>0)
                                                <span class="badge badge-{{ $dataCount<1 ? "success" : ""  }}"> {{ $dataCount<1 ? "Verified" : "" }}
                                                </span>
                                            @endif
                                            @else
                                            <a href="#AddLoanType{{ $key+1 }}" data-toggle="modal"
                                            class="pl-3 pr-3 badge badge-danger text-light">Add Loan Type</a>
                                            <div class="modal fade pr-0" id="AddLoanType{{ $key+1 }}" tabindex="-1"
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
                                                                <div id="loanSms{{ $key+1 }}"></div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Add Loan Type</label>
                                                                    <select name="loan" id="loan{{ $key+1 }}" class="form-control">
                                                                        <option value="">Select Loan Type</option>
                                                                        @foreach($Agreement as $AgreementData)
                                                                            <option value="{{ $AgreementData->name }}">{{ $AgreementData->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="hidden" name="borrower_id"
                                                                        id="borrower_id{{ $key+1 }}"
                                                                        value="{{ $value->id }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer text-right">
                                                                <button type="submit" class="btn btn-sm btn-success"
                                                                    onclick="RMDeta1ls(event,{{ $key+1 }})">Save changes
                                                                    <i class="fas fa-upload"></i> </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View Modal -->
    <div class="modal fade pr-0" id="ViewDocumentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
            <div class="modal-content">
                <form method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- View modal -->
</section>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<!-- bs stepper -->
<script src="{{ asset('admin/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
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
                        $.each(array, function(index, value) {
                            $(`#RM${lastChar}`).append('<option value="' + value[0] + '">' + value[1] + "</option>");
                        });
                }else{
                    $(`#RM${lastChar} option`).remove();
                    $(`#RM${lastChar}`).append('<option value="">Select Another Branch...</option>');
                }
            }
        });
    }
    function RMDeta1ls2(e, id){
        e.preventDefault();
        var borrower_id = $('#borrower_id'+id+'').val();
        var rm_name = $('#RM'+id+'').val();
        // alert(id);
        if(rm_name.length == 0){
            $('#alertSms'+id+'').addClass('badge badge-danger').html("Please select relationship manager !");
            setTimeout(function () {
                $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({type: "POST",
            url: "{{ route('user.borrower.add-relationship-manager') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                rm_name : rm_name,
                borrower_id : borrower_id
            },
            success:function(result){
                if(result.status ==100){
                    $('#alertSms'+id+'').addClass('badge badge-success').html("RM Assigned Successfully");
                    setTimeout(function () {
                        $('#alertSms'+id+'').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 2000);
                    
                }else if(result.status ==200){
                    $('#alertSms'+id+'').addClass('badge badge-success').html("RM Updated Successfully");
                    setTimeout(function () {
                        $('#alertSms'+id+'').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 2000);
                }else{
                    $('#alertSms'+id+'').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);
                    
                }
        }});

    }
    function RMDeta1ls(e, id){
        e.preventDefault();
        var borrower_id = $('#borrower_id'+id+'').val();
        var loan_type = $('#loan'+id+'').val();
        if(loan_type.length == 0){
            $('#loanSms'+id+'').addClass('badge badge-danger').html("Please select loan type !");
            setTimeout(function () {
                $('#loanSms'+id+'').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({type: "POST",
            url: "{{ route('user.docu-checker.add-loan-type') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                loan_type : loan_type,
                borrower_id : borrower_id
            },
            success:function(result){
                if(result.status ==200){
                    $('#loanSms'+id+'').addClass('badge badge-success').html("Add Loan Type Successfully");
                    setTimeout(function () {
                        $('#loanSms'+id+'').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 1000);
                    
                }else{
                    $('#loanSms'+id+'').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#loanSms'+id+'').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);
                    
                }
        }});

    }
</script>
@endsection
