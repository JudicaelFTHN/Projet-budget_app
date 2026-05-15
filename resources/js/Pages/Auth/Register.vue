<!-- resources/js/Pages/Auth/Register.vue -->
<template>
  <div class="auth-container">
    <div class="auth-card">

      <!-- Logo -->
      <div class="auth-logo">
        <i class="bi bi-currency-dollar"></i> BudgetApp
      </div>
      <p class="auth-subtitle">Créez votre espace personnel</p>

      <form @submit.prevent="submit">

        <!-- Nom -->
        <div class="mb-3">
          <label class="label-dark"><i class="bi bi-person me-1"></i>Nom complet</label>
          <input
            v-model="form.name"
            type="text"
            class="input-dark"
            placeholder="Jean Dupont"
            required
            autofocus
          />
          <small v-if="errors.name" style="color:#ef5350;">{{ errors.name }}</small>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="label-dark"><i class="bi bi-envelope me-1"></i>Adresse email</label>
          <input
            v-model="form.email"
            type="email"
            class="input-dark"
            placeholder="exemple@email.com"
            required
          />
          <small v-if="errors.email" style="color:#ef5350;">{{ errors.email }}</small>
        </div>

        <!-- Mot de passe -->
        <div class="mb-3">
          <label class="label-dark"><i class="bi bi-lock me-1"></i>Mot de passe</label>
          <input
            v-model="form.password"
            type="password"
            class="input-dark"
            placeholder="••••••••"
            required
          />
          <small v-if="errors.password" style="color:#ef5350;">{{ errors.password }}</small>
        </div>

        <!-- Confirmation -->
        <div class="mb-4">
          <label class="label-dark"><i class="bi bi-lock-fill me-1"></i>Confirmer le mot de passe</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            class="input-dark"
            placeholder="••••••••"
            required
          />
        </div>

        <!-- Bouton -->
        <button
          type="submit"
          class="btn-green w-100 py-2"
          :disabled="processing"
          :style="processing ? 'opacity:0.7;cursor:not-allowed;' : ''"
        >
          <span v-if="processing">
            <span class="spinner-border spinner-border-sm me-2"></span>Création...
          </span>
          <span v-else>
            <i class="bi bi-person-plus me-2"></i>Créer mon compte
          </span>
        </button>

      </form>

      <!-- Lien connexion -->
      <p class="text-center mt-4 mb-0" style="font-size:0.85rem;color:var(--text-muted);">
        Déjà un compte ?
        <a href="/login" style="color:var(--green-primary);text-decoration:none;font-weight:600;">
          Se connecter
        </a>
      </p>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    errors: { type: Object, default: () => ({}) }
  },
  data() {
    return {
      processing: false,
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      }
    }
  },
  methods: {
    submit() {
      this.processing = true
      this.$inertia.post('/register', this.form, {
        onFinish: () => { this.processing = false },
      })
    }
  }
}
</script>