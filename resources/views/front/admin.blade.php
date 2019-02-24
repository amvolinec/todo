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
                            <td>
                                <form method="POST" action="/admin/tasks/{{ $task->id }}">
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
                <form method="GET" action="/logout">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="col-md-12 form-group mb-3">

                    </div>
                    <div class="col-md-12 form-group">
                        <button class="btn btn-success">Atsijungti</button>
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