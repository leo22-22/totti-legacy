@extends('admin.layouts.app')
@section('title', 'Produtos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-semibold">Produtos</h5>
        <small class="text-muted">{{ $products->total() }} produto(s) encontrado(s)</small>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Novo Produto
    </a>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Buscar por nome, SKU ou time..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Todas as categorias</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos os status</option>
                    <option value="active"       {{ request('status') === 'active'       ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive"     {{ request('status') === 'inactive'     ? 'selected' : '' }}>Inativo</option>
                    <option value="featured"     {{ request('status') === 'featured'     ? 'selected' : '' }}>Destaque</option>
                    <option value="low_stock"    {{ request('status') === 'low_stock'    ? 'selected' : '' }}>Estoque Baixo (≤5)</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Sem Estoque</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-secondary">Filtrar</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px"></th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                 class="rounded" style="width:48px;height:48px;object-fit:cover" alt="">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;color:#adb5bd">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $product->name }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            SKU: {{ $product->sku }}
                            @if($product->team)
                                · 🏆 {{ $product->team }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge text-bg-primary" style="font-size:.72rem">{{ $product->category?->name ?? '—' }}</span>
                        @if($product->subcategory)
                            <div><span class="badge text-bg-secondary" style="font-size:.65rem">{{ $product->subcategory->name }}</span></div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                        @if($product->sale_price)
                            <div class="text-danger" style="font-size:.75rem">
                                Promo: R$ {{ number_format($product->sale_price, 2, ',', '.') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @php
                            $sc = $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger');
                        @endphp
                        <span class="badge text-bg-{{ $sc }}">{{ $product->stock }} un.</span>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            @if($product->is_active)
                                <span class="badge text-bg-success" style="font-size:.7rem">Ativo</span>
                            @else
                                <span class="badge text-bg-secondary" style="font-size:.7rem">Inativo</span>
                            @endif
                            @if($product->is_featured)
                                <span class="badge text-bg-warning" style="font-size:.7rem">Destaque</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                        title="{{ $product->is_active ? 'Desativar' : 'Ativar' }}">
                                    <i class="bi bi-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Excluir produto {{ addslashes($product->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-bag d-block fs-2 mb-2"></i>
                        Nenhum produto encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="card-footer bg-white">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
