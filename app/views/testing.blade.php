<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    @if(((($page == 'test') and ($page == 'test'))))
        Test
    @elseif(((($page == 'test2'))))
        Test 2
    @else
        Other
    @endif

    @for($i=0;$i<0;$i++)

    @endfor

    @while((((false))))

    @endwhile
    {{$page}}

    {{{ '<script>alert("CHUNKY BACON!");</script>' }}}


</body>
</html>