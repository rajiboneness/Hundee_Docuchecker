@extends('layouts.auth.master')

@section('title', 'View agreement details')

@section('content')
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
                                <a href="{{ route('user.estamp.list') }}" class="btn btn-sm btn-primary"> <i
                                        class="fas fa-chevron-left"></i> Back</a>

                                <a href="{{ route('user.estamp.edit', $stamp_details->id) }}" class="btn btn-sm btn-success"
                                    title="Edit estamp"><i class="fas fa-edit"></i> Edit</a>

                                <a href="javascript: void(0)" class="btn btn-sm btn-danger" title="Delete estamp"
                                    onclick="confirm4lert('{{ route('user.estamp.destroy') }}', {{ $stamp_details->id }}, 'delete')"><i
                                        class="fas fa-trash"></i> Delete</a>
                            </div>
                        </div>
                        <div class="card-body">

                            <p class="text-muted small mb-0">Unique Stamp Code</p>
                            <p class="text-dark small mb-0">{{ $stamp_details->unique_stamp_code }}</p>
                            <hr>
                            <p class="text-muted small mb-0">Amount(Rs)</p>
                            <p class="text-dark small mb-0">{{ $stamp_details->amount }}</p>
                            <hr>
                            File 
                            @php
                                $file_path = $stamp_details->file_path;
                                $file_extension= explode('.',$file_path)[1];
                            @endphp
                            @if ($file_extension === 'jpg' || $file_extension === 'jpeg' || $file_extension === 'png')
                            <div class="bl_img">
                                <img src="{{ asset($file_path) }}" alt="" class="img-fluid mx-auto">
                            </div>
                            @else
                            <div class="bl_img">
                                <a href="{{ asset($certificate->image) }}" target="_blank"
                                    class="img-fluid mx-auto w-100">PDF file
                                    <span
                                        title="Update on"></span>
                                    <i class="fas fa-arrow-right"></i></a>
                            </div>
                            @endif
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>

    </script>
@endsection
