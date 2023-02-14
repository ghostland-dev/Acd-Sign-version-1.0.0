<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap);

        body {
            font-family: "Montserrat";
        }

        .centered {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        input[type=submit] {
            margin-top: 50px;
            background-color: #93C34D;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: flex;
            font-size: 16px;
            border-radius: 8px;
            width: 20%;
        }
    </style>
</head>

<body>
    <p>
        Madame, Monsieur,
        <br />
        Veuillez cliquer sur le bouton ci-dessous pour confirmer la collecte de vos données.
    </p>

    {{-- <form method="post" action="{{ route('confirm') }}"> --}}
    <form method="post" action="{{$urlConfirm}}">
        @method("PUT")
        <input class="centered" type="submit" value="Autoriser" />
    </form>
    {{-- <form method="post" action="{{ route('confirm') }}">
        @method("POST")
        @csrf
        <input name="status_demande" type="hidden" value="true" />
        <input name="id" type="hidden" value="{{}}" />




        <button type="submit" value="test">
            test
        </button>
    </form> --}}
    <p>
        Bien cordialement,
        <br />
        l'équipe Access Energies.
    </p>
</body>

</html>
