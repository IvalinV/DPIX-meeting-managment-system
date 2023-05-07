<x-mail::message>
# Meeting reschedule

Unfortunatly, the requested by you date is not available here are some suggestions:

<x-mail::table>
    <table>
        <th>Datetime</th>
        @foreach ($dates as $date)
            <tr>
                <td>{{$date}}</td>
            </tr>
        @endforeach
    </table>
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
