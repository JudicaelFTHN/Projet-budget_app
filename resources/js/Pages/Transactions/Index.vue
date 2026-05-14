<template>
    <app-layout>
        <h1>Transactions</h1>

        <!-- Formulaire d'ajout -->
        <h2>Nouvelle transaction</h2>
        <transaction-form :errors="$page.props.errors" />

        <!-- Liste -->
        <h2>Historique</h2>

        <p v-if="transactions.length === 0">Aucune transaction pour le moment.</p>

        <table v-else>
            <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="t in transactions" :key="t.id">
                <td>{{ t.date }}</td>
                <td>{{ t.type === 'income' ? 'Revenu' : 'Dépense' }}</td>
                <td>{{ t.description || '—' }}</td>
                <td>{{ t.type === 'expense' ? '-' : '+' }}{{ t.amount }} Ar</td>
                <td>
                    <!-- Modifier inline -->
                    <button @click="edit(t)">Modifier</button>
                    <button @click="destroy(t.id)">Supprimer</button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Formulaire de modification inline -->
        <div v-if="editing">
            <h2>Modifier la transaction</h2>
            <transaction-form
                :transaction="editing"
                :errors="$page.props.errors"
            />
            <button @click="editing = null">Annuler</button>
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
        return {
            editing: null
        }
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
