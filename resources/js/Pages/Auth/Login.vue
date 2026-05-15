<!-- resources/js/Pages/Auth/Login.vue -->
<template>
  <div class="auth-container">
    <div class="auth-card">

      <!-- Logo -->
      <div class="auth-logo">
        <i class="bi bi-currency-dollar"></i> BudgetApp
      </div>
      <p class="auth-subtitle">Connectez-vous à votre espace</p>

      <!-- Erreurs globales -->
      <div v-if="errors.email" class="mb-3 p-3" style="background:rgba(244,67,54,0.1);border:1px solid rgba(244,67,54,0.3);border-radius:10px;">
        <small style="color:#ef5350;"><i class="bi bi-exclamation-circle me-1"></i>{{ errors.email }}</small>
      </div>

      <form @submit.prevent="submit">

        <!-- Email -->
        <div class="mb-3">
          <label class="label-dark"><i class="bi bi-envelope me-1"></i>Adresse email</label>
          <input
            v-model="form.email"
            type="email"
            class="input-dark"
            placeholder="exemple@email.com"
            required
            autofocus
          />
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

        <!-- Se souvenir -->
        <div class="mb-4 d-flex align-items-center gap-2">
          <input
            v-model="form.remember"
            type="checkbox"
            id="remember"
            style="accent-color:var(--green-primary);width:16px;height:16px;"
          />
          <label for="remember" style="color:var(--text-secondary);font-size:0.85rem;cursor:pointer;margin:0;">
            Se souvenir de moi
          </label>
        </div>

        <!-- Bouton -->
        <button
          type="submit"
          class="btn-green w-100 py-2"
          :disabled="processing"
          :style="processing ? 'opacity:0.7;cursor:not-allowed;' : ''"
        >
          <span v-if="processing">
            <span class="spinner-border spinner-border-sm me-2"></span>Connexion...
          </span>
          <span v-else>
            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
          </span>
        </button>

      </form>

      <!-- Lien inscription -->
      <p class="text-center mt-4 mb-0" style="font-size:0.85rem;color:var(--text-muted);">
        Pas encore de compte ?
        <a href="/register" style="color:var(--green-primary);text-decoration:none;font-weight:600;">
          S'inscrire
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
        email: '',
        password: '',
        remember: false,
      }
    }
  },
  methods: {
    submit() {
      this.processing = true
      this.$inertia.post('/login', this.form, {
        onFinish: () => { this.processing = false },
      })
    }
  }
}
</script>