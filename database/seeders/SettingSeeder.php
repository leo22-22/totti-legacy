<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Theme
            ['key' => 'theme_accent_color',    'value' => '#FFFFFF',              'group' => 'theme'],
            ['key' => 'theme_accent_hover',    'value' => '#E8E8E8',              'group' => 'theme'],
            ['key' => 'theme_dark_color',      'value' => '#0D0D0D',              'group' => 'theme'],
            ['key' => 'theme_body_font',       'value' => 'Montserrat',           'group' => 'theme'],
            ['key' => 'theme_heading_font',    'value' => 'Cormorant Garamond',   'group' => 'theme'],
            ['key' => 'theme_image_ratio',     'value' => '3/4',                  'group' => 'theme'],
            ['key' => 'theme_custom_css',      'value' => '',                     'group' => 'theme'],
            ['key' => 'theme_footer_logo',     'value' => '',                     'group' => 'theme'],

            // Site
            ['key' => 'site_maintenance',      'value' => '0',                    'group' => 'site'],
            ['key' => 'site_maintenance_msg',  'value' => 'Voltamos em breve. Estamos preparando novidades para você!', 'group' => 'site'],

            // Announcement bar
            ['key' => 'bar_enabled',           'value' => '1',                    'group' => 'bar'],
            ['key' => 'bar_text',              'value' => 'Frete grátis acima de R$ 299 · 12x sem juros · Trocas em até 30 dias · 5% no Pix', 'group' => 'bar'],
            ['key' => 'bar_link',              'value' => '',                     'group' => 'bar'],
            ['key' => 'bar_bg_color',          'value' => '#1A1A1A',              'group' => 'bar'],
            ['key' => 'bar_text_color',        'value' => '#FFFFFF',              'group' => 'bar'],

            // Coupon popup
            ['key' => 'popup_enabled',         'value' => '0',                    'group' => 'popup'],
            ['key' => 'popup_title',           'value' => 'OFERTA ESPECIAL',      'group' => 'popup'],
            ['key' => 'popup_text',            'value' => 'Use o cupom abaixo e ganhe desconto na sua primeira compra!', 'group' => 'popup'],
            ['key' => 'popup_coupon',          'value' => '',                     'group' => 'popup'],
            ['key' => 'popup_delay',           'value' => '5',                    'group' => 'popup'],

            // Second showcase
            ['key' => 'showcase2_enabled',     'value' => '0',                    'group' => 'showcase'],
            ['key' => 'showcase2_title',       'value' => 'Coleção Especial',     'group' => 'showcase'],
            ['key' => 'showcase2_subtitle',    'value' => 'Escolhidos especialmente para você', 'group' => 'showcase'],
            ['key' => 'showcase2_category_id', 'value' => '',                     'group' => 'showcase'],
            ['key' => 'showcase2_limit',       'value' => '4',                    'group' => 'showcase'],
            ['key' => 'showcase2_dark_bg',     'value' => '1',                    'group' => 'showcase'],
        ];

        foreach ($defaults as $row) {
            Setting::firstOrCreate(['key' => $row['key']], $row);
        }
    }
}
