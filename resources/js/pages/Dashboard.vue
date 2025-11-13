<template>
    <div class="flex flex-col w-full h-auto text-white relative z-0 p-9">
        <h1 class="text-2xl font-semibold poppins-semibold">Sistema Dashboard</h1>
        <span class="text-gray-500">Descripción general de todas la farmacia y sensore</span>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 w-full mt-10">
            <div class="flex flex-col p-5 bg-green-500/40 rounded-lg gap-5 items-center border border-gray-600 w-auto">
                <h1 class="text-sm text-gray-400">Sensores Activos</h1>
                <span class="font-black text-2xl">1</span>
            </div>

            <div class="flex flex-col p-5 bg-gray-950/55 rounded-lg gap-5 items-center border border-gray-600 w-auto">
                <h1 class="text-sm text-gray-400">Registros Obtenidos</h1>
                <span class="font-black text-2xl">{{ numberofdate }}</span>
            </div>

            <div class="flex flex-col p-5 bg-red-500/40 rounded-lg gap-5 items-center border border-gray-600 w-auto">
                <h1 class="text-sm text-gray-400">Sensores Offline</h1>
                <span class="font-black text-2xl">0</span>
            </div>
        </div>

        <div class="flex flex-col w-full bg-gray-950/55 border border-gray-600 rounded-lg mt-10 p-5">

            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold poppins-semibold">Recent Alerts & Notifications</h1>
            </div>

            <div class="mt-5 flex flex-col divide-y divide-gray-700">
                <div v-for="alert in alerts" :key="alert.id" class="flex items-center justify-between py-4">
                    <div class="flex gap-4 items-start">

                        <!-- Icono -->
                        <div>
                            <!-- High Temperature -->
                            <IconTemperatureSun v-if="alert.type === 'high'" class="text-yellow-400" stroke="2" />

                            <!-- Low Temperature -->
                            <IconSnowflake v-if="alert.type === 'low'" class="text-blue-400" stroke="2" />
                        </div>

                        <!-- Texto -->
                        <div class="flex flex-col">
                            <h2 class="text-white font-semibold text-sm">
                                {{ alert.type === 'high' ? 'High Temperature Alert' : 'Low Temperature Alert' }}
                            </h2>
                            <span class="text-gray-400 text-xs">
                                Valor: {{ alert.value }}°C — Sensor #{{ alert.id }}
                            </span>
                        </div>
                    </div>

                    <!-- Tiempo -->
                    <span class="text-gray-500 text-xs">
                        {{ timeAgo(alert.created_at) }}
                    </span>

                </div>
            </div>
        </div>
    </div>
    <Loading v-if="showLoadingScreen" :is-actually-loading="!allComponentsReady" />
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { IconTemperatureSun, IconSnowflake } from '@tabler/icons-vue';
import Loading from '../components/Loading.vue';

import axios from 'axios';

const data = ref(null);
const numberofdate = ref(0);
const allComponentsReady = ref(false)
const showLoadingScreen = ref(false);
const FADE_OUT_DURATION_LOADING_ELEMENTS = 0.2;
const EXPANSION_DURATION_LOADING = 0.7;


onMounted(async () => {
    showLoadingScreen.value = true;
    allComponentsReady.value = false;
    await fetchData();
        allComponentsReady.value = true;
    const totalLoadingTransitionTime = FADE_OUT_DURATION_LOADING_ELEMENTS + EXPANSION_DURATION_LOADING;
    setTimeout(() => {
        showLoadingScreen.value = false;
    }, (totalLoadingTransitionTime * 1000) + 100);
});

const fetchData = async () => {
    try {
        const response = await axios.get('api/sumary', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });

        if (response.status === 200) {
            data.value = response.data.todayData;
            numberofdate.value = response.data.numberofdate;
        }

        console.log('Dashboard data:', data.value);
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    }
}

const alerts = computed(() => {
    if (!data.value) return [];

    return data.value
        .filter(item => item.value > 65 || item.value < 45)
        .map(alert => ({
            ...alert,
            type: alert.value > 65 ? 'high' : 'low'
        }))
        .slice(-5) // últimas 5 alertas
        .reverse(); // más recientes primero
});

const timeAgo = (date) => {
    const now = new Date();
    const past = new Date(date);

    const diff = Math.floor((now - past) / 1000); // segundos

    if (diff < 60) return `${diff}s ago`;
    if (diff < 3600) return `${Math.floor(diff / 60)} min ago`;
    return `${Math.floor(diff / 3600)} hours ago`;
};


</script>