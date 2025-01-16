@if(count($state)>0)
    <option value="">{{__('State Select')}}</option>
    @foreach ($state as $item)
        <option value="{{$item->id}}">{{$item->name }}</option>
    @endforeach
@endif

