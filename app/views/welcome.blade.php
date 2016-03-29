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

        .footer{
            width: 100%;
            text-align: center;
            font-weight: 300;
            font-size: 13px;
            position: absolute;
            bottom: 10px;
            left: 0;
        }
    </style>
</head>
<body>
    @include('slices.header')

    <div class="content">
        Welcome to Elips PHP
    </div>

    @include('slices.footer')
</body>
</html>