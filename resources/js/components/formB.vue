<script>
import useVuelidate from '@vuelidate/core';
import { required, helpers, email } from '@vuelidate/validators';
import popup from './popup.vue';
export default {
    components: { popup },
    data() {
        return {
            email: '',
            loading: false,
            isVisiblePopup: false,
            popup_text: 'Вы успешно подписались на рассылку!'
        }
    },
    setup() {
        return { v$: useVuelidate() }
    },
    validations() {
        return {
            email: {
                email: helpers.withMessage('Введите корректный email', email),
                required: helpers.withMessage('Обязательно к заполнению', required)
            }
        }
    },
    methods: {
        sendFormB() {
            if (this.v$.$invalid) {
                this.v$.email.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/newsletter', { email: this.email }).then(response => {
                this.email = '';
                this.v$.$reset();
                this.isVisiblePopup = true;
            }).catch(error => {
                console.log(error.response.data);
            }).finally(() => (this.loading = false));
        },
        close() {
            this.isVisiblePopup = false;
        }
    }
}
</script>
<template>
    <div id="formB">
        <Transition>
            <popup v-if="isVisiblePopup" @close="close">{{ popup_text }}</popup>
        </Transition>
        <form class="b" @submit.prevent="sendFormB" action="php/index_email.php" method="POST">
            <div>
                <label for="email">Email</label>
                <input @blur="v$.email.$touch()" :class="{ error: v$.email.$invalid && v$.email.$dirty }"
                    v-model="email" type="email" id="email" class="text" placeholder="qwerty@gmail.com" name="email">
                <span class="error" v-if="v$.email.$dirty && v$.email.required.$invalid">{{
                v$.email.required.$message }}</span>
                <span class="error" v-if="v$.email.$dirty && v$.email.email.$invalid">{{ v$.email.email.$message
                }}</span>
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Подписаться</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>
    </div>
</template>