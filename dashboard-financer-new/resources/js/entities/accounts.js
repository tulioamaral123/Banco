export default {
  label: 'Contas',
  apiBase: '/api/accounts',
  columns: [
    { key: 'name',    label: 'Nome'                      },
    { key: 'bank',    label: 'Banco'                     },
    { key: 'balance', label: 'Saldo', type: 'currency'   },
    { key: 'type',    label: 'Tipo'                      },
  ],
  fields: [
    { key: 'name',    label: 'Nome',            type: 'text',   required: true },
    { key: 'bank',    label: 'Banco',           type: 'text',   required: true },
    { key: 'balance', label: 'Saldo Inicial (€)', type: 'number', required: true },
    { key: 'type',    label: 'Tipo',            type: 'select', required: true, options: [
      { value: 'Conta Corrente', label: 'Conta Corrente' },
      { value: 'Poupança',       label: 'Poupança'       },
      { value: 'Investimento',   label: 'Investimento'   },
    ]},
  ],
  mockData: [
    { id: 1, name: 'Conta Principal', bank: 'CGD',       balance: 4250.80,  type: 'Conta Corrente' },
    { id: 2, name: 'Poupança',        bank: 'Santander', balance: 12000.00, type: 'Poupança'       },
  ],
}