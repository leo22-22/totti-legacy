@extends('admin.layouts.app')
@section('title', 'Categorias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">Categorias</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nova Categoria
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px"></th>
                    <th>Categoria</th>
                    <th>Subcategorias</th>
                    <th>Produtos</th>
                    <th>Ordem</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 class="rounded" style="width:44px;height:44px;object-fit:cover">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                 style="width:44px;height:44px;color:#adb5bd">
                                <i class="bi bi-tag"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('admin.categories.subcategories', $category) }}"
                           class="badge text-bg-info text-decoration-none">
                            {{ $category->children_count }} subcategoria(s)
                        </a>
                    </td>
                    <td><span class="badge text-bg-primary">{{ $category->products_count }} produto(s)</span></td>
                    <td class="text-muted">{{ $category->sort_order }}</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge text-bg-success">Ativa</span>
                        @else
                            <span class="badge text-bg-secondary">Inativa</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir categoria {{ addslashes($category->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-tag d-block fs-2 mb-2"></i>
                        Nenhuma categoria cadastrada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
