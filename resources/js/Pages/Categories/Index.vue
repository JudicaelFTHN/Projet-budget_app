<template>
  <app-layout>

    <!-- Topbar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fw-bold mb-0" style="font-size:1.5rem;">
          <i class="bi bi-tag-fill me-2" style="color:var(--green-primary);"></i>Catégories
        </h1>
        <p style="color:var(--text-muted);font-size:0.85rem;" class="mb-0">Gérez vos catégories de transactions</p>
      </div>
    </div>

    <div class="row g-4">

      <!-- Formulaire ajout -->
      <div class="col-md-4">
        <div class="card-dark">
          <h2 class="fw-semibold mb-3" style="font-size:1rem;">
            <i class="bi bi-plus-circle me-2" style="color:var(--green-primary);"></i>Nouvelle catégorie
          </h2>

          <form @submit.prevent="submit">
            <div class="mb-3">
              <label class="label-dark"><i class="bi bi-fonts me-1"></i>Nom</label>
              <input
                v-model="form.name"
                type="text"
                class="input-dark"
                placeholder="Ex: Alimentation"
                required
              />
              <small v-if="$page.props.errors.name" style="color:#ef5350;">
                {{ $page.props.errors.name }}
              </small>
            </div>

            <div class="mb-4">
              <label class="label-dark"><i class="bi bi-palette me-1"></i>Couleur</label>
              <div class="d-flex align-items-center gap-3">
                <input
                  v-model="form.color"
                  type="color"
                  style="width:48px;height:40px;border-radius:10px;border:1px solid var(--border-color);background:var(--bg-primary);cursor:pointer;padding:2px;"
                />
                <span style="font-size:0.85rem;color:var(--text-muted);">{{ form.color }}</span>
              </div>
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

      <!-- Liste catégories -->
      <div class="col-md-8">
        <div class="card-dark">
          <h2 class="fw-semibold mb-3" style="font-size:1rem;">
            <i class="bi bi-list-ul me-2" style="color:var(--green-primary);"></i>Mes catégories
          </h2>

          <p v-if="categories.length === 0" style="color:var(--text-muted);font-size:0.875rem;">
            <i class="bi bi-inbox me-2"></i>Aucune catégorie pour l'instant.
          </p>

          <table v-else class="table table-dark-custom w-100">
            <thead>
              <tr>
                <th>Couleur</th>
                <th>Nom</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in categories" :key="c.id">

                <!-- Couleur -->
                <td style="width:60px;">
                  <span :style="{
                    background: editing && editing.id === c.id ? editing.color : c.color,
                    display: 'inline-block',
                    width: '28px',
                    height: '28px',
                    borderRadius: '8px',
                    border: '2px solid rgba(255,255,255,0.1)'
                  }"></span>
                </td>

                <!-- Nom -->
                <td>
                  <template v-if="editing && editing.id === c.id">
                    <div class="d-flex gap-2 align-items-center">
                      <input
                        v-model="editing.name"
                        type="text"
                        class="input-dark"
                        style="padding:0.4rem 0.75rem;width:150px;"
                      />
                      <input
                        v-model="editing.color"
                        type="color"
                        style="width:36px;height:36px;border-radius:8px;border:1px solid var(--border-color);background:var(--bg-primary);cursor:pointer;padding:2px;"
                      />
                    </div>
                  </template>
                  <template v-else>
                    <span style="font-size:0.875rem;font-weight:500;">{{ c.name }}</span>
                  </template>
                </td>

                <!-- Actions -->
                <td class="text-end">
                  <template v-if="editing && editing.id === c.id">
                    <button @click="update" class="btn-green me-2" style="padding:4px 12px;font-size:0.75rem;">
                      <i class="bi bi-check-lg me-1"></i>Sauvegarder
                    </button>
                    <button @click="editing = null" class="btn-outline-green" style="padding:4px 12px;font-size:0.75rem;">
                      <i class="bi bi-x-lg me-1"></i>Annuler
                    </button>
                  </template>
                  <template v-else>
                    <button @click="edit(c)" class="btn-outline-green me-2" style="padding:4px 12px;font-size:0.75rem;">
                      <i class="bi bi-pencil me-1"></i>Modifier
                    </button>
                    <button @click="destroy(c.id)" style="background:rgba(244,67,54,0.1);color:#ef5350;border:1px solid rgba(244,67,54,0.3);border-radius:10px;padding:4px 12px;font-size:0.75rem;cursor:pointer;transition:all 0.2s;">
                      <i class="bi bi-trash me-1"></i>Supprimer
                    </button>
                  </template>
                </td>

              </tr>
            </tbody>
          </table>
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
    categories: { type: Array, default: () => [] }
  },
  data() {
    return {
      processing: false,
      editing: null,
      form: {
        name: '',
        color: '#00c853',
      }
    }
  },
  methods: {
    submit() {
      this.processing = true
      this.$inertia.post('/categories', this.form, {
        onSuccess: () => { this.form.name = ''; this.form.color = '#00c853' },
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