<!-- resources/js/Pages/Auth/Register.vue -->
<template>
    <div class="auth-container">
        <h1>Créer un compte</h1>

        <form @submit.prevent="submit">
            <div>
                <label>Nom</label>
                <input v-model="form.name" type="text" required autofocus />
                <span v-if="errors.name" class="error">{{ errors.name }}</span>
            </div>

            <div>
                <label>Email</label>
                <input v-model="form.email" type="email" required />
                <span v-if="errors.email" class="error">{{ errors.email }}</span>
            </div>

            <div>
                <label>Mot de passe</label>
                <input v-model="form.password" type="password" required />
                <span v-if="errors.password" class="error">{{ errors.password }}</span>
            </div>

            <div>
                <label>Confirmer le mot de passe</label>
                <input v-model="form.password_confirmation" type="password" required />
            </div>

            <button type="submit" :disabled="processing">
                {{ processing ? 'Création...' : 'Créer mon compte' }}
            </button>
        </form>

        <a href="/login">Déjà un compte ?</a>
    </div>
</template>

<script>
export default {
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
