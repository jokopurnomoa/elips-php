<!DOCTYPE html>
<html>
<head lang="en">
    <style>
    body{
        font-family: Helvetica, Arial, Verdana, sans-serif;
        background: #efefef;
    }

    .wrapper{
        text-align: center;
        padding-top: 130px;
    }

    .wrapper .title{
        font-size: 80px;
    }

    .wrapper .content{
        font-size: 48px;
    }

    .wrapper .footer{
            font-size: 15px;
            margin-top: 24px;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <div class="title">
            404
        </div>
        <div class="content">
            Page Not Found!
        </div>
        <div class="footer">
            The page you requested does not exist. Click <a href="{{ base_url() }}">here</a> to continue.
        </div>
    </div>
</body>
</html>