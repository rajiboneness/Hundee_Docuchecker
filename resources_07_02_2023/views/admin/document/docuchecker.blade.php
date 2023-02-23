@extends('layouts.auth.master')

@section('title', 'Agreement List')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="text-right">
                            <a href="http://127.0.0.1:8000/user/borrower/create" class="btn  btn-sm btn-primary"> <i class="fas fa-plus"></i> Create New Agreement</a>
                        </div>
                        <h5 class="font-weight-light text-dark">
                            <span class="font-weight-normal" title="Borrower's name">
                                {{ $customer }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-hover" id="borrowers-table">
                            <thead>
                                <tr class="text-center">
                                    <th width="4%">SR</th>
                                    <th width="20%">Agreement Type</th>
                                    <th>Loan Ac No</th>
                                    <th>RM(branch)</th>
                                    <th>Sansan Amount & Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <table class="table table-sm table-bordered table-hover">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
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
@endsection