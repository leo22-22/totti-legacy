<?php

namespace App\Services;

class FreightService
{
    private const ORIGIN_CEP = '01310100'; // CEP de origem da loja (configurar no .env)

    // Tabela de frete por prefixo de CEP (primeiros 2 dígitos = estado/região)
    private const REGION_TABLE = [
        // SP Capital
        '01' => ['pac' => ['price' => 15.90, 'days' => 4], 'sedex' => ['price' => 29.90, 'days' => 1]],
        '02' => ['pac' => ['price' => 15.90, 'days' => 4], 'sedex' => ['price' => 29.90, 'days' => 1]],
        '03' => ['pac' => ['price' => 15.90, 'days' => 4], 'sedex' => ['price' => 29.90, 'days' => 1]],
        '04' => ['pac' => ['price' => 15.90, 'days' => 4], 'sedex' => ['price' => 29.90, 'days' => 1]],
        '05' => ['pac' => ['price' => 15.90, 'days' => 4], 'sedex' => ['price' => 29.90, 'days' => 1]],
        // SP Interior
        '06' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '07' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '08' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '09' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '11' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '12' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '13' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '14' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '15' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '16' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '17' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '18' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        '19' => ['pac' => ['price' => 18.90, 'days' => 5], 'sedex' => ['price' => 34.90, 'days' => 2]],
        // RJ
        '20' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '21' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '22' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '23' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '24' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '25' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '26' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '27' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '28' => ['pac' => ['price' => 20.90, 'days' => 5], 'sedex' => ['price' => 39.90, 'days' => 2]],
        // MG / ES
        '29' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '30' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '31' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '32' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '33' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '34' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '35' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '36' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '37' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '38' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        '39' => ['pac' => ['price' => 20.90, 'days' => 6], 'sedex' => ['price' => 39.90, 'days' => 2]],
        // PR / SC
        '80' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '81' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '82' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '83' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '84' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '85' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '86' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '87' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '88' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '89' => ['pac' => ['price' => 22.90, 'days' => 7], 'sedex' => ['price' => 44.90, 'days' => 3]],
        // RS
        '90' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '91' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '92' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '93' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '94' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '95' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '96' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '97' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '98' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
        '99' => ['pac' => ['price' => 22.90, 'days' => 8], 'sedex' => ['price' => 44.90, 'days' => 3]],
    ];

    // Frete padrão para regiões não mapeadas (Norte/Nordeste/Centro-Oeste)
    private const DEFAULT_RATES = [
        'pac'   => ['price' => 29.90, 'days' => 12],
        'sedex' => ['price' => 64.90, 'days' => 5],
    ];

    public function calculate(string $cep): array
    {
        $cep    = preg_replace('/\D/', '', $cep);
        $prefix = substr($cep, 0, 2);
        $rates  = self::REGION_TABLE[$prefix] ?? self::DEFAULT_RATES;

        return [
            [
                'service'      => 'PAC',
                'code'         => 'pac',
                'price'        => $rates['pac']['price'],
                'days'         => $rates['pac']['days'],
                'label'        => 'PAC — ' . $rates['pac']['days'] . ' dias úteis',
            ],
            [
                'service'      => 'SEDEX',
                'code'         => 'sedex',
                'price'        => $rates['sedex']['price'],
                'days'         => $rates['sedex']['days'],
                'label'        => 'SEDEX — ' . $rates['sedex']['days'] . ' dia(s) útil(eis)',
            ],
        ];
    }

    public function isFreeShipping(float $subtotal): bool
    {
        return $subtotal >= (float) config('store.free_shipping_threshold', 299);
    }
}
