<!DOCTYPE html>
<html>
<head lang="en">
    @include('slices.head')

    <style>
        body{
            font-family: "Helvetica Neue", "Helvetica", "Arial", sans-serif;
        }

        .content{
            text-align: center;
            font-size: 15px;
            font-weight: 300;
            white-space: pre;
        }
    </style>
</head>
<body>
    @include('slices.header')

    <div class="content">
    @foreach($member_list as $row)
        {{ $row->member_id . ' ' . $row->name }}
        <br>
    @endforeach
    </div>

    @include('slices.footer')
</body>
</html>