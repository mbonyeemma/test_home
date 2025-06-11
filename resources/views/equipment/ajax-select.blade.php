<option></option>

@if(!empty($bikes))

  @foreach($bikes as $key => $value)

    <option value="{{ $key }}">{{ $value }}</option>

  @endforeach

@endif