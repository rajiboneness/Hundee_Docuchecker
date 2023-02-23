@extends('layouts.auth.master')

@section('title', 'Agreement management')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <a href="{{route('user.agreement.create')}}" class="btn btn-sm btn-primary"> <i class="fas fa-plus"></i> Create</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered table-hover" id="showRoleTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th width="25%">Default Loan Account Number</th>
                                        <th>Description</th>
                                        <th>Fields</th>
                                        {{-- <th class="text-right">PDF</th> --}}
                                        <th>Documents</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $index => $item)
                                        <tr id="tr_{{ $item->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <h6 class="font-weight-bold single-line">{{ $item->name }}</h6>
                                            </td>
                                            <td>
                                                <h6 class="font-weight-bold single-line">{{ $item->loan_acc_no_format }}</h6>
                                            </td>
                                            <td>
                                                <p class="small text-muted">{{ substr($item->description,0,200) }}</p>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.agreement.fields', $item->id) }}"
                                                    class="badge badge-dark action-button" title="Setup fields">Setup</a>
                                            </td>
                                            {{-- <td class="single-line">
                                        @if ($item->html != '' || $item->html != null)
                                            <a href="{{route('user.agreement.pdf.view', $item->id)}}" class="badge badge-primary action-button" target="_blank"> <i class="fas fa-file-pdf"></i> View</a>
                                            <a href="{{route('user.agreement.pdf.download', $item->id)}}" class="badge badge-primary action-button download-agreement"> <i class="fas fa-download"></i> Download</a>
                                        @endif
                                    </td> --}}
                                            <td class="text-right">
                                                <a href="javascript:void(0)" class="badge badge-dark action-button"
                                                    title="Setup fields" onclick="OpenFSDMODAL('{{$item->id}}','{{$item->required_fsd}}')">Set FSD</a>
                                                <a href="{{ route('user.agreement.documents.list', $item->id) }}"
                                                    class="badge badge-dark action-button" title="Setup documents">Setup</a>
                                            </td>
                                            <td class="text-right">
                                                <div class="single-line">
                                                    <a href="javascript: void(0)" class="badge badge-dark action-button"
                                                        title="View"
                                                        onclick="viewDeta1ls('{{ route('user.agreement.show') }}', {{ $item->id }})">View</a>

                                                    <a href="{{ route('user.agreement.edit', $item->id) }}"
                                                        class="badge badge-dark action-button" title="Edit">Edit</a>

                                                    {{-- <a href="javascript: void(0)" class="badge badge-dark action-button" title="Delete" onclick="confirm4lert('{{route('user.agreement.destroy')}}', {{$item->id}}, 'delete')">Delete</a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    {{-- @empty
                                        <tr>
                                            <td colspan="100%"><em>No records found</em></td>
                                        </tr> --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="fsdmodal" tabindex="-1" role="dialog" aria-labelledby="fsdmodalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="fsdmodalLabel">Set FSD for the Agreement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="all_fsds" class="py-2">
                            
                    </div>
                    <form action="{{route('user.borrower.agreement.fsd.store')}}" method="post">@csrf
                        <div class="text-right">
                            <button id="add_more" type="button" class="btn btn-outline-dark btn-sm">+Add FSD</button>
                        </div>
                        <div>
                            <input type="hidden" name="agreement_id" value="{{$item->id}}">
                        </div>
                        <div id="all_inputs">
                            <div>
                                <input type="text" class="form-control col-8" placeholder="Document name" name="required_fsd[]">
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="text-right">
                            <button type="submit" class="btn btn-danger btn-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function OpenFSDMODAL(x,y) {
            $('input[name="agreement_id"]').val(x);
            if(y != ''){
                $('#all_fsds').html('');
                const data = y.split(",");
                data.forEach(element => {
                    var row = '<p class="d-flex justify-content-between"><b>'+element+'</b><span><button onclick="deleteFsdData('+ x +',`'+element+'`,this)" class="btn btn-danger btn-sm">X</button></span></p>';
                    $('#all_fsds').append(row);
                });
                $('#all_fsds').append('<hr class="my-2">');
            }
            $('#fsdmodal').modal('show');
        }

        function deleteFsdData(x,y,z){
            Swal.fire({
                title: 'Do you want to delete document ' + y,
                confirmButtonText: 'Yes',
                showDenyButton: true,
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'GET',
                        url: window.location.origin + "/user/agreement/" + x + "/fsd/" + y + "/delete",
                        success: function(res){
                            $(z).closest('p').remove();
                            Swal.fire('Deleted!', '', 'success');
                        }
                    })
                } else if (result.isDenied) {
                    Swal.fire('Not Deleted', '', 'info');
                }
            })
        }

        $('#add_more').on('click',function(){
            let html = '<div class="single_input d-flex my-3 justify-content-between">\
                            <input type="text" class="form-control col-8" placeholder="Document name" name="required_fsd[]">\
                            <span><button class="btn btn-outline-danger" onclick="deleteFsd(this)">X</button></span>\
                        </div>';
            $('#all_inputs').append(html);
        });

        function deleteFsd(x){
            $(x).closest('.single_input').remove();
        }
    </script>
    <script>
        function viewDeta1ls(route, id) {
            $.ajax({
                url: route,
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    id: id
                },
                success: function(result) {
                    console.log(result.data);
                    let content = '';
                    if (result.error == false) {
                        /* let authorised_signatoryShow = '<em class="text-muted">No data</em>';
                        if (result.data.authorised_signatory.length > 0) {
                            authorised_signatoryShow = result.data.authorised_signatory;
                        }

                        let borrowerShow = '<em class="text-muted">No data</em>';
                        if (result.data.borrower.length > 0) {
                            borrowerShow = result.data.borrower;
                        }

                        let co_borrowerShow = '<em class="text-muted">No data</em>';
                        if (result.data.co_borrower.length > 0) {
                            co_borrowerShow = result.data.co_borrower;
                        } */

                        content += '<h6 class="font-weight-bold mb-3">' + result.data.name + '</h6>';
                        content += '<p class="text-muted small mb-0">Description</p>';
                        content += '<p class="text-dark small">' + result.data.description + '</p>';
                        /*content += '<p class="text-muted small mb-0">Authorised Signatory</p>';
                        content += '<p class="text-dark small">'+authorised_signatoryShow+'</p>';
                        content += '<p class="text-muted small mb-0">Borrower</p>';
                        content += '<p class="text-dark small">'+borrowerShow+'</p>';
                        content += '<p class="text-muted small mb-0">Co-borrower</p>';
                        content += '<p class="text-dark small">'+co_borrowerShow+'</p>'; */

                        let route = '{{ route('user.agreement.details', 'id') }}';
                        route = route.replace('id', result.data.id);
                        $('#userDetails .modal-content').append('<div class="modal-footer"><a href="' + route +
                            '" class="btn btn-sm btn-primary">View details <i class="fa fa-chevron-right"></i> </a></div>'
                            );
                    } else {
                        content += '<p class="text-muted small mb-1">No data found. Try again</p>';
                    }
                    $('#appendContent').html(content);
                    $('#userDetailsModalLabel').text('Agreement details');
                    $('#userDetails').modal('show');
                }
            });
        }
    </script>
@endsection
