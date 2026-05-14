<template>
    <form @submit.prevent="submit">

        <div>
            <label>Type</label>
            <select v-model="form.type">
                <option value="income">Revenu</option>
                <option value="expense">Dépense</option>
            </select>
            <span v-if="errors.type">{{ errors.type }}</span>
        </div>

        <div>
            <label>Montant</label>
            <input v-model="form.amount" type="number" step="0.01" min="0" />
            <span v-if="errors.amount">{{ errors.amount }}</span>
        </div>

        <div>
            <label>Description</label>
            <input v-model="form.description" type="text" />
            <span v-if="errors.description">{{ errors.description }}</span>
        </div>

        <div>
            <label>Date</label>
            <input v-model="form.date" type="date" />
            <span v-if="errors.date">{{ errors.date }}</span>
        </div>

        <button type="submit" :disabled="processing">
            {{ processing ? 'Enregistrement...' : 'Enregistrer' }}
        </button>

        <a href="/transactions">Annuler</a>

    </form>
</template>

<script>
export default {
    props: {
        transaction: { type: Object, default: null },
        errors:      { type: Object, default: () => ({}) }
    },

    data() {
        return {
            processing: false,
            form: {
                type:        this.transaction?.type        || 'expense',
                amount:      this.transaction?.amount      || '',
                description: this.transaction?.description || '',
                date:        this.transaction?.date        || new Date().toISOString().substr(0, 10),
            }
        }
    },

    methods: {
        submit() {
            this.processing = true

            if (this.transaction) {
                // Modification
                this.$inertia.put(`/transactions/${this.transaction.id}`, this.form, {
                    onFinish: () => { this.processing = false }
                })
            } else {
                // Ajout
                this.$inertia.post('/transactions', this.form, {
                    onFinish: () => { this.processing = false }
                })
            }
        }
    }
}
</script>
