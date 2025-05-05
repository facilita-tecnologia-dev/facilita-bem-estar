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
                <img src="{{ public_path($companyLogo) }}" style="max-width: 8cm; object-fit:contain; margin-bottom: 32px;">            
            @else
                <h2> {{ $companyName }}</h2>
            @endif
            <h1 style="margin-bottom: 8px; font-size: 32px;">Clima Organizacional</h1>
            <p>Resultados dos testes de Clima Organizacional compilados em gráficos.</p>
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

    @php
        $chunks = array_chunk($charts, 4, true); // Divide os gráficos em grupos de 3
    @endphp

    @foreach ($chunks as $chunk)
        <div style="
            width: 100%;   
            height: 100%;
            position: relative;
            text-align:center;
        ">
            <div style="
                width: 100%;
                position: relative;
                text-align:center;
                position: absolute;
                padding-top: 60px;
            ">
                @foreach ($chunk as $chartName => $chart)
                    <div style="margin-bottom: 40px;">
                        <h2 style="margin-bottom: 8px">{{ $chartName }}</h2>
                        <p style="margin-bottom: 8px; font-size: 12px;">Índice de satisfação (%)</p>
                        <img src="{{ $chart }}" style="width:90%">
                    </div>
                @endforeach
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

        {{-- Quebra de página após cada grupo, menos no último --}}
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
    {{-- <div class="cover">
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

    @foreach ($charts as $chartName => $chart)
        <h2>{{ $chartName }}</h2>
        <p class="subtitle">Indice de satisfação (%)</p>
        <img src="{{ $chart }}" alt="Gráfico" >
    @endforeach --}}

</body>
</html>