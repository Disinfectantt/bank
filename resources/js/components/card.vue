<script>
import useVuelidate from '@vuelidate/core';
import { required, minLength, maxLength, numeric, helpers, between } from '@vuelidate/validators';
import popup from './popup.vue';
export default {
    components: { popup },
    data() {
        return {
            surname: '',
            name: '',
            otc: '',
            document: '',
            card: this.$route.query.card || 'card1',
            tel: '',
            credit: 0,
            loading: false,
            isVisiblePopup: false,
            popup_text: 'Вы успешно заказали карту!'
        }
    },
    setup() {
        return { v$: useVuelidate() }
    },
    validations() {
        return {
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
            otc: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Введите корректное имя от 4 до 50 символов', minLength(4)),
                maxLength: maxLength(50)
            },
            document: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                numeric: helpers.withMessage('Введите корректный номер', numeric),
                minLength: minLength(10),
                maxLength: maxLength(10)
            },
            tel: {
                numeric: helpers.withMessage('Введите корректный номер', numeric),
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: minLength(11),
                maxLength: maxLength(11)
            },
            credit: {
                numeric: helpers.withMessage('Введите корректную сумму от 0 до 100000', numeric),
                required: helpers.withMessage('Обязательно к заполнению', required),
                between: between(0, 100000)
            }
        }
    },
    methods: {
        sendFormE() {
            if (this.v$.$invalid) {
                this.v$.tel.$touch();
                this.v$.surname.$touch();
                this.v$.name.$touch();
                this.v$.otc.$touch();
                this.v$.document.$touch();
                this.v$.credit.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/card', {
                surname: this.surname,
                name: this.name,
                otc: this.otc,
                document: this.document,
                card: this.card,
                tel: this.tel,
                credit: this.credit
            }).then(response => {
                this.surname = '';
                this.name = '';
                this.otc = '';
                this.document = '';
                this.tel = '';
                this.credit = '';
                this.v$.$reset();
                this.isVisiblePopup = true;
            }).catch(error => {
                console.log(error);
            }).finally(() => (this.loading = false));
        },
        close() {
            this.isVisiblePopup = false;
        }
    }
}
</script>

<template>
    <main>
        <section class="recive">
            <div class="center">
                <h2>Заказать карту</h2>
            </div>
            <div id="formE">
                <Transition>
                    <popup v-if="isVisiblePopup" @close="close">{{ popup_text }}</popup>
                </Transition>
                <form class="e" @submit.prevent="sendFormE" method="POST" action="../php/card_form.php">
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
                        <input @blur="v$.name.$touch()" :class="{ error: v$.name.$invalid && v$.name.$dirty }"
                            v-model="name" type="text" id="name" class="text" placeholder="Василий" name="name">
                        <span class="error" v-if="v$.name.$dirty && v$.name.required.$invalid">{{
                        v$.name.required.$message }}</span>
                        <span class="error"
                            v-if="v$.name.$dirty && (v$.name.minLength.$invalid || v$.name.maxLength.$invalid)">{{
                            v$.name.minLength.$message }}</span>
                    </div>
                    <div><label for="otc">Отчество</label>
                        <input @blur="v$.otc.$touch()" :class="{ error: v$.otc.$invalid && v$.otc.$dirty }"
                            v-model="otc" type="text" id="otc" class="text" placeholder="Петрович" name="otc">
                        <span class="error" v-if="v$.otc.$dirty && v$.otc.required.$invalid">{{ v$.otc.required.$message
                        }}</span>
                        <span class="error"
                            v-if="v$.otc.$dirty && (v$.otc.minLength.$invalid || v$.otc.maxLength.$invalid)">{{
                            v$.otc.minLength.$message }}</span>
                    </div>
                    <div><label for="document">Паспорт</label>
                        <input :class="{ error: v$.document.$invalid && v$.document.$dirty }"
                            @blur="v$.document.$touch()" v-model="document" type="text" id="document" class="text"
                            placeholder="88005553535" name="document">
                        <span class="error" v-if="v$.document.$dirty && v$.document.numeric.$invalid">{{
                        v$.document.numeric.$message }}</span>
                        <span class="error"
                            v-if="v$.document.$dirty && (v$.document.minLength.$invalid || v$.document.maxLength.$invalid)">{{
                            v$.tel.numeric.$message }}</span>
                        <span class="error" v-if="v$.document.$dirty && v$.document.required.$invalid">{{
                        v$.document.required.$message }}</span>
                    </div>
                    <div>
                        <label for="card">Карта</label>
                        <select id="card" class="text" name="card" v-model="card">
                            <option value="card1">Bank black</option>
                            <option value="card2">Bank red</option>
                            <option value="card3">Bank ultra platinum mega cool nagibator 3000</option>
                        </select>
                    </div>
                    <div><label for="tel">Телефон</label>
                        <input :class="{ error: v$.tel.$invalid && v$.tel.$dirty }" @blur="v$.tel.$touch()"
                            v-model="tel" type="tel" id="tel" class="text" placeholder="88005553535" name="tel">
                        <span class="error"
                            v-if="v$.tel.$dirty && (v$.tel.minLength.$invalid || v$.tel.maxLength.$invalid || v$.tel.numeric.$invalid)">{{
                            v$.tel.numeric.$message }}</span>
                        <span class="error" v-if="v$.tel.$dirty && v$.tel.required.$invalid">{{ v$.tel.required.$message
                        }}</span>
                    </div>
                    <div><label for="credit">Кредитный лимит</label>
                        <input :class="{ error: v$.credit.$invalid && v$.credit.$dirty }" v-model="credit" type="number"
                            @blur="v$.credit.$touch()" min="0" max="100000" step="5000" id="credit" class="text"
                            placeholder="Желаемый кредитный лимит" name="credit">
                        <span class="error" v-if="v$.credit.$dirty && v$.credit.numeric.$invalid">{{
                        v$.credit.numeric.$message }}</span>
                        <span class="error" v-if="v$.credit.$dirty && v$.credit.between.$invalid">{{
                        v$.credit.numeric.$message }}</span>
                        <span class="error" v-if="v$.credit.$dirty && v$.credit.required.$invalid">{{
                        v$.credit.required.$message
                        }}</span>
                    </div>
                    <button type="submit" class="button submit" :disabled="loading">
                        <p v-if="!loading">Заказать</p>
                        <span class="loader" v-if="loading"></span>
                    </button>
                </form>
            </div>
        </section>
    </main>
</template>

<style scoped>
section{
    margin-top: 4rem;
}
</style>