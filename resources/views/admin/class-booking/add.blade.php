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
                <a href="{{route('admin.class-booking.list')}}" class="btn btn-primary mb-0"><i class="fa fa-plus"></i> {{ __('Back') }}</a>
            </div>
            <div class="bg-white rounded p-3">
                <!-- class add form -->
                <form class="ajax-request reset" action="{{route('admin.class-booking.store')}}" method="POST" data-handler="responseWithPageLoad">
                    @csrf
                    <input type="hidden" id="class_slot_id" name="class_slot_id">
                    <input type="hidden" id="class_schedule_id" name="class_schedule_id">

                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Student Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control getTeacherCourse" name="student_id">
                                    <option value="">{{__("Select Student")}}</option>
                                    @foreach ($students as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control addCourse" id="getFilterCourse" name="course_id">
                                    
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Select Day') }} <span class="text-danger">*</span></label>
                                  <select class="form-control filterTeachersDate" name="day">
                                    <option value="">{{__("Select Day")}}</option>
                                    <option value="Monday">{{__("Mon Day")}}</option>
                                    <option value="Tuesday">{{__("Tues Day")}}</option>
                                    <option value="Wednesday">{{__("Wednes Day")}}</option>
                                    <option value="Thursday">{{__("Thurs Day")}}</option>
                                    <option value="Friday">{{__("Fri Day")}}</option>
                                    <option value="Saturday">{{__("Satur Day")}}</option>
                                    <option value="Sunday">{{__("Sun Day")}}</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label class="form-label">{{ __('Teacher Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control showCourseTeacher" id="getTeacherId" name="teacher_id">
                                  </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row showClassSlot justify-content-center"></div>

                    <div class="d-flex justify-content-center pt-4">
                        <button type="submit" class="btn btn-primary w-25">{{ __('Booked Class') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Page content area end -->
<input type="hidden" id="get-filter-course-route" value="{{ route('admin.class-booking.get-filter-course') }}">
<input type="hidden" id="get-filter-teacher" value="{{ route('admin.class-booking.get-teacher-filter') }}">
<input type="hidden" id="get-teacher-class-list" value="{{ route('admin.class-booking.get-teacher-class-list') }}">

@endsection

@push('script')
    <script src="{{ asset('admin/js/class-booking.js') }}"></script>
@endpush
