<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingSeeder::class);

        // ── Admin ──────────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin Totti Legacy',
            'email'    => 'admin@tottilegacy.com.br',
            'password' => Hash::make('totti2025@admin'),
            'is_admin' => true,
        ]);

        // ── Imagens reais: copia public/images → storage/app/public/products ──
        $this->copyImages([
            'corinthians.png'        => 'corinthians-titular.png',
            'corinthians costas.png' => 'corinthians-costas.png',
            'palmeiras.png'          => 'palmeiras-titular.png',
            'Palmeiras costas.png'   => 'palmeiras-costas.png',
            'santos bmg .png'        => 'santos-bmg.png',
            'santos costas bmg.png'  => 'santos-bmg-costas.png',
            'santos unicor.png'      => 'santos-unicor.png',
            'santos unicor costas.png' => 'santos-unicor-costas.png',
        ]);

        // ── Categorias raiz ────────────────────────────────────────────────────
        $cats = [];
        foreach ([
            ['name' => 'Times do Brasil',  'slug' => 'times-brasil',   'description' => 'Camisas dos maiores clubes brasileiros.', 'sort_order' => 1],
            ['name' => 'Times Europeus',   'slug' => 'times-europeus', 'description' => 'Os gigantes do futebol europeu.',          'sort_order' => 2],
            ['name' => 'Seleções',         'slug' => 'selecoes',       'description' => 'Camisas das principais seleções do mundo.','sort_order' => 3],
            ['name' => 'Retrô',            'slug' => 'retro',          'description' => 'Clássicos atemporais que marcaram época.', 'sort_order' => 4],
        ] as $cat) {
            $cats[$cat['slug']] = Category::create(array_merge($cat, ['is_active' => true]))->id;
        }

        // ── Subcategorias de Times do Brasil ───────────────────────────────────
        $subs = [];
        foreach ([
            ['name' => 'Corinthians', 'slug' => 'corinthians', 'sort_order' => 1],
            ['name' => 'Palmeiras',   'slug' => 'palmeiras',   'sort_order' => 2],
            ['name' => 'Santos',      'slug' => 'santos',      'sort_order' => 3],
            ['name' => 'Flamengo',    'slug' => 'flamengo',    'sort_order' => 4],
            ['name' => 'São Paulo',   'slug' => 'sao-paulo',   'sort_order' => 5],
        ] as $sub) {
            $subs[$sub['slug']] = Category::create([
                'name'       => $sub['name'],
                'slug'       => $sub['slug'],
                'parent_id'  => $cats['times-brasil'],
                'sort_order' => $sub['sort_order'],
                'is_active'  => true,
            ])->id;
        }

        // ── Produtos ───────────────────────────────────────────────────────────
        foreach ([

            // ── Corinthians 2006 Reserva #10 Carlitos — Nike (foto real) ──────────
            [
                'name'              => 'Corinthians 2006 — Reserva #10 Carlitos | Nike',
                'team'              => 'Corinthians',
                'slug'              => 'corinthians-2006-reserva-carlitos',
                'category_id'       => $cats['retro'],
                'subcategory_id'    => $subs['corinthians'],
                'price'             => 2999.00,
                'short_description' => 'Camisa reserva do Corinthians 2006, número 10 CARLITOS. Fornecida pela Nike. Excelente condição (9,5/10) — algumas bolinhas no tecido. Tamanho G.',
                'description'       => "Camisa reserva do Corinthians temporada 2006, número 10 do meia CARLITOS.\n\nFornecedor: Nike\nTamanho: G\nCondição: Excelente (9,5/10)\n\nDetalhes: Algumas bolinhas no tecido (conforme fotos). Peça rara do acervo corintiano do início dos anos 2000.\n\nFrete grátis para todo o Brasil.",
                'main_image'        => 'products/corinthians-titular.png',
                'images'            => ['products/corinthians-titular.png', 'products/corinthians-costas.png'],
                'sizes'             => ['G'],
                'colors'            => [['name' => 'Preto']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 1, 'gender' => 'unisex',
            ],

            // ── Palmeiras 1996 Titular #10 — Reebok Patch Paulista (foto real) ─
            [
                'name'              => 'Palmeiras 1996 — Titular #10 | Reebok + Patch Campeão Paulista',
                'team'              => 'Palmeiras',
                'slug'              => 'palmeiras-1996-titular-reebok-paulista',
                'category_id'       => $cats['retro'],
                'subcategory_id'    => $subs['palmeiras'],
                'price'             => 899.00,
                'short_description' => 'Camisa titular do Palmeiras 1996, número 10. Reebok com Patch campeão Paulista 1996. Ótima condição (8,5/10) — alguns fios puxados e poucas bolinhas. Tamanho G.',
                'description'       => "Camisa titular do Palmeiras temporada 1996, número 10.\n\nFornecedor: Reebok\nPatch: Campeão Paulista 1996\nTamanho: G\nCondição: Ótima (8,5/10)\n\nDetalhes: Alguns fios puxados no tecido (conforme fotos) e poucas bolinhas. Costuras em perfeitas condições.\n\nFrete a combinar.",
                'main_image'        => 'products/palmeiras-titular.png',
                'images'            => ['products/palmeiras-titular.png', 'products/palmeiras-costas.png'],
                'sizes'             => ['G'],
                'colors'            => [['name' => 'Verde/Branco']],
                'composition'       => ['Algodão' => '70%', 'Poliéster' => '30%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 1, 'gender' => 'unisex',
            ],

            // ── Santos 1994 Titular #10 Giovanni — Amddma RARIDADE (foto real) ─
            [
                'name'              => 'Santos 1994 — Titular #10 Giovanni | Amddma ✨ RARIDADE',
                'team'              => 'Santos',
                'slug'              => 'santos-1994-titular-giovanni-amddma',
                'category_id'       => $cats['retro'],
                'subcategory_id'    => $subs['santos'],
                'price'             => 2499.00,
                'short_description' => '✨ RARIDADE ✨ Camisa DE JOGO utilizada por Giovanni em 1994. Amddma titular. Excelente condição (9,0/10) — sem fios puxados ou descosturas. Tamanho G.',
                'description'       => "✨ RARIDADE ✨\n\nCamisa titular do Santos temporada 1994, número 10. Camisa DE JOGO utilizada por GIOVANNI em 1994.\n\nFornecedor: Amddma\nTamanho: G\nCondição: Excelente (9,0/10)\n\nDetalhes: Tecido em excelente condição. Sem fios puxados ou descosturas. Peça de colecionador de altíssimo valor histórico.\n\nFrete a combinar.",
                'main_image'        => 'products/santos-unicor.png',
                'images'            => ['products/santos-unicor.png', 'products/santos-unicor-costas.png'],
                'sizes'             => ['G'],
                'colors'            => [['name' => 'Branco/Preto']],
                'composition'       => ['Algodão' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 1, 'gender' => 'unisex',
            ],

            // ── Santos 2011 Titular #10 Autografada — Umbro (foto real) ────────
            [
                'name'              => 'Santos 2011 — Titular #10 Autografada | Umbro | Neymar, Ganso e mais',
                'team'              => 'Santos',
                'slug'              => 'santos-2011-titular-autografada-neymar',
                'category_id'       => $cats['retro'],
                'subcategory_id'    => $subs['santos'],
                'price'             => 4999.00,
                'short_description' => 'Camisa de jogo autografada por NEYMAR, GANSO, LÉO, ELANO, RAFAEL CABRAL e mais. Umbro 2011. Espetacular condição (9,5/10). Tamanho G.',
                'description'       => "Camisa titular do Santos temporada 2011, número 10. Modelo Paulistão e Campeonato Brasileiro.\n\nFornecedor: Umbro\nTamanho: G\nCondição: Espetacular (9,5/10)\n\nAutografada pelos ídolos:\n• NEYMAR\n• GANSO\n• LÉO\n• ELANO\n• RAFAEL CABRAL\n• e outros jogadores do elenco\n\nDetalhes: Pequeno furo na barra das costas (conforme foto). Peça única e de valor histórico incalculável.\n\nFrete grátis para todo o Brasil.",
                'main_image'        => 'products/santos-bmg.png',
                'images'            => ['products/santos-bmg.png', 'products/santos-bmg-costas.png'],
                'sizes'             => ['G'],
                'colors'            => [['name' => 'Branco/Preto']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 1, 'gender' => 'unisex',
            ],

            // ── Flamengo ───────────────────────────────────────────────────────
            [
                'name'              => 'Flamengo 2024 — Titular',
                'team'              => 'Flamengo',
                'slug'              => 'flamengo-2024-titular',
                'category_id'       => $cats['times-brasil'],
                'subcategory_id'    => $subs['flamengo'],
                'price'             => 199.90, 'sale_price' => 169.90,
                'short_description' => 'A camisa mais amada do Brasil. Rubro-negra com acabamento premium.',
                'sizes'             => ['PP', 'P', 'M', 'G', 'GG', 'XG'],
                'colors'            => [['name' => 'Preto/Vermelho']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 60, 'gender' => 'unisex',
            ],
            [
                'name'              => 'Flamengo 2023 — Reserva',
                'team'              => 'Flamengo',
                'slug'              => 'flamengo-2023-reserva',
                'category_id'       => $cats['times-brasil'],
                'subcategory_id'    => $subs['flamengo'],
                'price'             => 179.90,
                'short_description' => 'Camisa reserva branca do Mengão, temporada 2023.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Branco/Vermelho']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => false, 'is_new' => false,
                'stock'             => 25, 'gender' => 'unisex',
            ],

            // ── São Paulo ──────────────────────────────────────────────────────
            [
                'name'              => 'São Paulo FC 2024',
                'team'              => 'São Paulo',
                'slug'              => 'sao-paulo-fc-2024',
                'category_id'       => $cats['times-brasil'],
                'subcategory_id'    => $subs['sao-paulo'],
                'price'             => 189.90,
                'short_description' => 'Tricolor paulista. Tradição e elegância em cada detalhe.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Branco/Vermelho/Preto']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => false, 'is_new' => false,
                'stock'             => 35, 'gender' => 'unisex',
            ],

            // ── Times Europeus ─────────────────────────────────────────────────
            [
                'name'              => 'AS Roma 2024 — Titular',
                'team'              => 'AS Roma',
                'slug'              => 'as-roma-2024-titular',
                'category_id'       => $cats['times-europeus'],
                'price'             => 229.90, 'sale_price' => 199.90,
                'short_description' => 'Homenagem ao eterno Capitão. A camisa da Loba de Roma.',
                'sizes'             => ['PP', 'P', 'M', 'G', 'GG', 'XG'],
                'colors'            => [['name' => 'Grená']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 40, 'gender' => 'masculine',
            ],
            [
                'name'              => 'Real Madrid 2024 — Titular',
                'team'              => 'Real Madrid',
                'slug'              => 'real-madrid-2024-titular',
                'category_id'       => $cats['times-europeus'],
                'price'             => 219.90,
                'short_description' => 'O maior clube do mundo. Branca e imponente.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Branco']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 50, 'gender' => 'unisex',
            ],
            [
                'name'              => 'Manchester City 2024',
                'team'              => 'Manchester City',
                'slug'              => 'manchester-city-2024',
                'category_id'       => $cats['times-europeus'],
                'price'             => 209.90,
                'short_description' => 'O azul celeste dos campeões da Premier League.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Azul Celeste']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => false, 'is_new' => true,
                'stock'             => 30, 'gender' => 'unisex',
            ],

            // ── Seleções ───────────────────────────────────────────────────────
            [
                'name'              => 'Brasil 2024 — Titular',
                'team'              => 'Brasil',
                'slug'              => 'brasil-2024-titular',
                'category_id'       => $cats['selecoes'],
                'price'             => 219.90,
                'short_description' => 'A camisa mais famosa do mundo. Amarela, verde e eterna.',
                'sizes'             => ['PP', 'P', 'M', 'G', 'GG', 'XG'],
                'colors'            => [['name' => 'Amarelo']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => true,
                'stock'             => 80, 'gender' => 'unisex',
            ],
            [
                'name'              => 'Argentina 2024 — Titular',
                'team'              => 'Argentina',
                'slug'              => 'argentina-2024-titular',
                'category_id'       => $cats['selecoes'],
                'price'             => 219.90,
                'short_description' => 'A camisa dos campeões do mundo. Azul e branca, eterna.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Azul/Branco']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => false, 'is_new' => true,
                'stock'             => 55, 'gender' => 'unisex',
            ],
            [
                'name'              => 'Itália 2024 — Titular',
                'team'              => 'Itália',
                'slug'              => 'italia-2024-titular',
                'category_id'       => $cats['selecoes'],
                'price'             => 209.90,
                'short_description' => 'Azzurra. O azul mais elegante do futebol mundial.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Azul']],
                'composition'       => ['Poliéster' => '100%'],
                'is_active'         => true, 'is_featured' => false, 'is_new' => false,
                'stock'             => 25, 'gender' => 'unisex',
            ],

            // ── Retrô ──────────────────────────────────────────────────────────
            [
                'name'              => 'Brasil 1970 — Retrô',
                'team'              => 'Brasil',
                'slug'              => 'brasil-1970-retro',
                'category_id'       => $cats['retro'],
                'price'             => 249.90,
                'short_description' => 'A camisa da Copa do México. Pelé, Tostão e o futebol arte.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Amarelo']],
                'composition'       => ['Algodão' => '80%', 'Poliéster' => '20%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => false,
                'stock'             => 20, 'gender' => 'unisex',
            ],
            [
                'name'              => 'AS Roma 2000/01 — Retrô Scudetto',
                'team'              => 'AS Roma',
                'slug'              => 'as-roma-2000-retro-scudetto',
                'category_id'       => $cats['retro'],
                'price'             => 279.90,
                'short_description' => 'A camisa do título. Totti, ano 2001, o Scudetto eterno.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Grená']],
                'composition'       => ['Algodão' => '80%', 'Poliéster' => '20%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => false,
                'stock'             => 15, 'gender' => 'unisex',
            ],
            [
                'name'              => 'Argentina 1986 — Retrô Maradona',
                'team'              => 'Argentina',
                'slug'              => 'argentina-1986-retro-maradona',
                'category_id'       => $cats['retro'],
                'price'             => 259.90,
                'short_description' => 'A mão de Deus. A camisa mais icônica da história do futebol.',
                'sizes'             => ['P', 'M', 'G', 'GG'],
                'colors'            => [['name' => 'Azul/Branco']],
                'composition'       => ['Algodão' => '80%', 'Poliéster' => '20%'],
                'is_active'         => true, 'is_featured' => true, 'is_new' => false,
                'stock'             => 18, 'gender' => 'unisex',
            ],

        ] as $product) {
            Product::create($product);
        }

        // ── Cupons ────────────────────────────────────────────────────────────
        foreach ([
            ['code' => 'TOTTI10',    'description' => '10% de desconto',  'type' => 'percentage', 'value' => 10, 'usage_limit' => 100],
            ['code' => 'BEMVINDO',   'description' => 'R$20 de desconto', 'type' => 'fixed',      'value' => 20, 'minimum_order' => 100],
            ['code' => 'FRETELIVRE', 'description' => 'Frete grátis',     'type' => 'percentage', 'value' => 0,  'free_shipping' => true],
            ['code' => 'LEGACY20',   'description' => '20% de desconto',  'type' => 'percentage', 'value' => 20, 'minimum_order' => 200, 'maximum_discount' => 80, 'expires_at' => now()->addMonths(3)],
        ] as $coupon) {
            Coupon::create([...$coupon, 'is_active' => true]);
        }
    }

    private function copyImages(array $map): void
    {
        $destDir = storage_path('app/public/products');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        foreach ($map as $source => $dest) {
            $src = public_path('images/' . $source);
            if (file_exists($src)) {
                copy($src, $destDir . '/' . $dest);
            }
        }
    }
}
