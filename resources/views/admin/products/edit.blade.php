@extends('admin.layouts.app')
@section('title', 'Editar Produto')

@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-semibold">Editar: {{ $product->name }}</h5>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        {{-- Left column --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Identificação</div>
                <div class="card-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Categoria <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Selecione...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subcategoria</label>
                        <select name="subcategory_id" id="subcategory_id"
                                class="form-select" {{ $subcategories->isEmpty() ? 'disabled' : '' }}>
                            <option value="">Nenhuma</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id', $product->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gênero</label>
                        <select name="gender" class="form-select">
                            <option value="unisex"    {{ old('gender', $product->gender) === 'unisex'    ? 'selected' : '' }}>Unissex</option>
                            <option value="masculine" {{ old('gender', $product->gender) === 'masculine' ? 'selected' : '' }}>Masculino</option>
                            <option value="feminine"  {{ old('gender', $product->gender) === 'feminine'  ? 'selected' : '' }}>Feminino</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time / Seleção</label>
                        <input type="text" name="team" class="form-control"
                               value="{{ old('team', $product->team) }}" placeholder="Ex: Flamengo, Brasil">
                    </div>
                    <div class="col-12">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descrição Curta</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descrição Completa</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Preço & Estoque</div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Preço (R$) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="price" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Preço Promocional</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="sale_price" step="0.01" min="0"
                                   class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Promoção termina em <span class="text-muted small">(contador)</span></label>
                        <input type="datetime-local" name="sale_ends_at" class="form-control"
                               value="{{ old('sale_ends_at', $product->sale_ends_at?->format('Y-m-d\TH:i')) }}">
                        <div class="form-text">Exibe contador regressivo no produto</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estoque <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="stock" min="0"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $product->stock) }}" required>
                            <span class="input-group-text">un.</span>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Ativo</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                   value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Destaque</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_new" id="is_new"
                                   value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_new">Novo</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Tamanhos</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach(['PP','P','M','G','GG','XG','XXG'] as $size)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sizes[]"
                                       id="size_{{ $size }}" value="{{ $size }}"
                                       {{ in_array($size, old('sizes', $product->sizes ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="size_{{ $size }}">{{ $size }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Cores Disponíveis</div>
                <div class="card-body">
                    <div id="colors-container">
                        @php $colors = old('colors', collect($product->colors ?? [])->pluck('name')->toArray()); @endphp
                        @foreach(count($colors) ? $colors : [''] as $color)
                            <div class="input-group mb-2 color-row">
                                <span class="input-group-text"><i class="bi bi-palette"></i></span>
                                <input type="text" name="colors[]" class="form-control"
                                       placeholder="Ex: Vermelho e Preto" value="{{ $color }}">
                                <button type="button" class="btn btn-outline-danger btn-remove-color">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="btn-add-color" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-plus-lg me-1"></i> Adicionar Cor
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Composição do Tecido</div>
                <div class="card-body">
                    <div id="composition-container">
                        @php
                            $compKeys = old('composition_key', array_keys($product->composition ?? []));
                            $compVals = old('composition_value', array_values($product->composition ?? []));
                        @endphp
                        @foreach($compKeys as $i => $key)
                            <div class="row g-2 mb-2 composition-row">
                                <div class="col-5">
                                    <input type="text" name="composition_key[]" class="form-control"
                                           placeholder="Material" value="{{ $key }}">
                                </div>
                                <div class="col-5">
                                    <input type="text" name="composition_value[]" class="form-control"
                                           placeholder="Percentual" value="{{ $compVals[$i] ?? '' }}">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-danger w-100 btn-remove-composition">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="btn-add-composition" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-plus-lg me-1"></i> Adicionar Material
                    </button>
                </div>
            </div>

        </div>

        {{-- Right column --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Imagem Principal</div>
                <div class="card-body">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             class="img-fluid rounded mb-2" style="max-height:180px" alt="Imagem atual">
                        <div class="text-muted small mb-2">Imagem atual. Selecione abaixo para substituir.</div>
                    @endif
                    <input type="file" name="main_image" class="form-control @error('main_image') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text">Máx. 10MB.</div>
                    @error('main_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Galeria de Imagens</div>
                <div class="card-body">
                    @if($product->images && count($product->images))
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                     class="rounded" style="width:60px;height:60px;object-fit:cover" alt="">
                            @endforeach
                        </div>
                        <div class="text-muted small mb-2">Galeria atual. Selecione abaixo para substituir todas.</div>
                    @endif
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <div class="form-text">Máx. 10MB cada.</div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Salvar Alterações
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('category_id').addEventListener('change', function() {
    const catId = this.value;
    const subSel = document.getElementById('subcategory_id');
    subSel.innerHTML = '<option value="">Carregando...</option>';
    subSel.disabled = true;

    if (!catId) {
        subSel.innerHTML = '<option value="">Selecione uma categoria primeiro</option>';
        return;
    }

    fetch(`/admin/api/subcategories?category_id=${catId}`)
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                subSel.innerHTML = '<option value="">Sem subcategorias</option>';
            } else {
                subSel.innerHTML = '<option value="">Nenhuma</option>' +
                    data.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
                subSel.disabled = false;
            }
        });
});

document.getElementById('btn-add-color').addEventListener('click', function() {
    const div = document.createElement('div');
    div.className = 'input-group mb-2 color-row';
    div.innerHTML = `<span class="input-group-text"><i class="bi bi-palette"></i></span>
        <input type="text" name="colors[]" class="form-control" placeholder="Ex: Azul e Dourado">
        <button type="button" class="btn btn-outline-danger btn-remove-color"><i class="bi bi-trash"></i></button>`;
    document.getElementById('colors-container').appendChild(div);
});

document.getElementById('colors-container').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-color')) e.target.closest('.color-row').remove();
});

document.getElementById('btn-add-composition').addEventListener('click', function() {
    const div = document.createElement('div');
    div.className = 'row g-2 mb-2 composition-row';
    div.innerHTML = `
        <div class="col-5"><input type="text" name="composition_key[]" class="form-control" placeholder="Material"></div>
        <div class="col-5"><input type="text" name="composition_value[]" class="form-control" placeholder="Percentual"></div>
        <div class="col-2"><button type="button" class="btn btn-outline-danger w-100 btn-remove-composition"><i class="bi bi-trash"></i></button></div>`;
    document.getElementById('composition-container').appendChild(div);
});

document.getElementById('composition-container').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-composition')) e.target.closest('.composition-row').remove();
});
</script>
@endsection
