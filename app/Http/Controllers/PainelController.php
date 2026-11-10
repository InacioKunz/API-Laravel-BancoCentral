<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BcService;
use App\Services\SgsService;




class PainelController extends Controller
{
  public function index(BcService $bc, SgsService $sgs, Request $request)
{
    $data = request()->input('data', date('d-m-Y')); // Obtém a data do parâmetro da requisição ou usa a data atual como padrão
    $data = date('m-d-Y', strtotime($data)); // Converte a data para o formato Y-m-d, formato API PTAX
    $dolar = $bc->getCotacaoMoeda(BcService::DOLAR, $data);
    $euro = $bc->getCotacaoMoeda(BcService::EURO, $data);

    $data = date('d/m/Y', strtotime($data)); // Formata a data para o formato da API SGS

    $selic = $sgs->getSerie(SgsService::SELIC, $data);


    return view('painel', [
        'dolar' => $dolar,
        'euro' => $euro,
        'selic' =>  $selic,
        'data' => $data,
        
    ]);
}

}
