<script>
import useVuelidate from '@vuelidate/core';
import { required, helpers, email, minLength, maxLength } from '@vuelidate/validators';
import axios from 'axios';
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            error: false,
            userExists: false,
            loading: false
        }
    },
    setup() {
        return { v$: useVuelidate() }
    },
    validations() {
        return {
            name: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Введите корректное имя от 4 до 255 символов', minLength(4)),
                maxLength: maxLength(255)
            },
            email: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                email: helpers.withMessage('Email должен быть корректным', email),
                maxLength: maxLength(255)
            },
            password: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Минимальная длина 8 символов', minLength(8)),
                maxLength: maxLength(255)
            },
            password_confirmation: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                password_confirm: helpers.withMessage('Пароли не совпадают', this.password_confirm),
            }
        }
    },
    methods: {
        sendFormRegister() {
            if (this.v$.$invalid) {
                this.v$.name.$touch();
                this.v$.email.$touch();
                this.v$.password.$touch();
                this.v$.password_confirmation.$touch();
                return;
            }
            this.loading = true;
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('api/register', { name: this.name, email: this.email, password: this.password, password_confirmation: this.password_confirmation }).then(response => {
                    this.$store.state.isAuth = true;
                    this.$store.state.email = this.email;
                    this.$router.push({ name: 'home' });
                }).catch(error => {
                    if (error.response.data.errors.hasOwnProperty('email')) {
                        this.userExists = true;
                    } else {
                        this.error = true;
                    }
                    console.log(error.response.data);
                })
            }).finally(() => (this.loading = false));
        },
        password_confirm() {
            if (this.password == this.password_confirmation) {
                return true;
            }
            return false;
        }
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
            <form class="l" @submit.prevent="sendFormRegister">
                <div>
                    <span class="error err_bg" v-if="error">Ошибка регистрации</span>
                    <span class="error err_bg" v-if="userExists">Email уже занят</span>
                    <label for="name">Имя</label>
                    <input @blur="v$.name.$touch()" :class="{ error: v$.name.$invalid && v$.name.$dirty }" v-model="name"
                        type="text" class="text" id="name">
                    <span class="error" v-if="v$.name.$dirty && v$.name.required.$invalid">{{
                        v$.name.required.$message }}</span>
                    <span class="error"
                        v-if="v$.name.$dirty && (v$.name.minLength.$invalid || v$.name.maxLength.$invalid)">{{
                            v$.name.minLength.$message }}</span>
                </div>
                <div>
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
                    <span class="error"
                        v-if="v$.password.$dirty && (v$.password.minLength.$invalid || v$.password.maxLength.$invalid)">{{
                            v$.password.minLength.$message }}</span>
                </div>
                <div><label for="password_confirmation">Подтверждение пароля</label>
                    <input @blur="v$.password_confirmation.$touch()"
                        :class="{ error: v$.password_confirmation.$invalid && v$.password_confirmation.$dirty }"
                        v-model="password_confirmation" type="password" class="text" id="password_confirmation">
                    <span class="error"
                        v-if="v$.password_confirmation.$dirty && v$.password_confirmation.required.$invalid">{{
                            v$.password_confirmation.required.$message }}</span>
                    <span class="error"
                        v-if="v$.password_confirmation.$dirty && v$.password_confirmation.password_confirm.$invalid">{{
                            v$.password_confirmation.password_confirm.$message }}</span>
                </div>
                <button type="submit" class="button submit" :disabled="loading">
                    <p v-if="!loading">Зарегистрироваться</p>
                    <span class="loader" v-if="loading"></span>
                </button>
            </form>
        </section>
    </main>
</template>

<style scoped>
.l {
    width: 90%;
}
</style>