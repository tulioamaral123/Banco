export default {
  label: 'Categorias',
  apiBase: '/api/categories',
  columns: [
    { key: 'name',   label: 'Nome'                    },
    { key: 'color',  label: 'Cor',   type: 'badge'    },
    { key: 'budget', label: 'Orçamento', type: 'currency' },
  ],
  fields: [
    { key: 'name',   label: 'Nome',          type: 'text',   required: true },
    { key: 'color',  label: 'Cor',           type: 'select', options: [
      { value: 'Azul',    label: 'Azul'    },
      { value: 'Índigo',  label: 'Índigo'  },
      { value: 'Roxo',    label: 'Roxo'    },
      { value: 'Rosa',    label: 'Rosa'    },
      { value: 'Verde',   label: 'Verde'   },
    ]},
    { key: 'budget', label: 'Orçamento (€)', type: 'number' },
  ],
  mockData: [
    { id: 1, name: 'Alimentação', color: 'Azul',   budget: 300 },
    { id: 2, name: 'Habitação',   color: 'Índigo', budget: 800 },
    { id: 3, name: 'Lazer',       color: 'Roxo',   budget: 150 },
    { id: 4, name: 'Transportes', color: 'Rosa',   budget: 100 },
  ],
}