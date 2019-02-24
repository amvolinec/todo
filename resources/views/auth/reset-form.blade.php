@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col md-4"></div>
            <div class="col md-4">
                <form method="POST" url="password-reset/"{{ $token }}>
                    <h4 class="mb-3">Naujas slaptažodis</h4>
                    <div class="col-md-12 form-group">
                        <label for="password">Slaptažodis *</label>
                        <input type="password" name="password" class="form-control" required>
                        <small id="passwordHelp" class="form-text text-muted">Slaptažodį turi sudaryti bent 6 simboliai.
                        </small>
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="password-confirm">Patvirtinkite slaptažodį *</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                               required>
                    </div>

                    <div class="col-md-12 form-group">
                        <button type="submit" class="btn btn-success">Pakeisti</button>
                    </div>

                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger mt-3" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
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