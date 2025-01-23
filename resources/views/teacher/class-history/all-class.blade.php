@extends('layouts.app')

@push('title')
{{$pageTitle}}
@endpush

@section('content')
<!-- Page content area start -->
<div class="container-fluid pb-4">
    <div class="p-30">
        <div class="row justify-content-center mt-4 bg-white shadow-sm rounded p-2">
            <h5 class="my-3 text-center">{{$pageTitle}}</h5>
            @foreach ($allday as $day)
                <div class="col-md-6 col-12 p-0">
                    <div class="border rounded p-3 text-center shadow m-3 bg-muted">
                        <h6 class="mb-3 text-center">{{$day}}</h6>
                        <div class="row justify-content-center align-items-center">
                            @foreach ($class as $item)
                                @if ($item->day == $day)
                                    <div class="col-md-6 col-12">
                                        <div class="btn btn-outline-primary btn-sm w-100 text-center px-2 ">
                                            <span class="mb-2 fs-6 fw-bold text-light-emphasis">{{__('Class Time')}}</span>
                                            <span class="d-flex py-2 justify-content-center"> {{\Carbon\Carbon::parse($item?->start_time)->format('h:i A') }} - {{\Carbon\Carbon::parse($item?->end_time)->format('h:i A') }} </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Page content area end -->
@endsection
