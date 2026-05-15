<template>
  <app-layout>

    <!-- Topbar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
      <div>
        <h1 class="fw-bold mb-0" style="font-size:1.3rem;">
          <i class="bi bi-bullseye me-2" style="color:var(--green-primary);"></i>Objectifs d'épargne
        </h1>
        <p style="color:var(--text-muted);font-size:0.85rem;" class="mb-0">Suivez votre progression vers vos objectifs</p>
      </div>
    </div>

    <div class="row g-4">

      <!-- Formulaire ajout -->
      <div class="col-12 col-md-4">
        <div class="card-dark">
          <h2 class="fw-semibold mb-3" style="font-size:1rem;">
            <i class="bi bi-plus-circle me-2" style="color:var(--green-primary);"></i>Nouvel objectif
          </h2>

          <form @submit.prevent="submit">
            <div class="mb-3">
              <label class="label-dark"><i class="bi bi-flag me-1"></i>Nom de l'objectif</label>
              <input v-model="form.name" type="text" class="input-dark" placeholder="Ex: Vacances" required />
              <small v-if="$page.props.errors.name" style="color:#ef5350;">{{ $page.props.errors.name }}</small>
            </div>

            <div class="mb-3">
              <label class="label-dark"><i class="bi bi-bullseye me-1"></i>Montant cible (Ar)</label>
              <input v-model="form.target_amount" type="number" step="0.01" min="0" class="input-dark" placeholder="0" inputmode="decimal" required />
              <small v-if="$page.props.errors.target_amount" style="color:#ef5350;">{{ $page.props.errors.target_amount }}</small>
            </div>

            <div class="mb-3">
              <label class="label-dark"><i class="bi bi-wallet2 me-1"></i>Montant actuel (Ar)</label>
              <input v-model="form.current_amount" type="number" step="0.01" min="0" class="input-dark" placeholder="0" inputmode="decimal" />
            </div>

            <div class="mb-4">
              <label class="label-dark"><i class="bi bi-calendar me-1"></i>Échéance</label>
              <input v-model="form.deadline" type="date" class="input-dark" />
            </div>

            <button
              type="submit"
              class="btn-green w-100 py-2"
              :disabled="processing"
              :style="processing ? 'opacity:0.7;cursor:not-allowed;' : ''"
            >
              <span v-if="processing">
                <span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...
              </span>
              <span v-else>
                <i class="bi bi-plus-lg me-2"></i>Ajouter
              </span>
            </button>
          </form>
        </div>
      </div>

      <!-- Liste objectifs -->
      <div class="col-12 col-md-8">

        <p v-if="goals.length === 0" class="card-dark" style="color:var(--text-muted);font-size:0.875rem;">
          <i class="bi bi-inbox me-2"></i>Aucun objectif défini.
        </p>

        <div v-for="g in goals" :key="g.id" class="card-dark mb-3">

          <!-- Mode édition -->
          <template v-if="editing && editing.id === g.id">
            <h3 class="fw-semibold mb-3" style="font-size:0.95rem;color:var(--green-primary);">
              <i class="bi bi-pencil me-2"></i>Modifier l'objectif
            </h3>
            <div class="row g-2 mb-3">
              <div class="col-12">
                <label class="label-dark">Nom</label>
                <input v-model="editing.name" type="text" class="input-dark" />
              </div>
              <div class="col-12 col-sm-6">
                <label class="label-dark">Montant cible (Ar)</label>
                <input v-model="editing.target_amount" type="number" step="0.01" class="input-dark" inputmode="decimal" />
              </div>
              <div class="col-12 col-sm-6">
                <label class="label-dark">Montant actuel (Ar)</label>
                <input v-model="editing.current_amount" type="number" step="0.01" class="input-dark" inputmode="decimal" />
              </div>
              <div class="col-12">
                <label class="label-dark">Échéance</label>
                <input v-model="editing.deadline" type="date" class="input-dark" />
              </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
              <button @click="update" class="btn-green" style="padding:6px 16px;font-size:0.8rem;">
                <i class="bi bi-check-lg me-1"></i>Sauvegarder
              </button>
              <button @click="editing = null" class="btn-outline-green" style="padding:6px 16px;font-size:0.8rem;">
                <i class="bi bi-x-lg me-1"></i>Annuler
              </button>
            </div>
          </template>

          <!-- Mode affichage -->
          <template v-else>
            <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
              <div>
                <h3 class="fw-semibold mb-0" style="font-size:1rem;">
                  <i class="bi bi-flag me-2" style="color:var(--green-primary);"></i>{{ g.name }}
                </h3>
                <small v-if="g.deadline" style="color:var(--text-muted);">
                  <i class="bi bi-calendar me-1"></i>Échéance : {{ g.deadline }}
                </small>
              </div>
              <span v-if="progress(g) >= 100" class="badge-income">
                <i class="bi bi-trophy me-1"></i>Atteint !
              </span>
            </div>

            <!-- Montants -->
            <div class="d-flex justify-content-between mb-2 flex-wrap gap-1">
              <span style="font-size:0.85rem;color:var(--text-secondary);">
                <i class="bi bi-wallet2 me-1"></i>{{ Number(g.current_amount).toLocaleString() }} Ar
              </span>
              <span style="font-size:0.85rem;color:var(--text-muted);">
                sur {{ Number(g.target_amount).toLocaleString() }} Ar
              </span>
            </div>

            <!-- Barre de progression -->
            <div class="progress-dark mb-1">
              <div
                class="progress-fill"
                :style="{
                  width: progress(g) + '%',
                  background: progress(g) >= 100 ? 'var(--green-light)' : 'var(--green-primary)'
                }"
              ></div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center mt-2 gap-2">
              <span style="font-size:0.75rem;color:var(--text-muted);">{{ progress(g) }}%</span>
              <div class="d-flex flex-wrap gap-2">
                <button @click="edit(g)" class="btn-outline-green" style="padding:4px 12px;font-size:0.75rem;">
                  <i class="bi bi-pencil me-1"></i><span class="d-none d-sm-inline">Modifier</span>
                </button>
                <button @click="destroy(g.id)" style="background:rgba(244,67,54,0.1);color:#ef5350;border:1px solid rgba(244,67,54,0.3);border-radius:10px;padding:4px 12px;font-size:0.75rem;cursor:pointer;transition:all 0.2s;">
                  <i class="bi bi-trash me-1"></i><span class="d-none d-sm-inline">Supprimer</span>
                </button>
              </div>
            </div>
          </template>

        </div>
      </div>

    </div>

  </app-layout>
