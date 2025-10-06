<template>
    <div class="flex flex-col items-center justify-center w-full min-h-screen h-full bg-[#101c22]">
        <div class="w-full h-[40px] p-10 flex items-center">
            <IconMedicineSyrup stroke={2} class="text-[#1193d4]" />
            <h1 class="text-white poppins-semibold text-2xl">PharmaTrack</h1>
        </div>

        <!-- Body -->
        <div class="flex flex-col w-auto h-full items-center justify-center flex-1 p-10">
            <h1 class="text-white poppins-semibold text-3xl text-center">Bienvenido de nuevo</h1>
            <span class="text-gray-600 poppins-medium text-sm">Inicia sesion en tu sensor de temperatura</span>

            <form @submit.prevent="checkLogin" action="" class="flex flex-col mt-5 w-full items-center justify-center">
                <input v-model="email" type="email" placeholder="Correo Electronico"
                    class="border border-gray-600 p-2 w-80 md:w-96 placeholder:text-gray-400 text-gray-400 rounded-t-lg focus:border-gray-600 outline-none"
                    required>
                <input v-model="password" type="password" placeholder="Contraseña"
                    class="border border-gray-600 p-2 w-80 md:w-96 placeholder:text-gray-400 text-gray-400 rounded-b-lg focus:border-gray-600 outline-none"
                    required>
                <div class="flex w-full h-auto justify-end mt-5">
                    <h1 class="poppins-normal text-[#1193d4]">Aun no tienes cuenta?</h1>
                </div>

                <button type="submit"
                    class="bg-[#1193d4] text-white w-full mt-5 hover:bg-[#067fc3] p-2 rounded-lg shadow-xl cursor-pointer">
                    Iniciar sesion
                </button>
            </form>
        </div>

    </div>
    <Loading v-if="showLoadingScreen" :is-actually-loading="!allComponentsReady" />
    <AlertDialog v-if="showAlert" :title="title" :message="content" :onConfirm="close" @close="close"/>
</template>

<script setup>
import { IconMedicineSyrup } from '@tabler/icons-vue';
import { ref } from 'vue';
import { useAuthStore } from '../store/authSotre';
import { useRouter } from 'vue-router';

//componentes
import Loading from '../components/Loading.vue';
import AlertDialog from '../components/AlertDialog.vue';

const allComponentsReady = ref(false)
const showLoadingScreen = ref(false);
const FADE_OUT_DURATION_LOADING_ELEMENTS = 0.2;
const EXPANSION_DURATION_LOADING = 0.7;
const email = ref('');
const password = ref('');
const title = ref('');
const content = ref('');
const showAlert = ref(false);
const router = useRouter();

const auth = useAuthStore();

const checkLogin = async () => {
    showLoadingScreen.value = true;
    allComponentsReady.value = false;

    const response = await auth.login(email.value, password.value);

    if (response === true) {
        router.push({ name: 'home' })
    } else {
        title.value = 'Error autenticacion';
        content.value = response.message;
        showAlert.value = true;
    }

    allComponentsReady.value = true;
    const totalLoadingTransitionTime = FADE_OUT_DURATION_LOADING_ELEMENTS + EXPANSION_DURATION_LOADING;
    setTimeout(() => {
        showLoadingScreen.value = false;
    }, (totalLoadingTransitionTime * 1000) + 100);
}

const close = () => {
    showAlert.value = false;
}


</script>