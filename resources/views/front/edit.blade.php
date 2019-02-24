@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col md-4"></div>
            <div class="col md-4">
                <h4 class="mb-3">TO-DO redagavimas</h4>

                <form method="POST" action="/admin/tasks/{{ $task->id }}">
                    <div class="col-md-12 form-group mb-3">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="_method" value="PUT">
                        <input class="form-control" type="text" placeholder="TO-DO pavadinimas" name="title"
                               value="{{ $task->title }}">
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <input class="form-control" type="text" placeholder="Aprašymas" name="description"
                               value="{{ $task->description }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <button class="btn btn-success">Atnaujinti</button>
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
            <div class="col md-4"></div>
        </div>
    </div>
@endsection