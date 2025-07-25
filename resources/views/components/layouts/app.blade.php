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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Facilita Saúde Mental</title>
</head>
<body
    style="
        /* background: linear-gradient(215deg , #F4C5E0 0%, #F6F1F550 100%); */
        /* background: linear-gradient(175deg , #d5f9eb 0%, #F6F1F550 100%); */
        /* background: linear-gradient(215deg , #e8e8e8 0%, #cecece50 100%); */
        background: radial-gradient(circle,rgba(238, 174, 202, 0.35) 0%, rgba(148, 187, 233, 0.25) 100%);
        /* background: radial-gradient(circle, rgba(255, 171, 202, 0.3) 0%, rgba(148, 233, 206, 0.3) 100%); */

    "
    class="bg-cover h-screen"
>
    {{ $slot }}


    <!-- Tippy.js styles -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />

    <!-- Tippy.js core -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
</body>
</html>