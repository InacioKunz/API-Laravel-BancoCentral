<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BcService;
use App\Services\SgsService;
use App\Services\IpcaService;
use App\Models\Cotacao;



class PainelController extends Controller
{

  public function indicadores(BcService $bc, SgsService $sgs, IpcaService $ipca)
  {
    $data = request()->input('data', date('d-m-Y')); // Obtém a data do parâmetro da requisição ou usa a data atual como padrão
    $dataInicial = date('m-d-Y', strtotime($data)); // Converte a data para o formato Y-m-d, formato API PTAX
    $dataFinal = date('m-d-Y', strtotime('-10 days'));

    try{
    $dolar = $bc->getCotacaoMoeda(BcService::DOLAR, $dataInicial);
        if (count($dolar) === 0 ){
            $dolar = $bc->getUltimoValor(BcService::DOLAR);
        }
    $euro = $bc->getCotacaoMoeda(BcService::EURO, $dataInicial);
        if (count($euro) === 0) {
            $euro = $bc->getUltimoValor(BcService::EURO);
    }
    } catch (\Exception $e){
    $dolar = $bc->getUltimoValor(BcService::DOLAR);
    $euro = $bc->getUltimoValor(BcService::EURO);
    }

    $data = date('d/m/Y', strtotime($data)); // Formata a data para o formato da API SGS
    
    try{
    $selic = $sgs->getSerie(SgsService::SELIC, $data);
    }catch (\Exception  $e){
    $selic = $sgs->getUltimoValor(SgsService::SELIC);
    }

    try{
    $ipca = $ipca->getSerie(IpcaService::IPCA, $data);
    }catch (\Exception  $e){
    $ipca = $ipca->getUltimoValor(IpcaService::IPCA); 
    }

    return [
            'dolar' => $dolar,
            'euro' => $euro,
            'selic' => $selic,
            'ipca' => $ipca,
            'data' => $data,
        ];
  }

  public function view(BcService $bc, SgsService $sgs, IpcaService $ipca)
    {
    $dados = $this->indicadores($bc, $sgs, $ipca);

    return view('painel', $dados);
   
    }

    public function indicadoresParaJson(BcService $bc, SgsService $sgs, IpcaService $ipca)
    {
        $dados = $this->indicadores($bc, $sgs, $ipca);

        return response()->json($dados);
    }
}



