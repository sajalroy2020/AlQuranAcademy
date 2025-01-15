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
                <button type="submit" id="add-news" class="btn btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="fa fa-plus"></i> {{ __('Add New') }}</button>
            </div>
        <div class="bg-white rounded p-3">
            <!-- Table -->
            <div class="table-responsive zTable-responsive">
                <table class="table zTable" id="classScheduleDataTable">
                    <thead>
                        <tr>
                            <th scope="col"><div>{{ __('Teacher Name') }}</div></th>
                            <th scope="col"><div>{{ __('Date') }}</div></th>
                            <th scope="col"><div>{{ __('Start Time') }}</div></th>
                            <th scope="col"><div>{{ __('End Time') }}</div></th>
                            <th scope="col"><div>{{ __('Status') }}</div></th>
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

<!-- Add Modal section start -->
<div class="modal" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="ajax-request reset" action="{{route('admin.class-schedule.store')}}" method="POST" data-handler="commonResponse">
                @csrf
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center pb-30">
                        <h5>{{__('Add New Class Schedule')}}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Date') }} <span class="text-danger">*</span></label>
                                  <input type="date" class="form-control" name="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Teacher Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control getTeacherCourse" name="teacher_id">
                                    <option value="">{{__("Select Teacher")}}</option>
                                    @foreach ($teachers as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control addCourse" name="course_id">
                                    
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                                  <input type="time" class="form-control" name="start_time">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('End Time') }} <span class="text-danger">*</span></label>
                                  <input type="time" class="form-control" name="end_time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary w-25">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->

<!-- Edit Modal section start -->
<div class="modal fade" id="editModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">

        </div>
    </div>
</div>
<!-- Edit Modal section end -->

<input type="hidden" id="class-schedule-list-route" value="{{ route('admin.class-schedule.list') }}">
<input type="hidden" id="get-filter-course-route" value="{{ route('admin.class-schedule.get-filter-course') }}">

@endsection

@push('script')
    <script src="{{ asset('admin/js/class-schedule.js') }}"></script>
@endpush
