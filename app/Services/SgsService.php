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

        $response->throw(); // lança exceção se der erro HTTP

        return $response->json();
    }

    
    public function getSelic(string $selic, string $data)
    {
        $url = "{$this->baseURL}/dados/serie/bcdata.sgs.{$selic}/dados";

        $response = Http::withoutVerifying()->get($url, [
            'formato'=>'json',
            'selic'=> $selic,
            'dataInicial' => $data,
            'dataFinal' => $data,
    ]);
        
        $response->throw();

        return $response->json();
    }
}