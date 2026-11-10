<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cotações - Banco Central</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
            color: #1f2937;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #111827;
        }

        .card {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #1d4ed8;
        }

        thead th {
            color: #ffffff;
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
        }

        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        .indicador {
            font-weight: 600;
            color: #1d4ed8;
        }

        .sem-dado {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>

    <h1>Cotação do dia</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Indicador</th>
                    <th>Valor</th>
                    <th>Venda</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @if(count($dolar) > 0)
                    <tr>
                        <td class="indicador">Dólar</td>
                        <td>{{ $dolar[0]['cotacaoCompra'] }}</td>
                        <td>{{ $dolar[0]['cotacaoVenda'] }}</td>
                        <td>{{ $dolar[0]['dataHoraCotacao'] }}</td>
                    </tr>
                @else
                    <tr><td colspan="4" class="sem-dado">Sem cotação de dólar disponível hoje</td></tr>
                @endif

                @if(count($euro) > 0)
                    <tr>
                        <td class="indicador">Euro</td>
                        <td>{{ $euro[0]['cotacaoCompra'] }}</td>
                        <td>{{ $euro[0]['cotacaoVenda'] }}</td>
                        <td>{{ $euro[0]['dataHoraCotacao'] }}</td>
                    </tr>
                @else
                    <tr><td colspan="4" class="sem-dado">Sem cotação do euro disponível hoje</td></tr>
                @endif

                @if(count($selic) > 0)
                    <tr>
                        <td class="indicador">Selic (a.a.)</td>
                        <td>{{ $selic[0]['valor'] }}%</td>
                        <td>—</td>
                        <td>{{ $selic[0]['data'] }}</td>
                    </tr>
                @else
                    <tr><td colspan="4" class="sem-dado">Sem Taxa Selic disponível hoje</td></tr>
                @endif
            </tbody>
        </table>
    </div>

</body>
</html>