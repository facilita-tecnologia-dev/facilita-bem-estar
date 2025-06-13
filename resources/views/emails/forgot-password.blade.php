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
            line-height: 1.6;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Redefinição de Senha</h1>
        </div>
        <div class="content">
            <p>Olá,</p>

            <p>Recebemos uma solicitação de redefinição de senha no <strong>Facilita Saúde Mental</strong>.</p>
            <p>Clique no link abaixo para acessar a página de redefinição de senha</p>

            <a href="{{ $url }}"
                style="display: inline-block; padding: 12px 24px; background-color: #3490dc; color: white; text-decoration: none; border-radius: 4px;">
                Redefinir Senha
            </a>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>