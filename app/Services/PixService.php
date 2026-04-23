<?php

namespace App\Services;

class PixService
{
    public function generatePayload(string $key, string $keyType, float $amount, string $orderNumber, string $merchantName = 'Totti Legacy', string $city = 'Sao Paulo'): string
    {
        $merchantName = substr($this->sanitize($merchantName), 0, 25);
        $city         = substr($this->sanitize($city), 0, 15);
        $txId         = substr(preg_replace('/[^A-Za-z0-9]/', '', $orderNumber), 0, 25);
        $amount       = number_format($amount, 2, '.', '');

        $gui  = $this->tlv('00', 'br.gov.bcb.pix') . $this->tlv('01', $key);
        $mad  = $this->tlv('26', $gui);
        $body = $this->tlv('00', '01')
              . $mad
              . $this->tlv('52', '0000')
              . $this->tlv('53', '986')
              . $this->tlv('54', $amount)
              . $this->tlv('58', 'BR')
              . $this->tlv('59', $merchantName)
              . $this->tlv('60', $city)
              . $this->tlv('62', $this->tlv('05', $txId));

        return $body . $this->tlv('63', $this->crc16($body . '6304'));
    }

    public function generateQrCodeUrl(string $payload): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($payload);
    }

    private function tlv(string $id, string $value): string
    {
        return $id . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private function sanitize(string $text): string
    {
        return preg_replace('/[^A-Za-z0-9 ]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $text));
    }

    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? ($crc << 1) ^ 0x1021 : $crc << 1;
            }
        }
        return strtoupper(sprintf('%04X', $crc & 0xFFFF));
    }
}
