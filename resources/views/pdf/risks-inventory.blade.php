<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Inventário de Riscos Psicossociais</title>

  <style>
    body        { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color:#1f2937; margin: 0 25px; }
    h1, h2      { color:#1f2937; margin-bottom: 16px; }
    h1          { font-size: 32px; margin-top: 16px; font-weight: bold; text-align: center }
    h2          { font-size: 22px; margin-top: 8px; }

    table       { width:100%; border-collapse:collapse; }
    th, td      { border:1px solid #999; padding:8px; vertical-align:center; }
    th          { background:#e6f2ff; text-align:left; }

    tbody tr:nth-child(even) { background:#f9f9f9; }

    .risco-alto   { color:#b22222; font-weight:bold; }
    .risco-medio  { color:#ff8c00; font-weight:bold; }
    .risco-baixo  { color:#228b22; font-weight:bold; }

    ul { margin:0; padding-left:16px; }
    ul li {margin-bottom: 8px;}
    ul li:last-of-type {margin-bottom: 0;}

    .page-break { page-break-after:always; }

    .cover            { width:100%; height: 100%; display:table;}
    /* .cover-inner      { display:table-cell; vertical-align:middle; text-align:center; } */
    .cover-inner      { width:100%; position: absolute; left:50%; top:50%; transform: translate(-50%, -50%); text-align: center; }
    .cover p.subtitle { font-size:20px; color:#1f2937; }
    .cover p { font-size:16px; color:#1f2937; }
    .logo             { max-width:300px; height:auto; margin:0 auto 20px; }

    .footer { position: absolute; left:50%; bottom:0px; transform: translateX(-50%); text-align: center; }
    .footer p {font-size: 13px; text-align:center; margin-bottom: 0;}
  </style>
</head>

<body>

    <div class="cover">
        <div class="cover-inner">
            @if($companyLogo)
                <img class="logo" src="{{ public_path($companyLogo) }}" alt="Logo da Empresa">            
            @else
                <img class="logo" src="{{ asset('assets/icon-facilita.svg') }}" alt="Logo da Empresa">
            @endif
            <h1>{{ $companyName }}</h1>
            <p class="subtitle">Inventário de Riscos Psicossociais</p>
            <p>Resultado detalhado da avaliação de riscos psicossociais.</p>
        </div>
        <div class="footer">
            <p>Relatório desenvolvido por Facilita Tecnologia &copy;</p>
            <p>Emissão: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    @foreach ($risks as $testName => $test)
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Fator Identificado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:0 8px;"><h2>{{ $testName }}</h2></ st
                    </tr>
                </tbody>
            </table>
            
            <table>
                <thead>
                    <tr>
                        <th>Risco Identificado</th>
                        <th>Nível de Risco</th>
                        <th>Medidas de Controle e Prevenção</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($test as $key => $risk)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $risk['risk'] }}</td>
                            <td>
                                <ul>
                                    @if($risk['score'] == 1)
                                        <li class="text-sm w-full rounded-md">
                                            Não exige ação imediata
                                        </li>
                                    @elseif ($risk['score'] == 2)
                                        <li class="text-sm w-full rounded-md">
                                            Manter controle e monitoramento
                                        </li>
                                    @else
                                        @foreach ($risk['control-actions'] as $action)
                                            <li class="text-sm w-full rounded-md">
                                                {{ $action->content }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer">
                <p>Relatório desenvolvido por Facilita Tecnologia &copy;</p>
                <p>Emissão: {{ date('d/m/Y') }}</p>
            </div>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>