@extends('layout.base')

@section('head')
    <style>
        body{
            font-family: "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            font-weight: 300;
        }

        .content{
            text-align: center;
            font-size: 52px;
            font-weight: 100;
            padding-top: 200px;
        }
    </style>
@stop

@section('body')
    {{ 'Hello' }}

    {{{ 'Hello' }}}

    {{{ '<script>alert()</script>' }}}

    <?php
    $a = 'car';
    ?>

    @if ($a == 'car' and ($a != 'truck'))
        This is a car<br>
    @elseif ($a == 'truck')
        This is a truck<br>
    @endif

    <?php
    $data = array('car', 'truck', 'bus');
    ?>

    @foreach ($data as $row)
        {{ $row }} {{ '<br>' }}
    @endforeach

    @for ($i = 0; $i < 10; $i++)
        {{ $i }}
        {{ '<br>' }}
    @endfor

    <?php
    $i = 0;
    $max = 10;
    ?>
    @while ($i < $max || ($i < 10 && $i > 0))
        {{ $i; $i++ }}
        {{ '<br>' }}
    @endwhile
@stop
