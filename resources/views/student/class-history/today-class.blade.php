@extends('layouts.app')

@push('title')
{{$pageTitle}}
@endpush

@section('content')
<!-- Page content area start -->
<div class="container-fluid py-4">
    <div class="p-30">
        <div class="row justify-content-center align-items-center mt-4 bg-white shadow-sm rounded p-4">
            <h6 class="mb-3 text-center">{{$pageTitle}}</h6>
            @foreach ($class as $item)
                <div class="col-md-2 col-12">
                    <div class="btn btn-outline-primary btn-sm w-100 text-center px-2">
                        <span class="mb-2 fs-6 fw-bold text-light-emphasis">
                            {{$item->classSchedule->day === $today ? $today : ''}}
                        </span>
                        <span class="d-flex pt-2"> {{\Carbon\Carbon::parse($item->classSchedule?->start_time)->format('h:i A') }} - {{\Carbon\Carbon::parse($item->classSchedule?->end_time)->format('h:i A') }} </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Page content area end -->
@endsection
