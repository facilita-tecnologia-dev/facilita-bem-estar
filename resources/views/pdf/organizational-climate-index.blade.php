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
        <h1 style="margin-bottom: 8px; font-size: 32px;">Clima Organizacional</h1>
        <p>Resultados dos testes de Clima Organizacional compilados em gráficos.</p>
        @if(count($filtersApplied) > 0)
            <h3 style="
                margin-top: 40px;
            ">
                Este relatório contém os seguintes filtros:
            </h3>
            @foreach ($filtersApplied as $filterName => $filter)
                <p style="
                    margin-top: 8px;
                ">
                    {{ $filterName }}: {{ $filter }}
                </p>
            @endforeach
        @endif
    </x-pdf.cover>

    <div class="page-break"></div>

    @php
        $chunks = array_chunk($charts, 2, true);
    @endphp

    @foreach ($chunks as $chunk)
        <x-pdf.main-content>
            @foreach ($chunk as $chartName => $chart)
                <div style="margin-bottom: 60px;">
                    <h2 style="margin-bottom: 12px">{{ $chartName }}</h2>
                    @if($userDepartmentScopes && $userDepartmentScopes->count())
                        <div style="margin-bottom: 12px;">
                            <div style="display:inline-block; width: 10px; height:10px; background-color: #cccccc; margin-right:5px; font-size: 10px;"></div>
                            <span>Geral da empresa (%)</span>
                        </div>
                    @endif
                    <img src="{{ $chart }}" style="width:100%">
                </div>
            @endforeach
        </x-pdf.main-content>

        {{-- Quebra de página após cada grupo, menos no último --}}
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <x-pdf.footer />
</body>
</html>