@extends('layouts.auth.master')

@section('title', 'Enquiry List')

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
                            <a href="{{ route('user.docu-checker.customer') }}" class="btn btn-sm btn-primary"> <i class="fas fa-chevron-left"></i> Back</a>
                            <a href="#csvExportEnquiryModal" data-toggle="modal" class="btn {{auth()->user()->user_type == 1 || auth()->user()->user_type == 3 ? '' : 'disabled'}} btn-sm btn-primary"> <i
                                class="fas fa-file-csv"></i> Export CSV</a>
                            <a href="{{ route('user.docu-checker.maildetails') }}" class="btn btn-sm btn-primary"> <i class="nav-icon fas fa-edit"></i> Mail Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="agreement_id" id="agreement_id" value="1">
                        <table class="table table-sm table-bordered table-hover" id="showOfficeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>RM(branch)</th>
                                    <th>Agreement</th>
                                    <th>Loan Acc No</th>
                                    <th>Request Amount</th>
                                    <th>Sanction Amount</th>
                                    <th>Sanction Date</th>
                                    <th>First Submission Date</th>
                                    <th>Initiation Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($AllEnquiry as $key => $data)
                                
                                @php
                                    $onlydate = array();
                                    $firstSubDate = App\Models\CustomerLoanDocument::where('docuchecker_id', $data->id)->get();
                                   $firstDate="";
                                    if(count($firstSubDate)>0){
                                        foreach($firstSubDate as $subDate){
                                        $onlydate[] = date('Y-m-d', strtotime($subDate->created_at));
                                        }
                                        $uniquedate = array_unique($onlydate);
                                        $firstDate = min($uniquedate);
                                        
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $data->CustomerData->name_prefix.' '.$data->CustomerData->full_name }}</td>
                                    <td>
                                        <span>RM: {{ $data->RMData->name }}</span><br>
                                        <span>Branch: {{ $data->OfficeData->name }}</span>
                                    </td>
                                    <td>{{ $data->agreement_name }}</td>
                                    <td>{{ $data->loan_ac_no }}</td>
                                    <td>{{ $data->loanAmount ?  "₹".number_format($data->loanAmount) :"" }}</td>
                                    <td>{{ $data->sanction_date ?  $data->sanction_date : "NA"}}</td>
                                    <td>{{ $data->sanction_amount ?  $data->sanction_amount : "NA"}}</td>
                                    <td>{{ $data->first_submission_date ? date('d M Y', strtotime($data->first_submission_date)) : "NA" }}</td>
                                    <td>{{ !empty($firstDate) ? date('d M Y', strtotime($firstDate)) : "NA" }}</td>
                                    <td>
                                        <span class="pl-3 pr-3 badge badge-{{ $data->status ==1 ? "danger" : "success"}}">{{ $data->status ==1 ? "Pending" : "Sanction"}}</span>
                                    </td>
                                    <td>
                                        <a href="{{route('user.docu-checker.details', $data->id)}}"
                                        class="pl-3 pr-3 badge badge-primary text-light">Check Details</a>
                                        {{-- <a href="{{route('user.docu-checker.details', $data->id)}}"
                                        class="pl-3 pr-3 badge badge-primary text-light">Edit</a> --}}
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
    {{-- Export Borroers--}}
    <div class="modal fade" id="csvExportEnquiryModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Export enquiry details
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="get" action="{{ route('user.docuchecker.enquiry.export') }}">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="">Start</label>
                                <input type="date" class="form-control" name="to_date" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="">End</label>
                                <input type="date" class="form-control" name="from_date" placeholder="" required>
                            </div>
                        </div>
                        <input type="hidden" name="EnquiryDetails" value="EnquiryDetails">
                        <div class="text-right mt-3">
                            <a href="{{ route('user.customer.enquiry-list') }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-sm rounded-0" type="button"
                                        title="Refresh page"> Reset <i
                                            class="fas fa-sync-alt"></i></button>
                                </span>
                            </a>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success btn-sm rounded-0">Export <i class="fas fa-upload"></i></button>
                                </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Export --}}
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
