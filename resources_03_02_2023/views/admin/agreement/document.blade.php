@extends('layouts.auth.master')

@section('title', 'Documents for '.$data->agreement->name)

@section('content')
<style>

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
                            <a href="#CreateModal" class="btn btn-sm btn-primary" data-toggle="modal"><i
                                    class="fas fa-plus"></i> Create</a>
                            <a href="{{route('user.agreement.list')}}" class="btn btn-sm btn-primary"><i
                                    class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Document Type</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($ParentDocument as $dataKey => $parentdata)
                                    <tr>
                                        @php
                                            $getDoc = App\Models\Document::where('id', $parentdata)->first();
                                            @endphp
                                        <td>{{ $dataKey + 1 }}</td>
                                        <td>{{ $getDoc->document_name}}</td>
                                        <td>
                                            @forelse ($data->documents as $index => $item)
                                            @if($item->DocumentDetails->parent_id == $parentdata)
                                                <table width="100%">
                                                    <tr colspan="2" id="tr_{{ $item->id }}">
                                                        <td>
                                                        <span>{{$item->DocumentDetails->document_name }} </span>
                                                        </td>
                                                        <td width="20%" class="text-center"><a href="javascript: void(0)" class="badge badge-dark"
                                                            title="Delete"
                                                            onclick="confirm4lert('{{ route('user.agreement.documents.new-delete') }}', {{ $item->id }}, 'delete')">Delete</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            @empty
                                                <div class="text-center text-muted" colspan="100%"><em>No records found</em></div>
                                            
                                            @endforelse
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
<!-- Create Modal -->
<div class="modal fade pr-0" id="CreateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-sm" role="document">
        <div class="modal-content">
            <form method="post" id="DocumentDataForm">
                <div class="modal-header">
                    <p class="modal-title"><label class="form-label">Document Name</label></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body document-modal">
                    @csrf
                    <div id="alertSms"></div>
                    <div class="mb-3">
                        <select name="DocumentSelect[]" multiple id="DocumentSelect" class="form-control">
                            @foreach($AllDocument as $AllValue)
                            @php
                                $LeftData = App\Models\AgreementDocument::where('document_id', $AllValue->id)->where('agreement_id', $id)->first();
                            @endphp 
                            @if(!isset($LeftData))
                                <option value="{{ $AllValue->id }}">{{ $AllValue->document_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <input type="hidden" name="agreement_id" id="agreement_id" value="{{request()->id}}">
                    <button type="submit" class="btn btn-sm btn-success" id="StoreDocumentBtn">Save changes <i
                            class="fas fa-upload"></i>
                         </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create modal -->
@endsection

@section('script')
<script>
    $('#DocumentSelect').multiselect({
        columns: 1,
        placeholder: 'Select Documents',
        search: true,
        selectAll: true
    });

    $("#StoreDocumentBtn").click(function (e) {
        e.preventDefault();
        var list = $('#DocumentSelect').val();
        var agreement_id = $('#agreement_id').val();
        $.ajax({
            type: "POST",
            url: "{{ route('user.agreement.documents.new-store') }}",
            data: {
                '_token': '{{ csrf_token() }}',
                list: list,
                agreement_id: agreement_id
            },
            success: function (result) {
                if (result.status == 200) {
                    $('#alertSms').addClass('badge badge-success').html("Added New Documents");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-success').html('');
                        location.reload();
                    }, 1000);

                } else {
                    $('#alertSms').addClass('badge badge-danger').html("Something Happened !");
                    setTimeout(function () {
                        $('#alertSms').removeClass('badge badge-danger').html('');
                        location.reload();
                    }, 3000);

                }
            }
        });
    });

</script>
@endsection
