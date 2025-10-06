import { createRouter, createWebHistory} from 'vue-router';

import Home from '../pages/Home.vue';
import Login from '../pages/Login.vue';

const routes = [
    {
        path: '/home',
        name: 'home',
        component: Home,
        meta: { requiresAuth: true }
    },
    {
        path: '/',
        name: 'login',
        component: Login
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,

    scrollBehavior() {
        return { top: 0 }
    }
});

router.beforeEach(async (to, form, next) => {
    const token = localStorage.getItem('token');

    if (to.matched.some(record => record.meta.requiresAuth) && !token) {
        return next({ name: 'login' });
    }

    if (to.name === 'login' && token) {
        return next({ name: 'home' });
    }

    next();
})

export default router;