@if(count($state)>0)
    @foreach ($state as $item)
        <option value="{{$item->id}}">{{$item->name }}</option>
    @endforeach
@endif

