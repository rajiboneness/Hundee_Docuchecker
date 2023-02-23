@extends('layouts.auth.master')

@section('title', 'Set Relationship-Manager')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="text-small">{{ $data[0]->borrowerDetails->full_name }}</h5>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                    <a href="{{ route('user.borrower.list') }}" class="btn btn-sm btn-primary"> <i
                                            class="fas fa-chevron-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal" method="POST" action="{{ route('user.borrower.storeRm') }}">
                                @csrf

                                <input type="hidden" name="borrowers_id" value="{{ $data[0]->borrower_id }}">
                                <div class="form-group row">
                                    <label for="rm_id" class="col-sm-2 col-form-label">Select Loan agreement<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="borrowers_agreement_id" class="form-control"
                                            id="borrowers_agreement_id">
                                            <option value="">Select Agreement</option>
                                            @foreach ($data as $item)
                                                <option value="{{ $item->id }}">{{ $item->agreementDetails->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('borrowers_agreement_id')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Borrower<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            value="{{ $data->borrowerDetails->full_name }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Agreement<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                            value="{{ $data->agreementDetails->name }}" readonly>
                                    </div>
                                </div> --}}

                                <div class="form-group row">
                                    <label for="rm_id" class="col-sm-2 col-form-label">Select R.M<span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="rm_id" id="rm_id" class="form-control">
                                            <option value="">Select R.M</option>
                                            @foreach ($relationship_managers as $rm)
                                                <option value="{{ $rm->id }}">{{ $rm->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('rm_id')
                                            <p class="small mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>Loan Agreement</th>
                                        <th>R.M</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agrm as $a)
                                        <tr>
                                            <td>{{ $a->id }}</td>
                                            @php
                                                $lag = App\Models\BorrowerAgreement::find($a->borrowers_agreement_id);
                                                $uname = App\Models\User::find($a->rm_id);
                                            @endphp
                                            <td>{{ $lag->agreementDetails->name }}</td>
                                            <td>{{ $uname->name }}</td>
                                            <td><a href="javascript:void(0)" data-id="{{ $a->id }}"
                                                    class="badge badge-dark" data-toggle="modal"
                                                    data-target="#exampleModal{{ $a->id }}">Comments</a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="exampleModal{{ $a->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Write Comments</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('user.borrower.storeRmComments', [$a->id]) }}" method="POST">@csrf
                                                            <textarea name="rm_comments" class="form-control">{{ $a->rm_comments }}</textarea>

                                                            <div class="w-100 text-end text-right mt-3">
                                                                <button class="btn btn-danger">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    <script></script>
@endsection
