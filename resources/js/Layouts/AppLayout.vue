<template>
    <div>
        <nav>
            <span>Budget App</span>
            <span>{{ $page.props.auth.user.name }}</span>
            <a href="#" @click.prevent="logout">Déconnexion</a>
        </nav>
        <main>
            <slot />
        </main>
    </div>
</template>

<script>
export default {
    name: 'Layout',
    methods: {
        logout() {
            this.$inertia.post('/logout')
        }
    }
}
</script>
