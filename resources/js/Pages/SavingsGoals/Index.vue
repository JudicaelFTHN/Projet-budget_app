<template>
    <app-layout>
        <h1>Objectifs d'épargne</h1>

        <!-- Formulaire ajout -->
        <form @submit.prevent="submit">
            <div>
                <label>Nom de l'objectif</label>
                <input v-model="form.name" type="text" required />
                <span v-if="$page.props.errors.name">{{ $page.props.errors.name }}</span>
            </div>

            <div>
                <label>Montant cible</label>
                <input v-model="form.target_amount" type="number" step="0.01" min="0" required />
                <span v-if="$page.props.errors.target_amount">{{ $page.props.errors.target_amount }}</span>
            </div>

            <div>
                <label>Montant actuel</label>
                <input v-model="form.current_amount" type="number" step="0.01" min="0" />
            </div>

            <div>
                <label>Échéance</label>
                <input v-model="form.deadline" type="date" />
            </div>

            <button type="submit" :disabled="processing">
                {{ processing ? 'Enregistrement...' : 'Ajouter' }}
            </button>
        </form>

        <!-- Liste -->
        <h2>Mes objectifs</h2>

        <p v-if="goals.length === 0">Aucun objectif défini.</p>

        <div v-for="g in goals" :key="g.id">

            <template v-if="editing && editing.id === g.id">
                <!-- Mode édition -->
                <input v-model="editing.name" type="text" />
                <input v-model="editing.target_amount" type="number" step="0.01" />
                <input v-model="editing.current_amount" type="number" step="0.01" />
                <input v-model="editing.deadline" type="date" />
                <button @click="update">Sauvegarder</button>
                <button @click="editing = null">Annuler</button>
            </template>

            <template v-else>
                <!-- Mode affichage -->
                <p>{{ g.name }}</p>
                <p>{{ g.current_amount }} / {{ g.target_amount }} Ar</p>

                <!-- Barre de progression -->
                <div style="background:#eee; border-radius:4px; height:10px; margin: 4px 0;">
                    <div :style="{
            width: progress(g) + '%',
            background: progress(g) >= 100 ? 'green' : 'blue',
            height: '10px',
            borderRadius: '4px',
            transition: 'width .3s'
          }"></div>
                </div>
                <p>{{ progress(g) }}% {{ progress(g) >= 100 ? '— Objectif atteint !' : '' }}</p>

                <p v-if="g.deadline">Échéance : {{ g.deadline }}</p>

                <button @click="edit(g)">Modifier</button>
                <button @click="destroy(g.id)">Supprimer</button>
            </template>

        </div>

    </app-layout>
</template>

<script>
import Layout from '../../Layouts/AppLayout.vue'

export default {
    components: {
        'app-layout': Layout,
    },

    props: {
        goals: { type: Array, default: () => [] }
    },

    data() {
        return {
            processing: false,
            editing: null,
            form: {
                name:           '',
                target_amount:  '',
                current_amount: 0,
                deadline:       '',
            }
        }
    },

    methods: {
        progress(goal) {
            if (!goal.target_amount) return 0
            const pct = (goal.current_amount / goal.target_amount) * 100
            return Math.min(Math.round(pct), 100)
        },

        submit() {
            this.processing = true
            this.$inertia.post('/savings', this.form, {
                onSuccess: () => {
                    this.form = { name: '', target_amount: '', current_amount: 0, deadline: '' }
                },
                onFinish: () => { this.processing = false }
            })
        },

        edit(goal) {
            this.editing = { ...goal }
        },

        update() {
            this.$inertia.put(`/savings/${this.editing.id}`, {
                name:           this.editing.name,
                target_amount:  this.editing.target_amount,
                current_amount: this.editing.current_amount,
                deadline:       this.editing.deadline,
            }, {
                onSuccess: () => { this.editing = null }
            })
        },

        destroy(id) {
            if (!confirm('Supprimer cet objectif ?')) return
            this.$inertia.delete(`/savings/${id}`)
        }
    }
}
</script>
