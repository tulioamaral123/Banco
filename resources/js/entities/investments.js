export default {
  label: 'Investimentos',
  apiBase: '/api/v1/investimentos',
  columns: [
    { key: 'nome',  label: 'Nome'                       },
    { key: 'tipo',  label: 'Tipo'                       },
    { key: 'moeda', label: 'Moeda'                      },
    { key: 'valor', label: 'Valor',  type: 'currency'   },
    { key: 'data',  label: 'Data',   type: 'date'       },
  ],
  fields: [
    { key: 'nome',  label: 'Nome',       type: 'text',   required: true },
    { key: 'tipo',  label: 'Tipo',       type: 'select', required: true, options: [
      { value: 'Ações',          label: 'Ações'          },
      { value: 'FII',            label: 'FII'            },
      { value: 'Tesouro Direto', label: 'Tesouro Direto' },
      { value: 'CDB',            label: 'CDB'            },
      { value: 'Criptomoeda',    label: 'Criptomoeda'    },
      { value: 'Outro',          label: 'Outro'          },
    ]},
    { key: 'moeda', label: 'Moeda',      type: 'select', required: true, options: [
      { value: 'BRL', label: 'BRL — Real'         },
      { value: 'USD', label: 'USD — Dólar'        },
      { value: 'EUR', label: 'EUR — Euro'         },
    ]},
    { key: 'valor', label: 'Valor',      type: 'number', required: true },
    { key: 'data',  label: 'Data',       type: 'date',   required: true },
  ],
}
