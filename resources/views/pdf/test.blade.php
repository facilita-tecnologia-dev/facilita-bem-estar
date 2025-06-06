<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Inventário de Riscos Psicossociais</title>

  <style>
    body        { font-family: Arial, Helvetica, sans-serif; font-size: 14px; color:#1f2937; margin: 0 25px; }
    /* h1, h2      { color:#1f2937; margin-bottom: 16px; }
    h1          { font-size: 32px; margin-top: 16px; font-weight: bold; text-align: center }
    h2          { font-size: 22px; margin-top: 8px; }

    table       { width:100%; border-collapse:collapse; }
    th, td      { border:1px solid #999; padding:8px; vertical-align:center; }
    th          { background:#e6f2ff; text-align:left; }

    tbody tr:nth-child(even) { background:#f9f9f9; }

    img {width: 100%; }

    ul { margin:0; padding-left:16px; }
    ul li {margin-bottom: 8px;}
    ul li:last-of-type {margin-bottom: 0;}

    .page-break { page-break-after:always; }

    .cover            { width:100%; height: 100%; display:table;}
    /* .cover-inner      { display:table-cell; vertical-align:middle; text-align:center; } */
    /* .cover-inner      { width:100%; position: absolute; left:50%; top:50%; transform: translate(-50%, -50%); text-align: center; }
    .cover p.subtitle { font-size:20px; color:#1f2937; }
    .cover p { font-size:16px; color:#1f2937; }
    .logo             { max-width:300px; height:auto; margin:0 auto 20px; }

    .footer { position: absolute; left:50%; bottom:0px; transform: translateX(-50%); text-align: center; }
    .footer p {font-size: 13px; text-align:center; margin-bottom: 0;} */ 
    /* @page {
    margin-top: 20mm;
    margin-bottom: 25mm;
    margin-left: 30mm;
    margin-right: 30mm;
    } */

    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    table {margin:0 auto; width:90%; border-collapse:collapse;}
    td, th {border:1px solid #999; padding:8px; vertical-align:center; font-size:12px; color: #333;}
    th {background-color: #c3e9fd; color: #333;}

    .page-break {
        page-break-after: always;
    }
  </style>
</head>

<body>
    @php
        $pageCounter = 1;
    @endphp


    <div style="width: 100%;height: 100%; position: relative; text-align:center;">
        <div style="width: 100%; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); text-align:center;">
            @if($companyLogo)
                <img src="{{ public_path($companyLogo) }}" style="max-width: 8cm; object-fit:contain; margin-bottom: 24px;">            
            @endif
            <h2 style="margin-bottom: 18px;">{{ $companyName }}</h2>
            <h1 style="margin-bottom: 8px; font-size: 32px;">Inventário de Riscos Psicossociais</h1>
            <p>Resultado detalhado da avaliação de riscos psicossociais.</p>
        </div>

        <div style="width: 100%; position: absolute; bottom: 35px; text-align:center;">
            <span style="margin-right: 4px; font-size:12px;">Facilita Tecnologia &copy; | Emissão: {{ date('d/m/Y') }} | Página {{ $pageCounter }}</span>
            @php
                $pageCounter++
            @endphp
        </div>
    </div>

    <div class="page-break"></div>
    
    <div style="width: 100%;height: 100%; position: relative; text-align:center;">
        <div style="width: 100%; position: relative; text-align:center; position: absolute; padding-top: 60px;">    
            <h2 style="margin-bottom: 8px;">Matriz de Risco</h2>

            <p style="margin-bottom: 16px;">O nível de risco é determinado pela combinação de severidade e probabilidade:</p>
            
            <table style="width: 50%;">
                <tbody>
                    <tr style="">
                        <td style="width:25%; font-weight:bold;">Probabilidade/Severidade</td>
                        <td style="width:5%; font-weight:bold;">Baixa</td>
                        <td style="width:5%; font-weight:bold;">Moderada</td>
                        <td style="width:5%; font-weight:bold;">Alta</td>
                        <td style="width:5%; font-weight:bold;">Crítica</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight: bold;">Improvável</td>
                        <td>Baixo</td>
                        <td>Baixo</td>
                        <td>Moderado</td>
                        <td>Moderado</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight: bold;">Possível</td>
                        <td>Baixo</td>
                        <td>Moderado</td>
                        <td>Alto</td>
                        <td>Alto</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight: bold;">Provável</td>
                        <td>Moderado</td>
                        <td>Alto</td>
                        <td>Alto</td>
                        <td>Crítico</td>
                    </tr>
                    <tr style="">
                        <td style="font-weight: bold;">Muito Provável</td>
                        <td>Moderado</td>
                        <td>Alto</td>
                        <td>Crítico</td>
                        <td>Crítico</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="width: 100%; position: absolute; bottom: 35px; text-align:center;">
            <span style="margin-right: 4px; font-size:12px;">Facilita Tecnologia &copy; | Emissão: {{ date('d/m/Y') }} | Página {{ $pageCounter }}</span>
            @php
                $pageCounter++
            @endphp
        </div>
    </div>


    <div class="page-break"></div>
    
    @php
        $chunks = array_chunk($risks, 1, true); // Divide os gráficos em grupos de 3
    @endphp

    @foreach ($chunks as $chunk)
        <div style="width: 100%; height: 100%; position: relative; text-align:center;">
            <div style="width: 100%; position: relative; text-align:center; position: absolute; padding-top: 60px;">
                @foreach ($chunk as $testName => $test)
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Fator Identificado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:8px;"><h2>{{ $testName }}</h2></td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th style="width:25%;">Risco Identificado</th>
                                <th style="width:5%;">Severidade</th>
                                <th style="width:5%;">Probabilidade</th>
                                <th style="width:15%;">Nível de Risco</th>
                                <th style="width:60%;">Medidas de Controle e Prevenção</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($test as $key => $risk)
                                <tr style="">
                                    <td>{{ $key }}</td>
                                    <td>{{ $risk['severity'] }}</td>
                                    <td>{{ $risk['probability'] }}</td>
                                    <td>{{ $risk['risk'] }}</td>
                                    <td>
                                        <ul style="padding-left: 20px;">
                                            @if($risk['score'] == 1)
                                                <li class="text-sm w-full rounded-md">
                                                    Não exige ação imediata
                                                </li>
                                            @elseif ($risk['score'] == 2)
                                                <li class="text-xs w-full rounded-md">
                                                    Manter controle e monitoramento
                                                </li>
                                            @else
                                                @foreach ($risk['controlActions'] as $action)
                                                    @php
                                                        $isAllowed = $action instanceof App\Models\ControlAction || $action->allowed;
                                                    @endphp
                                                    @if($isAllowed)
                                                        <li class="text-sm w-full rounded-md">
                                                            {{ $action->content }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>

            <div style="width: 100%; position: absolute; bottom: 35px; text-align:center;">
                <span style="margin-right: 4px; font-size:12px;">Facilita Tecnologia &copy; | Emissão: {{ date('d/m/Y') }} | Página {{ $pageCounter }}</span>
                @php
                    $pageCounter++
                @endphp
            </div>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>