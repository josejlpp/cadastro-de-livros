@extends('simulador.layout')

@use('App\Helper\SimuladorHelper')

@section('title', 'Simulador de Recuperação Tributária')

@section('cabecalho')
    <h1>Descubra quanto sua empresa pode recuperar em tributos</h1>
    <p class="lead mb-0">
        Em poucos minutos, estime créditos de PIS/COFINS, INSS, ICMS, ISS e outras oportunidades
        dos últimos {{ $mesesMaximo / 12 }} anos. Gratuito e sem compromisso.
    </p>
@endsection

@section('content')
    @php
        $camposPorBase = [
            'folha_pagamento' => [
                'campo' => 'folha_pagamento_mensal',
                'label' => 'Folha de pagamento mensal (bruta)',
                'ajuda' => 'Total de salários, horas extras e adicionais pagos no mês.',
            ],
            'energia' => [
                'campo' => 'energia_mensal',
                'label' => 'Gasto mensal com energia elétrica',
                'ajuda' => 'Valor médio das contas de energia de todos os estabelecimentos.',
            ],
            'combustivel' => [
                'campo' => 'combustivel_mensal',
                'label' => 'Gasto mensal com combustíveis',
                'ajuda' => 'Combustíveis e lubrificantes usados na frota, máquinas e entregas.',
            ],
            'icms' => [
                'campo' => 'icms_mensal',
                'label' => 'ICMS destacado nas notas de venda (média mensal)',
                'ajuda' => 'Se não souber, deixe em branco: estimaremos a partir do faturamento.',
            ],
            'iss' => [
                'campo' => 'iss_mensal',
                'label' => 'ISS sobre os serviços (média mensal)',
                'ajuda' => 'Se não souber, deixe em branco: estimaremos a partir do faturamento.',
            ],
        ];
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card sim-card">
                <div class="card-body p-4 p-md-5">

                    <ol class="sim-passos list-unstyled d-flex mb-4">
                        <li data-passo="1"><span>1</span> Sua empresa</li>
                        <li data-passo="2"><span>2</span> Custos e tributos</li>
                        <li data-passo="3"><span>3</span> Seus dados</li>
                    </ol>

                    @if(session('error'))
                        <div class="alert alert-warning">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Revise as informações:</strong><br>
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <form id="form-simulador" action="{{ route('simulador.store') }}" method="POST">
                        @csrf

                        {{-- Etapa 1: perfil da empresa --}}
                        <section class="sim-etapa" data-etapa="1">
                            <div class="form-group">
                                <label class="d-block">Regime tributário</label>
                                <div class="row">
                                    @foreach($regimes as $valor => $label)
                                        <div class="col-md-4 mb-2">
                                            <label class="sim-opcao mb-0">
                                                <input type="radio" name="regime" value="{{ $valor }}" required
                                                    {{ old('regime') === $valor ? 'checked' : '' }}>
                                                <span class="sim-opcao-corpo @error('regime') is-invalid @enderror">
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('regime')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="segmento">Segmento de atuação</label>
                                <select id="segmento" name="segmento" required
                                        class="form-control @error('segmento') is-invalid @enderror">
                                    <option value="">Selecione...</option>
                                    @foreach($segmentos as $valor => $label)
                                        <option value="{{ $valor }}" {{ old('segmento') === $valor ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('segmento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="faturamento_mensal">Faturamento médio mensal</label>
                                    <input id="faturamento_mensal" name="faturamento_mensal" type="text" inputmode="decimal"
                                           class="form-control dinheiro @error('faturamento_mensal') is-invalid @enderror"
                                           value="{{ SimuladorHelper::valorAntigo('faturamento_mensal') }}" placeholder="R$ 0,00" required>
                                    @error('faturamento_mensal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="meses">Período de análise</label>
                                    <select id="meses" name="meses" required
                                            class="form-control @error('meses') is-invalid @enderror">
                                        @foreach([60, 48, 36, 24, 12] as $meses)
                                            @continue($meses > $mesesMaximo)
                                            <option value="{{ $meses }}" {{ (int) old('meses', $mesesMaximo) === $meses ? 'selected' : '' }}>
                                                Últimos {{ $meses }} meses ({{ $meses / 12 }} {{ $meses === 12 ? 'ano' : 'anos' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('meses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{-- Etapa 2: bases de cálculo exibidas conforme as teses aplicáveis ao perfil --}}
                        <section class="sim-etapa" data-etapa="2">
                            <p class="text-muted">
                                Informe valores médios mensais. Mostramos apenas os campos usados nas oportunidades
                                aplicáveis ao seu regime e segmento.
                            </p>

                            <div class="row">
                                @foreach($camposPorBase as $base => $campo)
                                    <div class="form-group col-md-6 sim-campo" data-base="{{ $base }}">
                                        <label for="{{ $campo['campo'] }}">{{ $campo['label'] }}</label>
                                        <input id="{{ $campo['campo'] }}" name="{{ $campo['campo'] }}" type="text" inputmode="decimal"
                                               class="form-control dinheiro @error($campo['campo']) is-invalid @enderror"
                                               value="{{ SimuladorHelper::valorAntigo($campo['campo']) }}" placeholder="R$ 0,00">
                                        <small class="form-text text-muted">{{ $campo['ajuda'] }}</small>
                                        @error($campo['campo'])
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach

                                <div class="form-group col-md-6 sim-campo" data-base="receita_monofasica">
                                    <label for="percentual_monofasico">% do faturamento com produtos monofásicos</label>
                                    <div class="input-group">
                                        <input id="percentual_monofasico" name="percentual_monofasico" type="number"
                                               min="0" max="100" step="0.01" inputmode="decimal"
                                               class="form-control @error('percentual_monofasico') is-invalid @enderror"
                                               value="{{ old('percentual_monofasico') }}" placeholder="0">
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Combustíveis, medicamentos, cosméticos, autopeças, pneus e bebidas frias.
                                    </small>
                                    @error('percentual_monofasico')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <p id="sem-campos" class="alert alert-light mb-0">
                                Nenhuma informação adicional é necessária para o seu perfil. Avance para a próxima etapa.
                            </p>
                        </section>

                        {{-- Etapa 3: contato --}}
                        <section class="sim-etapa" data-etapa="3">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Seu nome</label>
                                    <input id="nome" name="nome" type="text" maxlength="100" autocomplete="name"
                                           class="form-control @error('nome') is-invalid @enderror"
                                           value="{{ old('nome') }}" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="empresa">Empresa</label>
                                    <input id="empresa" name="empresa" type="text" maxlength="150" autocomplete="organization"
                                           class="form-control @error('empresa') is-invalid @enderror"
                                           value="{{ old('empresa') }}" required>
                                    @error('empresa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail</label>
                                    <input id="email" name="email" type="email" maxlength="150" autocomplete="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telefone">Telefone / WhatsApp</label>
                                    <input id="telefone" name="telefone" type="tel" maxlength="20" autocomplete="tel"
                                           class="form-control @error('telefone') is-invalid @enderror"
                                           value="{{ old('telefone') }}" placeholder="(00) 00000-0000" required>
                                    @error('telefone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cnpj">CNPJ <small class="text-muted">(opcional)</small></label>
                                    <input id="cnpj" name="cnpj" type="text" maxlength="18"
                                           class="form-control @error('cnpj') is-invalid @enderror"
                                           value="{{ old('cnpj') }}" placeholder="00.000.000/0000-00">
                                    @error('cnpj')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input @error('aceite') is-invalid @enderror"
                                       id="aceite" name="aceite" value="1" {{ old('aceite') ? 'checked' : '' }} required>
                                <label class="custom-control-label" for="aceite">
                                    Autorizo o uso dos meus dados para esta simulação e o contato de um especialista (LGPD).
                                </label>
                                @error('aceite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </section>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" id="btn-voltar" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Voltar
                            </button>
                            <button type="button" id="btn-avancar" class="btn btn-primary ml-auto">
                                Avançar <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                            <button type="submit" id="btn-enviar" class="btn btn-success ml-auto">
                                <i class="fas fa-chart-line mr-1"></i> Ver resultado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .sim-passos { gap: .5rem; counter-reset: passo; }
        .sim-passos li { flex: 1; color: #adb5bd; font-weight: 600; border-bottom: 3px solid #dee2e6; padding-bottom: .5rem; }
        .sim-passos li span {
            display: inline-flex; align-items: center; justify-content: center;
            width: 1.6rem; height: 1.6rem; border-radius: 50%; background: #dee2e6; color: #fff; margin-right: .35rem;
        }
        .sim-passos li.ativo, .sim-passos li.concluido { color: var(--sim-primaria); border-color: var(--sim-primaria); }
        .sim-passos li.ativo span, .sim-passos li.concluido span { background: var(--sim-primaria); }
        .sim-etapa { display: none; }
        .sim-etapa.ativa { display: block; }
        .sim-opcao { display: block; position: relative; cursor: pointer; }
        .sim-opcao input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .sim-opcao-corpo {
            display: block; text-align: center; font-weight: 600; padding: .85rem .5rem;
            border: 2px solid #dee2e6; border-radius: .5rem; transition: all .15s;
        }
        .sim-opcao-corpo.is-invalid { border-color: #dc3545; }
        .sim-opcao input:checked + .sim-opcao-corpo { border-color: var(--sim-primaria); background: #e8f0fa; color: var(--sim-primaria); }
        .sim-opcao input:focus-visible + .sim-opcao-corpo { box-shadow: 0 0 0 .2rem rgba(11, 83, 148, .25); }
        @media (max-width: 575.98px) { .sim-passos li { font-size: .8rem; } }
    </style>
@endpush

@push('js')
    <script src="{{ asset('vendor/inputmask/jquery.inputmask.min.js') }}"></script>
    <script>
        $(function () {
            var perfisPorBase = @json($perfisPorBase);
            var totalEtapas = 3;
            var etapaAtual = 1;

            $('.dinheiro').inputmask('decimal', {
                alias: 'numeric',
                groupSeparator: '.',
                autoGroup: true,
                digits: 2,
                radixPoint: ',',
                digitsOptional: true,
                allowMinus: false,
                prefix: 'R$ ',
                rightAlign: false,
                placeholder: '',
                removeMaskOnSubmit: true
            });
            $('#telefone').inputmask({ mask: ['(99) 9999-9999', '(99) 99999-9999'], keepStatic: true });
            $('#cnpj').inputmask({ mask: '**.***.***/****-99', casing: 'upper', placeholder: ' ' });

            // Exibe só os campos usados pelas teses aplicáveis ao perfil; campos ocultos ficam
            // desabilitados para não serem validados nem enviados.
            function atualizarCampos() {
                var perfil = $('input[name=regime]:checked').val() + ':' + $('#segmento').val();
                var algumVisivel = false;

                $('.sim-campo').each(function () {
                    var visivel = (perfisPorBase[$(this).data('base')] || []).indexOf(perfil) !== -1;
                    $(this).toggle(visivel).find(':input').prop('disabled', !visivel);
                    algumVisivel = algumVisivel || visivel;
                });

                $('#sem-campos').toggle(!algumVisivel);
            }

            function mostrarEtapa(etapa) {
                etapaAtual = etapa;
                $('.sim-etapa').removeClass('ativa').filter('[data-etapa=' + etapa + ']').addClass('ativa');
                $('.sim-passos li').each(function () {
                    var passo = $(this).data('passo');
                    $(this).toggleClass('ativo', passo === etapa).toggleClass('concluido', passo < etapa);
                });
                $('#btn-voltar').toggle(etapa > 1);
                $('#btn-avancar').toggle(etapa < totalEtapas);
                $('#btn-enviar').toggle(etapa === totalEtapas);
            }

            function etapaValida(etapa) {
                var valida = true;
                $('.sim-etapa[data-etapa=' + etapa + ']').find(':input:enabled').each(function () {
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        valida = false;
                        return false;
                    }
                });
                return valida;
            }

            $('input[name=regime], #segmento').on('change', atualizarCampos);

            // Enter nas etapas iniciais avança em vez de enviar o formulário incompleto.
            $('#form-simulador').on('keydown', 'input', function (e) {
                if (e.key === 'Enter' && etapaAtual < totalEtapas) {
                    e.preventDefault();
                    $('#btn-avancar').trigger('click');
                }
            });

            $('#btn-avancar').on('click', function () {
                if (etapaValida(etapaAtual)) {
                    mostrarEtapa(etapaAtual + 1);
                }
            });

            $('#btn-voltar').on('click', function () {
                mostrarEtapa(etapaAtual - 1);
            });

            $('#form-simulador').on('submit', function () {
                $('#btn-enviar').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin mr-1"></i> Calculando...');
            });

            atualizarCampos();

            // Após erro de validação no servidor, abre a etapa do primeiro campo inválido.
            var etapaComErro = $('.sim-etapa .is-invalid').first().closest('.sim-etapa').data('etapa');
            mostrarEtapa(etapaComErro || 1);
        });
    </script>
@endpush
