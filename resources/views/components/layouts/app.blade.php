<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    <title>Bem Estar | Facilita</title>
</head>
<body class="bg-teal-50">
    <main class="w-screen h-screen flex flex-col gap-5 items-center justify-center">
        {{ $slot }}
    </main>
</body>
</html>