@extends('layouts.auth.master')

@section('title', 'Required Documents List')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="font-weight-light text-dark">
                            <span class="font-weight-normal" title="Borrower's name">
                                {{ $Agreement->borrowerDetails->name_prefix }} {{ $Agreement->borrowerDetails->full_name }}
                            </span> - <span title="Agreement name">{{ $Agreement->agreementDetails->name }}</span>
                        </h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                            <a href="{{route('user.borrower.agreement', $Agreement->id)}}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover" id="borrowers-table">
                            <thead>
                                <tr class="text-center">
                                    <th width="4%">SR</th>
                                    <th width="50%">Document</th>
                                    <th>Expiry Date</th>
                                    <th width="25%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loanDocument as $key => $docuVal)
                                {{-- @php
                                $Document = $docuVal->AgreementDocumentDetails->document_id;
                                $selectDocumentName = App\Models\Document::where('id', $Document)->first();
                                @endphp --}}
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $docuVal->document_name}}</td>
                                    <td>
                                        <span>{{$docuVal->expiry_date ? date('d M Y', strtotime($docuVal->expiry_date)): ""}}</span><br>
                                        <a href="#DateModal{{ $docuVal->id }}" data-toggle="modal" class="pl-3 pr-3 badge badge-dark text-light">Update Date</a>
                                        <!-- Expiry Modal -->
                                        <div class="modal fade pr-0" id="DateModal{{ $docuVal->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <p class="modal-title"><label class="form-label">Expiry Date</label></p>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body document-modal">
                                                            @csrf
                                                            <div id="alertSms2{{ $docuVal->id }}"></div>
                                                            <div class="mb-3">
                                                                <input type="date" name="date" id="date{{ $docuVal->id }}" class="form-control" value="{{ $docuVal->expiry_date }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="EditDate(event,{{ $docuVal->id }})">Save changes <i class="fas fa-upload"></i>
                                                                </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Expiry modal -->
                                    
                                    </td>
                                    <td>
                                        @php
                                        if($docuVal->status==1){
                                            $color = "danger";
                                            $text = "Pending";
                                        }
                                        elseif($docuVal->status==2){
                                            $color = "success";
                                            $text = "Submitted";
                                        }else{
                                            $color = "info";
                                            $text = "Approved By MD";
                                        }
                                        @endphp
                                        <a href="#StatusModal{{ $docuVal->id }}" data-toggle="modal" class="pl-3 pr-3 badge badge-{{ $color }}">{{ $text }}</a>
                                        <span>{{ $docuVal->status==1 ? " ": "on ".date('d M Y', strtotime($docuVal->updated_at)) }}</span>
                                           <!-- Status Modal -->
                                        <div class="modal fade pr-0" id="StatusModal{{ $docuVal->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                            <div id="alertSms{{ $docuVal->id }}"></div>
                                                            <div class="mb-3">
                                                                <select name="status" id="status{{ $docuVal->id }}" class="form-control">
                                                                    <option value="">Select Status..</option>
                                                                    <option value="1" {{ $docuVal->status==1 ? "Selected" : "" }}>Pending</option>
                                                                    <option value="2"{{ $docuVal->status==2 ? "Selected" : "" }}>Submitted</option>
                                                                    <option value="3"{{ $docuVal->status==3 ? "Selected" : "" }}>Approved By RM</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="EditStatus(event,{{ $docuVal->id }})">Update Status <i class="fas fa-upload"></i>
                                                                </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Status modal -->
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
 
</section>

@endsection

@section('script')
    <script>
    function EditStatus(e, id){
        e.preventDefault();
        var status = $('#status'+id+'').val();
        $.ajax({
            type: "POST",
            url: "{{ route('user.borrower.docuchecker-status') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                status : status,
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
    function EditDate(e, id){
        e.preventDefault();
        var date = $('#date'+id+'').val();
        $.ajax({
            type: "POST",
            url: "{{ route('user.borrower.docuchecker-expirydate') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                date : date,
                id : id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms2'+id+'').addClass('badge badge-success').html("Set Expiry Date");
                    setTimeout(function () {
                        $('#alertSms2'+id+'').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 1000);
                    
                }else{
                    $('#alertSms2'+id+'').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms2'+id+'').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);
                     
                }
        }});
    }
    </script>
@endsection