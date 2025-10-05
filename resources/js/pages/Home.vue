<template>
  <div class="relative flex flex-col md:flex-row w-full h-full bg-[#101c22] overflow-x-hidden">
    <!-- Sidebar -->
    <sidebar class="z-50 md:z-auto" />

    <!-- Contenido -->
    <div class="flex flex-col w-full min-h-screen h-auto text-white relative z-0 pt-5 px-5">
      <h1 class="text-2xl font-semibold p-4 poppins-semibold">Sensor de Temperatura #101c22</h1>

      <!-- Menu -->
      <div class="flex w-auto h-auto mx-5 gap-x-10 border-b border-gray-400">
        <div 
        @click="changeState('Resumen')"
        class="flex flex-col w-auto cursor-pointer">
          <h1 :class="{
            'text-gray-400 text-sm': state !== 'Resumen',
            'text-[#1193d4] text-sm': state === 'Resumen',
          }">Resumen</h1>
          <div :class="{
            'w-full bg-[#1193d4] h-[2px] rounded-full mt-3 translate-y-[1px]': state === 'Resumen',
            'hidden': state !== 'Resumen'
          }"></div>
        </div>

        <div 
        @click="changeState('Historial')"
        class="flex flex-col w-auto cursor-pointer">
          <h1 :class="{
            'text-gray-400 text-sm': state !== 'Historial',
            'text-[#1193d4] text-sm': state === 'Historial',
          }">Historial</h1>
          <div :class="{
            'w-full bg-[#1193d4] h-[2px] rounded-full mt-3 translate-y-[1px]': state === 'Historial',
            'hidden': state !== 'Historial'
          }"></div>
        </div>

        <div 
        @click="changeState('Configuración')"
        class="flex flex-col w-auto cursor-pointer">
          <h1 :class="{
            'text-gray-400 text-sm': state !== 'Configuración',
            'text-[#1193d4] text-sm': state === 'Configuración',
          }" >Configuración</h1>
          <div :class="{
            'w-full bg-[#1193d4] h-[2px] rounded-full mt-3 translate-y-[1px]': state === 'Configuración',
            'hidden': state !== 'Configuración'
          }"></div>
        </div>
      </div>

      <!-- Contenedor dinamico -->
       <div class="flex flex-col w-full h-auto px-5 py-10">
          <h1 class="text-xl poppins-semibold">Historial de Lecturas</h1>
          <div class="flex flex-col w-full h-auto items-start justify-start border border-gray-600/50 mt-5 rounded-lg">
            <div class="p-5">
              <h1 class="poppins-normal text-sm">Temperatura (°C)</h1>
              <h1 class="text-3xl poppins-semibold">{{ currentTemperature }}</h1>
              <span class="poppins-normal text-gray-400 text-sm">Ultimas 24 horas</span>
            </div>
            <TemperatureChart class="w-full" :newTemperature="latestTemperatureData"/>
          </div>
       </div>

    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Sidebar from '../components/Sidebar.vue';
import TemperatureChart from '../components/TemperatureChart.vue';

const state = ref('Historial');
const latestTemperatureData = ref(null);
const currentTemperature = ref('N/A');

onMounted(() => {
  try {
    window.Echo.channel('sensor')
      .listen('MessageSent', (e) => {
        if (e.temperatura && e.timestamp) {
          latestTemperatureData.value = {
            temperatura: e.temperatura,
            timestamp: e.timestamp
          };
          currentTemperature.value = parseFloat(e.temperatura).toFixed(1);
        }
      });

  } catch (error) {
    console.error('❌ Error al intentar suscribirse:', error);
  }
});

const changeState = (stateValue) => {
  state.value = stateValue;
}


</script>
