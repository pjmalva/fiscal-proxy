<?php

namespace App\Services;

use NFePHP\Ibpt\Ibpt;

class IBPTService
{
    // private $cnpj = '22627105000108';
    // private $token = 'amCMCvtrBHefxFq_ShLjEC9ZTuSBdLbRH5Ar6CPp1PIu_Fy0lLJqI8TcAhd5GdNo';
    private $token;
    private $cnpj;
    private $uf;
    private $ncm;
    private $extarif;
    private $name;
    private $unity;
    private $value;
    private $gtin;
    private $reference;

    private $font = 'IBPT';

    public function __construct(
        String $token,
        String $cnpj,
        String $uf,
        String $ncm,
        int $extarif,
        String $name,
        String $unity,
        float $value,
        ?String $gtin = NULL,
        ?String $reference = NULL
    ) {
        $this->token = $token;
        $this->cnpj = $cnpj;
        $this->uf = $uf;
        $this->ncm = $ncm;
        $this->extarif = $extarif;
        $this->name = $name;
        $this->unity = $unity;
        $this->value = $value;
        $this->gtin = $gtin;
        $this->reference = $reference;
    }

    public function productTaxes()
    {
        $ibpt = new Ibpt($this->cnpj, $this->token);

        $resp = $ibpt->productTaxes(
            $this->uf,
            $this->ncm,
            $this->extarif ?? 0,
            $this->name,
            $this->unity ?? 'UN',
            $this->value,
            $this->getGtin($this->gtin),
            $this->reference
        );

        return [
            "country" => $resp->ValorTributoNacional,
            "state" => $resp->ValorTributoEstadual,
            "city" => $resp->ValorTributoMunicipal,
            "import" => $resp->ValorTributoImportado,
        ];
    }

    public static function consultProduct(
        String $token,
        String $cnpj,
        String $uf,
        String $ncm,
        int $extarif,
        String $name,
        String $unity,
        float $value,
        ?String $gtin = NULL,
        ?String $reference = NULL
    ) {
        $service = new IBPTService(
            $token,
            $cnpj,
            $uf,
            $ncm,
            $extarif,
            $name,
            $unity,
            $value,
            $gtin,
            $reference
        );
        return $service->productTaxes();
    }

    public static function mountMessage(Array $taxes)
    {
        $country = 0;
        $state = 0;
        $city = 0;
        $import = 0;

        foreach ($taxes as $tax) {
            $country += $tax['country'] ?? 0;
            $state += $tax['state'] ?? 0;
            $city += $tax['city'] ?? 0;
            $import += $tax['import'] ?? 0;
        }

        $message = "Você pagou aproximadamente: ";

        if($country) {
            $message .= " R$". number_format($country, 2, ',', '.') ." de tributos federais -";
        }

        if($state) {
            $message .= " R$". number_format($state, 2, ',', '.') ." de tributos estaduais -";
        }

        if($city) {
            $message .= " R$". number_format($city, 2, ',', '.') ." de tributos municipais -";
        }

        if($import) {
            $message .= " R$". number_format($import, 2, ',', '.') ." de tributos de importação -";
        }

        $message .= " Fonte : IBPT.";

        return $message;
    }

    public function getGtin(String $barcode)
    {
        $gtinSizes = [8, 12, 13, 14];
        if(
            !$barcode
            || $barcode == ''
            || !in_array(strlen($barcode), $gtinSizes)
        ) {
            return 'SEM GTIN';
        }

        return $barcode;
    }
}
