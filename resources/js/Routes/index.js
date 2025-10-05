import { createRouter, createWebHistory} from 'vue-router';

import Home from '../pages/Home.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,

    scrollBehavior() {
        return { top: 0 }
    }
})

export default router;