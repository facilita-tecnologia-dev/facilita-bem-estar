<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Inventário de Riscos Psicossociais</title>

  <style>
    body { 
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 14px; 
        color:#1f2937; 
        margin: 20px 20px 30px 20px;
    }

    table {margin:0 auto; width:90%; border-collapse:collapse;}
    td, th {border:1px solid #999; padding: 6px; vertical-align:center; font-size:12px; color: #333;}
    th {background-color: #c3e9fd; color: #333;}

    .page-break {
        page-break-after: always;
    }

    .footer {
        position: fixed;
        left: 50%;
        bottom: 0px;
        transform: translateX(-50%);
        text-align: center;
    }

    .pagenum:before {
        content: counter(page);
    }
  </style>
</head>

<body>
    <x-pdf.cover>          
        @if($companyLogo)
            <img src="{{ public_path($companyLogo) }}" style="max-width: 8cm; object-fit:contain; margin-bottom: 24px;">            
        @endif
        <h2 style="margin-bottom: 18px;">{{ $companyName }}</h2>
        <h1 style="margin-bottom: 8px; font-size: 32px;">Inventário de Riscos Psicossociais</h1>
        <p>Resultado detalhado da avaliação de riscos psicossociais.</p>
    </x-pdf.cover>

    <div class="page-break"></div>
    
    {{-- Matriz de Risco --}}
    <x-pdf.main-content>
        <x-pdf.risk-matrix />
    </x-pdf.main-content>
    
    <div class="page-break"></div>

    @foreach ($risks as $testName => $test)
        {{-- Title --}}
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

        @foreach ($test['risks'] as $key => $risk)
            <table style="margin-top: 16px;">
                <tbody>
                    <tr style="font-weight:bold;">
                        <td style="width:25%;">Risco Identificado: {{ $key }}</td>
                        <td style="width:15%;">Severidade: {{ $risk['severity'] }}</td>
                        <td style="width:15%;">Probabilidade: {{ $risk['probability'] }}</td>
                        <td style="width:15%;">Nível de Risco: {{ $risk['riskCaption'] }}</td>
                    </tr>                            
                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th style="width:25%; font-size: 10px">Medida de Controle</th>
                        <th style="width:15%; font-size: 10px">Responsável</th>
                        <th style="width:15%; font-size: 10px">Prazo</th>
                        <th style="width:15%; font-size: 10px">Situação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($risk['controlActions'] as $ca) 
                        <tr>
                            <td style="width:25%; font-size: 10px;">{{ $ca['content'] }}</td>
                            <td style="width:15%; font-size: 10px;">{{ $ca['assignee'] ?? 'Indefinido' }}</td>
                            <td style="width:15%; font-size: 10px;">{{ $ca['deadline'] ?? 'Indefinido' }}</td>
                            <td style="width:15%; font-size: 10px;">{{ $ca['status'] ?? 'Indefinido' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <div class="page-break"></div>
    @endforeach

    <x-pdf.footer />
</body>
</html>