# Padrão de Branch

## 📌 Formato obrigatório:

```
tipo/#numero-do-card-descricao
```

## 📌 Exemplos válidos:

```
feat/#123-criar-endpoint-login
fix/#456-corrigir-validacao-token
chore/#789-ajustar-pipeline-ci
```

## 📌 Regras:

- Obrigatório iniciar com:
    - `feat` → Nova funcionalidade
    - `fix` → Correção de bug
    - `chore` → Ajustes técnicos / infraestrutura
- Sempre usar `/` após o tipo
- Sempre usar `#` antes do número do card
- O número deve ser o mesmo do card no Jira
- A descrição deve ser curta e objetiva

---

# 💬 2️⃣ Padrão de Commit

## 📌 Formato obrigatório:

```
tipo(modulo): #numero-do-card descricao-do-commit
```

## 📌 Exemplos válidos:

```
feat(auth): #123 adiciona endpoint de login
fix(token): #456 corrige expiração incorreta
chore(ci): #789 melhora validação do pipeline
```

## 📌 Regras:

- Mesmo tipo usado na branch
- O módulo deve estar entre parênteses
- Obrigatório `:` após o parênteses
- Obrigatório `#numero-do-card`
- O número do card deve ser o mesmo da branch
- A descrição deve refletir exatamente o que foi alterado