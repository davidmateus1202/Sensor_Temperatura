<template>
    <div class="flex flex-col w-auto max-w-full overflow-hidden mx-5 my-10">

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-5 w-full">

            <div class="flex p-5 bg-gray-950/55 rounded-lg gap-5 items-center border border-gray-600 w-auto">
                <div class="flex items-center justify-center aspect-square bg-[#1193d4]/50 p-3 rounded-full">
                    <IconTemperatureMinusFilled class="text-[#1193d4]" />
                </div>
                <div class="flex flex-col gap-y-2">
                    <h1 class="text-sm text-gray-400">Temperatura <br /> Promedio</h1>
                    <span class="font-black text-2xl">{{ averageData }}°C</span>
                </div>
            </div>

            <div class="flex p-5 bg-gray-950/55 rounded-lg gap-5 items-center border border-gray-600 w-full">
                <div class="flex items-center justify-center aspect-square bg-green-500/50 p-3 rounded-full">
                    <IconArrowDown class="text-green-500" />
                </div>
                <div class="flex flex-col gap-y-2">
                    <h1 class="text-sm text-gray-400">Temperatura <br /> Minima</h1>
                    <span class="font-black text-2xl">{{ minData }}°C</span>
                </div>
            </div>

            <div class="flex p-5 bg-gray-950/55 rounded-lg gap-5 items-center border border-gray-600 w-full">
                <div class="flex items-center justify-center aspect-square bg-red-500/50 p-3 rounded-full">
                    <IconArrowUp class="text-red-500" />
                </div>
                <div class="flex flex-col gap-y-2">
                    <h1 class="text-sm text-gray-400">Temperatura <br /> Maxima</h1>
                    <span class="font-black text-2xl">{{ maxData }}°C</span>
                </div>
            </div>

            <div class="flex p-5 bg-gray-950/55 rounded-lg gap-5 items-center border border-gray-600 w-full">
                <div class="flex items-center justify-center aspect-square bg-yellow-500/50 p-3 rounded-full">
                    <IconAlertTriangle class="text-yellow-500" />
                </div>
                <div class="flex flex-col gap-y-2">
                    <h1 class="text-sm text-gray-400">Desviaciones</h1>
                    <span class="font-black text-2xl">{{ outOfRangeData }}°C</span>
                </div>
            </div>
        </div>

        <SumaryChart v-if="summaryData.length > 0" class="w-full mt-10" :todayData="summaryData" :minAllowed="minData"
            :maxAllowed="maxData" />

    </div>
    <Loading v-if="showLoadingScreen" :is-actually-loading="!allComponentsReady" />
</template>

<script setup>
import { IconTemperatureMinusFilled, IconArrowDown, IconArrowUp, IconAlertTriangle } from '@tabler/icons-vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import Loading from './Loading.vue';
import SumaryChart from './SumaryChart.vue';

const summaryData = ref([]);
const minData = ref(0);
const maxData = ref(0);
const outOfRangeData = ref(0);
const averageData = ref(0);

const allComponentsReady = ref(false)
const showLoadingScreen = ref(false);
const FADE_OUT_DURATION_LOADING_ELEMENTS = 0.2;
const EXPANSION_DURATION_LOADING = 0.7;

onMounted(async () => {
    showLoadingScreen.value = true;
    allComponentsReady.value = false;

    await fetchSummaryData();

    allComponentsReady.value = true;
    const totalLoadingTransitionTime = FADE_OUT_DURATION_LOADING_ELEMENTS + EXPANSION_DURATION_LOADING;
    setTimeout(() => {
        showLoadingScreen.value = false;
    }, (totalLoadingTransitionTime * 1000) + 100);
});

const fetchSummaryData = async () => {
    try {
        const response = await axios.get('api/sumary', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });

        if (response.status === 200) {
            summaryData.value = response.data.todayData;
            minData.value = response.data.minTemp;
            maxData.value = response.data.maxTemp;
            outOfRangeData.value = response.data.outOfRange;
            averageData.value = response.data.averageTemp;
        }
        console.log("Datos del resumen obtenidos:", summaryData.value);

    } catch (error) {
        console.error('Error fetching summary data:', error);
    }
}

</script>