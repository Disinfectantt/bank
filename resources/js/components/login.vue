<script>
import useVuelidate from '@vuelidate/core';
import { required, helpers } from '@vuelidate/validators';
import axios from 'axios';
export default {
    data() {
        return {
            email: '',
            password: '',
            error: false,
            loading: false
        }
    },
    setup() {
        return { v$: useVuelidate() }
    },
    validations() {
        return {
            email: {
                required: helpers.withMessage('Обязательно к заполнению', required),
            },
            password: {
                required: helpers.withMessage('Обязательно к заполнению', required),
            },
        }
    },
    methods: {
        sendFormLogin() {
            if (this.v$.$invalid) {
                this.v$.email.$touch();
                this.v$.password.$touch();
                return;
            }
            this.loading = true;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('api/login', { email: this.email, password: this.password }).then(response => {
                    this.$router.push({ name: 'admin_panel' });
                }).catch(error => {
                    this.error = true;
                    console.log(error);
                })
            }).finally(() => (this.loading = false));
        }
    }
}
</script>

<template>
    <section>
        <form class="l" @submit.prevent="sendFormLogin">
            <div>
                <span class="error err_bg" v-if="error">Ошибка авторизации</span>
                <label for="surname">Email</label>
                <input @blur="v$.email.$touch()" :class="{ error: v$.email.$invalid && v$.email.$dirty }"
                    v-model="email" type="text" class="text">
                <span class="error" v-if="v$.email.$dirty && v$.email.required.$invalid">{{
                v$.email.required.$message }}</span>
            </div>
            <div><label for="name">Password</label>
                <input @blur="v$.password.$touch()" :class="{ error: v$.password.$invalid && v$.password.$dirty }"
                    v-model="password" type="password" class="text">
                <span class="error" v-if="v$.password.$dirty && v$.password.required.$invalid">{{
                v$.password.required.$message }}</span>
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Login</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>
    </section>
</template>

<style scoped>
section {
    margin-top: 8rem;
}

.l {
    width: 80%;
}
</style>