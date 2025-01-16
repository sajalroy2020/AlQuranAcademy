@if(count($course)>0)
    <option value="">{{__('Course Select')}}</option>
    @foreach ($course as $item)
        <option value="{{$item->course_id}}">{{$item->course_list->subject_name}}</option>
    @endforeach
@endif

