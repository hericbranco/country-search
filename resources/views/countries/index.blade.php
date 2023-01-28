<div class="container">
    <table>
        <tr>
            <th>Code</th>
            <th>Country</th>
            <th>Region</th>
        </tr>
        @foreach ($countries['data'] as $code => $country)
            <tr>
                <td>{{$code}}</td>
                <td>{{$country->country}}</td>
                <td>{{$country->region}}</td>
            </tr>
        @endforeach
    </table>
    
</div>