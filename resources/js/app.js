import './bootstrap';
import './echo';
import VueApexCharts from 'vue3-apexcharts';

import { createApp } from 'vue';
import router from './Routes';
import app from './app.vue';

const App = createApp(app);

App.use(router)
    .use(VueApexCharts)
    .mount('#app');