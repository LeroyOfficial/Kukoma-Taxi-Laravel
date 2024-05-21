<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'History';
    @endphp
    @include('components.new.user.css')
    {{-- <link rel="stylesheet" href={{asset('assets/css/dataTables.dataTables.min.css')}} />

    <script src={{asset('assets/js/dataTables.min.js')}}></script> --}}

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />

<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
</head>

<body>
    @include('components.new.user.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
                <a href="index.html">Home</a>
                <span class="ms-1 color-main">// history</span>
            </span>
            <h1 class="text-uppercase fw-bold">history</h1>
        </div>
    </div>
    <div class="section">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1>Taxi History</h1>

                @if ($history->count() > 0)
                    <div>
                        <div class="dropdown show text-capitalize">
                            <button class="btn dropdown-toggle btn-main rounded-pill fs4 py-2 px-4" aria-expanded="true" data-bs-toggle="dropdown" type="button">Generate Report</button>
                            <div class="dropdown-menu ms-3" data-bs-popper="none">
                                <a class="dropdown-item fw-bold btn-hover-main" href="{{url('user/history/generate_report/today')}}">for Today</a>
                                <a class="dropdown-item fw-bold btn-hover-main" href="{{url('user/history/generate_report/weekly')}}">For this week</a>
                                <a class="dropdown-item fw-bold btn-hover-main" href="{{url('user/history/generate_report/monthly')}}">for this month</a>
                                <a class="dropdown-item fw-bold btn-hover-main" href="{{url('user/history/generate_report/all')}}">Entire History</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-2 py-4">
                @if ($history->count() > 0)
                    <div class="table-responsive">
                        <table id="history-table" class="table">
                            <thead>
                                <tr >
                                    <th style="background-color:var(--color-main)">Car</th>
                                    <th style="background-color:var(--color-main)">Driver</th>
                                    <th style="background-color:var(--color-main)">Pickup</th>
                                    <th style="background-color:var(--color-main)">Destination</th>
                                    <th style="background-color:var(--color-main)">Distance</th>
                                    <th style="background-color:var(--color-main)">Fee</th>
                                    <th style="background-color:var(--color-main)">Date</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($history as $trip)
                                    <tr>
                                        <td>
                                            <a class="text-dark" href="{{url('user/car/'.$trip->car_id)}}">
                                                {{$car->where('id',$trip->car_id)->value('brand')}}
                                                {{$car->where('id',$trip->car_id)->value('model')}}
                                                (
                                                    {{$car->where('id',$trip->car_id)->value('number_plate')}}
                                                )
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-dark" href="{{url('user/driver/'.$trip->driver_id)}}">
                                                {{$driver->where('id',$trip->driver_id)->value('first_name')}}
                                                {{$driver->where('id',$trip->driver_id)->value('last_name')}}
                                            </a>
                                        </td>
                                        <?php $pickupData = json_decode($trip->pickup, true); ?>
                                        <td class="picup">
                                            {{$pickupData['name']}}
                                        </td>

                                        <td>
                                            {{$address->where('id',$trip->destination)->value('name')}}
                                        </td>

                                        <td>
                                            <span>{{$trip->distance}} km</span>
                                        </td>

                                        <td>
                                            <span>K{{$trip->price}}</span>
                                        </td>

                                        <td>{{$trip->created_at->format('d M Y')}}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>K{{$total_spent}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <h3 class="text-center">
                        It seems that you havent taken any trips with us
                    <br>
                        <a href={{url('user/booking')}} class="color-main">click here to book a taxi</a>
                    </h3>
                @endif
            </div>
        </div>
    </div>
    @include('components.new.user.footer')

    <script>
        $(document).ready(function() {
            // $('#dataTable').DataTable({
            //     "order": [[4, 'desc']]
            // });

            // Get a list of all elements with the class "a"
            var pickup = $(".pickup");

            // $('.number').toLocaleString();

            // $('#history-table').DataTable();

        });
    </script>
</body>

</html>
