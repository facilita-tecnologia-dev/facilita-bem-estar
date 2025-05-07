<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Inventário de Riscos Psicossociais</title>

  <style>
    body        { font-family: Arial, Helvetica, sans-serif; font-size: 14px; color:#1f2937; margin: 0 25px; }

    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    td, th{
        padding:4px; 
        font-size: 10px; 
        text-align:left; 
        border: 0.5px solid #333; 
    }

    th {
        overflow: hidden; 
        white-space: nowrap; 
        text-overflow: ellipsis;
    }

    .page-break {
        page-break-after: always;
    }
  </style>
</head>

<body>
    @php
        $pageCounter = 1;
    @endphp


    <div style="
        width: 100%;   
        height: 100%;
        position: relative;
        /* background-color: lightgray; */
        text-align:center;   
    ">
        <div style="
            width: 100%;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align:center;
        ">
            @if($companyLogo)
                <img src="{{ public_path($companyLogo) }}" style="max-width: 8cm; object-fit:contain; margin-bottom: 24px;">            
            @else
                <h2> {{ $companyName }}</h2>
            @endif
            <h2 style="margin-bottom: 18px;">{{ $companyName }}</h2>
            <h1 style="margin-bottom: 8px; font-size: 32px;">Clima Organizacional</h1>
            <p>Resultados dos testes de Clima Organizacional por questão.</p>
        </div>

        <div style="
            width: 100%;
            position: absolute;
            bottom: 35px;
            text-align:center;
        ">
            <span style="margin-right: 4px; font-size:12px;">Relatório desenvolvido por Facilita Tecnologia &copy; | Emissão: {{ date('d/m/Y') }} | Página {{ $pageCounter }}</span>
            @php
                $pageCounter++
            @endphp
        </div>
    </div>

    <div class="page-break"></div>

    @foreach ($organizationalClimateResults as $testName => $test)      
        <div style="
            width: 100%;   
            position: relative;
            text-align:center;
            padding-top: 60px;   
        ">
            <div style="">
                <h2 style="margin-bottom: 24px; text-align:center;">{{ $testName }}</h2>

                <table style="margin-bottom: 40px; width:100%; border-collapse:collapse; table-layout: fixed;">
                    <thead>
                        <th style="width:25%;">Enunciado</th>

                        @foreach ($test[array_keys($test)[0]] as $departmentName => $department)
                            <th>
                                {{ $departmentName }}
                            </th>
                        @endforeach
                    </thead>
                    <tbody>
                        @foreach ($test as $questionStatement => $question)
                            <tr>
                                <td style="width:25%;">{{ $questionStatement }}</td>
                                @foreach ($question as $departmentName => $department)
                                    <td style="{{ $department['total_average'] <= 75 ? 'color: darkred;' : '' }}">{{ number_format($department['total_average'], 0) }}%</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="
                width: 100%;
                position: absolute;
                bottom: 35px;
                text-align:center;
            ">
                <span style="margin-right: 4px; font-size:12px;">Relatório desenvolvido por Facilita Tecnologia &copy; | Emissão: {{ date('d/m/Y') }} | Página {{ $pageCounter }}</span>
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