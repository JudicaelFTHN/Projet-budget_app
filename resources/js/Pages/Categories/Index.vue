<template>
    <app-layout>
        <h1>Catégories</h1>

        <!-- Formulaire ajout -->
        <form @submit.prevent="submit">
            <div>
                <label>Nom</label>
                <input v-model="form.name" type="text" required />
                <span v-if="$page.props.errors.name">{{ $page.props.errors.name }}</span>
            </div>

            <div>
                <label>Couleur</label>
                <input v-model="form.color" type="color" />
            </div>

            <button type="submit" :disabled="processing">
                {{ processing ? 'Enregistrement...' : 'Ajouter' }}
            </button>
        </form>

        <!-- Liste -->
        <h2>Mes catégories</h2>

        <p v-if="categories.length === 0">Aucune catégorie.</p>

        <table v-else>
            <thead>
            <tr>
                <th>Couleur</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="c in categories" :key="c.id">
                <td>
                    <span :style="{ background: c.color, display: 'inline-block', width: '20px', height: '20px', borderRadius: '4px' }"></span>
                </td>
                <td>
                    <template v-if="editing && editing.id === c.id">
                        <input v-model="editing.name" type="text" />
                        <input v-model="editing.color" type="color" />
                    </template>
                    <template v-else>
                        {{ c.name }}
                    </template>
                </td>
                <td>
                    <template v-if="editing && editing.id === c.id">
                        <button @click="update">Sauvegarder</button>
                        <button @click="editing = null">Annuler</button>
                    </template>
                    <template v-else>
                        <button @click="edit(c)">Modifier</button>
                        <button @click="destroy(c.id)">Supprimer</button>
                    </template>
                </td>
            </tr>
            </tbody>
        </table>

    </app-layout>
</template>

<script>
import Layout from '../../Layouts/AppLayout.vue'

export default {
    components: {
        'app-layout': Layout,
    },

    props: {
        categories: { type: Array, default: () => [] }
    },

    data() {
        return {
            processing: false,
            editing: null,
            form: {
                name:  '',
                color: '#3B82F6',
            }
        }
    },

    methods: {
        submit() {
            this.processing = true
            this.$inertia.post('/categories', this.form, {
                onSuccess: () => { this.form.name = ''; this.form.color = '#3B82F6' },
                onFinish:  () => { this.processing = false }
            })
        },

        edit(category) {
            this.editing = { ...category }
        },

        update() {
            this.$inertia.put(`/categories/${this.editing.id}`, {
                name:  this.editing.name,
                color: this.editing.color,
            }, {
                onSuccess: () => { this.editing = null }
            })
        },

        destroy(id) {
            if (!confirm('Supprimer cette catégorie ?')) return
            this.$inertia.delete(`/categories/${id}`)
        }
    }
}
</script>
