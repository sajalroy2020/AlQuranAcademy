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
                <div class="d-flex flex-wrap gap-2 align-items-end justify-content-center">
                    <div class="primary-form-group">
                        <div class="primary-form-group-wrap">
                            <label class="form-label">{{ __('Select Time') }}</label>
                            <input type="time" class="form-control time-input" name="time">
                        </div>
                    </div>
                    <div>
                        <button data-day="Monday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Monday</button>
                    </div>
                    <div>
                        <button data-day="Tuesday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Tuesday</button>
                    </div>
                    <div>
                        <button data-day="Wednesday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Wednesday</button>
                    </div>
                    <div>
                        <button data-day="Thursday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Thursday</button>
                    </div>
                    <div>
                        <button data-day="Friday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Friday</button>
                    </div>
                    <div>
                        <button data-day="Saturday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Saturday</button>
                    </div>
                    <div>
                        <button data-day="Sunday" class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-4 mb-1">Sunday</button>
                    </div>
                </div>

                <div class="row showClassSlot justify-content-center mt-4"></div>
            </div>
        </div>
    </div>
</div>
<!-- Page content area end -->

<!-- Add Modal section start -->
<div class="modal" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="ajax-request reset" action="{{route('admin.student.store')}}" method="POST" data-handler="commonResponse">
                @csrf
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center pb-30">
                        <h5>{{__('Add New Student')}}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="currentPassword" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="currentPassword" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                  <input type="email" class="form-control" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="currentPassword" class="form-label">{{ __('Phone - (WhatsApp)') }} <span class="text-danger">*</span></label>
                                  <input type="number" class="form-control" name="phone">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="BatchName" class="form-label">{{ __('Gender Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control" id="BatchName" name="gender">
                                    <option value="{{GENDER_MALE}}">{{ __('Male') }}</option>
                                    <option value="{{GENDER_FEMALE}}">{{ __('Fimale') }}</option>
                                    <option value="{{GENDER_OTHERS}}">{{ __('Other') }}</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 pt-3">
                            <label for="BatchName" class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-control multiple-select-clear-field" data-placeholder="Select Teacher Course" multiple name="course_id[]">
                                @foreach ($courseList as $data)
                                    <option value="{{$data->id}}">{{$data->subject_name}}</option>
                                @endforeach
                            </select>
                            <div class="course_id"></div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="BatchName" class="form-label">{{ __('Country Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control getCountryState" id="BatchName" name="country_id">
                                    <option value="">{{__("Select Country")}}</option>
                                    @foreach ($countryList as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="BatchName" class="form-label">{{ __('State Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control addState" id="BatchName" name="state_id">
                                    <option value="">{{__("Select State")}}</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="currentPassword" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                  <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary w-25">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->

<input type="hidden" id="get-filter-schedule" value="{{ route('admin.class-schedule.filter-schedule') }}">
<input type="hidden" id="get-state-route" value="{{ route('admin.student.get-state') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/js/check-shedule.js') }}"></script>
    <script src="{{ asset('admin/js/student.js') }}"></script>
@endpush
