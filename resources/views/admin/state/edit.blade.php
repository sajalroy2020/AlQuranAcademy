<!-- Top -->
<div class="d-flex justify-content-between align-items-center">
  <h5>{{$pageTitle}}</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<!--  -->
<form class="ajax-request reset" action="{{route('admin.state.store')}}" method="POST" data-handler="commonResponse">
  @csrf
  <input type="hidden" name="id" value="{{$state->id}}">

    <div class="row mt-4">
      <div class="col-12">
        <div class="primary-form-group my-2 pt-2">
            <div class="primary-form-group-wrap">
              <label class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
              <select class="form-control" name="country_id">
                    <option value="">{{__("Select Country")}}</option>
                    @foreach ($countryList as $data)
                        <option {{$state->country_id == $data->id ? 'selected' : ''}} value="{{$data->id}}">{{$data->name}}</option>
                    @endforeach
              </select>
            </div>
        </div>
    </div>
      <div class="col-12">
        <label class="form-label">{{ __('State Name') }} <span class="text-danger">*</span></label>
        <input type="text" value="{{$state->name}}" name="name" class="form-control" placeholder="Enter State Name" />
      </div>
      <div class="col-12 mt-2">
        <label class="form-label">{{__('Status')}}</label>
        <select class="form-control" name="status">
          <option {{$state->status == STATUS_ACTIVE ? 'selected' : ''}} value="{{STATUS_ACTIVE}}">{{__("Active")}}</option>
          <option {{$state->status == STATUS_DEACTIVATE ? 'selected' : ''}} value="{{STATUS_DEACTIVATE}}">{{__('Deactivate')}}</option>
        </select>
      </div>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <button type="submit" class="btn btn-primary w-25">{{ __('Update') }}</button>
  </div>
</form>
