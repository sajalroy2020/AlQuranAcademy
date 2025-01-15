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
                <table class="table zTable" id="studentDataTable">
                    <thead>
                        <tr>
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
                                  <label for="currentPassword" class="form-label">{{ __('Phone') }} <span class="text-danger">*</span></label>
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
                        <div class="col-12">
                            <div class="primary-form-group mt-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="currentPassword" class="form-label">{{ __('Date Of Birth') }} <span class="text-danger">*</span></label>
                                  <input type="date" class="form-control" name="dob">
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
                        <!-- <div class="col-12">
                            <div class="primary-form-group my-2 pt-2">
                                <div class="primary-form-group-wrap">
                                  <label for="BatchName" class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                                  <select class="form-control" id="BatchName" name="course_id">
                                    <option value="">{{__("Select Course")}}</option>
                                    @foreach ($courseList as $data)
                                        <option value="{{$data->id}}">{{$data->subject_name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                        </div> -->
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

<!-- Edit Modal section start -->
<div class="modal fade" id="editModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        </div>
    </div>
</div>
<!-- Edit Modal section end -->

<input type="hidden" id="student-list-route" value="{{ route('admin.student.all') }}">
<input type="hidden" id="get-state-route" value="{{ route('admin.student.get-state') }}">

@endsection

@push('script')
    <script src="{{ asset('admin/js/student.js') }}"></script>
@endpush
