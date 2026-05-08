@extends('admin.layouts.app')
@section('title', 'Configurações do Tema')

@php
    function sv($settings, $key, $default = '') {
        return $settings->get($key)?->value ?? $default;
    }
@endphp

@section('styles')
<style>
.settings-tab { cursor: pointer; padding: .5rem 1rem; border-radius: 6px; font-size: .85rem; color: #6b7280; }
.settings-tab:hover, .settings-tab.active { background: #e5e7eb; color: #111827; font-weight: 600; }
.tab-panel { display: none; }
.tab-panel.active { display: block; }
.color-preview { width: 32px; height: 32px; border-radius: 4px; border: 1px solid #dee2e6; display: inline-block; vertical-align: middle; margin-left: .5rem; }
</style>
@endsection

@section('content')
<h5 class="fw-semibold mb-4">Configurações do Tema</h5>

<div class="row g-3">
    {{-- Sidebar tabs --}}
    <div class="col-lg-2">
        <div class="list-group list-group-flush gap-1">
            <button class="settings-tab active" onclick="showTab('tema')">🎨 Tema</button>
            <button class="settings-tab" onclick="showTab('barra')">📢 Barra do Topo</button>
            <button class="settings-tab" onclick="showTab('popup')">🎁 Popup Cupom</button>
            <button class="settings-tab" onclick="showTab('vitrine')">✨ 2ª Vitrine</button>
            <button class="settings-tab" onclick="showTab('site')">🔧 Site</button>
            <button class="settings-tab" onclick="showTab('avancado')">⚙️ Avançado</button>
        </div>
    </div>

    {{-- Content panels --}}
    <div class="col-lg-10">

        {{-- ABA: TEMA --}}
        <div id="tab-tema" class="tab-panel active">
            <form action="{{ route('admin.settings.update', 'theme') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">🎨 Cores & Tipografia</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cor de Destaque (Accent)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="theme_accent_color" class="form-control form-control-color"
                                       value="{{ sv($settings,'theme_accent_color','#FFFFFF') }}" style="width:50px;height:38px">
                                <input type="text" name="theme_accent_color" class="form-control"
                                       value="{{ sv($settings,'theme_accent_color','#FFFFFF') }}"
                                       id="accent_color_text" placeholder="#FFFFFF">
                            </div>
                            <div class="form-text">Cor dos botões, bordas e destaques</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cor de Fundo Escuro</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="theme_dark_color" class="form-control form-control-color"
                                       value="{{ sv($settings,'theme_dark_color','#0D0D0D') }}" style="width:50px;height:38px">
                                <input type="text" name="theme_dark_color" class="form-control"
                                       value="{{ sv($settings,'theme_dark_color','#0D0D0D') }}" placeholder="#0D0D0D">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fonte do Corpo</label>
                            <select name="theme_body_font" class="form-select" id="body_font_sel">
                                @foreach(['Montserrat','Inter','Poppins','Raleway','Nunito Sans'] as $f)
                                    <option value="{{ $f }}" {{ sv($settings,'theme_body_font','Montserrat') === $f ? 'selected' : '' }}>{{ $f }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Textos, preços, menus</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fonte dos Títulos</label>
                            <select name="theme_heading_font" class="form-select">
                                @foreach(['Cormorant Garamond','Playfair Display','Libre Baskerville','Merriweather','Lora'] as $f)
                                    <option value="{{ $f }}" {{ sv($settings,'theme_heading_font','Cormorant Garamond') === $f ? 'selected' : '' }}>{{ $f }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Nomes de produtos, títulos de seções</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">📐 Layout de Imagens</div>
                    <div class="card-body">
                        <label class="form-label fw-semibold">Proporção das Imagens de Produto</label>
                        <div class="d-flex gap-3 flex-wrap mt-2">
                            @foreach(['3/4' => ['Retrato (3:4)','70px','93px'], '1/1' => ['Quadrado (1:1)','80px','80px'], '4/3' => ['Paisagem (4:3)','93px','70px']] as $ratio => [$label, $w, $h])
                            <label class="d-flex flex-column align-items-center gap-2" style="cursor:pointer">
                                <input type="radio" name="theme_image_ratio" value="{{ $ratio }}"
                                       {{ sv($settings,'theme_image_ratio','3/4') === $ratio ? 'checked' : '' }}
                                       class="visually-hidden ratio-radio">
                                <div class="ratio-box border rounded" style="width:{{ $w }};height:{{ $h }};background:#f3f4f6;display:flex;align-items:center;justify-content:center;transition:border .2s">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                <span class="small">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">🖼️ Logo do Rodapé</div>
                    <div class="card-body">
                        @if(sv($settings,'theme_footer_logo'))
                            <img src="{{ asset('storage/' . sv($settings,'theme_footer_logo')) }}"
                                 style="height:60px;margin-bottom:1rem" class="d-block">
                        @endif
                        <input type="file" name="footer_logo_file" class="form-control" accept="image/*">
                        <div class="form-text">Logo exibida no rodapé (diferente da logo do topo). Deixe vazio para usar a mesma do topo.</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Tema</button>
            </form>
        </div>

        {{-- ABA: BARRA DO TOPO --}}
        <div id="tab-barra" class="tab-panel">
            <form action="{{ route('admin.settings.update', 'bar') }}" method="POST">
                @csrf @method('PUT')
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">📢 Barra do Topo</div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="bar_enabled"
                                       id="bar_enabled" value="1" {{ sv($settings,'bar_enabled','1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="bar_enabled">Exibir barra do topo</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Texto da Barra</label>
                            <input type="text" name="bar_text" class="form-control"
                                   value="{{ sv($settings,'bar_text') }}"
                                   placeholder="Frete grátis acima de R$ 299 · 12x sem juros">
                            <div class="form-text">Use · para separar itens (animação automática)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Link da Barra (opcional)</label>
                            <input type="url" name="bar_link" class="form-control"
                                   value="{{ sv($settings,'bar_link') }}" placeholder="https://...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Cor de Fundo</label>
                            <div class="d-flex gap-2">
                                <input type="color" name="bar_bg_color" class="form-control form-control-color"
                                       value="{{ sv($settings,'bar_bg_color','#1A1A1A') }}" style="width:50px;height:38px">
                                <input type="text" name="bar_bg_color" class="form-control"
                                       value="{{ sv($settings,'bar_bg_color','#1A1A1A') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Cor do Texto</label>
                            <div class="d-flex gap-2">
                                <input type="color" name="bar_text_color" class="form-control form-control-color"
                                       value="{{ sv($settings,'bar_text_color','#FFFFFF') }}" style="width:50px;height:38px">
                                <input type="text" name="bar_text_color" class="form-control"
                                       value="{{ sv($settings,'bar_text_color','#FFFFFF') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Barra</button>
            </form>
        </div>

        {{-- ABA: POPUP CUPOM --}}
        <div id="tab-popup" class="tab-panel">
            <form action="{{ route('admin.settings.update', 'popup') }}" method="POST">
                @csrf @method('PUT')
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">🎁 Popup de Cupom</div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="popup_enabled"
                                       id="popup_enabled" value="1" {{ sv($settings,'popup_enabled','0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="popup_enabled">Ativar popup</label>
                            </div>
                            <div class="form-text">O popup aparece uma vez por sessão após o visitante ficar X segundos na página</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Título do Popup</label>
                            <input type="text" name="popup_title" class="form-control"
                                   value="{{ sv($settings,'popup_title','OFERTA ESPECIAL') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Código do Cupom</label>
                            <input type="text" name="popup_coupon" class="form-control"
                                   value="{{ sv($settings,'popup_coupon') }}"
                                   placeholder="Ex: BEMVINDO10" style="text-transform:uppercase"
                                   oninput="this.value=this.value.toUpperCase()">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Texto do Popup</label>
                            <textarea name="popup_text" class="form-control" rows="3">{{ sv($settings,'popup_text') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Atraso para exibir (segundos)</label>
                            <input type="number" name="popup_delay" class="form-control" min="1" max="60"
                                   value="{{ sv($settings,'popup_delay','5') }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Popup</button>
            </form>
        </div>

        {{-- ABA: 2ª VITRINE --}}
        <div id="tab-vitrine" class="tab-panel">
            <form action="{{ route('admin.settings.update', 'showcase') }}" method="POST">
                @csrf @method('PUT')
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">✨ Segunda Vitrine Personalizada</div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="showcase2_enabled"
                                       id="showcase2_enabled" value="1" {{ sv($settings,'showcase2_enabled','0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="showcase2_enabled">Exibir segunda vitrine na home</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" name="showcase2_title" class="form-control"
                                   value="{{ sv($settings,'showcase2_title','Coleção Especial') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subtítulo</label>
                            <input type="text" name="showcase2_subtitle" class="form-control"
                                   value="{{ sv($settings,'showcase2_subtitle') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Filtrar por Categoria (opcional)</label>
                            <select name="showcase2_category_id" class="form-select">
                                <option value="">Todos (produtos em destaque)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ sv($settings,'showcase2_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Quantidade de produtos</label>
                            <input type="number" name="showcase2_limit" class="form-control" min="2" max="12"
                                   value="{{ sv($settings,'showcase2_limit','4') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="showcase2_dark_bg"
                                       id="showcase2_dark_bg" value="1" {{ sv($settings,'showcase2_dark_bg','1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="showcase2_dark_bg">Fundo escuro</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Vitrine</button>
            </form>
        </div>

        {{-- ABA: SITE --}}
        <div id="tab-site" class="tab-panel">
            <form action="{{ route('admin.settings.update', 'site') }}" method="POST">
                @csrf @method('PUT')
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">🔧 Modo Manutenção</div>
                    <div class="card-body row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="site_maintenance"
                                       id="site_maintenance" value="1" {{ sv($settings,'site_maintenance','0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="site_maintenance">
                                    Ativar modo manutenção
                                </label>
                            </div>
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                Quando ativo, visitantes verão a página de manutenção. Admins continuam com acesso normal.
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Mensagem de Manutenção</label>
                            <textarea name="site_maintenance_msg" class="form-control" rows="3">{{ sv($settings,'site_maintenance_msg') }}</textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>

        {{-- ABA: AVANÇADO --}}
        <div id="tab-avancado" class="tab-panel">
            <form action="{{ route('admin.settings.update', 'theme') }}" method="POST">
                @csrf @method('PUT')
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold py-3">⚙️ CSS Adicional</div>
                    <div class="card-body">
                        <label class="form-label">Cole aqui seu CSS personalizado</label>
                        <textarea name="theme_custom_css" class="form-control" rows="14"
                                  style="font-family:monospace;font-size:.82rem"
                                  placeholder="/* Exemplo: */&#10;.product-card { border-radius: 12px; }">{{ sv($settings,'theme_custom_css') }}</textarea>
                        <div class="form-text">Este CSS é injetado em todas as páginas do site, após os estilos padrão.</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar CSS</button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(name) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    event.currentTarget.classList.add('active');
}

// Highlight selected image ratio
document.querySelectorAll('.ratio-radio').forEach(r => {
    const updateBox = () => {
        document.querySelectorAll('.ratio-box').forEach(b => b.style.borderColor = '#dee2e6');
        if (r.checked) r.closest('label').querySelector('.ratio-box').style.borderColor = '#6366f1';
    };
    r.addEventListener('change', updateBox);
    if (r.checked) r.closest('label').querySelector('.ratio-box').style.borderColor = '#6366f1';
});

// Sync color pickers with text inputs
document.querySelectorAll('input[type="color"]').forEach(picker => {
    const name = picker.name;
    const textInputs = document.querySelectorAll(`input[type="text"][name="${name}"]`);
    picker.addEventListener('input', () => textInputs.forEach(i => i.value = picker.value));
    textInputs.forEach(t => t.addEventListener('input', () => picker.value = t.value));
});
</script>
@endsection
