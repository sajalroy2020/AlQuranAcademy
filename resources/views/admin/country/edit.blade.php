<!-- Top -->
<div class="d-flex justify-content-between align-items-center">
  <h5>{{$pageTitle}}</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<!--  -->
<form class="ajax-request reset" action="{{route('country.store')}}" method="POST" data-handler="commonResponse">
  @csrf
  <input type="hidden" name="id" value="{{$country->id}}">

    <div class="row mt-4">
      <div class="col-12">
        <label class="form-label">{{ __('Country Name') }} <span class="text-danger">*</span></label>
        <input type="text" value="{{$country->name}}" name="name" class="form-control" placeholder="Enter Country Name" />
      </div>
      <div class="col-12 mt-2">
        <label class="form-label">{{__('Status')}}</label>
        <select class="form-control" name="status">
          <option {{$country->status == STATUS_ACTIVE ? 'selected' : ''}} value="{{STATUS_ACTIVE}}">{{__("Active")}}</option>
          <option {{$country->status == STATUS_DEACTIVATE ? 'selected' : ''}} value="{{STATUS_DEACTIVATE}}">{{__('Deactivate')}}</option>
        </select>
      </div>
    </div>
    <div class="d-flex justify-content-center mt-3">
      <button type="submit" class="btn btn-primary w-25">{{ __('Update') }}</button>
  </div>
</form>
