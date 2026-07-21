<!DOCTYPE html>
<html>
<head lang="en">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: "Instrument Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
        }

        .content{
            text-align: center;
            font-size: 52px;
            font-weight: 100;
            padding-top: 200px;
        }
    </style>
    <script src="{{ base_url() }}assets/jquery/jquery-2.1.3.min.js"></script>
    <script>
        $(document).ready(function(){
            $.post('{{ base_url() }}testing/session').done(function(data){
                alert(data);
            });
        });
    </script>
</head>
<body>

</body>
</html>