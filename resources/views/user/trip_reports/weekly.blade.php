<!DOCTYPE html>
<html>
<head>
    <title>Weekly Trip Report - {{ $today->format('d M Y') }}</title>
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
    <h1>{{$user->name}} -
        @if ($type == 'today') Today @endif
        @if ($type == 'week') Weekly @endif
        @if ($type == 'monthly') Monthly @endif
        @if ($type == 'all') All @endif
        Trip History Report
    </h1>

    <h1>
        ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
    </h1>

    <table>
        <thead style="font-weight: bold">
            <tr>
                <th>Car</th>
                <th>Driver</th>
                <th>Pickup</th>
                <th>Destination</th>
                <th>Distance</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Date</th>
                </tr>
        </thead>
        <tbody>
            @forelse ($trips as $trip)
            <tr>
                <td>{{ $trip->car }}</td>
                <td>{{ $trip->driver }}</td>
                <td>{{ $trip->pickup }}</td>
                <td>{{ $trip->destination }}</td>
                <td>{{ $trip->distance }}</td>
                <td>{{ $trip->duration }}</td>
                <td>{{ $trip->fee }}</td>
                <td>{{ $trip->created_at->format('d M  Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No trips found for this week.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
