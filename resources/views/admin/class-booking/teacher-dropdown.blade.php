@if(count($teachers)>0)
    <option value="">{{__('Teacher Select')}}</option>
    @foreach ($teachers as $item)
        <option value="{{$item->id}}">{{$item->name}}</option>
    @endforeach
@endif

