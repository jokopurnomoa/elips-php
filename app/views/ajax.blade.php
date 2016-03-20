<!DOCTYPE html>
<html>
<head lang="en">
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
    <script src="{{ base_url() }}assets/jquery/jquery-2.1.3.min.js"></script>
    <script>
        $(document).ready(function(){
            $.post('{{ base_url() }}testing').done(function(data){
                alert(data);
            });
        });
    </script>
</head>
<body>

</body>
</html>