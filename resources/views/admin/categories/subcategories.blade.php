@extends('admin.layouts.app')
@section('title', 'Subcategorias de ' . $category->name)

@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-semibold">Subcategorias de "{{ $category->name }}"</h5>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">Adicionar Subcategoria</div>
            <div class="card-body">
                <form action="{{ route('admin.categories.subcategories.store', $category) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required placeholder="Ex: Seleções Brasileiras">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Ativa</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Adicionar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold py-3">
                Subcategorias ({{ $subcategories->count() }})
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Produtos</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcategories as $sub)
                        <tr>
                            <td class="fw-semibold">{{ $sub->name }}</td>
                            <td><span class="badge text-bg-primary">{{ $sub->products_count }}</span></td>
                            <td>
                                @if($sub->is_active)
                                    <span class="badge text-bg-success">Ativa</span>
                                @else
                                    <span class="badge text-bg-secondary">Inativa</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.subcategories.destroy', $sub) }}" method="POST"
                                      onsubmit="return confirm('Excluir subcategoria {{ addslashes($sub->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Nenhuma subcategoria ainda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
