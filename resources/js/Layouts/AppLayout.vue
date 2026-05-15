<template>
  <div style="display:flex;min-height:100vh;background:var(--bg-primary);">

    <!-- SIDEBAR -->
    <aside class="sidebar">

      <!-- Logo -->
      <div class="sidebar-logo">
        <i class="bi bi-currency-dollar"></i> BudgetApp
      </div>

      <!-- Navigation -->
      <nav style="flex:1;">
        <a href="/dashboard" class="sidebar-link" :class="{ active: $page.url === '/dashboard' }">
          <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="/transactions" class="sidebar-link" :class="{ active: $page.url.startsWith('/transactions') }">
          <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
        <a href="/categories" class="sidebar-link" :class="{ active: $page.url.startsWith('/categories') }">
          <i class="bi bi-tag-fill"></i> Catégories
        </a>
        <a href="/savings" class="sidebar-link" :class="{ active: $page.url.startsWith('/savings') }">
          <i class="bi bi-bullseye"></i> Objectifs
        </a>
      </nav>

      <!-- User footer -->
      <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <div style="width:36px;height:36px;border-radius:50%;background:var(--green-secondary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-person-fill" style="color:var(--green-primary);"></i>
          </div>
          <div style="overflow:hidden;">
            <div style="font-size:0.82rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              {{ $page.props.auth.user.name }}
            </div>
            <div style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              {{ $page.props.auth.user.email }}
            </div>
          </div>
        </div>
        <button @click="logout" class="btn-outline-green w-100" style="font-size:0.78rem;padding:6px;">
          <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
        </button>
      </div>

    </aside>

    <!-- MAIN WRAPPER -->
    <div style="margin-left:var(--sidebar-width);flex:1;display:flex;flex-direction:column;">

      <!-- TOPBAR -->
      <header style="
        background:var(--bg-secondary);
        border-bottom:1px solid var(--border-color);
        padding:0.875rem 2rem;
        display:flex;
        align-items:center;
        justify-content:space-between;
        position:sticky;
        top:0;
        z-index:50;
      ">
        <!-- Breadcrumb -->
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="color:var(--text-muted);font-size:0.8rem;">BudgetApp</span>
          <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:0.7rem;"></i>
          <span style="color:var(--text-primary);font-size:0.85rem;font-weight:600;">{{ currentPage }}</span>
        </div>

        <!-- Right side -->
        <div style="display:flex;align-items:center;gap:1rem;">
          <!-- Badge live -->
          <span style="background:rgba(0,200,83,0.1);color:var(--green-primary);padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:600;">
            <i class="bi bi-circle-fill me-1" style="font-size:0.45rem;vertical-align:middle;"></i> Live
          </span>

          <!-- Notification placeholder -->
          <button style="background:transparent;border:none;color:var(--text-muted);font-size:1.1rem;cursor:pointer;padding:0;">
            <i class="bi bi-bell"></i>
          </button>

          <!-- Avatar -->
          <div style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--green-secondary);display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-person-fill" style="color:var(--green-primary);font-size:0.85rem;"></i>
            </div>
            <span style="font-size:0.82rem;font-weight:500;color:var(--text-primary);">
              {{ $page.props.auth.user.name }}
            </span>
          </div>
        </div>
      </header>

      <!-- PAGE CONTENT -->
      <main style="padding:2rem;flex:1;">
        <slot />
      </main>

    </div>

  </div>
</template>

<script>
export default {
  name: 'AppLayout',
  computed: {
    currentPage() {
      const url = this.$page.url
      if (url === '/dashboard') return 'Dashboard'
      if (url.startsWith('/transactions')) return 'Transactions'
      if (url.startsWith('/categories')) return 'Catégories'
      if (url.startsWith('/savings')) return 'Objectifs d\'épargne'
      return 'BudgetApp'
    }
  },
  methods: {
    logout() {
      this.$inertia.post('/logout')
    }
  }
}
</script>