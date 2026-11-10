<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BcService;



class PainelController extends Controller
{
  public function index(BcService $bc)
{
    $hoje = date('06/11/2026'); // formato que a API do Bacen pede

    $dolar = $bc->getCotacaoMoeda('USD', $hoje);
    $euro = $bc->getCotacaoMoeda('EUR', $hoje);

    $dataInicial = date('01/01/2026');
    $dataFinal = date('06/11/2026');

    $selic = $bc->getSelic('1178', $dataInicial, $dataFinal);


    return view('painel', [
        'dolar' => $dolar,
        'euro' => $euro,
        'selic' =>  $selic,
        'dataInicial' => $dataInicial,
        'dataFinal' => $dataFinal,
        
    ]);
}

}
