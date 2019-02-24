@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="row">
            @foreach($log AS $line)
                <?php $line_array =  explode("\t", $line);?>
                {{ print_r($line_array) }}
            @endforeach
        </div>
    </div>
@endsection



