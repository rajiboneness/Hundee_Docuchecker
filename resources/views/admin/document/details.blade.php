@extends('layouts.auth.master')

@section('title', 'Document List')

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
    .parentName{
        background:#e9e9e9;
    }
    .badge-secondary{
        background: #f76c6c80;
        color: #000;
    }
    .table_row{
        background:#f1aeae;
    }
    .reason_alert{
        background: #f1ced0;
        color:#000;
    }
    .primary_btn{
        background: #265482;
        color: #fff;
        padding-left: 25px;
        padding-right: 25px;
    }
    .primary_btn:hover{
        color: #fff;
    }
    #additionalData label{
        cursor: pointer;
        padding-left: 8px;
    }
    .addition_status select{
        border: none;
        font-weight: 600;
        font-size: 18px;
        background: transparent;
    }
    .parent_header{
        background: #2e6092;
        color: #fff;
    }

</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="font-weight-light text-dark">
                            <span class="font-weight-normal" title="Borrower's name">
                                {{ $Docuchecker->CustomerData->name_prefix }} {{ $Docuchecker->CustomerData->full_name }}
                            </span> - <span title="Agreement name">{{ $Docuchecker->agreement_name }}</span>
                        </h5>
                        <span class="btn btn-default"><strong>RM - </strong>{{ $Docuchecker->RMData->name.'('.$Docuchecker->RMData->emp_id.')' }}</span>
                        <span class="btn btn-default"><strong>Office - </strong>{{$Docuchecker->OfficeData->name }}</span>
                        <span class="btn btn-default"><strong>Account No. - </strong>{{$Docuchecker->loan_ac_no }}</span>
                        <span class="btn btn-default"><strong>Request Amount - </strong>{{'₹'.$Docuchecker->loanAmount }}</span>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                            <a href="{{ route('user.docu-checker.index', $Docuchecker->borrower_id)}}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover" id="borrowers-table">
                            <thead>
                                <tr class="text-center parent_header">
                                    {{-- <th width="4%">SR</th> --}}
                                    <th width="40%">Document</th>
                                    <th></th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($AgreementDocument as $Pkey => $ParentVal)
                                <tr>
                                    @php
                                    $Parent = App\Models\Document::where('id', $ParentVal->document_id)-> first();
                                    $getAllDetails = App\Models\CustomerLoanDocument::where('docuchecker_id', $Docuchecker->id)->where('document_name', $Parent->document_name)->first();
                                   @endphp
                                    <td colspan="2" class="text-dark parentName font-weight-bold px-4">{{$Parent->document_name}}
                                    </td>
                                    <td class="parentName text-center">
                                        @php
                                        if($getAllDetails){
                                            $status = $getAllDetails->status;
                                            if($getAllDetails->status==1){
                                            $color = "secondary";
                                            $text = "Pending";
                                            }
                                            elseif($getAllDetails->status==2){
                                                $color = "success";
                                                $text = "Submitted";
                                            }elseif($getAllDetails->status==3){
                                                $color = "success";
                                                $text = "Approved By MD";
                                            }else{
                                                $color = "danger";
                                                $text = "Rejected";
                                            }
                                        }else{
                                            $status = 0;
                                            $color = "secondary";
                                            $text = "Pending";
                                        }
                                        
                                        @endphp
                                        <a href="#StatusModal{{ $Parent->id }}" data-toggle="modal" class="pl-3 pr-3 badge badge-{{ $color }}">{{ $text }}</a>
                                        <!-- Status Modal -->
                                        <div class="modal fade pr-0" id="StatusModal{{ $Parent->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
                                                <div class="modal-content text-left">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <p class="modal-title"><label class="form-label">Status</label></p>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body document-modal">
                                                                @csrf
                                                                <div id="alertSms{{ $Parent->id }}"></div>
                                                                <div class="mb-3">
                                                                <input type="hidden" id="Reason_head{{$Parent->id}}" value="{{$Parent->id}}">
                                                                    <select name="status" id="status{{ $Parent->id }}" class="form-control" onchange="SelectReason(event,{{ $Parent->id }})">
                                                                        <option value="" selected disabled>Select Status..</option>
                                                                        <option value="1" {{ $status==1 ? "Selected" : "" }}>Pending</option>
                                                                        <option value="2"{{ $status==2 ? "Selected" : "" }}>Submitted</option>
                                                                    <option value="3"{{ $status==3 ? "Selected" : "" }}>Approved By MD</option>
                                                                    <option value="4">Rejected</option>
                                                                </select>
                                                                </div>
                                                                <div class="mb-3 d-none" id="RejectDiv{{$Parent->id}}">
                                                                <select name="RejectReason" id="RejectReason{{ $Parent->id }}" class="form-control">
                                                                    <option value="">Select Reason..</option>
                                                                </select>
                                                                <label> Observation</label>
                                                                <textarea id="Observation{{ $Parent->id }}" cols="30" rows="2" class="form-control"></textarea>
                                                            </div>
                                                                <input type="hidden" name="docuCheckId" id="docuCheckId{{ $Parent->id }}" value="{{$Docuchecker->id}}">
                                                            </div>
                                                            <div class="modal-footer text-right">
                                                                <button type="submit" class="btn btn-sm btn-success" onclick="EditStatus(event,{{ $Parent->id }})">Update Status <i class="fas fa-upload"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                        </div>
                                        <!-- Status modal -->
                                    </td>
                                    @if($getAllDetails)
                                        @if($getAllDetails->status==4)
                                            @php
                                                $RejectDocument = App\Models\RejectDocument::orderBy('created_at', 'ASC')->where('customer_loan_documents_id', $getAllDetails->id)->where('docucheckers_id', $Docuchecker->id)->get();
                                            @endphp
                                            <tr class="text-center">
                                                <td colspan="1">Rejected Reasons</td>
                                                <td>Observations</td>
                                            </tr>
                                            @foreach($RejectDocument as $RejectValue)
                                                <tr>
                                                    <td><span class="badge badge-danger">{{ $RejectValue->document_reason.' - '.date('d M Y', strtotime($RejectValue->created_at))}}</span><br></td>
                                                    <td>{{ $RejectValue->observation }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    {{-- <tr>
                                        <td colspan="2" class="text-dark font-weight-bold px-4">ABCD</td>
                                    </tr> --}}
                                   
                                </tr>
                                        
                                    {{-- @foreach($TotalDocument_id as $Dkey => $DocuId)
                                        @php
                                            $Document = App\Models\Document::where('parent_id', $ParentVal)->where('id', $DocuId)->first();
                                        @endphp
                                        @if(isset($Document))
                                            @php
                                            $DocumentName[] = $Document->document_name;
                                                $getAllDetails = App\Models\CustomerLoanDocument::where('docuchecker_id', $Docuchecker->id)->where('document_name', $Document->document_name)->first();
                                                $getReasonDoc = null;
                                                if(($getAllDetails!=null)){
                                                    $getReasonDoc = App\Models\RejectDocument::orderBy('created_at', 'ASC')->where('docucheckers_id',$Docuchecker->id)->where('customer_loan_documents_id', $getAllDetails->id)->get();
                                                }
                                                @endphp
                                            <tr>
                                                <td>{{ $Document->document_name}} <br>
                                                    @if($getReasonDoc!=null)
                                                        @foreach($getReasonDoc as $getReasonDocData)
                                                            <span class="badge badge-danger">{{ 'Reason : '.$getReasonDocData->document_reason.' - '.date('d M Y', strtotime($getReasonDocData->created_at))}}</span><br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>{{ $getAllDetails ? date('d M Y', strtotime($getAllDetails->created_at)): "" }}</td>
                                                <td colspan="2">
                                                    @php
                                                    if($getAllDetails){
                                                        $status = $getAllDetails->status;
                                                        if($getAllDetails->status==1){
                                                        $color = "secondary";
                                                        $text = "Pending";
                                                        }
                                                        elseif($getAllDetails->status==2){
                                                            $color = "success";
                                                            $text = "Submitted";
                                                        }elseif($getAllDetails->status==3){
                                                            $color = "success";
                                                            $text = "Approved By MD";
                                                        }else{
                                                            $color = "danger";
                                                            $text = "Rejected";
                                                        }
                                                    }else{
                                                        $status = 0;
                                                        $color = "secondary";
                                                        $text = "Pending";
                                                    }
                                                    
                                                    @endphp
                                                    <a href="#StatusModal{{ $Document->id }}" data-toggle="modal" class="pl-3 pr-3 badge badge-{{ $color }}">{{ $text }}</a>
                                                    <span>{{ $status==0 ? " ": "on ".date('d M Y', strtotime($getAllDetails->updated_at)) }}</span>
                                                    <!-- Status Modal -->
                                                    <div class="modal fade pr-0" id="StatusModal{{ $Document->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
                                                            <div class="modal-content">
                                                                    <form method="post">
                                                                        <div class="modal-header">
                                                                            <p class="modal-title"><label class="form-label">Status</label></p>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body document-modal">
                                                                            @csrf
                                                                            <div id="alertSms{{ $Document->id }}"></div>
                                                                            <div class="mb-3">
                                                                            <input type="hidden" id="Reason_head{{$Document->id}}" value="{{$Document->parent_id}}">
                                                                                <select name="status" id="status{{ $Document->id }}" class="form-control" onchange="SelectReason(event,{{ $Document->id }})">
                                                                                    <option value="" selected disabled>Select Status..</option>
                                                                                    <option value="1" {{ $status==1 ? "Selected" : "" }}>Pending</option>
                                                                                    <option value="2"{{ $status==2 ? "Selected" : "" }}>Submitted</option>
                                                                                <option value="3"{{ $status==3 ? "Selected" : "" }}>Approved By MD</option>
                                                                                <option value="4">Rejected</option>
                                                                            </select>
                                                                            </div>
                                                                            <div class="mb-3 d-none" id="RejectDiv{{$Document->id}}">
                                                                            <select name="RejectReason" id="RejectReason{{ $Document->id }}" class="form-control">
                                                                                <option value="">Select Reason..</option>
                                                                            </select>
                                                                        </div>
                                                                            <input type="hidden" name="docuCheckId" id="docuCheckId{{ $Document->id }}" value="{{$Docuchecker->id}}">
                                                                        </div>
                                                                        <div class="modal-footer text-right">
                                                                            <button type="submit" class="btn btn-sm btn-success" onclick="EditStatus(event,{{ $Document->id }})">Update Status <i class="fas fa-upload"></i>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <!-- Status modal -->
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach --}}
                                @endforeach
                                @if(count($AdditionalRejectDocument)>0)
                                    <td colspan="3" class="parent_header px-4 font-weight-bold">Additional Documents</td>
                                    @foreach($AdditionalRejectDocument as $rejectKey => $Reject)
                                    @php
                                        $getParent = App\Models\AdditionalDocument::where('name', $Reject->document_name)->first();

                                        if($getParent->parent_id==1){
                                            $documentParent = "AFFIDAVIT";
                                        }
                                        elseif($getParent->parent_id==2){
                                            $documentParent = "DECLARATION";
                                        }elseif($getParent->parent_id==3){
                                            $documentParent = "OWNERSHIP PROOF";
                                        }else{
                                            $documentParent = "ADDL PHOTOCOPIES";
                                        }
                                    @endphp
                                        <tr>
                                            <td width="30%">{{ $documentParent}}</td>
                                            <td>{{  $Reject->document_name}}</td>
                                            <td>
                                                <div class="pl-2 addition_status text-dark">
                                                    @if($Reject->status==2)
                                                    <img src="{{ asset('admin/img/check.png') }}" alt="">
                                                    @elseif($Reject->status==1)
                                                    <img src="{{ asset('admin/img/clock.png') }}" alt="">
                                                    @else
                                                    <img src="{{ asset('admin/img/remove.png') }}" alt="">
                                                    @endif
                                                    <select onchange="AdditionalStatus(event,{{ $Reject->id }}, this.value)">
                                                        <option value="1" {{ $Reject->status==1 ? "Selected":"" }}>Pending</option>
                                                        <option value="2" {{ $Reject->status==2 ? "Selected":"" }}>Accepted</option>
                                                        <option value="0" {{ $Reject->status==0 ? "Selected":"" }}>Rejected</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{-- Additional Documents --}}
                       <div class="mt-4" id="additionalData">
                        <input type="checkbox" id="additionalDoc" onclick="checkMe(this.checked);" /><label for="additionalDoc">Additional Documents</label>
                            <form action="{{ route('user.docucheccker.additional-document.store') }}" method="POST">
                            <div class="row" id="divcheck" style="display:none;">
                                <div class="col-md-5">
                                    <select id="DocParent" class="form-control" onchange="SelectAddionalReason(event)">
                                        <option value="" selected disabled>Select document parent..</option>
                                        <option value="1">AFFIDAVIT</option>
                                        <option value="2">DECLARATION</option>
                                        <option value="3">OWNERSHIP PROOF</option>
                                        <option value="4">ADDL PHOTOCOPIES</option>
                                    </select>
                                </div>
                                @csrf
                                <div class="col-md-5">
                                    <select id="AddDocuments" class="form-control d-none" name="AddDocuments">
                                        <option value="" selected disabled>Select document parent..</option>
                                    </select>
                                    <input type="hidden" name="Docuchecker" value="{{ $Docuchecker->id }}">
                                </div>
                                <div class="col-md-2 d-none text-center" id="AddSubmit">
                                    <input type="submit" class="btn btn-primary w-100" value="Submit">
                                </div>
                            </div>
                            </form>
                       </div>
                        @php
                            $notifyDate = App\Models\CustomerLoanDocument::select('created_at')->latest('created_at')->where('docuchecker_id', $Docuchecker->id)->first();
                            // dd(count($notifyDate));
                        @endphp
                        @if($notifyDate != null)
                            <div class="mt-4 text-center">
                                <button class="btn primary_btn" data-toggle="modal" data-target="#NotifyRMModal">Notify RM</button>
                            </div>
                        <!-- Modal -->
                            @php
                            $dataArray = array();
                            @endphp
                            @foreach($pendingData as $pendingKey => $pendingValue)
                                @php
                                if($pendingValue->status==4){
                                    $RejectDocument = App\Models\RejectDocument::select('document_reason')->latest('created_at')->where('customer_loan_documents_id', $pendingValue->id)->where('status', 0)->get();
                                    foreach($RejectDocument as $RejectData){
                                        $dataArray[] = $RejectData->document_reason;
                                        $dataArray[] = 'Rejected';
                                    }
                                }
                                if($pendingValue->status==1){
                                    $dataArray[]=$pendingValue->document_name;
                                    $dataArray[]='Pending';
                                }
                                if($pendingValue->status==0){
                                    $dataArray[]=$pendingValue->document_name;
                                    $dataArray[]='Rejected';
                                }
                                $odd = array();
                                $even = array();
                                foreach ($dataArray as $k => $v) {
                                    if ($k % 2 == 0) {
                                        $even[] = $v;
                                    }
                                    else {
                                        $odd[] = $v;
                                    }
                                }
                                @endphp
                            @endforeach
                            <div class="modal fade" id="NotifyRMModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Mail to RM</h5>
                                    </div>
                                    <div class="modal-body">
                                    <p> Dear <strong>{{ $Docuchecker->RMData->name }}</strong>. <br>You are hereby requested to resolve the following pendency/issue against the mentioned loan Account no. <strong>{{ $Docuchecker->loan_ac_no }}</strong>, Customer name <strong>{{ $Docuchecker->CustomerData->name_prefix }} {{ $Docuchecker->CustomerData->full_name }}</strong> . for which the documents have been first submitted by you on {{ date('d M Y', strtotime($notifyDate->created_at))}} , Looking for your co-operation.</p>
                                    <form action="{{ route('user.docuchecker.notify-mail') }}" method="post">
                                        @csrf
                                        <table class="table table-sm table-bordered table-hover">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="4%">SR</th>
                                                    <th width="90%">Pending/Rejected Document</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($even))
                                                    @foreach($even as $evenKey => $evenData)
                                                    <tr>
                                                        <input type="hidden" name="documents[]" value="{{ $evenData }}">
                                                        <input type="hidden" name="status[]" value="{{ $odd[$evenKey] }}">
                                                        <td>{{ $evenKey+1 }}</td>
                                                        <td>{{ $evenData }}</td>
                                                        <td>{{ $odd[$evenKey] }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                {{-- @foreach($DocumentName as $DocNameKey => $DocumentNamedata)
                                                @php
                                                   $alredyHave = App\Models\CustomerLoanDocument::where('docuchecker_id', $Docuchecker->id)->where('document_name', $DocumentNamedata)->first();
                                                @endphp
                                                    @if(empty($alredyHave))
                                                        <tr>
                                                            <input type="hidden" name="documents[]" value="{{ $DocumentNamedata }}">
                                                            <input type="hidden" name="status[]" value="Pending">
                                                            <td>{{ $DocNameKey+1 }}</td>
                                                            <td>{{ $DocumentNamedata }}</td>
                                                            <td>{{ "Pending" }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="docuId" value="{{ $Docuchecker->id }}">
                                            <input type="hidden" name="notifydate" value="{{ date('d M Y', strtotime($notifyDate->created_at))}}">
                                            <button type="submit" class="btn btn-primary">Send Mail</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<!-- bs stepper -->
<script src="{{ asset('admin/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
<script>

function checkMe(selected){
    if(selected)
    {
    document.getElementById("divcheck").style.display = "";
    } 
    else
    {
    document.getElementById("divcheck").style.display = "none";
    }

}
    $(document).ready(function () {
        $('#showOfficeTable').DataTable();
    });
    function EditStatus(e, id){
        e.preventDefault();
        var Observation = "";
        var status = $('#status'+id+'').val();
        var docuCheckId = $('#docuCheckId'+id+'').val();
        var RejectReason = $('#RejectReason'+id+'').val();
        var Observation = $('#Observation'+id+'').val();
        var ReasonData = "";
        if(RejectReason.length > 0){
            ReasonData = RejectReason;
        }
        $.ajax({
            type: "POST",
            url: "{{ route('user.borrower.docuchecker-status') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                docuCheckId : docuCheckId,
                status : status,
                ReasonData : ReasonData,
                Observation : Observation,
                id : id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms'+id+'').addClass('badge badge-success').html("Updated Document Status");
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
    function SelectReason(e, id){
        e.preventDefault();
        var Reason_head = $('#Reason_head'+id+'').val();
        var status = $('#status'+id+'').val();
        // alert(status);
        $('#RejectDiv'+id+'').addClass('d-none');
        if(status ==4){
            $('#RejectDiv'+id+'').removeClass('d-none');
            $.ajax({
                type: "POST",
                url: "{{ route('user.borrower.rejection-reason-list') }}",
                data: { 
                    '_token': '{{ csrf_token() }}',
                    Reason_head : Reason_head
                },
                success:function(response){
                    if (response.status == 200) {
                        var array = response.data;
                        $('#RejectReason'+id+'').find('option').remove();
                        $('#RejectReason'+id+'').append('<option value="">Select Reason..</option>');
                            $.each(array, function(index, value) {
                                $('#RejectReason'+id+'').append('<option value="' + value[1] + '">' + value[1] + "</option>");
                            });
                    }else{
                        $('#RejectReason'+id+'').find('option').remove();
                        $('#RejectReason'+id+'').append('<option value="">Data Not Found..</option>');
                    }
            }});
        }
       
    }
    function SelectAddionalReason(e){
        e.preventDefault();
        var DocParent = $('#DocParent').val();
        // alert(status);
        $('#AddDocuments').removeClass('d-none');
        $('#AddSubmit').removeClass('d-none');
            $.ajax({
                type: "POST",
                url: "{{ route('user.borrower.additional-reason-list') }}",
                data: { 
                    '_token': '{{ csrf_token() }}',
                    DocParent : DocParent
                },
                success:function(response){
                    if (response.status == 200) {
                        var array = response.data;
                        $('#AddDocuments').find('option').remove();
                        $('#AddDocuments').append('<option value="">Select Reason..</option>');
                            $.each(array, function(index, value) {
                                $('#AddDocuments').append('<option value="' + value[1] + '">' + value[1] + "</option>");
                            });
                    }else{
                        $('#AddDocuments').find('option').remove();
                        $('#AddDocuments').append('<option value="">Data Not Found..</option>');
                    }
            }});

    }
    function AdditionalStatus(e, id, value){
        e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('user.docucheccker.additional-status') }}",
                data: { 
                    '_token': '{{ csrf_token() }}',
                    value : value,
                    id : id
                },
                success:function(response){
                    if (response.status == 200) {
                            location.reload();
                    }else{
                        location.reload();
                    }
            }});

    }
    // function EditDate(e, id){
    //     e.preventDefault();
    //     var date = $('#date'+id+'').val();
    //     var DocuId = $('#DocuId'+id+'').val();
    //     $.ajax({
    //         type: "POST",
    //         url: "{{ route('user.borrower.docucheckerexpirydate') }}",
    //         data: { 
    //             '_token': '{{ csrf_token() }}',
    //             DocuId : DocuId,
    //             date : date,
    //             id : id
    //          },
    //         success:function(result){
    //             if(result.status ==200){
    //                 $('#alertSms2'+id+'').addClass('badge badge-success').html("Update Expiry Date");
    //                 setTimeout(function () {
    //                     $('#alertSms2'+id+'').removeClass('badge badge-success').html('');
    //                     location.reload();
    //                 }, 1000);
                    
    //             }else{
    //                 $('#alertSms2'+id+'').addClass('badge badge-danger').html("Something Happened !");
    //                 setTimeout(function () {
    //                     $('#alertSms2'+id+'').removeClass('badge badge-danger').html('');
    //                     location.reload();
    //                 }, 3000);
                     
    //             }
    //     }});
    // }
</script>
@endsection
