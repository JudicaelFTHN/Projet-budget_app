<!-- resources/js/Pages/Auth/Login.vue -->
<template>
    <div class="auth-container">
        <h1>Connexion</h1>

        <form @submit.prevent="submit">
            <div>
                <label>Email</label>
                <input v-model="form.email" type="email" required autofocus />
                <span v-if="errors.email" class="error">{{ errors.email }}</span>
            </div>

            <div>
                <label>Mot de passe</label>
                <input v-model="form.password" type="password" required />
                <span v-if="errors.password" class="error">{{ errors.password }}</span>
            </div>

            <div>
                <label>
                    <input v-model="form.remember" type="checkbox" />
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" :disabled="processing">
                {{ processing ? 'Connexion...' : 'Se connecter' }}
            </button>
        </form>

        <a href="/register">Pas encore de compte ?</a>
    </div>
</template>

<script>
export default {
    // Inertia passe les erreurs de validation comme props automatiquement
    props: {
        errors: {
            type: Object,
            default: () => ({})
        }
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
