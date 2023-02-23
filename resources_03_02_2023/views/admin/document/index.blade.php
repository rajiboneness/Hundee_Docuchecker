@extends('layouts.auth.master')

@section('title', 'Borrowers Loan Ducument')

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
                            <a href="#CreateModal" class="btn btn-sm btn-primary" data-toggle="modal"><i class="fas fa-plus"></i> Create</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="agreement_id" id="agreement_id" value="1">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="30%">Document Type</th>
                                    <th width="50%">Document Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parent as $key => $value)
                                @php
                                $getDocument = App\Models\Document::where('parent_id', $value->id)->get();
                                @endphp
                                <tr id="tr_{{ $value->id }}">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->document_name }}</td>
                                    <td>
                                        <ul>
                                            @foreach($getDocument as $document)
                                            <li>{{ $document->document_name}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @if(count($getDocument)>0)
                                            <a href="javascript: void(0)" class="badge badge-dark action-button" title="View" onclick="viewDeta1ls('{{ route('user.document.view') }}', {{ $value->id }}, 'view')" data-toggle="modal">View</a>
                                        @endif
                                        <a href="#EditModal{{ $value->id }}" class="badge badge-dark action-button" data-toggle="modal" title="Edit">Edit</a>
                                        <a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{ route('user.document.delete') }}', {{ $value->id }}, 'delete')">Delete</a>
                                        <div class="modal fade pr-0" id="EditModal{{ $value->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                            <div id="alertSms{{ $value->id }}"></div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Document Type</label>
                                                                <textarea name="document_name" id="document_name{{ $value->id }}" class="form-control">{{ $value->document_name }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="EditDeta1ls(event,{{ $value->id }})">Save changes <i class="fas fa-upload"></i> </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Edit modal -->
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
                            <label class="form-label">Document Type</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">Select Document Type..</option>
                                @foreach($parent as $parentkey => $parentValue)
                                <option value="{{ $parentValue->id }}">{{ $parentValue->document_name }}</option>
                                @endforeach
                                <option value="new-parent">New Document Type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Document Name</label>
                            <textarea name="document_name" id="document_name" class="form-control"></textarea>
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

    function EditDeta1ls(e, id){
        e.preventDefault();
        var document_name = $('#document_name'+id+'').val();
        // alert(document_name);
        if(document_name.length == 0){
            $('#alertSms'+id+'').addClass('badge badge-danger').html("Please Enter Document Name !");
            setTimeout(function () {
                $('#alertSms'+id+'').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
        $.ajax({type: "POST",
            url: "{{ route('user.document.edit') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                document_name : document_name,
                id : id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms'+id+'').addClass('badge badge-success').html("Updated Document Type");
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
        if(document_name.length == 0){
            $('#alertSms').addClass('badge badge-danger').html("Please Enter Document Name !");
            setTimeout(function () {
                $('#alertSms').removeClass('badge badge-danger').html('');
            }, 3000);
            return false;
        }
      $.ajax({type: "POST",
            url: "{{ route('user.document.create') }}",
            data: { 
                '_token': '{{ csrf_token() }}',
                document_name : document_name,
                parent_id : parent_id
             },
            success:function(result){
                if(result.status ==200){
                    $('#alertSms').addClass('badge badge-success').html("Added New Document");
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
    function viewDeta1ls(route, id, type) {
            $.ajax({
                url: route,
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id: id
                },
                success: function(result) {
                    let content = '';
                    if (type == 'view') {
                        $('#ViewDocumentModal .modal-title').text(result.document)
                        content += '<table class="table table-sm table-bordered table-hover table-striped">';
                        $.each(result.siblingsDocuments, function(key, value) {
                            let deleteRouteShow = "'{{route("user.document.sub_delete")}}'";
                            let deleteShow = "'delete'";
                            let subShow = "'sub'";
                            content += '<tr id="tr_sub_'+value.id+'"><td>'+value.document_name+'</td><td class="text-right"><a href="javascript: void(0)" class="badge badge-danger rounded-0" title="Delete" onclick="confirm4lert('+deleteRouteShow+', '+value.id+', '+deleteShow+', '+subShow+')"><i class="fas fa-trash"></i></a></td></tr>';
                        });
                        content += '</table>';
                    } 
                    $('#ViewDocumentModal .modal-body').html(content);
                    $('#ViewDocumentModal').modal('show');
                }
            });
        }
</script>
@endsection
