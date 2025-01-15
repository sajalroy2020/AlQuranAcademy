@if(count($course)>0)
    @foreach ($course as $item)
        <option value="{{$item->course_id}}">{{$item->course_list->subject_name}}</option>
    @endforeach
@endif

