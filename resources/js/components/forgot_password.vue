<script>
import useVuelidate from '@vuelidate/core';
import { required, helpers, email } from '@vuelidate/validators';

export default {
    data() {
        return {
            email: '' || this.$store.state.email,
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
            email: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                email: helpers.withMessage('Email должен быть корректным', email),
            }
        }
    },
    methods: {
        sendFormReset() {
            if (this.v$.$invalid) {
                this.v$.email.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/password/email', { email: this.email }).then(response => {
                this.email = '';
                this.sended = true;
            }).catch(error => {
                this.error = true;
                console.log(error.response.data);
            }).finally(() => (this.loading = false));
        },
    },
}
</script>

<template>
    <main>
        <section>
            <h2 class="center">Сброс пароля</h2>
            <div class="empty" v-if="sended">
                <p>Письмо отправлено</p>
            </div>
            <form class="l" @submit.prevent="sendFormReset" v-if="!sended">
                <div>
                    <span class="error err_bg" v-if="error">Email не найден</span>
                    <label for="email">Email</label>
                    <input @blur="v$.email.$touch()" :class="{ error: v$.email.$invalid && v$.email.$dirty }"
                        v-model="email" type="email" class="text" id="email">
                    <span class="error" v-if="v$.email.$dirty && v$.email.required.$invalid">{{
                        v$.email.required.$message }}</span>
                    <span class="error" v-if="v$.email.$dirty && v$.email.email.$invalid">{{
                        v$.email.email.$message }}</span>
                </div>
                <button type="submit" class="button submit" :disabled="loading">
                    <p v-if="!loading">Отправить email</p>
                    <span class="loader" v-if="loading"></span>
                </button>
            </form>
        </section>
    </main>
</template>