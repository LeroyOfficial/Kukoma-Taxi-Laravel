<!DOCTYPE html>
<html>
<head>
    <title>{{$user->name}} - {{$type}} Trip Report - {{ $startDate->format('d M Y') }}</title>
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

        <h3>{{$user->name}} - {{$type}} Trip History Report</h3>
        <h3>
            ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
        </h3>
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

                <td>{{$driver->where('id',$trip->driver_id)->value('first_name')}}
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
                <td colspan="4">No trips found for
                    @if ($type == 'today')
                        today
                    @endif

                    @if ($type == 'weekly')
                        this week
                    @endif

                    @if ($type == 'monthly')
                        this month
                    @endif

                    @if ($type == 'all')
                        entire history
                    @endif
                .</td>
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
                <th>K{{$total_spent}}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <script src={{asset("assets/js/jquery.min.js")}}></script>

    <script>
        $(document).ready(function() {
            // Get a list of all elements with the class "a"
            var pickup = $(".pickup");

            // Map each element in the list and replace its text content with "ba"
            pickup.map(function() {
                var pJ = JSON.parse($(this).text());
                var pickupName = pJ.name;
                console.log(pickupName);
                $(this).text(pickupName);
            });
        });
    </script>
</body>
</html>