</template>

<script>
import Layout from '../../Layouts/AppLayout.vue'

export default {
  components: { 'app-layout': Layout },
  props: {
    goals: { type: Array, default: () => [] }
  },
  data() {
    return {
      processing: false,
      editing: null,
      form: {
        name: '',
        target_amount: '',
        current_amount: 0,
        deadline: '',
      }
    }
  },
  methods: {
    progress(goal) {
      if (!goal.target_amount) return 0
      return Math.min(Math.round((goal.current_amount / goal.target_amount) * 100), 100)
    },
    submit() {
      this.processing = true
      this.$inertia.post('/savings', this.form, {
        onSuccess: () => { this.form = { name: '', target_amount: '', current_amount: 0, deadline: '' } },
        onFinish: () => { this.processing = false }
      })
    },
    edit(goal) { this.editing = { ...goal } },
    update() {
      this.$inertia.put(`/savings/${this.editing.id}`, {
        name: this.editing.name,
        target_amount: this.editing.target_amount,
        current_amount: this.editing.current_amount,
        deadline: this.editing.deadline,
      }, { onSuccess: () => { this.editing = null } })
    },
    destroy(id) {
      if (!confirm('Supprimer cet objectif ?')) return
      this.$inertia.delete(`/savings/${id}`)
    }
  }
}
</script>