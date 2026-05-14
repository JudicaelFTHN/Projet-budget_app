<template>
  <app-layout>
    <h1>Tableau de bord</h1>
    <p>Bienvenue {{ $page.props.auth.user.name }}</p>

    <!-- Résumé financier -->
    <div>
      <div>
        <p>Solde</p>
        <p>{{ balance }} Ar</p>
      </div>
      <div>
        <p>Revenus</p>
        <p>{{ income }} Ar</p>
      </div>
      <div>
        <p>Dépenses</p>
        <p>{{ expense }} Ar</p>
      </div>
    </div>

    <!-- Dernières transactions -->
    <h2>Dernières transactions</h2>

    <p v-if="recent.length === 0">Aucune transaction.</p>

    <table v-else>
      <thead>
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Description</th>
        <th>Montant</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="t in recent" :key="t.id">
        <td>{{ t.date }}</td>
        <td>{{ t.type === 'income' ? 'Revenu' : 'Dépense' }}</td>
        <td>{{ t.description || '—' }}</td>
        <td>{{ t.type === 'expense' ? '-' : '+' }}{{ t.amount }} Ar</td>
      </tr>
      </tbody>
    </table>

    <!-- Objectifs d'épargne -->
    <h2>Objectifs d'épargne</h2>

    <p v-if="goals.length === 0">Aucun objectif défini.</p>

    <div v-for="g in goals" :key="g.id">
      <p>{{ g.name }}</p>
      <p>{{ g.current_amount }} / {{ g.target_amount }} Ar</p>

      <!-- Barre de progression -->
      <div style="background:#eee; border-radius:4px; height:10px;">
        <div :style="{
          width: progress(g) + '%',
          background: progress(g) >= 100 ? 'green' : 'blue',
          height: '10px',
          borderRadius: '4px'
        }"></div>
      </div>
      <p>{{ progress(g) }}%</p>

      <p v-if="g.deadline">Échéance : {{ g.deadline }}</p>
    </div>

  </app-layout>
</template>

<script>
import Layout from '../Layouts/AppLayout.vue'

export default {
  components: {
    'app-layout': Layout,
  },

  props: {
    income: {type: Number, default: 0},
    expense: {type: Number, default: 0},
    balance: {type: Number, default: 0},
    goals: {type: Array, default: () => []},
    recent: {type: Array, default: () => []},
  },

  methods: {
    progress(goal) {
      if (!goal.target_amount) return 0
      const pct = (goal.current_amount / goal.target_amount) * 100
      return Math.min(Math.round(pct), 100)
    }
  }
}
</script>
