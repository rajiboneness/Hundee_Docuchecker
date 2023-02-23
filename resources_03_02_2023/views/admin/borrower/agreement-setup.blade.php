@extends('layouts.auth.master')

@section('title', 'Setup borrower agreement')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $borrower->name_prefix }} {{ $borrower->full_name }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                            <a href="javascript: void(0)" class="btn btn-sm btn-primary" onclick="openSidebarModal()"><i class="fas fa-plus"></i> Add new agreement</a>
                            <a href="{{route('user.borrower.list')}}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover" id="borrowers-table">
                            <thead>
                                <tr>
                                    <th>SR</th>
                                    <th>Agreement</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $key => $borrower_single_agreement)
                                <tr id="tr_{{ $borrower_single_agreement->id }}">
                                    <td>{{$key + 1}}</td>
                                    <td>{{$borrower_single_agreement->agreementDetails->name}}</td>
                                    <td class="text-right">
                                        @if (!$borrower_single_agreement->borrowerDetails->borrowerAgreementRfq)
                                        <a href="{{route('user.borrower.agreement',$borrower_single_agreement->id)}}" class="badge badge-danger">Prepare Agreement</a>
                                            <a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{ route('user.borrower.agreement.destroy') }}', {{ $borrower_single_agreement->id }}, 'delete')">Delete</a>
                                        @else
                                            <a href="{{route('user.borrower.agreement',$borrower_single_agreement->id)}}" class="badge badge-primary">View Agreement</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr><td class="text-center text-muted small" colspan="100%"><em>No records found</em></td></tr>
                                @endforelse
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
        // create
        function openSidebarModal() {
            let content = '';
            content += '<div class="alert rounded-0 px-2 py-1 small" id="newDeptAlert" style="display:none"></div>';
            content += '<p class="text-dark small mb-1">Agreement <span class="text-danger">*</span></p><select class="form-control form-control-sm mb-2" name="agreement_id" id="agreement_id"><option value="" hidden selected>Select agreement...</option>';

            content += '@foreach ($agreements as $single_agreement) <option value="{{$single_agreement->id}}">{{$single_agreement->name}}</option> @endforeach';

            content += '</select>';
            content += '<input type="hidden" name="borrower_id" id="borrower_id" value="{{$id}}">';

            $('#userDetails .modal-content').append('<div class="modal-footer text-right"><a href="javascript: void(0)" class="btn btn-sm btn-success" onclick="storeDept()">Save changes <i class="fas fa-upload"></i> </a></div>');

            $('#appendContent').html(content);
            $('#userDetailsModalLabel').text('Add new agreement');
            $('#userDetails').modal('show');
        }

        // store
        function storeDept() {
            $('#newDeptAlert').removeClass('alert-danger alert-success').html('').hide();
            $.ajax({
                url: '{{ route('user.borrower.agreement.create') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    borrower: $('#borrower_id').val(),
                    agreement: $('#agreement_id').val(),
                },
                success: function(result) {
                    $("#userDetails .modal-body").animate({
                        scrollTop: $("#userDetails .modal-body").offset().top - 60
                    });
                    if (result.status == 200) {
                        location.href = '{{ route('user.borrower.agreement.setup', $id) }}';
                    } else {
                        $('#newDeptAlert').addClass('alert-danger').html(result.message).show();
                    }
                }
            });
        }
    </script>
@endsection