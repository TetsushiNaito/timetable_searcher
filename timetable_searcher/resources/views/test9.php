<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form name="hoge">
        <select name="fuga">
            <option value="PHP">PHP</option>
            <option value="Perl">Perl</option>
        </select>
    </form>
    <script type="text/javascript">
        let lists = document.hoge.fuga;
        let options = lists;
        alert( options.selectedIndex );
    </script>
</body>
</html>