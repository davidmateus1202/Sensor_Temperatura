<template>
    <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
</template>

<script setup>
import { ref, watch } from 'vue';
import moment from 'moment'; // Importa moment.js

// Máximo de puntos de datos a mostrar
const MAX_DATA_POINTS = 7;

// Datos reactivos para la serie de la gráfica
const series = ref([{
    name: 'Temperatura (°C)',
    data: [] // Inicialmente vacío
}]);

// Opciones reactivas para la gráfica
const chartOptions = ref({
    chart: {
        id: 'temperature-readings',
        toolbar: {
            show: false
        },
        zoom: {
            enabled: false
        },
        animations: { // ¡NUEVO! Configuración de animaciones
            enabled: true, // Habilita las animaciones generales
            easing: 'easeinout', // Tipo de curva de animación
            speed: 800, // Velocidad general de la animación en ms
            animateGradually: {
                enabled: true,
                delay: 150
            },
            dynamicAnimation: { // ¡CLAVE! Animación para actualizaciones de datos
                enabled: true,
                speed: 350 // Velocidad de la animación cuando los datos cambian
            }
        }
    },
    xaxis: {
        categories: [], // Inicialmente vacío, se llenará con las horas
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        labels: {
            style: {
                colors: '#A0A0A0' // Color de las etiquetas de hora
            },
            rotate: -45, // ¡NUEVO! Rota las etiquetas 45 grados para evitar solapamientos
            // tickPlacement: 'on' // Esto puede ayudar a alinear las etiquetas con los puntos de datos
        }
    },
    yaxis: {
        labels: {
            show: false // Oculta las etiquetas del eje Y
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth', // Línea suave
        width: 3,
        colors: ['#3A8DCD'] // Color de la línea (azul)
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0,
            stops: [0, 90, 100],
            colors: ['#101c22'] // Color del relleno del área, que se fundirá con el fondo si es el mismo.
        }
    },
    tooltip: {
        enabled: true,
        theme: 'dark' // Tema oscuro para el tooltip
    },
    grid: {
        show: false // Oculta la cuadrícula
    }
});

const props = defineProps({
    newTemperature: Object
});

watch(() => props.newTemperature, (newVal) => {
    if (newVal && newVal.temperatura && newVal.timestamp) {
        const temperature = parseFloat(newVal.temperatura);
        // Formatear el timestamp a HH:mm
        const time = moment(newVal.timestamp).format('HH:mm'); 

        // Crea copias de los arrays actuales para asegurar la reactividad
        const currentData = [...series.value[0].data];
        const currentCategories = [...chartOptions.value.xaxis.categories];

        // Añadir el nuevo dato y categoría
        currentData.push(temperature);
        currentCategories.push(time);

        // Mantener solo los últimos MAX_DATA_POINTS datos
        if (currentData.length > MAX_DATA_POINTS) {
            currentData.shift(); // Eliminar el dato más antiguo
            currentCategories.shift(); // Eliminar la categoría más antigua
        }

        // Actualizar las series de la gráfica, asignando un NUEVO array de datos
        series.value = [{
            name: 'Temperatura (°C)',
            data: currentData
        }];

        // Actualizar las categorías del eje X de la gráfica, asignando un NUEVO array de categorías
        chartOptions.value = {
            ...chartOptions.value, // Copia las opciones existentes
            xaxis: {
                ...chartOptions.value.xaxis, // Copia las opciones existentes del eje X
                categories: '' // Asigna el nuevo array de categorías
            }
        };
    }
}, { deep: true }); // Observar cambios profundos en el objeto prop
</script>