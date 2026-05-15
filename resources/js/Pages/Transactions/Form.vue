<template>
  <form @submit.prevent="submit">

    <!-- Type -->
    <div class="mb-3">
      <label class="label-dark"><i class="bi bi-toggle-on me-1"></i>Type</label>
      <div class="d-flex gap-2">
        <button
          type="button"
          @click="form.type = 'income'"
          :class="form.type === 'income' ? 'btn-green' : 'btn-outline-green'"
          style="flex:1;padding:8px;font-size:0.85rem;"
        >
          <i class="bi bi-arrow-down-short me-1"></i>Revenu
        </button>
        <button
          type="button"
          @click="form.type = 'expense'"
          :class="form.type === 'expense' ? 'btn-green' : 'btn-outline-green'"
          :style="form.type === 'expense' ? 'flex:1;padding:8px;font-size:0.85rem;background:#ef5350;color:#fff;' : 'flex:1;padding:8px;font-size:0.85rem;color:#ef5350;border-color:#ef5350;'"
        >
          <i class="bi bi-arrow-up-short me-1"></i>Dépense
        </button>
      </div>
      <small v-if="errors.type" style="color:#ef5350;">{{ errors.type }}</small>
    </div>

    <!-- Montant -->
    <div class="mb-3">
      <label class="label-dark"><i class="bi bi-cash me-1"></i>Montant (Ar)</label>
      <input
        v-model="form.amount"
        type="number"
        step="0.01"
        min="0"
        class="input-dark"
        placeholder="0"
      />
      <small v-if="errors.amount" style="color:#ef5350;">{{ errors.amount }}</small>
    </div>

    <!-- Description -->
    <div class="mb-3">
      <label class="label-dark"><i class="bi bi-chat-left-text me-1"></i>Description</label>
      <input
        v-model="form.description"
        type="text"
        class="input-dark"
        placeholder="Ex: Courses alimentaires"
      />
      <small v-if="errors.description" style="color:#ef5350;">{{ errors.description }}</small>
    </div>

    <!-- Après le champ Description, avant Date -->
<div class="mb-3">
  <label class="label-dark"><i class="bi bi-tag me-1"></i>Catégorie</label>
  <select v-model="form.category_id" class="input-dark">
    <option :value="null">— Sans catégorie —</option>
    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
      {{ cat.name }}
    </option>
  </select>
</div>

    <!-- Date -->
    <div class="mb-4">
      <label class="label-dark"><i class="bi bi-calendar3 me-1"></i>Date</label>
      <input
        v-model="form.date"
        type="date"
        class="input-dark"
      />
      <small v-if="errors.date" style="color:#ef5350;">{{ errors.date }}</small>
    </div>

    <!-- Bouton submit -->
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
        <i class="bi bi-check-lg me-2"></i>Enregistrer
      </span>
    </button>

  </form>
</template>

<script>
export default {
  props: {
    transaction: { type: Object, default: null },
    errors:      { type: Object, default: () => ({}) },
    categories:  { type: Array,  default: () => [] },
  },
  data() {
    return {
      processing: false,
      form: {
        type:        this.transaction?.type        || 'expense',
        amount:      this.transaction?.amount      || '',
        description: this.transaction?.description || '',
        date:        this.transaction?.date        || new Date().toISOString().substr(0, 10),
      category_id: this.transaction?.category_id || null,
      }
    }
  },
  methods: {
    submit() {
      this.processing = true
      if (this.transaction) {
        this.$inertia.put(`/transactions/${this.transaction.id}`, this.form, {
          onFinish: () => { this.processing = false }
        })
      } else {
        this.$inertia.post('/transactions', this.form, {
          onSuccess: () => {
            this.form = {
              type: 'expense',
              amount: '',
              description: '',
              date: new Date().toISOString().substr(0, 10),
            }
          },
          onFinish: () => { this.processing = false }
        })
      }
    }
  }
}
</script>