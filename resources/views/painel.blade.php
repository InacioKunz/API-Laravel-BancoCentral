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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: #f4f5f7;
            margin: 0;
            padding: 48px 20px;
            color: #2d2d2d;
            min-height: 100vh;
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 4px;
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .header p {
            margin: 0;
            color: #767676;
            font-size: 13px;
        }

        .filtro {
            background: #ffffff;
            border: 1px solid #e3e4e7;
            border-radius: 6px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filtro label {
            font-weight: 500;
            font-size: 13px;
            color: #4a4a4a;
        }

        .filtro input[type="date"] {
            padding: 7px 10px;
            border: 1px solid #d4d5d9;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
            flex: 1;
            min-width: 150px;
            color: #2d2d2d;
        }

        .filtro button {
            background: #2d2d2d;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
        }

        .filtro button:hover {
            background: #1a1a1a;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e3e4e7;
            border-radius: 6px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #fafafb;
            color: #6b6b6b;
            padding: 10px 18px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e3e4e7;
        }

        tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid #eeeef0;
            font-size: 14px;
            color: #2d2d2d;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .indicador {
            font-weight: 600;
            color: #1a1a1a;
        }

        .sigla {
            color: #9a9a9a;
            font-weight: 400;
            font-size: 12px;
            margin-left: 4px;
        }

        .sem-dado {
            text-align: center;
            color: #9a9a9a;
            font-style: italic;
            padding: 20px;
            font-size: 13px;
        }

        .valor-principal {
            font-weight: 600;
            font-size: 14px;
            font-variant-numeric: tabular-nums;
        }

        .valor-secundario {
            font-variant-numeric: tabular-nums;
            color: #4a4a4a;
        }

        .data-coluna {
            color: #767676;
            font-size: 13px;
        }

        footer {
            margin-top: 16px;
            font-size: 12px;
            color: #9a9a9a;
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
            <label for="data">Data da consulta</label>
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
                            <td class="indicador">Dólar <span class="sigla">USD</span></td>
                            <td class="valor-principal">R$ {{ number_format(end($dolar)['cotacaoCompra'], 4, ',', '.') }}</td>
                            <td class="valor-secundario">R$ {{ number_format(end($dolar)['cotacaoVenda'], 4, ',', '.') }}</td>
                            <td class="data-coluna">{{ date('d/m/Y', strtotime(end($dolar)['dataHoraCotacao'])) }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem cotação de dólar disponível para essa data</td></tr>
                    @endif

                    @if(count($euro) > 0)
                        <tr>
                            <td class="indicador">Euro <span class="sigla">EUR</span></td>
                            <td class="valor-principal">R$ {{ number_format(end($euro)['cotacaoCompra'], 4, ',', '.') }}</td>
                            <td class="valor-secundario">R$ {{ number_format(end($euro)['cotacaoVenda'], 4, ',', '.') }}</td>
                            <td class="data-coluna">{{ date('d/m/Y', strtotime(end($euro)['dataHoraCotacao'])) }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem cotação de euro disponível para essa data</td></tr>
                    @endif

                    @if(count($selic) > 0)
                        <tr>
                            <td class="indicador">Selic <span class="sigla">a.a.</span></td>
                            <td class="valor-principal">{{ number_format($selic[0]['valor'], 2, ',', '.') }}%</td>
                            <td class="valor-secundario">—</td>
                            <td class="data-coluna">{{ $selic[0]['data'] }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem Taxa Selic disponível para essa data</td></tr>
                    @endif

                    @if(count($ipca) > 0)
                        <tr>
                            <td class="indicador">IPCA <span class="sigla">mensal</span></td>
                            <td class="valor-principal">{{ number_format($ipca[0]['valor'], 2, ',', '.') }}%</td>
                            <td class="valor-secundario">—</td>
                            <td class="data-coluna">{{ $ipca[0]['data'] }}</td>
                        </tr>
                    @else
                        <tr><td colspan="4" class="sem-dado">Sem IPCA disponível para essa data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <footer>Fonte: API do Banco Central do Brasil (PTAX e SGS)</footer>
    </div>

</body>
</html>
