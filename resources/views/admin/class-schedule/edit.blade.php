<!-- Top -->
<div class="d-flex justify-content-between align-items-center">
  <h5>{{$pageTitle}}</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<!--  -->
<form class="ajax-request reset" action="{{route('admin.class-schedule.store')}}" method="POST" data-handler="commonResponse">
  @csrf
  <input type="hidden" name="id" value="{{$schedule->id}}">

    <div class="row mt-4">
      <div class="col-12">
        <div class="primary-form-group mt-2 pt-2">
          <div class="primary-form-group-wrap">
            <label class="form-label">{{ __('Date') }} <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="date" value="{{$schedule->date}}">
          </div>
        </div>
      </div>
      <div class="col-12">
          <div class="primary-form-group my-2 pt-2">
              <div class="primary-form-group-wrap">
                <label class="form-label">{{ __('Teacher Select') }} <span class="text-danger">*</span></label>
                <select class="form-control getTeacherCourse" id="BatchName" name="teacher_id">
                  <option value="">{{__("Select Teacher")}}</option>
                  @foreach ($teachers as $data)
                      <option {{$schedule->teacher_id == $data->id ? 'selected' : ''}} value="{{$data->id}}">{{$data->name}}</option>
                  @endforeach
                </select>
              </div>
          </div>
      </div>
      <div class="col-12">
          <div class="primary-form-group my-2 pt-2">
              <div class="primary-form-group-wrap">
                <label class="form-label">{{ __('Course Select') }} <span class="text-danger">*</span></label>
                <select class="form-control addCourse" id="BatchName" name="course_id">
                  <option value="">{{__("Select Course")}}</option>
                  @foreach ($courseList as $data)
                      <option {{$schedule->course_id == $data->id ? 'selected' : ''}} value="{{$data->id}}">{{$data->subject_name}}</option>
                  @endforeach
                </select>
              </div>
          </div>
      </div>
      <div class="col-6">
          <div class="primary-form-group mt-2 pt-2">
              <div class="primary-form-group-wrap">
                <label class="form-label">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                <input type="time" class="form-control" name="start_time" value="{{$schedule->start_time}}">
              </div>
          </div>
      </div>
      <div class="col-6">
          <div class="primary-form-group mt-2 pt-2">
              <div class="primary-form-group-wrap">
                <label class="form-label">{{ __('End Time') }} <span class="text-danger">*</span></label>
                <input type="time" class="form-control" name="end_time" value="{{$schedule->end_time}}">
              </div>
          </div>
      </div>
      <div class="col-12 mt-2">
        <label class="form-label">{{__('Status')}}</label>
        <select class="form-control" name="status">
          <option {{$schedule->status == STATUS_ACTIVE ? 'selected' : ''}} value="{{STATUS_ACTIVE}}">{{__("Active")}}</option>
          <option {{$schedule->status == STATUS_DEACTIVATE ? 'selected' : ''}} value="{{STATUS_DEACTIVATE}}">{{__('Deactivate')}}</option>
        </select>
      </div>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <button type="submit" class="btn btn-primary w-25">{{ __('Update') }}</button>
  </div>
</form>
