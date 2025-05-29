
<table id="barcodesample" class="table table-striped table-sm">
    <thead>
        <tr>
            <th>Facility</th>  
            <th>Form Number</th>  
        </tr>
    </thead>
    <tbody>
        @foreach($result AS $data)    
            <tr>
                <td>{{ $data->facility }}</td>
                <td>{{ $data->form_number }}</td>
            </tr>
        @endforeach
    </tbody>
</table>