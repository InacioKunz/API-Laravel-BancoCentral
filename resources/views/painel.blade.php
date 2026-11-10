<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cotações - Banco Central</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7 0%, #e2e8f0 100%);
            margin: 0;
            padding: 40px 20px;
            color: #1f2937;
            min-height: 100vh;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 28px;
            color: #111827;
        }

        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .filtro {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .filtro label {
            font-weight: 600;
            font-size: 14px;
            color: #374151;
        }

        .filtro input[type="date"] {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            flex: 1;
            min-width: 160px;
        }

        .filtro button {
            background: #1d4ed8;
            color: #fff;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .filtro button:hover {
            background: #1e40af;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }

        thead th {
            color: #ffffff;
            padding: 16px 18px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 15px;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        .indicador {
            font-weight: 700;
            color: #1d4ed8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
        }

        .sem-dado {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 24px;
        }

        .valor-principal {
            font-weight: 600;
            font-size: 16px;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Painel de Cotações</h1>
            <p>Dados oficiais do Banco Central do Brasil</p>
        </div>

        <form method="GET" action="{{ url('/api/painel') }}" class="filtro">
            <label for="data">Data da consulta:</label>
            <input type="date" id="data" name="data" value="{{ request('data', date('Y-m-d')) }}">
            <button type="submit">Consultar</button>
        </form>

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
                            <td class="indicador">💵 Dólar <span class="badge">USD</span></td>
                            <td class="valor-principal">R$ {{ number_format($dolar[0]['cotacaoCompra'], 4, ',', '.') }}</td>
                            <td>R$ {{ number_format($dolar[0]['cotacaoVenda'], 4, ',', '.') }}</td>
                            <td>{{ $dolar[0]['dataHoraCotacao'] }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem cotação de dólar disponível para essa data</td></tr>
                    @endif

                    @if(count($euro) > 0)
                        <tr>
                            <td class="indicador">💶 Euro <span class="badge">EUR</span></td>
                            <td class="valor-principal">R$ {{ number_format($euro[0]['cotacaoCompra'], 4, ',', '.') }}</td>
                            <td>R$ {{ number_format($euro[0]['cotacaoVenda'], 4, ',', '.') }}</td>
                            <td>{{ $euro[0]['dataHoraCotacao'] }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem cotação de euro disponível para essa data</td></tr>
                    @endif

                    @if(count($selic) > 0)
                        <tr>
                            <td class="indicador">📊 Selic <span class="badge">a.a.</span></td>
                            <td class="valor-principal">{{ number_format($selic[0]['valor'], 2, ',', '.') }}%</td>
                            <td>—</td>
                            <td>{{ $selic[0]['data'] }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem Taxa Selic disponível para essa data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <footer>Fonte: API do Banco Central do Brasil (PTAX e SGS)</footer>
    </div>

</body>
</html>