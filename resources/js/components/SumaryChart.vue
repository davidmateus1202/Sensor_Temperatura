<template>
    <div class="w-full h-96 mt-10">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Chart, LineController, LineElement, PointElement, LinearScale, TimeScale, Title, Tooltip, Legend, CategoryScale } from "chart.js";
import 'chartjs-adapter-date-fns';

Chart.register(LineController, LineElement, PointElement, LinearScale, TimeScale, Title, Tooltip, Legend, CategoryScale);

const props = defineProps({
    todayData: {
        type: Array,
        required: true,
        default: () => []
    },
    minAllowed: Number,
    maxAllowed: Number
});

const chartCanvas = ref(null);
let myChart = null;

onMounted(() => {

    console.log("Datos recibidos para la gráfica:", props.todayData);
    const labels = props.todayData.map(item =>
        new Date(item.created_at).toLocaleTimeString("es-CO", {
            hour: "2-digit",
            minute: "2-digit"
        })
    );

    const values = props.todayData.map(item => item.value);

    myChart = new Chart(chartCanvas.value, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "Temperatura",
                    data: values,
                    borderColor: "#00AFFF",
                    backgroundColor: "rgba(0, 175, 255, 0.20)",
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: "#00AFFF",
                    pointBorderColor: "#ffffff",
                    pointHoverRadius: 6
                },

                {
                    label: "Límite Superior",
                    data: Array(values.length).fill(props.maxAllowed),
                    borderDash: [6, 6],
                    borderWidth: 2,
                    pointRadius: 0
                },
                {
                    label: "Límite Inferior",
                    data: Array(values.length).fill(props.minAllowed),
                    borderDash: [6, 6],
                    borderWidth: 2,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: "Temperatura (°C)"
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: "Hora"
                    }
                }
            }
        }
    });

});
</script>
