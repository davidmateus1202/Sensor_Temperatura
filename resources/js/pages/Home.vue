<template>
  <div class="relative flex flex-col md:flex-row w-full min-h-screen h-full bg-[#101c22] overflow-x-hidden">
    <!-- Sidebar -->
    <sidebar class="z-50 md:z-auto" @sectionChanged="changeSection" />

    <!-- Contenido -->
    <div v-if="toggleSection === 'sensores'" class="flex flex-col w-full h-auto text-white relative z-0 pt-5 px-5">
      <h1 class="text-2xl font-semibold p-4 poppins-semibold">Sensor de Temperatura #101c22</h1>

      <!-- Menu -->
      <div class="flex w-auto h-auto mx-5 gap-x-10 border-b border-gray-400">
        <div @click="changeState('Resumen')" class="flex flex-col w-auto cursor-pointer">
          <h1 :class="{
            'text-gray-400 text-sm': state !== 'Resumen',
            'text-[#1193d4] text-sm': state === 'Resumen',
          }">Resumen</h1>
          <div :class="{
            'w-full bg-[#1193d4] h-[2px] rounded-full mt-3 translate-y-[1px]': state === 'Resumen',
            'hidden': state !== 'Resumen'
          }"></div>
        </div>

        <div @click="changeState('Historial')" class="flex flex-col w-auto cursor-pointer">
          <h1 :class="{
            'text-gray-400 text-sm': state !== 'Historial',
            'text-[#1193d4] text-sm': state === 'Historial',
          }">Historial</h1>
          <div :class="{
            'w-full bg-[#1193d4] h-[2px] rounded-full mt-3 translate-y-[1px]': state === 'Historial',
            'hidden': state !== 'Historial'
          }"></div>
        </div>

        <div @click="changeState('Configuración')" class="flex flex-col w-auto cursor-pointer">
          <h1 :class="{
            'text-gray-400 text-sm': state !== 'Configuración',
            'text-[#1193d4] text-sm': state === 'Configuración',
          }">Configuración</h1>
          <div :class="{
            'w-full bg-[#1193d4] h-[2px] rounded-full mt-3 translate-y-[1px]': state === 'Configuración',
            'hidden': state !== 'Configuración'
          }"></div>
        </div>
      </div>

      <!-- Contenedor dinamico -->
      <div v-if="state === 'Historial'" class="flex flex-col w-full h-auto px-5 py-10">
        <h1 class="text-xl poppins-semibold">Historial de Lecturas</h1>
        <div class="flex flex-col w-full h-auto items-start justify-start border border-gray-600/50 mt-5 rounded-lg">
          <div class="p-5">
            <h1 class="poppins-normal text-sm">Temperatura (°C)</h1>
            <h1 class="text-3xl poppins-semibold">{{ currentTemperature }}</h1>
            <span class="poppins-normal text-gray-400 text-sm">Ultimas 24 horas</span>
          </div>
          <TemperatureChart class="w-full" :newTemperature="latestTemperatureData" />
        </div>
      </div>

      <div v-if="state === 'Configuración'"
        class="flex flex-col w-auto h-auto mx-5 my-10 border border-gray-600 rounded-lg">
        <div class="w-full border-b border-gray-600 p-5">
          <h1 class="poppins-semibold text-2xl">Estado del Sensor</h1>
          <span class="text-sm text-gray-400 poppins-medium">Activa o desactiva el sensor de forma remota.</span>
        </div>

        <div class="flex items-center justify-between w-full p-5">
          <div class="flex flex-col">
            <h1 class="poppins-semibold">Sensor</h1>
            <span class="text-sm text-gray-400">
              Actualmente
              <span v-if="isOn === true" class="poppins-semibold text-[#00f37a]">
                Encendido
              </span>
              <span v-if="isOn === false" class="poppins-semibold text-[#ff3c00]">
                Apagado
              </span>
            </span>
          </div>

          <div class="relative inline-flex items-center cursor-pointer transition-colors duration-200 ease-in-out"
            :class="{ 'bg-green-500': isOn, 'bg-gray-300': !isOn, 'w-10 h-6 rounded-full': true }"
            @click="toggleSwitch">
            <span
              class="absolute left-0 inline-block w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-200 ease-in-out"
              :class="{ 'translate-x-5': isOn, 'translate-x-0.5': !isOn }"></span>
          </div>


        </div>
      </div>
      <Sumary v-if="state === 'Resumen'" />
    </div>
    <Dashboard v-if="toggleSection === 'dashboard'" />
  </div>
  <AlertDialog v-if="showAlert" :title="'Error (500)'" :message="'Error al momento de encender el sensor'"
    @close="closeAlert" :onConfirm="closeAlert" />
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Sidebar from '../components/Sidebar.vue';
import TemperatureChart from '../components/TemperatureChart.vue';
import axios from 'axios';
import AlertDialog from '../components/AlertDialog.vue';
import Sumary from '../components/Sumary.vue';
import Dashboard from './Dashboard.vue';

const state = ref('Historial');
const latestTemperatureData = ref(null);
const currentTemperature = ref('N/A');
const isOn = ref(false);
const showAlert = ref(false);
const toggleSection = ref('sensores');

onMounted(() => {
  requestNotificationPermission();

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
        handleTemperatureNotification(e);
      });

  } catch (error) {
    console.error('❌ Error al intentar suscribirse:', error);
  }
});

const requestNotificationPermission = () => {
  if ("Notification" in window) {
    Notification.requestPermission().then((permission) => {
      if (permission === "granted") {
        console.log("✅ Permiso de notificaciones concedido.");
      } else {
        console.log("❌ Permiso de notificaciones denegado.");
      }
    });
  } else {
    console.log("❌ Las notificaciones no son soportadas en este navegador.");
  }
}

const handleTemperatureNotification = (e) => {
  if (e.temperatura && e.timestamp) {

    latestTemperatureData.value = {
      temperatura: e.temperatura,
      timestamp: e.timestamp
    };

    currentTemperature.value = parseFloat(e.temperatura).toFixed(1);

    const temp = parseFloat(e.temperatura);

    // ✔ Temperatura MUY BAJA
    if (temp < 10) {
      showTemperatureAlert(
        "⚠ Temperatura muy baja",
        `La temperatura cayó a ${temp}°C`
      );
    }

    // ✔ Temperatura MUY ALTA
    if (temp > 80) {
      showTemperatureAlert(
        "🔥 Temperatura muy alta",
        `La temperatura subió a ${temp}°C`
      );
    }
  }
}

const showTemperatureAlert = (title, message) => {
  if (!("Notification" in window)) return;

  if (Notification.permission === "granted") {
    new Notification(title, {
      body: message,
      icon: "/icon.png"
    });
  }
};


const changeState = (stateValue) => {
  state.value = stateValue;
}

const toggleSwitch = async () => {
  isOn.value = !isOn.value
  const response = await axios.post('/api/turn/sensor',
    {
      estado: isOn.value ? 'on' : 'off'
    },
    {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    }
  )

  if (response.status !== 200) {
    showAlert.value = true;
  }
}

const closeAlert = () => {
  showAlert.value = false;
}

const changeSection = (section) => {
  toggleSection.value = section;
}

</script>
