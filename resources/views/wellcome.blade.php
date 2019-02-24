@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col md-4"></div>
            <div class="col md-4">
                <h4 class="mb-3">Naudotojo prisijungimas</h4>
                <form method="POST" action="/login">
                    <input type="hidden" name="_method" value="POST">
                    <div class="col-md-12 form-group mb-3">
                        <label for="email">El. pašto adresas:</label>
                        <input type="text" name="email" value="" class="form-control"
                               placeholder="Įveskite el. pašto adresą">
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="password">Slapražodis:</label>
                        <input type="password" name="password" value="" class="form-control"
                               placeholder="Įveskite slaptažodį">
                    </div>
                    <div class="col-md-12 form-group">
                        <button class="btn btn-success">Prisijungti</button>
                    </div>

                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger mt-3" role="alert">
                            <ul>
                                @if(is_array($errors))
                                    @foreach ($errors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @else
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endif

                </form>

            </div>

            <div class="col md-4"></div>
        </div>
    </div>
@endsection
