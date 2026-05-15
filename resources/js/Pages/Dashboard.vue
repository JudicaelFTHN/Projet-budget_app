<template>
  <app-layout>

    <!-- Topbar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">Tableau de bord</h1>
        <p style="color:var(--text-muted);font-size:0.85rem;" class="mb-0">
          Bienvenue, {{ $page.props.auth.user.name }} 👋
        </p>
      </div>
      <span style="background:rgba(0,200,83,0.1);color:var(--green-primary);padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:600;">
        <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Live
      </span>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="stat-card">
          <div class="label"><i class="bi bi-wallet2 me-1"></i> Solde total</div>
          <div class="value">{{ balance.toLocaleString() }} Ar</div>
          <span class="badge-income"><i class="bi bi-arrow-up-short"></i> Solde actuel</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <div class="label"><i class="bi bi-arrow-down-circle me-1"></i> Revenus</div>
          <div class="value" style="color:var(--green-primary);">+{{ income.toLocaleString() }} Ar</div>
          <span class="badge-income"><i class="bi bi-graph-up-arrow"></i> Ce mois</span>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <div class="label"><i class="bi bi-arrow-up-circle me-1"></i> Dépenses</div>
          <div class="value" style="color:#ef5350;">-{{ expense.toLocaleString() }} Ar</div>
          <span class="badge-expense"><i class="bi bi-graph-down-arrow"></i> Ce mois</span>
        </div>
      </div>
    </div>

    <!-- Dernières transactions -->
    <div class="card-dark mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-semibold mb-0" style="font-size:1rem;">
          <i class="bi bi-arrow-left-right me-2" style="color:var(--green-primary);"></i>
          Dernières transactions
        </h2>
        <a href="/transactions" class="btn-outline-green" style="font-size:0.75rem;padding:4px 12px;text-decoration:none;">
          Voir tout <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <p v-if="recent.length === 0" style="color:var(--text-muted);font-size:0.875rem;">
        <i class="bi bi-inbox me-2"></i>Aucune transaction pour l'instant.
      </p>

      <table v-else class="table table-dark-custom w-100">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th class="text-end">Montant</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in recent" :key="t.id">
            <td style="color:var(--text-muted);font-size:0.8rem;">{{ t.date }}</td>
            <td>
              <span :class="t.type === 'income' ? 'badge-income' : 'badge-expense'">
                <i :class="t.type === 'income' ? 'bi bi-arrow-down-short' : 'bi bi-arrow-up-short'"></i>
                {{ t.type === 'income' ? 'Revenu' : 'Dépense' }}
              </span>
            </td>
            <td style="color:var(--text-secondary);">{{ t.description || '—' }}</td>
            <td class="text-end fw-semibold" :style="t.type === 'expense' ? 'color:#ef5350;' : 'color:var(--green-primary);'">
              {{ t.type === 'expense' ? '-' : '+' }}{{ t.amount.toLocaleString() }} Ar
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Objectifs d'épargne -->
    <div class="card-dark">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-semibold mb-0" style="font-size:1rem;">
          <i class="bi bi-bullseye me-2" style="color:var(--green-primary);"></i>
          Objectifs d'épargne
        </h2>
        <a href="/savings" class="btn-outline-green" style="font-size:0.75rem;padding:4px 12px;text-decoration:none;">
          Voir tout <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <p v-if="goals.length === 0" style="color:var(--text-muted);font-size:0.875rem;">
        <i class="bi bi-inbox me-2"></i>Aucun objectif défini.
      </p>

      <div v-for="g in goals" :key="g.id" class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span style="font-size:0.875rem;font-weight:600;color:var(--text-primary);">
            <i class="bi bi-flag me-1" style="color:var(--green-primary);"></i>{{ g.name }}
          </span>
          <span style="font-size:0.75rem;color:var(--text-muted);">
            {{ progress(g) }}%
          </span>
        </div>

        <div class="progress-dark mb-1">
          <div
            class="progress-fill"
            :style="{
              width: progress(g) + '%',
              background: progress(g) >= 100 ? 'var(--green-light)' : 'var(--green-primary)'
            }"
          ></div>
        </div>

        <div class="d-flex justify-content-between">
          <span style="font-size:0.75rem;color:var(--text-muted);">
            {{ g.current_amount.toLocaleString() }} Ar
          </span>
          <span style="font-size:0.75rem;color:var(--text-muted);">
            {{ g.target_amount.toLocaleString() }} Ar
            <span v-if="g.deadline"> · Échéance : {{ g.deadline }}</span>
          </span>
        </div>
      </div>
    </div>

  </app-layout>
</template>

<script>
import Layout from '../Layouts/AppLayout.vue'

export default {
  components: { 'app-layout': Layout },
  props: {
    income:  { type: Number, default: 0 },
    expense: { type: Number, default: 0 },
    balance: { type: Number, default: 0 },
    goals:   { type: Array,  default: () => [] },
    recent:  { type: Array,  default: () => [] },
  },
  methods: {
    progress(goal) {
      if (!goal.target_amount) return 0
      return Math.min(Math.round((goal.current_amount / goal.target_amount) * 100), 100)
    }
  }
}
</script>