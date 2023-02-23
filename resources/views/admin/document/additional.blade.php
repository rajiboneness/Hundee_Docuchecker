@extends('layouts.auth.master')

@section('title', 'Additional Documents List')

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
                            <a href="#CreateModal" class="btn btn-sm btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Add New</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="agreement_id" id="agreement_id" value="1">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="30%">Parent Document</th>
                                    <th width="60%">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($AdditionalDocument as $key => $value)
                                @php
                                $getDocument = App\Models\AdditionalDocument::where('parent_id', $value->parent_id)->get();
                                if($value->parent_id == 1){
                                    $parent_name= "AFFIDAVIT";
                                }elseif($value->parent_id == 2){
                                    $parent_name= "DECLARATION";
                                }elseif($value->parent_id == 3){
                                    $parent_name= "OWNERSHIP PROOF";
                                }elseif($value->parent_id == 4){
                                    $parent_name= "ADDL PHOTOCOPIES";
                                }else{
                                    $parent_name= "CONCENT LETTER"; 
                                }
                                @endphp
                                <tr id="tr_{{ $value->id }}">
                                    <td>{{ $key+1 }}</td>
                                    <td> {{ $parent_name }}</td>
                                    <td>
                                        <ul>
                                            @foreach($getDocument as $document)
                                                @if(count($getDocument)>0)
                                                    <li  id="tr_{{ $document->id }}"> <span>{{ $document->name }}</span>
                                                        <a href="#EditModal{{ $document->id }}" class="badge badge-dark action-button" data-toggle="modal" title="Edit">Edit</a>
                                                        <a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{ route('user.document.additional-delete') }}', {{ $document->id }}, 'delete')">Delete</a></span>
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
                                                                                <label class="form-label">Parent name</label>
                                                                                <select name="parent_id" id="parent_id{{ $document->id }}" class="form-control" required>
                                                                                    <option value="">Select Parent name..</option>
                                                                                    <option value="1" {{ $document->parent_id==1 ? "Selected":"" }}>AFFIDAVIT</option>
                                                                                    <option value="2" {{ $document->parent_id==2 ? "Selected":"" }}>DECLARATION</option>
                                                                                    <option value="3" {{ $document->parent_id==3 ? "Selected":"" }}>OWNERSHIP PROOF</option>
                                                                                    <option value="4" {{ $document->parent_id==4 ? "Selected":"" }}>ADDL PHOTOCOPIES</option>
                                                                                    <option value="5" {{ $document->parent_id==5 ? "Selected":"" }}>CONCENT LETTER</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Document</label>
                                                                                <textarea name="document_edit" id="document_edit{{ $document->id }}" class="form-control">{{ $document->name }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer text-right">
                                                                            <button type="submit" class="btn btn-sm btn-success" onclick="EditDescription(event,{{ $document->id }})">Save changes <i class="fas fa-upload"></i> </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
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
                            <label class="form-label">Parent name</label>
                            <select name="parent_id" id="parent_id" class="form-control" required>
                                <option value="">Select Parent name..</option>
                                <option value="1">AFFIDAVIT</option>
                                <option value="2">DECLARATION</option>
                                <option value="3">OWNERSHIP PROOF</option>
                                <option value="4">ADDL PHOTOCOPIES</option>
                                <option value="5">CONCENT LETTER</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Document</label>
                            <textarea name="document_name" id="document_name" class="form-control"></textarea>
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
        var document_edit = $('#document_edit'+id+'').val();
        var parent_id = $('#parent_id'+id+'').val();
        if(parent_id.length == 0){
            $('#alertSms'+id+'').addClass('badge badge-danger').html("Please select parent name!");
            setTimeout(function () {
                $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }else if(document_edit.length == 0){
            $('#alertSms'+id+'').addClass('badge badge-danger').html("Please enter document!");
            setTimeout(function () {
                $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({type: "POST",
            url: "{{ route('user.document.additional-update') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                document_edit : document_edit,
                parent_id : parent_id,
                id : id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms'+id+'').addClass('badge badge-success').html("Updated Additional Document");
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
        var document_name = $('#document_name').val();
        var parent_id = $('#parent_id').val();
        if(parent_id.length == 0){
            $('#alertSms').addClass('badge badge-danger').html("Please select parent name!");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }else if(document_name.length == 0){
            $('#alertSms').addClass('badge badge-danger').html("Please enter document name !");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
      $.ajax({type: "POST",
            url: "{{ route('user.customer.additional-store') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                document_name : document_name,
                parent_id : parent_id,
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms').addClass('badge badge-success').html("Added New Additional Document");
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
