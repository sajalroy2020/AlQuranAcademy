@if(count($classes_list)>0)
    <h6 class="mt-4 mb-3 text-center">{{__('Class Slot List')}}</h6>
    @foreach ($classes_list as $item)
        <div class="col-md-2 col-12">
            @if ($item->is_booked == 0)
                <div class="btn btn-outline-primary btn-sm w-100 text-center px-2 class-slot-button" data-id="{{$item->id}}" data-scheduleid="{{$item->class_schedule_id}}">
                    <span class="mb-2 fs-6 fw-bold text-light-emphasis">{{__('Available')}}</span>
                    <span class="d-flex pt-2"> {{\Carbon\Carbon::parse($item->start_time)->format('h:i A') }} - {{\Carbon\Carbon::parse($item->end_time)->format('h:i A') }} </span>
                </div>
            @else
                <button disabled class="btn btn-outline-primary border-danger btn-sm w-100 text-center px-2">
                    <span class="mb-2 fs-6 fw-bold text-danger">{{$item->is_booked == 1 ? 'Boocked' : 'Boocked'}}</span>
                    <span class="d-flex pt-2"> {{\Carbon\Carbon::parse($item->start_time)->format('h:i A') }} - {{\Carbon\Carbon::parse($item->end_time)->format('h:i A') }} </span>
                </button>
            @endif
        </div>
    @endforeach
@endif
