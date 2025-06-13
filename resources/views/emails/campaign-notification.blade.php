<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Campanha Ativa</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4c78af;
            color: white;
            padding: 24px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .content {
            padding: 24px;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.4;
        }

        p{
            margin: 0;
        }

        header p{
            margin-bottom: 16px;
        }

        .campaign-infos{
            margin-top: 24px;
        }

        .campaign-infos h2{
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .campaign-infos p{
            margin-top: 8px;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            border-width: 2px;
            border-color: #4c78af;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .footer {
            background-color: #f1f1f1;
            color: #777777;
            padding: 16px;
            text-align: center;
            font-size: 13px;
        }

        .logo{
            display: block;
            margin-bottom: 24px;
            max-height: 56px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Campanha de Testes Ativa 🎯</h1>
        </div>
        <div class="content">
            <header>
                <p>Olá <strong>{{ $user->name ?? 'usuário' }}</strong>,</p>
                <p>A empresa <strong>{{ $company->name }}</strong> está com uma nova <strong>campanha de testes ativa</strong> neste momento.</p>
            </header>

            <div class="campaign-infos">
                <h2>Informações sobre a campanha:</h2>
                <p>Nome da campanha: <strong>{{ $campaign->name }}</strong></p>
                <p>Descrição da campanha: <strong>{{ $campaign->description }}</strong></p>
                <p>Data de início: <strong>{{ $campaign->start_date->format('d/m/Y - H:i') }}</strong></p>
                <p>Data de encerramento: <strong>{{ $campaign->end_date->format('d/m/Y - H:i') }}</strong></p>
            </div>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>