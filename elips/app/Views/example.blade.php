@extends('layout.base')

@section('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: "Instrument Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
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
    @{{ hello() }}

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
        {{ $row }}
        {!! '<br>' !!}
    @endforeach

    @for ($i = 0; $i < 10; $i++)
        {{ $i }}
        {!! '<br>' !!}
    @endfor

    <?php
    $i = 0;
    $max = 10;
    ?>
    @while ($i < $max || ($i < 10 && $i > 0))
        {{ $i }}
        {!! '<br>' !!}
        <?php
        $i++;
        ?>
    @endwhile
@stop

@section('content')
    @parent

    Example Content
@stop