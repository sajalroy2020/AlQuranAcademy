@extends('layouts.app')

@push('title')
{{$pageTitle}}
@endpush

@section('content')
<!-- Page content area start -->
<div class="container-fluid py-4">
    <div class="p-30">
        <div>
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-3">
                <h5>{{$pageTitle}}</h5>
                <a href="{{route('admin.teacher.add')}}" class="btn btn-primary mb-0"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
            </div>
        <div class="bg-white rounded p-3">
            <!-- Table -->
            <div class="table-responsive zTable-responsive">
                <table class="table zTable" id="teacherDataTable">
                    <thead>
                        <tr>
                            <th scope="col"><div>{{ __('Image') }}</div></th>
                            <th scope="col"><div>{{ __('Name') }}</div></th>
                            <th scope="col"><div>{{ __('Email') }}</div></th>
                            <th scope="col"><div>{{ __('Phone') }}</div></th>
                            <th class="w-110 text-center" scope="col"><div>{{ __('Action') }}</div></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- Page content area end -->

<input type="hidden" id="teacher-list-route" value="{{ route('admin.teacher.all') }}">
<input type="hidden" id="get-state-route" value="{{ route('admin.teacher.get-state') }}">

@endsection

@push('script')
    <script src="{{ asset('admin/js/teacher.js') }}"></script>
@endpush
