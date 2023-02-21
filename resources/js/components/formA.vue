<script>
import useVuelidate from '@vuelidate/core';
import { required, minLength, maxLength, numeric, helpers } from '@vuelidate/validators';
import popup from './popup.vue';
export default {
    components: { popup },
    data() {
        return {
            region: 'MO',
            tel: '',
            surname: '',
            name: '',
            patronymic: '',
            loading: false,
            popup_text: 'С вами свяжутся в ближайшее время!',
            isVisiblePopup: false
        }
    },
    setup() {
        return { v$: useVuelidate() }
    },
    validations() {
        return {
            tel: {
                numeric: helpers.withMessage('Введите корректный номер', numeric),
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: minLength(11),
                maxLength: maxLength(11)
            },
            surname: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Введите корректное имя от 4 до 50 символов', minLength(4)),
                maxLength: maxLength(50)
            },
            name: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Введите корректное имя от 4 до 50 символов', minLength(4)),
                maxLength: maxLength(50)
            },
            patronymic: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Введите корректное имя от 4 до 50 символов', minLength(4)),
                maxLength: maxLength(50)
            },
        }
    },
    methods: {
        sendFormA() {
            if (this.v$.$invalid) {
                this.v$.tel.$touch();
                this.v$.surname.$touch();
                this.v$.name.$touch();
                this.v$.patronymic.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/feedback', { region: this.region, tel: this.tel, surname: this.surname, name: this.name, patronymic: this.patronymic }).then(response => {
                this.tel = '';
                this.surname = '';
                this.name = '';
                this.patronymic = '';
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
    <div id="formA">
        <Transition><popup v-if="isVisiblePopup" @close="close">{{ popup_text }}</popup></Transition>
        <form class="a" @submit.prevent="sendFormA" action="php/index_feedback.php" method="POST" name="a">
            <div>
                <label for="region">Регион</label>
                <select id="region" class="text" name="region" v-model="region">
                    <option value="MO">Москва и область</option>
                    <option value="Len obl">Ленинградская область</option>
                    <option value="Tveskaya obl">Тверская область</option>
                </select>
            </div>
            <div>
                <label for="tel">Телефон</label>
                <input :class="{ error: v$.tel.$invalid && v$.tel.$dirty }" @blur="v$.tel.$touch()" v-model="tel"
                    type="tel" id="tel" class="text" placeholder="88005553535" name="tel">
                <span class="error"
                    v-if="v$.tel.$dirty && (v$.tel.minLength.$invalid || v$.tel.maxLength.$invalid || v$.tel.numeric.$invalid)">{{
                    v$.tel.numeric.$message }}</span>
                <span class="error" v-if="v$.tel.$dirty && v$.tel.required.$invalid">{{ v$.tel.required.$message
                }}</span>
            </div>
            <div><label for="surname">Фамилия</label>
                <input @blur="v$.surname.$touch()" :class="{ error: v$.surname.$invalid && v$.surname.$dirty }"
                    v-model="surname" type="text" id="surname" class="text" placeholder="Петров" name="surname">
                <span class="error" v-if="v$.surname.$dirty && v$.surname.required.$invalid">{{
                v$.surname.required.$message }}</span>
                <span class="error"
                    v-if="v$.surname.$dirty && (v$.surname.minLength.$invalid || v$.surname.maxLength.$invalid)">{{
                    v$.surname.minLength.$message }}</span>
            </div>
            <div><label for="name">Имя</label>
                <input @blur="v$.name.$touch()" :class="{ error: v$.name.$invalid && v$.name.$dirty }" v-model="name"
                    type="text" id="name" class="text" placeholder="Василий" name="name">
                <span class="error" v-if="v$.name.$dirty && v$.name.required.$invalid">{{
                v$.name.required.$message }}</span>
                <span class="error"
                    v-if="v$.name.$dirty && (v$.name.minLength.$invalid || v$.name.maxLength.$invalid)">{{
                    v$.name.minLength.$message }}</span>
            </div>
            <div><label for="patronymic">Отчество</label>
                <input @blur="v$.patronymic.$touch()" :class="{ error: v$.patronymic.$invalid && v$.patronymic.$dirty }" v-model="patronymic"
                    type="text" id="patronymic" class="text" placeholder="Петрович" name="patronymic">
                <span class="error" v-if="v$.patronymic.$dirty && v$.patronymic.required.$invalid">{{ v$.patronymic.required.$message
                }}</span>
                <span class="error" v-if="v$.patronymic.$dirty && (v$.patronymic.minLength.$invalid || v$.patronymic.maxLength.$invalid)">{{
                v$.patronymic.minLength.$message }}</span>
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Перезвонить</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>
    </div>
</template>