@extends('layouts.auth.master')

@section('title', 'Rejection Reasons List')

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
                            <a href="{{route('user.document.list')}}" class="btn btn-sm btn-primary"><i class="fas fa-chevron-left"></i>Back</a>
                            <a href="#CreateModal" class="btn btn-sm btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Add Reason</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="agreement_id" id="agreement_id" value="1">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="30%">Reason head</th>
                                    <th width="50%">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parent as $key => $value)
                                @php
                                $getDocument = App\Models\RejectionReason::where('document_parent_id', $value->id)->get();
                                @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->document_name }}</td>
                                    <td>
                                        <ul>
                                            @foreach($getDocument as $document)
                                            @if(count($getDocument)>0)
                                                <li id="tr_{{ $document->id }}">{{ $document->rejection_reason}} <span> <a href="#EditModal{{ $document->id }}" class="badge badge-dark action-button" data-toggle="modal" title="Edit">Edit</a></span> <span><a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{ route('user.document.rejectionreason-delete') }}', {{ $document->id }}, 'delete')">Delete</a></span></li>
                                                <div class="modal fade pr-0" id="EditModal{{ $document->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <form method="post">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div id="alertSms{{ $document->id }}"></div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Description</label>
                                                                        <textarea name="document_reason" id="document_reason{{ $document->id }}" class="form-control">{{ $document->rejection_reason }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer text-right">
                                                                    <button type="submit" class="btn btn-sm btn-success" onclick="EditDescription(event,{{ $document->id }})">Save changes <i class="fas fa-upload"></i> </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @endforeach
                                        </ul>
                                    </td>
                                    <!-- Edit Modal -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Modal -->
    <div class="modal fade pr-0" id="CreateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div id="alertSms"></div>
                        <div class="mb-3">
                            <label class="form-label">Reason head</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">Select rejection reason..</option>
                                @foreach($parent as $parentkey => $parentValue)
                                <option value="{{ $parentValue->id }}">{{ $parentValue->document_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="document_description" id="document_description" class="form-control"></textarea>
                            {{-- <input class="form-control form-control-sm" type="text" name="document_name" id="document_name"> --}}
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-sm btn-success" id="CreateDocumentBtn">Save changes <i class="fas fa-upload"></i> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Create modal -->
</section>
 
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<!-- bs stepper -->
<script src="{{ asset('admin/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>

<script>

    function EditDescription(e, id){
        e.preventDefault();
        var document_reason = $('#document_reason'+id+'').val();
        if(document_reason.length == 0){
            $('#alertSms'+id+'').addClass('badge badge-danger').html("Please Enter Description!");
            setTimeout(function () {
                $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({type: "POST",
            url: "{{ route('user.document.rejectionreason-edit') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                document_reason : document_reason,
                id : id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms'+id+'').addClass('badge badge-success').html("Updated Rejection reason");
                    setTimeout(function () {
                        $('#alertSms'+id+'').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 1000);
                    
                }else{
                    $('#alertSms'+id+'').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);
                     
                }
        }});

    }
    $("#CreateDocumentBtn").click(function(e){
      e.preventDefault();
        var document_description = $('#document_description').val();
        var parent_id = $('#parent_id').val();
        if(document_description.length == 0){
            $('#alertSms').addClass('badge badge-danger').html("Please Enter Document Name !");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
      $.ajax({type: "POST",
            url: "{{ route('user.document.rejectionreason-create') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                document_description : document_description,
                parent_id : parent_id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms').addClass('badge badge-success').html("Added New Rejection Reason");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 1000);
                    
                }else{
                    $('#alertSms').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);
                     
                }
        }});
    });
   </script>
@endsection
