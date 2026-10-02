<?php

/*
|--------------------------------------------------------------------------
| Simulador de Recuperação Tributária
|--------------------------------------------------------------------------
|
| Parâmetros usados para estimar o potencial de recuperação de créditos.
| Para cada tese: valor mensal = base de cálculo × percentual do regime,
| e o resultado é exibido como faixa [mínimo, máximo].
|
| Os percentuais abaixo são referências de mercado para uma estimativa
| preliminar; valide-os com a equipe tributária antes de publicar.
|
| Chaves de cada tese:
|   base        faturamento | folha_pagamento | energia | combustivel | icms | iss | receita_monofasica
|   percentuais regime => [mínimo, máximo] (fração: 0.0925 = 9,25%). Regimes sem
|               entrada não recebem a tese (simples_nacional | lucro_presumido | lucro_real)
|   segmentos   comercio | industria | servicos | saude | transporte (vazio = todos)
|   base_estimada_sobre_faturamento  estima a base como faturamento × fator quando
|               o usuário não informa o valor (opcional)
|   meses_maximo limita o período recuperável, ex.: modulação de efeitos (opcional)
|
*/

return [

    'empresa' => env('SIMULADOR_EMPRESA', env('APP_NAME', 'Simulador Tributário')),

    // Número com DDI e DDD, somente dígitos (ex.: 5585999999999). Vazio oculta o botão.
    'whatsapp' => env('SIMULADOR_WHATSAPP'),

    // SELIC média mensal usada para estimar a correção dos valores (0 desativa).
    'selic_mensal' => (float) env('SIMULADOR_SELIC_MENSAL', 0.009),

    'aviso_legal' => 'Estimativa preliminar baseada nas informações declaradas e em percentuais médios de mercado. '
        . 'Não representa promessa de crédito: a confirmação depende de análise técnica da documentação fiscal, '
        . 'do histórico de recolhimentos e da legislação aplicável a cada caso.',

    'teses' => [

        'exclusao_icms_pis_cofins' => [
            'titulo' => 'Exclusão do ICMS da base do PIS/COFINS',
            'descricao' => 'Recuperação do PIS/COFINS pago sobre o ICMS destacado nas notas de venda, valor que não compõe a receita da empresa.',
            'fundamento' => 'STF, RE 574.706 (Tema 69)',
            'base' => 'icms',
            'base_estimada_sobre_faturamento' => 0.12,
            'segmentos' => ['comercio', 'industria', 'transporte'],
            'percentuais' => [
                // PIS/COFINS cumulativo (0,65% + 3%) e não cumulativo (1,65% + 7,6%)
                'lucro_presumido' => [0.0110, 0.0365],
                'lucro_real' => [0.0278, 0.0925],
            ],
        ],

        'exclusao_iss_pis_cofins' => [
            'titulo' => 'Exclusão do ISS da base do PIS/COFINS',
            'descricao' => 'Recuperação do PIS/COFINS pago sobre o ISS embutido no preço dos serviços. Tese judicial que depende de ação própria.',
            'fundamento' => 'STF, RE 592.616 (Tema 118)',
            'base' => 'iss',
            'base_estimada_sobre_faturamento' => 0.03,
            'segmentos' => ['servicos', 'saude'],
            'percentuais' => [
                'lucro_presumido' => [0.0110, 0.0365],
                'lucro_real' => [0.0278, 0.0925],
            ],
        ],

        'pis_cofins_monofasico' => [
            'titulo' => 'PIS/COFINS sobre produtos monofásicos',
            'descricao' => 'Revendedores de produtos com tributação monofásica (combustíveis, medicamentos, cosméticos, autopeças, bebidas frias) costumam recolher PIS/COFINS em duplicidade no DAS.',
            'fundamento' => 'Lei 10.147/2000; LC 123/2006, art. 18, § 4º-A, I',
            'base' => 'receita_monofasica',
            'percentuais' => [
                // alíquota efetiva do DAS (4% a 10%) × parcela de PIS/COFINS no Anexo I (15,5%)
                'simples_nacional' => [0.0062, 0.0155],
            ],
        ],

        'icms_st_pago_a_maior' => [
            'titulo' => 'Restituição de ICMS-ST pago a maior',
            'descricao' => 'Quando a venda ao consumidor final ocorre por valor inferior à base presumida da substituição tributária, a diferença de ICMS-ST pode ser restituída.',
            'fundamento' => 'STF, RE 593.849 (Tema 201)',
            'base' => 'faturamento',
            'segmentos' => ['comercio'],
            'percentuais' => [
                'simples_nacional' => [0.002, 0.008],
                'lucro_presumido' => [0.002, 0.008],
                'lucro_real' => [0.002, 0.008],
            ],
        ],

        'inss_verbas_indenizatorias' => [
            'titulo' => 'INSS patronal sobre verbas indenizatórias',
            'descricao' => 'Revisão da contribuição previdenciária patronal (CPP, RAT e terceiros) recolhida sobre verbas sem natureza remuneratória, como aviso prévio indenizado, 15 primeiros dias de afastamento e salário-maternidade.',
            'fundamento' => 'STJ, REsp 1.230.957 (Temas 478 e 738); STF, RE 576.967 (Tema 72)',
            'base' => 'folha_pagamento',
            'percentuais' => [
                'lucro_presumido' => [0.01, 0.03],
                'lucro_real' => [0.01, 0.03],
            ],
        ],

        'revisao_rat_fap' => [
            'titulo' => 'Revisão do RAT/FAP',
            'descricao' => 'Conferência do grau de risco (RAT) pela atividade preponderante e contestação do FAP calculado com acidentes indevidamente atribuídos à empresa.',
            'fundamento' => 'Lei 8.212/1991, art. 22, II; Lei 10.666/2003, art. 10',
            'base' => 'folha_pagamento',
            'percentuais' => [
                'lucro_presumido' => [0.002, 0.01],
                'lucro_real' => [0.002, 0.01],
            ],
        ],

        'creditos_pis_cofins_energia' => [
            'titulo' => 'Créditos de PIS/COFINS sobre energia elétrica',
            'descricao' => 'No regime não cumulativo, a energia elétrica consumida nos estabelecimentos gera crédito de PIS/COFINS que muitas vezes não é aproveitado integralmente.',
            'fundamento' => 'Leis 10.637/2002 e 10.833/2003, art. 3º, III',
            'base' => 'energia',
            'percentuais' => [
                'lucro_real' => [0.0231, 0.0925],
            ],
        ],

        'creditos_pis_cofins_combustiveis' => [
            'titulo' => 'Créditos de PIS/COFINS sobre combustíveis',
            'descricao' => 'Combustíveis e lubrificantes essenciais à atividade (frota, máquinas, entregas) são insumos e geram crédito de PIS/COFINS no regime não cumulativo.',
            'fundamento' => 'Lei 10.833/2003, art. 3º, II; STJ, REsp 1.221.170 (Tema 779)',
            'base' => 'combustivel',
            'percentuais' => [
                'lucro_real' => [0.0231, 0.0925],
            ],
        ],

        'creditos_icms_energia_industria' => [
            'titulo' => 'Créditos de ICMS sobre energia na produção',
            'descricao' => 'A energia consumida no processo industrial gera crédito de ICMS, frequentemente aproveitado só em parte por falta de laudo técnico de consumo.',
            'fundamento' => 'LC 87/1996, art. 33, II, "b"',
            'base' => 'energia',
            'segmentos' => ['industria'],
            'percentuais' => [
                'lucro_presumido' => [0.04, 0.12],
                'lucro_real' => [0.04, 0.12],
            ],
        ],

        'equiparacao_hospitalar' => [
            'titulo' => 'Equiparação hospitalar (IRPJ/CSLL)',
            'descricao' => 'Clínicas e laboratórios que prestam serviços hospitalares podem apurar IRPJ e CSLL no Lucro Presumido com presunção de 8% e 12%, em vez de 32%.',
            'fundamento' => 'Lei 9.249/1995, art. 15, § 1º, III, "a"; STJ, REsp 1.116.399 (Tema 217)',
            'base' => 'faturamento',
            'segmentos' => ['saude'],
            'percentuais' => [
                // (32% - 8%) × 15% de IRPJ + (32% - 12%) × 9% de CSLL = 5,4% da receita elegível
                'lucro_presumido' => [0.016, 0.054],
            ],
        ],

    ],

];
