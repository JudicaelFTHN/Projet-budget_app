<template>
  <app-layout>

    <!-- Topbar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">
          <i class="bi bi-arrow-left-right me-2" style="color:var(--green-primary);"></i>Transactions
        </h1>
        <p style="color:var(--text-muted);font-size:0.85rem;" class="mb-0">Gérez vos revenus et dépenses</p>
      </div>
    </div>

    <div class="row g-4">

      <!-- Formulaire ajout -->
      <div class="col-md-4">
        <div class="card-dark">
          <h2 class="fw-semibold mb-3" style="font-size:1rem;">
            <i class="bi bi-plus-circle me-2" style="color:var(--green-primary);"></i>Nouvelle transaction
          </h2>
          <transaction-form :errors="$page.props.errors" />
        </div>

        <!-- Formulaire modification -->
        <div v-if="editing" class="card-dark mt-3">
          <h2 class="fw-semibold mb-3" style="font-size:1rem;">
            <i class="bi bi-pencil me-2" style="color:var(--green-primary);"></i>Modifier la transaction
          </h2>
          <transaction-form :transaction="editing" :errors="$page.props.errors" />
          <button
            @click="editing = null"
            class="btn-outline-green w-100 mt-2"
            style="padding:6px 16px;font-size:0.8rem;"
          >
            <i class="bi bi-x-lg me-1"></i>Annuler
          </button>
        </div>
      </div>

      <!-- Liste transactions -->
      <div class="col-md-8">
        <div class="card-dark">
          <h2 class="fw-semibold mb-3" style="font-size:1rem;">
            <i class="bi bi-clock-history me-2" style="color:var(--green-primary);"></i>Historique
          </h2>

          <p v-if="transactions.length === 0" style="color:var(--text-muted);font-size:0.875rem;">
            <i class="bi bi-inbox me-2"></i>Aucune transaction pour le moment.
          </p>

          <table v-else class="table table-dark-custom w-100">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th class="text-end">Montant</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="t in transactions" :key="t.id">

                <td style="color:var(--text-muted);font-size:0.8rem;">
                  <i class="bi bi-calendar3 me-1"></i>{{ t.date }}
                </td>

                <td>
                  <span :class="t.type === 'income' ? 'badge-income' : 'badge-expense'">
                    <i :class="t.type === 'income' ? 'bi bi-arrow-down-short' : 'bi bi-arrow-up-short'"></i>
                    {{ t.type === 'income' ? 'Revenu' : 'Dépense' }}
                  </span>
                </td>

                <td style="color:var(--text-secondary);font-size:0.875rem;">
                  {{ t.description || '—' }}
                </td>

                <td class="text-end fw-semibold"
                  :style="t.type === 'expense' ? 'color:#ef5350;' : 'color:var(--green-primary);'"
                >
                  {{ t.type === 'expense' ? '-' : '+' }}{{ Number(t.amount).toLocaleString() }} Ar
                </td>

                <td class="text-end">
                  <button
                    @click="edit(t)"
                    class="btn-outline-green me-2"
                    style="padding:3px 10px;font-size:0.75rem;"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button
                    @click="destroy(t.id)"
                    style="background:rgba(244,67,54,0.1);color:#ef5350;border:1px solid rgba(244,67,54,0.3);border-radius:8px;padding:3px 10px;font-size:0.75rem;cursor:pointer;transition:all 0.2s;"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </app-layout>
</template>

<script>
import Layout from '../../Layouts/AppLayout.vue'
import TransactionForm from './Form.vue'

export default {
  components: {
    'app-layout': Layout,
    'transaction-form': TransactionForm,
  },
  props: {
    transactions: { type: Array, default: () => [] }
  },
  data() {
    return { editing: null }
  },
  methods: {
    edit(transaction) {
      this.editing = { ...transaction }
    },
    destroy(id) {
      if (!confirm('Supprimer cette transaction ?')) return
      this.$inertia.delete(`/transactions/${id}`)
    }
  }
}
</script>