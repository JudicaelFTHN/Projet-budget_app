<template>
  <div style="min-height:100vh;background:var(--bg-primary);">

    <!-- ══════════════════════════════════════════
         OVERLAY (mobile) — ferme le drawer
    ══════════════════════════════════════════ -->
    <div
      v-if="drawerOpen"
      class="sidebar-overlay"
      @click="drawerOpen = false"
    ></div>

    <!-- ══════════════════════════════════════════
         SIDEBAR
         • desktop : fixe, toujours visible
         • mobile  : drawer glissant (translateX)
    ══════════════════════════════════════════ -->
    <aside :class="['sidebar', { 'sidebar--open': drawerOpen }]">

      <!-- Logo -->
      <div class="sidebar-logo d-flex align-items-center justify-content-between">
        <span><i class="bi bi-currency-dollar"></i> BudgetApp</span>
        <!-- Croix fermeture (mobile uniquement) -->
        <button
          class="sidebar-close d-lg-none"
          @click="drawerOpen = false"
          aria-label="Fermer le menu"
        >
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav style="flex:1;">
        <a href="/dashboard"    class="sidebar-link" :class="{ active: $page.url === '/dashboard' }"                @click="drawerOpen = false">
          <i class="bi bi-grid-1x2-fill"></i>
          <span class="sidebar-label">Dashboard</span>
        </a>
        <a href="/transactions" class="sidebar-link" :class="{ active: $page.url.startsWith('/transactions') }"    @click="drawerOpen = false">
          <i class="bi bi-arrow-left-right"></i>
          <span class="sidebar-label">Transactions</span>
        </a>
        <a href="/categories"   class="sidebar-link" :class="{ active: $page.url.startsWith('/categories') }"      @click="drawerOpen = false">
          <i class="bi bi-tag-fill"></i>
          <span class="sidebar-label">Catégories</span>
        </a>
        <a href="/savings"      class="sidebar-link" :class="{ active: $page.url.startsWith('/savings') }"         @click="drawerOpen = false">
          <i class="bi bi-bullseye"></i>
          <span class="sidebar-label">Objectifs</span>
        </a>
      </nav>

      <!-- User footer -->
      <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
          <div class="sidebar-avatar">
            <i class="bi bi-person-fill" style="color:var(--green-primary);"></i>
          </div>
          <div style="overflow:hidden;min-width:0;">
            <div class="sidebar-username">{{ $page.props.auth.user.name }}</div>
            <div class="sidebar-email">{{ $page.props.auth.user.email }}</div>
          </div>
        </div>
        <button @click="logout" class="btn-outline-green w-100" style="font-size:0.78rem;padding:6px;">
          <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
        </button>
      </div>

    </aside>

    <!-- ══════════════════════════════════════════
         MAIN WRAPPER
    ══════════════════════════════════════════ -->
    <div class="main-wrapper">

      <!-- TOPBAR -->
      <header class="topbar">

        <!-- Bouton hamburger (mobile) -->
        <button
          class="hamburger d-lg-none"
          @click="drawerOpen = true"
          aria-label="Ouvrir le menu"
        >
          <i class="bi bi-list"></i>
        </button>

        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-2">
          <span class="topbar-brand d-none d-sm-inline">BudgetApp</span>
          <i class="bi bi-chevron-right topbar-sep d-none d-sm-inline"></i>
          <span class="topbar-page">{{ currentPage }}</span>
        </div>

        <!-- Right side -->
        <div class="d-flex align-items-center gap-2 gap-sm-3">
          <!-- Badge live — masqué sur très petit écran -->
          <span class="badge-live d-none d-sm-flex">
            <i class="bi bi-circle-fill me-1" style="font-size:0.45rem;vertical-align:middle;"></i>Live
          </span>

          <!-- Notification -->
          <button class="topbar-icon-btn">
            <i class="bi bi-bell"></i>
          </button>

          <!-- Avatar + nom -->
          <div class="d-flex align-items-center gap-2 topbar-user">
            <div class="topbar-avatar">
              <i class="bi bi-person-fill" style="color:var(--green-primary);font-size:0.85rem;"></i>
            </div>
            <span class="topbar-username d-none d-md-inline">
              {{ $page.props.auth.user.name }}
            </span>
          </div>
        </div>

      </header>

      <!-- PAGE CONTENT -->
      <main class="page-content">
        <slot />
      </main>

    </div><!-- /main-wrapper -->

  </div>
</template>

