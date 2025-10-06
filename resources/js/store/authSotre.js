import axios from "axios";
import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: {}
        }
    },

    actions: {
        async login(email, password) {
            try {
                const response = await axios.post('/api/login', {
                    email: email,
                    password: password
                });

                if (response.status === 200) {
                    localStorage.setItem('token', response.data.token);
                    this.user = response.data.user;
                }

                return true;

            } catch (error) {
                return error.response.data
            }
        }
    }
})