Painel de Indicadores Econômicos — API do Banco Central

Aplicação em Laravel que consome as APIs públicas do Banco Central do Brasil (PTAX e SGS) para exibir e armazenar indicadores econômicos — cotação do dólar, do euro, taxa Selic e IPCA — com atualização automática via agendamento de tarefas.

O projeto foi construído como exercício prático de consumo de APIs, modelagem de banco de dados e automação de rotinas, com o objetivo de aplicar conceitos que também aparecem em ferramentas de RPA (UiPath, Power Automate): busca de dados externos, tratamento de falhas/indisponibilidade e persistência de histórico.

Funcionalidades
Painel web com os indicadores do dia, permitindo consultar uma data específica.
Fallback automático: quando não há cotação publicada para a data escolhida (feriados, fins de semana, atraso de publicação), o sistema busca o último valor disponível em vez de retornar erro.
Persistência histórica: um comando Artisan agendado busca e salva os indicadores diariamente no banco de dados, evitando duplicidade de registros para o mesmo dia.
Endpoint JSON (/api/painel/relatorio) que expõe os indicadores em formato estruturado, pensado para ser consumido por scripts externos ou ferramentas de automação — usado, por exemplo, pelo script em Python deste mesmo portfólio.
Tecnologias
PHP / Laravel
Eloquent ORM
Laravel Task Scheduling (routes/console.php)
APIs públicas do Banco Central: PTAX (câmbio) e SGS (séries temporais — Selic, IPCA)
Arquitetura

A integração com as APIs externas fica isolada em service classes (BcService, SgsService), responsáveis por montar as requisições, tratar os formatos de data específicos de cada API e normalizar o retorno. O controller depende apenas dessas services, sem conhecer detalhes de implementação de cada API.

O banco de dados usa uma tabela única e normalizada (cotacoes, com as colunas indicador, valor, data), em vez de uma coluna por indicador — isso permite adicionar novos indicadores no futuro sem alterar o schema.

Endpoints
Rota	Descrição
GET /api/painel	Exibe o painel web com os 4 indicadores, com filtro de data
GET /api/painel/relatorio	Retorna os mesmos dados em JSON, para consumo externo
Automação

O comando php artisan app:salvar-cotacoes busca os indicadores atuais e salva no banco. Ele está agendado para rodar diariamente (routes/console.php), simulando uma rotina de coleta automática de dados — o mesmo princípio usado em automações RPA, só que nativo do Laravel.

Como rodar localmente
bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# preencher no .env:
# BCB_API=https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata
# SGS_API=https://api.bcb.gov.br

php artisan serve

Para testar o agendamento localmente:

bash
php artisan schedule:work
Possíveis evoluções
Endpoint com filtros por período e por indicador
Paginação e versionamento de API (/api/v1)
Exportação direta do histórico salvo no banco (hoje a exportação é feita pelo script Python do repositório irmão)

<img width="951" height="436" alt="{781AAE99-B7EF-47E0-A93F-BB05AA8DF81B}" src="https://github.com/user-attachments/assets/2061c664-ed12-402c-b2f0-629f55f4050a" />
