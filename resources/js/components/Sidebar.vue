<template>
  <div class="relative">
    <!-- Barra Superior para Móviles -->
    <div class="md:hidden flex min-w-screen w-auto items-center justify-between p-4 bg-[#101c22] border-b border-gray-700 z-50">
        <!-- Botón de Hamburguesa -->
      <button @click="toggleMenu" class="text-gray-400 hover:text-[#1193d4] focus:outline-none">
        <IconMenu2 v-if="!isMenuOpen" stroke={2} class="w-7 h-7" />
        <IconX v-else stroke={2} class="w-7 h-7" /> <!-- Icono de cerrar (opcional) -->
      </button>

      <div class="flex items-center gap-x-3">
        <img class="w-10 h-10 rounded-full border border-gray-700" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="Profile Picture">
      </div>
    </div>

    <!-- Menú Lateral / Overlay -->
    <div
      :class="{
        'flex flex-col w-80vh min-h-screen h-auto bg-[#101c22] border-r border-gray-700 transition-transform duration-300 ease-in-out': true,
        'fixed inset-y-0 left-0 z-50 transform translate-x-0': isMenuOpen, // Menu abierto en mobile
        'fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0': !isMenuOpen, // Menu cerrado en mobile, abierto en desktop
        'md:relative md:flex md:w-80 md:min-h-screen md:h-auto': true // Estilos de sidebar en desktop
      }"
    >
        <!-- Header del Sidebar (visible en desktop, o cuando el overlay está abierto en mobile) -->
         <div class="flex items-center justify-start p-6 gap-x-5">
            <img class="w-14 h-14 rounded-full border border-gray-700" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="Profile Picture">
            <h1 class="text-white text-lg poppins-normal">Farmacias La Rebaja</h1>
         </div>

         <!-- Body del Sidebar (ítems del menú) -->
         <div class="flex flex-col justify-between w-full h-full p-6">
            <ul>
                <li class="flex items-center gap-x-3 text-gray-400 poppins-medium cursor-pointer hover:bg-[#1193d4]/10 p-5 rounded-lg group">
                    <IconLayoutDashboard stroke={2} class="text-gray-400 text-xl group-hover:text-[#1193d4]" />
                    <span class="group-hover:text-[#1193d4]">Dashboard</span>
                </li>
                <li class="flex items-center gap-x-3 text-gray-400 poppins-medium cursor-pointer hover:bg-[#1193d4]/10 p-5 rounded-lg group">
                    <IconTemperatureSun  stroke={2} class="text-gray-400 text-xl group-hover:text-[#1193d4]" />
                    <span class="group-hover:text-[#1193d4]">Sensores</span>
                </li>
                <li class="flex items-center gap-x-3 text-gray-400 poppins-medium cursor-pointer hover:bg-[#1193d4]/10 p-5 rounded-lg group">
                    <IconBellExclamation stroke={2} class="text-gray-400 text-xl group-hover:text-[#1193d4]" />
                    <span class="group-hover:text-[#1193d4]">Alertas</span>
                </li>
                <li class="flex items-center gap-x-3 text-gray-400 poppins-medium cursor-pointer hover:bg-[#1193d4]/10 p-5 rounded-lg group">
                    <IconFileDescription stroke={2} class="text-gray-400 text-xl group-hover:text-[#1193d4]" />
                    <span class="group-hover:text-[#1193d4]">Reportes</span>
                </li>
                <li class="flex items-center gap-x-3 text-gray-400 poppins-medium cursor-pointer hover:bg-[#1193d4]/10 p-5 rounded-lg group">
                    <IconSettings stroke={2} class="text-gray-400 text-xl group-hover:text-[#1193d4]" />
                    <span class="group-hover:text-[#1193d4]">Configuración</span>
                </li>
            </ul>
            <button 
                @click="openAlert"
                class="flex items-center gap-x-3 text-gray-400 poppins-medium cursor-pointer hover:bg-[#1193d4]/10 p-5 rounded-lg group">
                <IconLogout stroke={2} class="text-gray-400 text-xl group-hover:text-[#1193d4]" />
                <span class="group-hover:text-[#1193d4]">Cerrar sesion</span>
            </button>
         </div>
    </div>

    <!-- Overlay de fondo cuando el menú está abierto en móvil -->
    <div
      v-if="isMenuOpen"
      @click="closeMenu"
      class="fixed inset-0 bg-black opacity-50 z-40 md:hidden"
    ></div>
  </div>

  <AlertDialog v-if="showAlertConfirm" :onConfirm="logout" :show="true" :onCancel="closeAlert" @close="closeAlert" />
</template>

<script setup>
import { ref } from 'vue';
import AlertDialog from './AlertDialog.vue';
import { useRouter } from 'vue-router';
import {
    IconLayoutDashboard,
    IconTemperatureSun,
    IconBellExclamation,
    IconFileDescription,
    IconSettings,
    IconLogout,
    IconMenu2, // Icono de hamburguesa
    IconX // Icono de cerrar (opcional, para el botón de hamburguesa)
}
from '@tabler/icons-vue';

const isMenuOpen = ref(false);
const showAlertConfirm = ref(false);
const router = useRouter();

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
    isMenuOpen.value = false;
};

const logout = () => {
  localStorage.removeItem('token');
  router.push({ name: 'login' })
}

const closeAlert = () => {
  showAlertConfirm.value = false;
}

const openAlert = () => {
  showAlertConfirm.value = true;
}
</script>