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

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Pcs</th>
        
        </tr>
    </thead>
       <tbody>
        @foreach($summary as $event => $count)
         <tr>
            <td>{{ __($event) }} </td>
            <td>{{  $count }} </td>
         </tr>
            
        @endforeach
       </tbody>
</table>
  