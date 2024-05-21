<!DOCTYPE html>
<html>
<head>
    <title>{{$user->name}} - Trip Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            text-align: left;
        }
    </style>
</head>
<body>
    <div style="text-align: center">
        <img src="{{ asset('assets/img/about.png') }}" alt="Kukoma Taxi logo" style="height:200px; width:150px; margin-bottom: 10px;">

        <h3>{{$user->name}} - Trip History Report</h3>
    </div>

    <table>
        <thead style="font-weight: bold">
            <tr>
                <th style="background-color:#ffc500">Car</th>
                <th style="background-color:#ffc500">Driver</th>
                <th style="background-color:#ffc500">Pickup</th>
                <th style="background-color:#ffc500">Destination</th>
                <th style="background-color:#ffc500">Distance</th>
                <th style="background-color:#ffc500">Duration</th>
                <th style="background-color:#ffc500">Price</th>
                <th style="background-color:#ffc500">Date</th>
                </tr>
        </thead>
        <tbody>
            @forelse ($trips as $trip)
            <tr>
                <td>
                    {{$car->where('id',$trip->car_id)->value('brand')}}
                    {{$car->where('id',$trip->car_id)->value('model')}}
                    (
                        {{$car->where('id',$trip->car_id)->value('number_plate')}}
                    )
                </td>

                <td>
                    {{$driver->where('id',$trip->driver_id)->value('first_name')}}
                    {{$driver->where('id',$trip->driver_id)->value('last_name')}}
                </td>

                <?php $pickupData = json_decode($trip->pickup, true); ?>
                <td class="picup">
                    {{$pickupData['name']}}
                </td>

                <td>{{$address->where('id',$trip->destination)->value('name')}}</td>

                <td>{{ $trip->distance }}km</td>

                <td>{{ $trip->duration }} minutes</td>

                <td>K{{ $trip->price }}</td>

                <td>{{ $trip->created_at->format('d M  Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No trips found</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>K{{$total_revenue}}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
