<script>
import useVuelidate from '@vuelidate/core';
import { required, helpers, email } from '@vuelidate/validators';
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
                email: helpers.withMessage('Email должен быть корректным', email),
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
                    this.isAuthenticated();
                    this.$router.push({ name: 'home' });
                }).catch(error => {
                    this.error = true;
                    console.log(error.response.data);
                })
            }).finally(() => (this.loading = false));
        },
        isAuthenticated() {
            axios.get(`api/user`).then(response => {
                this.$store.state.isAuth = true;
                this.$store.state.email = response.data.email;
                if (response.data.role >= 5) {
                    this.$store.state.isAdmin = true;
                }
            }).catch(error => {
                this.$store.state.isAuth = false;
                this.$store.state.isAdmin = false;
            });
        },
    },
    computed: {
        auth() {
            return this.$store.state.isAuth;
        }
    },
    watch: {
        auth(newAuth, oldAuth) {
            if (newAuth) {
                this.$router.push({ name: 'home' });
            }
        }
    }
}
</script>

<template>
    <main>
        <section>
            <form class="l" @submit.prevent="sendFormLogin">
                <div>
                    <span class="error err_bg" v-if="error">Ошибка авторизации</span>
                    <label for="email">Email</label>
                    <input @blur="v$.email.$touch()" :class="{ error: v$.email.$invalid && v$.email.$dirty }"
                        v-model="email" type="email" class="text" id="email">
                    <span class="error" v-if="v$.email.$dirty && v$.email.required.$invalid">{{
                        v$.email.required.$message }}</span>
                    <span class="error" v-if="v$.email.$dirty && v$.email.email.$invalid">{{
                        v$.email.email.$message }}</span>
                </div>
                <div><label for="password">Пароль</label>
                    <input @blur="v$.password.$touch()" :class="{ error: v$.password.$invalid && v$.password.$dirty }"
                        v-model="password" type="password" class="text" id="password">
                    <span class="error" v-if="v$.password.$dirty && v$.password.required.$invalid">{{
                        v$.password.required.$message }}</span>
                </div>
                <button type="submit" class="button submit" :disabled="loading">
                    <p v-if="!loading">Войти</p>
                    <span class="loader" v-if="loading"></span>
                </button>
                <div class="center"><router-link :to="{ name: 'forgot_password' }" style="color: blue;">Забыли
                        пароль?</router-link></div>
            </form>
        </section>
    </main>
</template>

<style scoped>
.l {
    width: 90%;
}
</style>