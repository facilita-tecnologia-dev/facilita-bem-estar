<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1cb73192b4.js" crossorigin="anonymous"></script>

    @vite('resources/css/app.css')
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app-Dasl6xyn.css') }}"> --}}
    <title>Facilita Saúde Mental</title>
</head>
<body
    style="
        background: linear-gradient(215deg , #F4C5E0 0%, #F6F1F550 100%);
    "
    class="bg-cover h-screen"
>
    {{ $slot }}
</body>
</html>