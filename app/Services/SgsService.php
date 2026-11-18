<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

Class SgsService
{

    const SELIC = '1178';

    protected string $baseURL;

    public function __construct()
    {
        $this->baseURL = config('services.sgs.url');
    }

   public function getSerie(string $codigoSerie, string $data)
{
    $url = "{$this->baseURL}/dados/serie/bcdata.sgs.{$codigoSerie}/dados";

    $response = Http::get($url, 
    [
        'formato'=>'json',
        'dataInicial' => $data,
        'dataFinal' => $data,
    ]);

    $response->throw();

    return $response->json();
}

    
    public function getSelic(string $selic, string $data)
    {
        $url = "{$this->baseURL}/dados/serie/bcdata.sgs.{$selic}/dados";

        $response = Http::withoutVerifying()->get($url, [
            'formato'=>'json',
            'selic'=> $selic,
            'dataInicial' => $data,  //A API do SGS espera dois parâmetros para fazer a requisição.
            'dataFinal' => $data, //A API do SGS espera dois parâmetros para fazer a requisição.
    ]);
        
        $response->throw();

        return $response->json();
    }

    public function getUltimoValor(string $codigoSerie)
{
    $url = "{$this->baseURL}/dados/serie/bcdata.sgs.{$codigoSerie}/dados/ultimos/1?formato=json&_=" . time();

    $response = Http::get($url);

    $response->throw();

    return $response->json();
}
}