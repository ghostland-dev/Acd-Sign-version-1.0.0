<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form method="post" action="{{ route('confirm') }}">
        @method("POST")
        @csrf
        <input name="status_demande" type="hidden" value="true" />
        <input name="id" type="hidden" value="{{}}" />




        <button type="submit" value="test">
            test
        </button>
    </form>
</body>

</html>
