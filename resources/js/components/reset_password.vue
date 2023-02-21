<script>
import useVuelidate from '@vuelidate/core';
import { required, helpers, minLength, maxLength } from '@vuelidate/validators';

export default {
    data() {
        return {
            password: '',
            password_confirmation: '',
            error: false,
            sended: false,
            loading: false
        }
    },
    setup() {
        return { v$: useVuelidate() }
    },
    validations() {
        return {
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
        sendFormReset() {
            if (this.v$.$invalid) {
                this.v$.password.$touch();
                this.v$.password_confirmation.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/password/reset', { token: this.$route.query.token, email: this.$route.query.email, password: this.password, password_confirmation: this.password_confirmation }).then(response => {
                if (this.$store.state.isAuth) {
                    this.$router.push({ name: 'home' });
                } else {
                    this.$router.push({ name: 'login' });
                }
            }).catch(error => {
                this.error = true;
                console.log(error);
            }).finally(() => (this.loading = false));
        },
        password_confirm() {
            if (this.password == this.password_confirmation) {
                return true;
            }
            return false;
        }
    },
}
</script>

<template>
    <main>
        <section>
            <h2 class="center">Сброс пароля</h2>
            <div class="goodAlert center" v-if="sended">
                <p>Письмо отправлено</p>
            </div>
            <form class="l" @submit.prevent="sendFormReset" v-if="!sended">
                <div><label for="password">Пароль</label>
                    <span class="error err_bg" v-if="error">Ошибка</span>
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
                    <p v-if="!loading">Обновить пароль</p>
                    <span class="loader" v-if="loading"></span>
                </button>
            </form>
        </section>
    </main>
</template>