<script>
export default {
  name: 'AppLayout',

  data() {
    return {
      drawerOpen: false,
    }
  },

  computed: {
    currentPage() {
      const url = this.$page.url
      if (url === '/dashboard')           return 'Dashboard'
      if (url.startsWith('/transactions')) return 'Transactions'
      if (url.startsWith('/categories'))  return 'Catégories'
      if (url.startsWith('/savings'))     return "Objectifs d'épargne"
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

<style scoped>
/* ══════════════════════
   Variables locales
══════════════════════ */
:root {
  --sidebar-width: 240px;
}

/* ══════════════════════
   Overlay (mobile)
══════════════════════ */
.sidebar-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  z-index: 199;
  backdrop-filter: blur(2px);
}

/* ══════════════════════
   Sidebar
══════════════════════ */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: var(--sidebar-width);
  background: var(--bg-secondary);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  z-index: 200;
  transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
  overflow-y: auto;
}

/* Mobile : caché par défaut, visible quand .sidebar--open */
@media (max-width: 991.98px) {
  .sidebar {
    transform: translateX(-100%);
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.4);
  }
  .sidebar--open {
    transform: translateX(0);
  }
}

/* ── Logo ── */
.sidebar-logo {
  padding: 1.25rem 1.25rem 1rem;
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--green-primary);
  border-bottom: 1px solid var(--border-color);
  flex-shrink: 0;
}

/* ── Bouton fermeture drawer ── */
.sidebar-close {
  background: transparent;
  border: none;
  color: var(--text-muted);
  font-size: 1.1rem;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}
.sidebar-close:hover {
  color: var(--text-primary);
}

/* ── Liens ── */
.sidebar-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0.7rem 1.25rem;
  color: var(--text-muted);
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  transition: background 0.15s, color 0.15s;
  border-radius: 0;
}
.sidebar-link:hover {
  background: rgba(0, 200, 83, 0.07);
  color: var(--text-primary);
}
.sidebar-link.active {
  background: rgba(0, 200, 83, 0.12);
  color: var(--green-primary);
  border-right: 3px solid var(--green-primary);
}
.sidebar-label {
  /* visible par défaut, jamais masqué ici — la sidebar a toujours sa largeur */
}

/* ── Footer utilisateur ── */
.sidebar-footer {
  padding: 1rem 1.25rem;
  border-top: 1px solid var(--border-color);
  flex-shrink: 0;
}
.sidebar-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--green-secondary);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.sidebar-username {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--text-primary);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.sidebar-email {
  font-size: 0.72rem;
  color: var(--text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ══════════════════════
   Main wrapper
══════════════════════ */
.main-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  /* Desktop : décalé de la largeur de la sidebar */
  margin-left: var(--sidebar-width);
  transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Mobile : pas de décalage */
@media (max-width: 991.98px) {
  .main-wrapper {
    margin-left: 0;
  }
}

/* ══════════════════════
   Topbar
══════════════════════ */
.topbar {
  background: var(--bg-secondary);
  border-bottom: 1px solid var(--border-color);
  padding: 0.875rem 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 50;
  gap: 0.75rem;
}

@media (min-width: 576px) {
  .topbar {
    padding: 0.875rem 2rem;
  }
}

/* ── Hamburger ── */
.hamburger {
  background: transparent;
  border: none;
  color: var(--text-primary);
  font-size: 1.4rem;
  cursor: pointer;
  padding: 0;
  line-height: 1;
  flex-shrink: 0;
}

/* ── Breadcrumb ── */
.topbar-brand {
  color: var(--text-muted);
  font-size: 0.8rem;
}
.topbar-sep {
  color: var(--text-muted);
  font-size: 0.7rem;
}
.topbar-page {
  color: var(--text-primary);
  font-size: 0.85rem;
  font-weight: 600;
}

/* ── Badge live ── */
.badge-live {
  display: flex;
  align-items: center;
  background: rgba(0, 200, 83, 0.1);
  color: var(--green-primary);
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 0.72rem;
  font-weight: 600;
  white-space: nowrap;
}

/* ── Icône notification ── */
.topbar-icon-btn {
  background: transparent;
  border: none;
  color: var(--text-muted);
  font-size: 1.1rem;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}
.topbar-icon-btn:hover {
  color: var(--text-primary);
}

/* ── Avatar topbar ── */
.topbar-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--green-secondary);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.topbar-user {
  cursor: pointer;
}
.topbar-username {
  font-size: 0.82rem;
  font-weight: 500;
  color: var(--text-primary);
}

/* ══════════════════════
   Contenu page
══════════════════════ */
.page-content {
  padding: 1.25rem;
  flex: 1;
}

@media (min-width: 768px) {
  .page-content {
    padding: 2rem;
  }
}
</style>
