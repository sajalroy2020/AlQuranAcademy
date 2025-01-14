<form class="ajax-request reset" action="{{route('student.store')}}" method="POST" data-handler="commonResponse">
    @csrf
    <div class="modal-body">
        <div class="d-flex justify-content-between align-items-center pb-30">
            <h5>{{__('Edit Student')}}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="primary-form-group mt-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="currentPassword" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="name" value="{{$student->name}}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="currentPassword" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                      <input type="email" class="form-control" name="email" value="{{$student->email}}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="currentPassword" class="form-label">{{ __('Phone') }} <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" name="phone" value="{{$student->phone}}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group my-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="BatchName" class="form-label">{{ __('Gender Select') }} <span class="text-danger">*</span></label>
                      <select class="form-control" id="BatchName" name="gender">
                        <option {{$student->gender == GENDER_MALE ? 'selected' : ''}} value="{{GENDER_MALE}}">{{ __('Male') }}</option>
                        <option {{$student->gender == GENDER_FEMALE ? 'selected' : ''}} value="{{GENDER_FEMALE}}">{{ __('Fimale') }}</option>
                        <option {{$student->gender == GENDER_OTHERS ? 'selected' : ''}} value="{{GENDER_OTHERS}}">{{ __('Other') }}</option>
                      </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group mt-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="currentPassword" class="form-label">{{ __('Date Of Birth') }} <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" name="dob" value="{{$student->dob}}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group my-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="BatchName" class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                      <select class="form-control" id="BatchName" name="course_id">
                        <option value="">{{__("Select Course")}}</option>
                        @foreach ($courseList as $data)
                            <option {{$student->course_id == $data->id ? 'selected' : ''}} value="{{$data->id}}">{{$data->subject_name}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="primary-form-group my-2 pt-2">
                    <div class="primary-form-group-wrap">
                      <label for="BatchName" class="form-label">{{ __('Country Select') }} <span class="text-danger">*</span></label>
                      <select class="form-control getCountryState" id="BatchName" name="country_id">
                        <option value="">{{__("Select Country")}}</option>
                        @foreach ($countryList as $data)
                            <option {{$student->country_id == $data->id ? 'selected' : ''}} value="{{$data->id}}">{{$data->name}}</option>
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
                        @foreach ($state as $data)
                            <option {{$student->state_id == $data->id ? 'selected' : ''}} value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
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
        <button type="submit" class="btn btn-primary w-25">{{ __('Update') }}</button>
    </div>
</form>