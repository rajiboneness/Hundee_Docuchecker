@extends('layouts.auth.master')

@section('title', 'Customer List')

@section('content')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
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
                                    <th>Customer Details</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allCustomer as $key => $data)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $data->name_prefix.' '.$data->full_name }}</td>
                                    <td>
                                        <span>Mobile: {{ $data->mobile }}</span><br>
                                        <span>Email: {{ $data->email }}</span>
                                    </td>
                                    <td><a href=" {{ route('user.docu-checker.index', [$data->id]) }}"
                                        class="pl-3 pr-3 badge badge-dark text-light">Docu Checker</a></td>
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
@endsection
