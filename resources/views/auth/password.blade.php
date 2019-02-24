@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col md-4"></div>
            <div class="col md-4">
                <h4 class="mb-3">Slaptažodžio atstatymas</h4>
                <form method="POST" url="/password-reset">
                    <input type="hidden" name="_method" value="POST">
                    <div class="col-md-12 form-group mb-3">
                        <label for="email">El. pašto adresas:</label>
                        <input type="text" name="email" value="" class="form-control"
                               placeholder="Įveskite el. pašto adresą">
                        <small id="emailHelp" class="form-text text-muted">Įveskite savo el. pašto adresą. Jums bus
                            atsiųsta slaptažodžio keitimo nuoroda.
                        </small>
                    </div>
                    <div class="col-md-12 form-group">
                        <button class="btn btn-success">Siųsti</button>
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