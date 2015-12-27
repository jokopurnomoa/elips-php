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
<body class="hold-transition skin-blue sidebar-mini">
    @include('slices.header')

    <div class="content">
        WELCOME TO ELIPS PHP
    </div>

    @include('slices.footer')
</body>
</html>