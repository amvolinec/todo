@extends('layouts.app')

@section('content')
    <?php
    $user = JWTAuth::user();
    if (Gate::allows('admin-only', $user)) {
        $tasks = App\Task::get();
    } else {
        $tasks = App\Task::where('user_id', $user->id)->get();
    }
    Log::channel('user_log')->info(sprintf('Front End. User %s got TO-DO list', $user->id));
    ?>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col md-12">
                <h4 class="mb-3">Profilis: {{ $user->name }}</h4>

                <form method="POST" action="/admin/tasks">
                    <div class="containre-fluid">
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input class="form-control" type="text" placeholder="TO-DO pavadinimas" name="title">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <input class="form-control" type="text" placeholder="Aprašymas" name="description">
                            </div>
                            <div class="col-md-4 form-group">
                                <button class="btn btn-success">Sukurti</button>
                            </div>
                        </div>
                    </div>
                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger mt-3" role="alert">
                            <ul>
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </form>

                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">TO-DO</th>
                        <th scope="col">Aprašymas</th>
                        <th scope="col">Data</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($tasks AS $task)
                        <tr>
                            <th scope="row">{{ $task->id }}</th>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->created_at }}</td>
                            <td><a href="/admin/tasks/{{ $task->id }}/edit?token={{$token}}" class="btn btn-warning">Edit</a></td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
                <form method="GET" action="/logout">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="col-md-12 form-group mb-3">

                    </div>
                    <div class="col-md-12 form-group">
                        <button class="btn btn-primary">Atsijungti</button>
                    </div>

                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger mt-3" role="alert">
                            <ul>
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </form>

            </div>
        </div>
    </div>
@endsection