<table>
    <tr>
        <th>Sales Summary</th>
    </tr>
    <tr>
        <td>Sales Agent : {{__($agent_info->first_name)}} {{__($agent_info->last_name) }}</td>
    </tr>
    <tr>
            <td>{{$date['start_date'] != ""? date('M d, Y', strtotime($date['start_date'])):''}} - {{$date['end_date'] != ""? date('M d, Y', strtotime($date['end_date'])):''}}</td>
    </tr>
</table>

@foreach ($details as $data)
   <table>
      <tr class="font-weight-bold">
        <th>Merchant </th>
        <th>{{ __($data['merchant']) }}</th>
      </tr>
      <tr>
        <th>Title</th>
        <th>Pcs</th>
      </tr>

      <tbody>
        @foreach ($data as $event => $count)
            @if($event !=='merchant')
                <tr>
                    <td>{{ __($event) }} </td>
                    <td>{{ $count }} </td>
                </tr>
            @endif
        @endforeach

    </tbody>

   </table>
@endforeach
