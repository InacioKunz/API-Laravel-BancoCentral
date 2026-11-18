<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BcService;
use App\Services\SgsService;
use App\Models\Cotacao;

class SalvarCotacoes extends Command
{
    protected $signature = 'app:salvar-cotacoes';
    protected $description = 'Busca cotações do Banco Central e salva no banco de dados';

    public function handle(BcService $bc, SgsService $sgs)
    {
        $hoje = date('m-d-Y');      // formato do PTAX (câmbio)
        $hojeSgs = date('d/m/Y');   // formato do SGS (Selic)

        // Câmbio: dólar e euro
        $moedas = [
            'USD' => BcService::DOLAR,
            'EUR' => BcService::EURO,
        ];

        foreach ($moedas as $nome => $codigo) {
            $cotacao = $bc->getCotacaoMoeda($codigo, $hoje);

            if (count($cotacao) > 0) {
                $this->salvar($nome, $cotacao[0]['cotacaoCompra']);
            }
        }

        try {
        $selic = $sgs->getSelic(SgsService::SELIC, $hojeSgs);

        if (count($selic) > 0) {
            $this->salvar('SELIC', $selic[0]['valor']);
        }

        $this->info('Cotações salvas com sucesso!');
        } catch (\Exception $e) {
            $this->error('Não foi possivel buscar a selic'. $e->getMessage());
        }
    }

    private function salvar(string $indicador, $valor)
    {
        Cotacao::updateOrCreate(
            ['indicador' => $indicador, 'data' => date('Y-m-d')], // condição de busca
            ['valor' => $valor]                                   // o que atualizar/criar
        );
    }
}
