<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\IBPTService;

class IBPTController extends Controller
{
    public function calculateProductTaxes()
    {
        // TODO Check fields
        // $this->request->token;
        // $this->request->cnpj;
        // $this->request->uf;
        // $this->request->ncm;
        // $this->request->extarif;
        // $this->request->name;
        // $this->request->unity;
        // $this->request->value;
        // $this->request->gtin;
        // $this->request->reference;

        $ibpt = IBPTService::consultProduct(
            $this->request->token,
            $this->request->cnpj,
            $this->request->uf,
            $this->request->ncm,
            $this->request->extarif,
            $this->request->name,
            $this->request->unity,
            $this->request->value,
            $this->request->gtin,
            $this->request->reference
        );

        return response()->json($ibpt);
    }

    public function prepareMessageIBPT()
    {
        $message = IBPTService::mountMessage($this->request->taxes);
        return response()->json([
            'message' => $message
        ]);
    }
}
