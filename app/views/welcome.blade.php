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
            font-size: 52px;
            font-weight: 100;
            padding-top: 200px;
        }
    </style>
</head>
<body>
    @include('slices.header')

    <div class="content">
        Welcome to ELIPS PHP
    </div>

    @include('slices.footer')
</body>
</html>