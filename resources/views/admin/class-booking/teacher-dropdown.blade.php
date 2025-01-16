@if(count($teachers)>0)
    <option value="">{{__('Teacher Select')}}</option>
    @foreach ($teachers as $item)
        <option value="{{$item->teacher_list->id}}">{{$item->teacher_list->name}}</option>
    @endforeach
@endif

