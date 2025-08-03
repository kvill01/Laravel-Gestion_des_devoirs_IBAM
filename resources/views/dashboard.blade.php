<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des devoirs - Ibam</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container">
        <header>
            <img src="{{ asset('images/logo_ibam.jpg') }}" alt="Logo Ibam" class="logo">
            <h1>Gestion des devoirs</h1>
        </header>
        <main>
            <div class="button-container">
                <a href="{{ route('login') }}" class="button">Login</a>
                <a href="{{ route('register') }}" class="button">Register</a>
            </div>
        </main>
    </div>
</body>
</html>
