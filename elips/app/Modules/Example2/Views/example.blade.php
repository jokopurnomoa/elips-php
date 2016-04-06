<!DOCTYPE html>
<html>
<head lang="en">
    @include('slices.head')

    <style>
        body{
            font-family: "Helvetica Neue", "Helvetica", "Arial", sans-serif;
        }

        .content{
            font-size: 52px;
            font-weight: 100;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    @include('slices.header')

    <div class="content">
        Example 2 View
    </div>

    @include('slices.footer')
</body>
</html>