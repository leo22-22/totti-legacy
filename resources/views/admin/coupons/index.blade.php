@extends('admin.layouts.app')
@section('title', 'Cupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">Cupons de Desconto</h5>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Novo Cupom
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Desconto</th>
                    <th>Mínimo</th>
                    <th>Usos</th>
                    <th>Validade</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <span class="badge text-bg-primary fs-7 fw-bold">{{ $coupon->code }}</span>
                        @if($coupon->free_shipping)
                            <div class="text-muted small">+ Frete grátis</div>
                        @endif
                    </td>
                    <td>
                        @if($coupon->type === 'percentage')
                            <span class="badge text-bg-info">% Porcentagem</span>
                        @else
                            <span class="badge text-bg-success">R$ Fixo</span>
                        @endif
                    </td>
                    <td class="fw-semibold">
                        @if($coupon->type === 'percentage')
                            {{ $coupon->value }}%
                        @else
                            R$ {{ number_format($coupon->value, 2, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-muted">
                        @if($coupon->minimum_order)
                            R$ {{ number_format($coupon->minimum_order, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @php
                            $over = $coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit;
                        @endphp
                        <span class="badge text-bg-{{ $over ? 'danger' : 'secondary' }}">
                            {{ $coupon->times_used }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                        </span>
                    </td>
                    <td class="text-muted small">
                        @if($coupon->expires_at)
                            {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                            @if($coupon->expires_at < now())
                                <span class="badge text-bg-danger ms-1">Expirado</span>
                            @endif
                        @else
                            Sem expiração
                        @endif
                    </td>
                    <td>
                        @if($coupon->is_active)
                            <span class="badge text-bg-success">Ativo</span>
                        @else
                            <span class="badge text-bg-secondary">Inativo</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Excluir cupom {{ $coupon->code }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-ticket-perforated d-block fs-2 mb-2"></i>
                        Nenhum cupom cadastrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
        <div class="card-footer bg-white">{{ $coupons->links() }}</div>
    @endif
</div>
@endsection
