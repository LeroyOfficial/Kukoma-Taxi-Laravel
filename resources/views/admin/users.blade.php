<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'Users'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')
        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Users</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>List of Users</h4>
                                    </div>
                                    <div class="card-body">
                                        @if ($user_count > 0)
                                            <div class="table-responsive">
                                                <table class="table" id="table1">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone Number</th>
                                                            <th>Email</th>
                                                            <th># of Trips</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($users as $user)
                                                            <tr>
                                                                <td class="col-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar avatar-md">
                                                                            <img src="{{asset('admin/assets/images/faces/5.jpg')}}">
                                                                        </div>
                                                                        <a href="{{url('admin/user/'.$user->id)}}" class="font-bold ms-3 mb-0">{{$user->name}}</a>
                                                                    </div>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class=" mb-0">{{$user->phone}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class=" mb-0">{{$user->email}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <a href="{{url('admin/user/'.$user->id)}}">{{$trip_count->where('user_id',$user->id)->count()}}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <h3 class="text-center">
                                            It seems that there are no users available
                                        </h3>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('components.new.admin.footer')
</body>

</html>
