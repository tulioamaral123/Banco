export default {
  label: 'Transações',
  apiBase: '/api/v1/transacoes',
  columns: [
    { key: 'nome',      label: 'Descrição'  },
    { key: 'categoria', label: 'Categoria'  },
    { key: 'data',      label: 'Data',   type: 'date'     },
    { key: 'valor',     label: 'Valor',  type: 'currency' },
  ],
  fields: [
    { key: 'nome',      label: 'Descrição',  type: 'text',   required: true },
    { key: 'categoria', label: 'Categoria',  type: 'select', required: true, options: [
      { value: 'Alimentação',  label: 'Alimentação'  },
      { value: 'Habitação',    label: 'Habitação'    },
      { value: 'Lazer',        label: 'Lazer'        },
      { value: 'Transportes',  label: 'Transportes'  },
      { value: 'Trabalho',     label: 'Trabalho'     },
      { value: 'Extra',        label: 'Extra'        },
    ]},
    { key: 'valor',     label: 'Valor (€)',  type: 'number', required: true },
    { key: 'data',      label: 'Data',       type: 'date',   required: true },
  ],
}
