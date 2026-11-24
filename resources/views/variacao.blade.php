<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Variação do Dólar - Banco Central</title>
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
            max-width: 560px;
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
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .campo label {
            font-weight: 500;
            font-size: 12px;
            color: #4a4a4a;
        }

        .campo input[type="date"] {
            padding: 7px 10px;
            border: 1px solid #d4d5d9;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
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
            height: 34px;
        }

        .filtro button:hover {
            background: #1a1a1a;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e3e4e7;
            border-radius: 6px;
            padding: 24px;
        }

        .resultado-linha {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eeeef0;
            font-size: 14px;
        }

        .resultado-linha:last-child {
            border-bottom: none;
        }

        .resultado-linha .rotulo {
            color: #767676;
        }

        .resultado-linha .valor {
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .positivo {
            color: #1a7f37;
        }

        .negativo {
            color: #c0392b;
        }

        .sem-dado {
            text-align: center;
            color: #9a9a9a;
            font-style: italic;
            font-size: 13px;
        }

        footer {
            margin-top: 16px;
            font-size: 12px;
            color: #9a9a9a;
        }

        .nav {
            margin-bottom: 20px;
        }

        .nav a {
            font-size: 13px;
            color: #3b5bdb;
            text-decoration: none;
            font-weight: 500;
        }

        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Variação do Dólar</h1>
            <p>Calcula a variação percentual entre duas datas, direto no banco de dados</p>
        </div>

        <div class="nav">
            <a href="{{ url('/api/painel') }}">← Voltar para o painel</a>
        </div>

        <form method="GET" action="{{ url('/api/painel/variacao') }}" class="filtro">
            <div class="campo">
                <label for="data_inicial">Data inicial</label>
                <input type="date" id="data_inicial" name="data_inicial" value="{{ $dataInicial }}">
            </div>
            <div class="campo">
                <label for="data_final">Data final</label>
                <input type="date" id="data_final" name="data_final" value="{{ $dataFinal }}">
            </div>
            <button type="submit">Calcular</button>
        </form>

        <div class="card">
            @if($resultado)
                <div class="resultado-linha">
                    <span class="rotulo">Valor inicial</span>
                    <span class="valor">R$ {{ number_format($resultado->valor_inicial, 4, ',', '.') }}</span>
                </div>
                <div class="resultado-linha">
                    <span class="rotulo">Valor final</span>
                    <span class="valor">R$ {{ number_format($resultado->valor_final, 4, ',', '.') }}</span>
                </div>
                <div class="resultado-linha">
                    <span class="rotulo">Variação percentual</span>
                    <span class="valor {{ $resultado->variacao_percentual >= 0 ? 'positivo' : 'negativo' }}">
                        {{ number_format($resultado->variacao_percentual, 2, ',', '.') }}%
                    </span>
                </div>
            @else
                <p class="sem-dado">Escolha duas datas com cotação salva no banco para ver a variação</p>
            @endif
        </div>

        <footer>Calculado via SQL direto sobre a tabela cotacoes</footer>
    </div>

</body>
</html>