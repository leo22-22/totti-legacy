@extends('admin.layouts.app')
@section('title', 'Novo Cupom')

@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-semibold">Novo Cupom</h5>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Identificação</div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Código <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code') }}" placeholder="Ex: TOTTI10"
                               oninput="this.value=this.value.toUpperCase()" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Descrição Interna</label>
                        <input type="text" name="description" class="form-control"
                               value="{{ old('description') }}" placeholder="Ex: Cupom de boas-vindas">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Tipo & Valor do Desconto</div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>% Porcentagem</option>
                            <option value="fixed"      {{ old('type') === 'fixed'      ? 'selected' : '' }}>R$ Valor Fixo</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Valor <span class="text-danger">*</span></label>
                        <input type="number" name="value" step="0.01" min="0"
                               class="form-control @error('value') is-invalid @enderror"
                               value="{{ old('value') }}" placeholder="10 ou 20.00" required>
                        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pedido Mínimo (R$)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="minimum_order" step="0.01" min="0"
                                   class="form-control" value="{{ old('minimum_order') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Desconto Máximo (R$) <span class="text-muted small">para porcentagem</span></label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="maximum_discount" step="0.01" min="0"
                                   class="form-control" value="{{ old('maximum_discount') }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="free_shipping"
                                   id="free_shipping" value="1" {{ old('free_shipping') ? 'checked' : '' }}>
                            <label class="form-check-label" for="free_shipping">Incluir Frete Grátis</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Limites de Uso</div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Limite Total de Usos</label>
                        <input type="number" name="usage_limit" min="1"
                               class="form-control" value="{{ old('usage_limit') }}"
                               placeholder="Deixe vazio para ilimitado">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usos por Usuário</label>
                        <input type="number" name="usage_limit_per_user" min="1"
                               class="form-control" value="{{ old('usage_limit_per_user', 1) }}">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold py-3">Validade</div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Válido a partir de</label>
                        <input type="datetime-local" name="starts_at" class="form-control"
                               value="{{ old('starts_at') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Expira em</label>
                        <input type="datetime-local" name="expires_at" class="form-control"
                               value="{{ old('expires_at') }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Cupom Ativo</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Criar Cupom
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
