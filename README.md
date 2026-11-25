# Painel de Indicadores Econômicos — API do Banco Central

Aplicação em Laravel que consome as APIs públicas do Banco Central do Brasil (PTAX e SGS) para exibir e armazenar indicadores econômicos — cotação do dólar, do euro, taxa Selic e IPCA — com atualização automática via agendamento de tarefas, histórico em banco de dados e consultas analíticas em SQL.

O projeto foi construído como exercício prático de consumo de APIs, modelagem de banco de dados, automação de rotinas e manipulação de dados via SQL, com o objetivo de aplicar conceitos que também aparecem em ferramentas de RPA (UiPath, Power Automate): busca de dados externos, tratamento de falhas/indisponibilidade e persistência de histórico.

## Funcionalidades

- **Painel web** com os indicadores do dia (dólar, euro, Selic, IPCA), permitindo consultar uma data específica.
- **Fallback automático**: quando não há cotação publicada para a data escolhida (feriados, fins de semana, atraso de publicação), o sistema busca o último valor disponível em vez de retornar erro.
- **Persistência histórica**: um comando Artisan agendado busca e salva os indicadores diariamente no banco de dados, evitando duplicidade de registros para o mesmo dia.
- **Endpoint JSON** (`/api/painel/relatorio`) que expõe os indicadores em formato estruturado, pensado para ser consumido por scripts externos ou ferramentas de automação — usado, por exemplo, pelo script em Python deste mesmo portfólio.
- **Variação percentual entre datas**, calculada com SQL puro (`DB::select`) sobre o histórico salvo, sem passar pelo ORM — disponível tanto em tela (`/api/painel/variacao`) quanto em JSON (`/api/painel/variacao/json`).

## Tecnologias

- PHP / Laravel
- Eloquent ORM (para o CRUD do dia a dia) e SQL puro (para consultas analíticas)
- Laravel Task Scheduling (`routes/console.php`)
- APIs públicas do Banco Central: [PTAX](https://olinda.bcb.gov.br) (câmbio) e [SGS](https://api.bcb.gov.br) (séries temporais — Selic, IPCA)

## Arquitetura

A integração com as APIs externas fica isolada em **service classes** (`BcService`, `SgsService`), responsáveis por montar as requisições, tratar os formatos de data específicos de cada API e normalizar o retorno. Os controllers dependem apenas dessas services, sem conhecer detalhes de implementação de cada API.

O banco de dados usa uma tabela única e normalizada (`cotacoes`, com as colunas `indicador`, `valor`, `data`), em vez de uma coluna por indicador — isso permite adicionar novos indicadores no futuro sem alterar o schema.

A busca dos indicadores é centralizada num único método (`indicadores()`), reaproveitado tanto pela rota que renderiza a tela quanto pela que devolve JSON — evitando duplicar a lógica de busca e fallback em dois lugares.

## Endpoints

| Rota | Descrição |
|---|---|
| `GET /` | Redireciona para o painel |
| `GET /api/painel` | Exibe o painel web com os 4 indicadores, com filtro de data |
| `GET /api/painel/relatorio` | Retorna os mesmos dados em JSON, para consumo externo |
| `GET /api/painel/variacao` | Tela com formulário de duas datas, calculando a variação percentual do dólar entre elas via SQL |
| `GET /api/painel/variacao/json` | Mesmo cálculo de variação, em JSON |

## Automação

O comando `php artisan app:salvar-cotacoes` busca os indicadores atuais e salva no banco. Ele está agendado para rodar diariamente (`routes/console.php`), simulando uma rotina de coleta automática de dados — o mesmo princípio usado em automações RPA, só que nativo do Laravel.

## Consulta SQL — variação percentual

A variação entre duas datas é calculada com uma consulta SQL direta, usando um *self-join* (a tabela `cotacoes` se junta com ela mesma, uma vez representando a data inicial e outra a data final):

```sql
SELECT
    a.valor AS valor_inicial,
    b.valor AS valor_final,
    ((b.valor - a.valor) / a.valor) * 100 AS variacao_percentual
FROM cotacoes a
JOIN cotacoes b ON a.indicador = b.indicador
WHERE a.indicador = 'USD'
    AND a.data = ?
    AND b.data = ?
```

Os parâmetros são sempre passados via *bindings* (`DB::select($sql, [...])`), nunca concatenados na string, evitando SQL injection.

## Como rodar localmente

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# preencher no .env:
# BCB_API=https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata
# SGS_API=https://api.bcb.gov.br

php artisan serve
```

Para testar o agendamento localmente:

```bash
php artisan schedule:work
```

<img width="1201" height="764" alt="{145450A8-71FE-42B8-99D0-AAD1D27A5C47}" src="https://github.com/user-attachments/assets/c6677dad-825c-48c7-8f3f-58d1df2d858f" />

<img width="1032" height="625" alt="{0B801B24-1118-47DE-86DE-51E29CD89535}" src="https://github.com/user-attachments/assets/a0861627-3781-47f5-b381-5a449f1f4b70" />

## Possíveis evoluções

- Mais consultas analíticas em SQL (médias móveis, agregações mensais)
- Endpoint com filtros por período e por indicador
- Paginação e versionamento de API (`/api/v1`)
- Testes automatizados (PHPUnit) cobrindo o fallback e as consultas SQL
