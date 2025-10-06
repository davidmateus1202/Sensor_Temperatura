import './bootstrap';
import './echo';
import VueApexCharts from 'vue3-apexcharts';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './Routes';
import app from './app.vue';

const pinia = createPinia();
const App = createApp(app);

App.use(pinia)
    .use(router)
    .use(VueApexCharts)
    .mount('#app');