<!-- Top -->
<div class="d-flex justify-content-between align-items-center">
  <h5>{{$pageTitle}}</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<!--  -->
<form class="ajax-request reset" action="{{route('admin.class-schedule.update')}}" method="POST" data-handler="commonResponse">
  @csrf
  <input type="hidden" name="id" value="{{$schedule->id}}">

    <div class="row mt-4">
      <div class="col-12">
        <div class="primary-form-group mt-2 pt-2">
            <div class="primary-form-group-wrap">
              <label class="form-label">{{ __('Select Day') }} <span class="text-danger">*</span></label>
              <select class="form-control select-day" name="day" data-day="{{$schedule->day}}"  data-teacherid="{{$schedule->teacher_id}}" >
                <option value="">{{__("Select Day")}}</option>
                <option {{$schedule->day == 'Monday' ? 'selected' : ''}} value="Monday">{{__("Mon Day")}}</option>
                <option {{$schedule->day == 'Tuesday' ? 'selected' : ''}} value="Tuesday">{{__("Tues Day")}}</option>
                <option {{$schedule->day == 'Wednesday' ? 'selected' : ''}} value="Wednesday">{{__("Wednes Day")}}</option>
                <option {{$schedule->day == 'Thursday' ? 'selected' : ''}} value="Thursday">{{__("Thurs Day")}}</option>
                <option {{$schedule->day == 'Friday' ? 'selected' : ''}} value="Friday">{{__("Fri Day")}}</option>
                <option {{$schedule->day == 'Saturday' ? 'selected' : ''}} value="Saturday">{{__("Satur Day")}}</option>
                <option {{$schedule->day == 'Sunday' ? 'selected' : ''}} value="Sunday">{{__("Sun Day")}}</option>
              </select>
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

      <div class="col-12">
          <div class="time-container">
            @foreach ($classSlots as $index => $slot)
              <input type="hidden" name="class_slot_id[]" value="{{$slot->id}}">
              <div class="row align-items-end time-row">
                <div class="col-5">
                    <div class="primary-form-group mt-2 pt-2">
                        <div class="primary-form-group-wrap">
                            <label class="form-label">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="start_time[]" value="{{$slot->start_time}}">
                        </div>
                        <div class="start_time"></div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="primary-form-group mt-2 pt-2">
                        <div class="primary-form-group-wrap">
                            <label class="form-label">{{ __('End Time') }} <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="end_time[]" value="{{$slot->end_time}}">
                        </div>
                        <div class="end_time"></div>
                    </div>
                </div>
                <div class="col-2">
                  @if ($index == 0)
                    <button type="button" class="btn btn-icon-only bg-gradient-primary mb-0 btn-add-more"> + </button>
                  @else
                    <button type="button" class="text-danger border-0 bg-transparent btn-delete-row mb-2" data-slotid="{{$slot->id}}">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                          <path d="M6.5 1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1h4a.5.5 0 0 1 0 1h-1v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2h-1a.5.5 0 0 1 0-1h4zm1-1h1a1 1 0 0 1 1 1v1H6V1a1 1 0 0 1 1-1zm-5 3h10v11a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3zM6 7.5a.5.5 0 0 1 1 0v4a.5.5 0 0 1-1 0v-4zm3 .5a.5.5 0 0 1 1 0v3a.5.5 0 0 1-1 0v-3z"/>
                      </svg>
                    </button>
                  @endif
                </div>
              </div>
            @endforeach
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
      <button type="submit" class="btn btn-primary w-25 save-button">{{ __('Update') }}</button>
  </div>
  <span class="text-danger error-message"></span>

</form>
<input type="hidden" id="check-day-list-route" value="{{ route('admin.class-schedule.check-day') }}">
<input type="hidden" id="check-slot-data" value="{{ route('admin.class-schedule.check-slot-data') }}">

@push('script')
    <script src="{{ asset('admin/js/class-schedule.js') }}"></script>
@endpush
