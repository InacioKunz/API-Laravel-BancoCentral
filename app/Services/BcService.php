<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

Class BcService
{
    const DOLAR = 'USD';
    CONST EURO = 'EUR';

    protected string $baseURL;

    public function __construct()
    {
        $this->baseURL = config('services.bc.url');
    }

    public function getSerie(string $codigoSerie, string $data)
    {
        $url = "{$this->baseURL}/dados/serie/bcdata.sgs.{$codigoSerie}/dados";

        $response = Http::get($url, 
        [
            'formato'=>'json',
            'data' => $data,
        ]);

        $response->throw(); // lança exceção se der erro HTTP

        return $response->json();
    }

    public function getCotacaoMoeda(string $moeda, string $data)
{
    $url = "{$this->baseURL}/CotacaoMoedaDia(moeda=@moeda,dataCotacao=@dataCotacao)";

    
    $response = Http::withoutVerifying()->get($url, [
        '@moeda' => "'{$moeda}'",
        '@dataCotacao' => "'{$data}'", //o @ é esperado pela API do Banco Central para identificar os parâmetros da requisição.
        '$format' => 'json',
    ]);

    $response->throw();

    return $response->json()['value'] ?? [];
}

    public function getUltimoValor(string $moeda){
        $url = "{$this->baseURL}/CotacaoMoedaPeriodo(moeda=@moeda,dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)";
        
        $dataFinal = date('m-d-Y');
        $dataInicial = date('m-d-Y', strtotime('-15 days'));
        
        $response = Http::withoutVerifying()->get($url, [
            '@moeda' => "'{$moeda}'",
            '@dataInicial' => "'{$dataInicial}'", //o @ é esperado pela API do Banco Central para identificar os parâmetros da requisição.
            '@dataFinalCotacao' => "'{$dataFinal}'",
            '$format' => 'json',
        ]);

        $response->throw();

    $valores = $response->json()['value'] ?? [];
    
    return end($valores) ? [end($valores)] : [];
    }

    

}