<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

Class BcService
{
    const DOLAR = 'USD';
    const EURO = 'EUR';
    
    protected string $baseURL;

    public function __construct()
    {
        $this->baseURL = config('services.bc.url');
    }

    public function getSerie(string $codigoSerie, string $dataInicial, string $dataFinal)
    {
        $url = "{$this->baseURL}/dados/serie/bcdata.sgs.{$codigoSerie}/dados";

        $response = Http::get($url, 
        [
            'formato'=>'json',
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
        ]);

        $response->throw(); // lança exceção se der erro HTTP

        return $response->json();
    }

    public function getCotacaoMoeda(string $moeda, string $data)
{
    $url = "{$this->baseURL}/CotacaoMoedaDia(moeda=@moeda,dataCotacao=@dataCotacao)";

    
    $response = Http::withoutVerifying()->get($url, [
        '@moeda' => "'{$moeda}'",
        '@dataCotacao' => "'{$data}'",
        '$format' => 'json',
    ]);

    $response->throw();

    return $response->json()['value'] ?? [];
}
}