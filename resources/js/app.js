import { plugin, InertiaApp } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Home from './Pages/Home.vue'

Vue.use(plugin)

const pages = {
    'Home': Home,
}

const el = document.getElementById('app')

new Vue({
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => pages[name],
        },
    }),
}).$mount(el)
