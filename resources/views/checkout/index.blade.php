@extends('layouts.app')
@section('title', 'Checkout — Totti Legacy')

@section('content')
<section style="max-width: 1200px; margin: 0 auto; padding: 4rem 2rem;">
    <h1 class="font-serif" style="font-size: 2.5rem; font-weight: 400; margin-bottom: 3rem;">Finalizar Compra</h1>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 4rem; align-items: start;">

        <!-- CHECKOUT FORM -->
        <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
            @csrf

            <!-- Personal Info -->
            <div style="margin-bottom: 2.5rem;">
                <h2 class="section-title"><span class="step-num">1</span> Dados Pessoais</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="grid-column: span 2;">
                        <label class="form-label">Nome Completo *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()?->name) }}" required>
                    </div>
                    <div>
                        <label class="form-label">E-mail *</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()?->email) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Telefone / WhatsApp *</label>
                        <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="(11) 99999-9999" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="form-label">CPF *</label>
                        <input type="text" name="cpf" class="form-input" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14" required>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div style="margin-bottom: 2.5rem;">
                <h2 class="section-title"><span class="step-num">2</span> Endereço de Entrega</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="form-label">CEP *</label>
                        <input type="text" name="cep" id="cep" class="form-input" value="{{ old('cep') }}" placeholder="00000-000" maxlength="9" required>
                    </div>
                    <div>
                        <label class="form-label">Estado *</label>
                        <input type="text" name="state" id="state" class="form-input" value="{{ old('state') }}" maxlength="2" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="form-label">Endereço *</label>
                        <input type="text" name="address" id="address" class="form-input" value="{{ old('address') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Número *</label>
                        <input type="text" name="number" class="form-input" value="{{ old('number') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Complemento</label>
                        <input type="text" name="complement" class="form-input" value="{{ old('complement') }}">
                    </div>
                    <div>
                        <label class="form-label">Bairro *</label>
                        <input type="text" name="neighborhood" id="neighborhood" class="form-input" value="{{ old('neighborhood') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Cidade *</label>
                        <input type="text" name="city" id="city" class="form-input" value="{{ old('city') }}" required>
                    </div>
                </div>
            </div>

            <!-- Freight -->
            <div style="margin-bottom: 2.5rem;">
                <h2 class="section-title"><span class="step-num">3</span> Frete</h2>

                <div id="freightLoading" style="display: none; color: var(--gray); font-size: 0.85rem; padding: 1rem 0;">
                    <i class="fas fa-spinner fa-spin"></i> Calculando frete...
                </div>

                <div id="freightOptions" style="display: flex; flex-direction: column; gap: 0.8rem;">
                    @if(old('shipping_option'))
                    {{-- keep selection on validation error --}}
                    @endif
                    <p id="freightHint" style="color: var(--gray); font-size: 0.85rem;">Preencha o CEP para ver as opções de entrega.</p>
                </div>

                <input type="hidden" name="shipping_option" id="shippingOptionInput" value="{{ old('shipping_option') }}" required>
            </div>

            <!-- Payment Method -->
            <div style="margin-bottom: 2.5rem;">
                <h2 class="section-title"><span class="step-num">4</span> Forma de Pagamento</h2>

                <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                    <label class="payment-opt" for="pay_stripe">
                        <input type="radio" id="pay_stripe" name="payment_method" value="stripe" {{ old('payment_method') === 'stripe' ? 'checked' : '' }} required>
                        <span style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                            <span style="display: flex; gap: 0.3rem;">
                                <i class="fab fa-cc-visa" style="font-size: 1.8rem; color: #1a1f71;"></i>
                                <i class="fab fa-cc-mastercard" style="font-size: 1.8rem; color: #eb001b;"></i>
                                <i class="fab fa-cc-amex" style="font-size: 1.8rem; color: #2e77bc;"></i>
                            </span>
                            <span>
                                <strong style="display: block; font-size: 0.85rem;">Cartão de Crédito / Débito</strong>
                                <small style="color: var(--gray);">Via Stripe — seguro e rápido</small>
                            </span>
                        </span>
                    </label>

                    <label class="payment-opt" for="pay_mp">
                        <input type="radio" id="pay_mp" name="payment_method" value="mercadopago" {{ old('payment_method') === 'mercadopago' ? 'checked' : '' }} required>
                        <span style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                            <span style="background: #009ee3; color: white; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 700; border-radius: 3px;">MP</span>
                            <span>
                                <strong style="display: block; font-size: 0.85rem;">MercadoPago</strong>
                                <small style="color: var(--gray);">Cartão, boleto, Pix ou saldo MP</small>
                            </span>
                        </span>
                    </label>

                    <label class="payment-opt" for="pay_pix">
                        <input type="radio" id="pay_pix" name="payment_method" value="pix" {{ old('payment_method') === 'pix' ? 'checked' : '' }} required>
                        <span style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                            <i class="fab fa-pix" style="font-size: 1.8rem; color: #32bcad;"></i>
                            <span>
                                <strong style="display: block; font-size: 0.85rem;">PIX</strong>
                                <small style="color: #27ae60; font-weight: 600;">Aprovação imediata</small>
                            </span>
                        </span>
                    </label>
                </div>
            </div>

            @if($errors->any())
            <div style="background: #4a1515; color: #fca5a5; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
                @foreach($errors->all() as $error)
                <p><i class="fas fa-times"></i> {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <button type="submit" class="btn btn-gold btn-full" style="padding: 1.1rem; font-size: 0.8rem;">
                <i class="fas fa-lock"></i> Confirmar Pedido e Pagar
            </button>
        </form>

        <!-- ORDER SUMMARY -->
        <div style="position: sticky; top: 90px;">
            <!-- Coupon -->
            <div style="background: var(--light); padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 700; margin-bottom: 1rem;">Cupom de Desconto</h3>
                @php
                    $appliedCoupon = session('coupon') ? \App\Models\Coupon::find(session('coupon')) : null;
                @endphp
                @if($appliedCoupon)
                <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(39,174,96,0.1); border: 1px solid #27ae60; padding: 0.8rem; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.85rem; color: #27ae60; font-weight: 600;"><i class="fas fa-ticket-alt"></i> {{ $appliedCoupon->code }}</span>
                    <form method="POST" action="{{ route('checkout.coupon.remove') }}">@csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: var(--gray); cursor: pointer; font-size: 0.75rem;">Remover</button>
                    </form>
                </div>
                @else
                <form method="POST" action="{{ route('checkout.coupon.apply') }}" style="display: flex; gap: 0.5rem;">
                    @csrf
                    <input type="text" name="code" placeholder="CÓDIGO DO CUPOM" style="flex: 1; border: 1px solid rgba(0,0,0,0.15); padding: 0.7rem; font-family: 'Montserrat'; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em;">
                    <button type="submit" class="btn btn-outline-dark" style="padding: 0.7rem 1rem; font-size: 0.7rem;">Aplicar</button>
                </form>
                @endif
            </div>

            <!-- Summary -->
            <div style="background: var(--light); padding: 1.5rem;">
                <h3 style="font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.5rem;">Resumo</h3>

                @foreach($cart as $item)
                <div style="display: flex; gap: 0.8rem; margin-bottom: 1rem; align-items: center;">
                    <div style="width: 50px; height: 60px; flex-shrink: 0; overflow: hidden; background: rgba(0,0,0,0.05);">
                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/placeholder.jpg') }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="flex: 1; font-size: 0.8rem;">
                        <p style="font-weight: 600;">{{ $item['name'] }}</p>
                        <p style="color: var(--gray);">{{ $item['size'] }} • {{ $item['color'] }} • {{ $item['quantity'] }}x</p>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 700;">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</span>
                </div>
                @endforeach

                @php
                    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
                    $discount = $appliedCoupon ? $appliedCoupon->calculateDiscount($subtotal) : 0;
                    $freeShipping = $subtotal >= 299 || $appliedCoupon && $appliedCoupon->free_shipping;
                @endphp

                <div style="border-top: 1px solid rgba(0,0,0,0.1); padding-top: 1rem; margin-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.5rem;">
                        <span style="color: var(--gray);">Subtotal</span>
                        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    @if($discount > 0)
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.5rem;">
                        <span style="color: #27ae60;">Desconto</span>
                        <span style="color: #27ae60;">-R$ {{ number_format($discount, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 1rem;">
                        <span style="color: var(--gray);">Frete</span>
                        <span id="summaryShipping" style="color: var(--gray);">
                            @if($freeShipping)
                                <span style="color: #27ae60;">Grátis</span>
                            @else
                                Selecione acima
                            @endif
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700; border-top: 1px solid rgba(0,0,0,0.1); padding-top: 1rem;">
                        <span>Total</span>
                        <span id="summaryTotal">R$ {{ number_format(max(0, $subtotal - $discount + ($freeShipping ? 0 : 0)), 2, ',', '.') }}</span>
                    </div>
                    <p id="summaryNote" style="font-size: 0.72rem; color: var(--gray); margin-top: 0.4rem; {{ $freeShipping ? '' : 'display:none' }}">
                        @if($freeShipping) + frete grátis @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.section-title { font-size: 0.8rem; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.8rem; }
.step-num { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: var(--black); color: var(--white); font-size: 0.7rem; border-radius: 50%; flex-shrink: 0; }
.form-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; display: block; margin-bottom: 0.4rem; }
.form-input { width: 100%; border: 1px solid rgba(0,0,0,0.15); padding: 0.75rem 1rem; font-family: 'Montserrat', sans-serif; font-size: 0.9rem; transition: border-color 0.2s; }
.form-input:focus { outline: none; border-color: var(--black); }
.payment-opt { display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid rgba(0,0,0,0.12); cursor: pointer; transition: all 0.2s; }
.payment-opt:has(input:checked) { border-color: var(--black); background: rgba(0,0,0,0.03); }
.payment-opt input[type=radio] { accent-color: var(--black); }
.freight-opt { display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 1px solid rgba(0,0,0,0.12); cursor: pointer; transition: all 0.2s; }
.freight-opt:has(input:checked) { border-color: var(--black); background: rgba(0,0,0,0.03); }
.freight-opt input[type=radio] { accent-color: var(--black); }
</style>

@push('scripts')
<script>
const SUBTOTAL = {{ $subtotal }};
const DISCOUNT = {{ $discount }};
const FREE_SHIPPING = {{ $freeShipping ? 'true' : 'false' }};
let selectedShippingPrice = FREE_SHIPPING ? 0 : null;

function updateSummary(shippingPrice, shippingLabel) {
    const shipping = FREE_SHIPPING ? 0 : shippingPrice;
    const total = Math.max(0, SUBTOTAL - DISCOUNT + shipping);

    document.getElementById('summaryShipping').innerHTML = FREE_SHIPPING
        ? '<span style="color:#27ae60">Grátis</span>'
        : (shippingLabel || 'Selecione acima');

    document.getElementById('summaryTotal').textContent = 'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

async function fetchCep(cep) {
    const digits = cep.replace(/\D/g, '');
    if (digits.length !== 8) return;

    // ViaCEP - fill address fields
    try {
        const r = await fetch(`https://viacep.com.br/ws/${digits}/json/`);
        const d = await r.json();
        if (!d.erro) {
            document.getElementById('address').value = d.logradouro;
            document.getElementById('neighborhood').value = d.bairro;
            document.getElementById('city').value = d.localidade;
            document.getElementById('state').value = d.uf;
        }
    } catch(e) {}

    // Freight calculation
    if (FREE_SHIPPING) {
        renderFreeShipping();
        return;
    }

    document.getElementById('freightHint')?.remove();
    document.getElementById('freightLoading').style.display = 'block';
    document.getElementById('freightOptions').innerHTML = '';

    try {
        const resp = await fetch('{{ route("checkout.freight") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ cep: digits }),
        });
        const options = await resp.json();
        document.getElementById('freightLoading').style.display = 'none';
        renderFreightOptions(options);
    } catch(e) {
        document.getElementById('freightLoading').style.display = 'none';
        document.getElementById('freightOptions').innerHTML = '<p style="color:#c0392b;font-size:0.85rem">Não foi possível calcular o frete. Tente novamente.</p>';
    }
}

function renderFreeShipping() {
    document.getElementById('freightOptions').innerHTML = `
        <div style="display:flex;align-items:center;gap:0.8rem;padding:1rem;background:rgba(39,174,96,0.07);border:1px solid #27ae60;">
            <i class="fas fa-truck" style="color:#27ae60;font-size:1.2rem;"></i>
            <span style="font-size:0.9rem;color:#27ae60;font-weight:600;">Frete Grátis no seu pedido!</span>
        </div>`;
    document.getElementById('shippingOptionInput').value = 'pac';
    updateSummary(0, 'Grátis');
}

function renderFreightOptions(options) {
    const container = document.getElementById('freightOptions');
    container.innerHTML = '';

    options.forEach(opt => {
        const label = document.createElement('label');
        label.className = 'freight-opt';
        label.htmlFor = 'freight_' + opt.code;
        label.innerHTML = `
            <input type="radio" id="freight_${opt.code}" name="_freight_display" value="${opt.code}">
            <i class="fas fa-${opt.code === 'sedex' ? 'shipping-fast' : 'truck'}" style="font-size:1.3rem;color:var(--gray);width:24px;text-align:center;"></i>
            <span style="flex:1;">
                <strong style="display:block;font-size:0.9rem;">${opt.service}</strong>
                <small style="color:var(--gray);">${opt.days} dia(s) útil(eis)</small>
            </span>
            <span style="font-weight:700;font-size:0.95rem;">${opt.price == 0 ? '<span style=color:#27ae60>Grátis</span>' : 'R$ ' + opt.price.toLocaleString('pt-BR',{minimumFractionDigits:2,maximumFractionDigits:2})}</span>
        `;
        label.querySelector('input').addEventListener('change', () => {
            document.getElementById('shippingOptionInput').value = opt.code;
            updateSummary(opt.price, opt.label_display);
        });
        container.appendChild(label);
    });

    // Auto-select first option
    const first = container.querySelector('input[type=radio]');
    if (first) {
        first.checked = true;
        first.dispatchEvent(new Event('change'));
    }
}

// CEP input events
const cepInput = document.getElementById('cep');
cepInput?.addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0,5) + '-' + v.slice(5,8);
    this.value = v;
    if (v.replace(/\D/g,'').length === 8) fetchCep(v);
});
cepInput?.addEventListener('blur', function() {
    if (this.value.replace(/\D/g,'').length === 8) fetchCep(this.value);
});

// CPF auto-format
document.querySelector('[name=cpf]')?.addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '');
    v = v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    this.value = v;
});

// Init if free shipping already
if (FREE_SHIPPING) {
    renderFreeShipping();
    updateSummary(0, 'Grátis');
} else {
    updateSummary(0, null);
}
</script>
@endpush
@endsection
