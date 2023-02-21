<script>
import useVuelidate from '@vuelidate/core';
import { required, minLength, maxLength, helpers } from '@vuelidate/validators';
import popup from './popup.vue';
export default {
    components: { popup },
    data() {
        return {
            surname: '',
            name: '',
            patronymic: '',
            period: 1,
            sum: 1000,
            loading: false,
            profit: 90000,
            isVisiblePopup: false,
            popup_text: 'Вы оставили заяку на оформление вклада!'
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
            patronymic: {
                required: helpers.withMessage('Обязательно к заполнению', required),
                minLength: helpers.withMessage('Введите корректное имя от 4 до 50 символов', minLength(4)),
                maxLength: maxLength(50)
            }
        }
    },
    methods: {
        sendFormF() {
            if (this.v$.$invalid) {
                this.v$.surname.$touch();
                this.v$.name.$touch();
                this.v$.patronymic.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/deposit', {
                surname: this.surname,
                name: this.name,
                patronymic: this.patronymic,
                period: this.period,
                sum: this.sum
            }).then(response => {
                this.surname = '';
                this.name = '';
                this.patronymic = '';
                this.v$.$reset();
                this.isVisiblePopup = true;
            }).catch(error => {
                console.log(error.response.data.message);
            }).finally(() => (this.loading = false));
        },
        getProfit() {
            this.profit = Math.round(this.sum * 1.5 * this.period);
        },
        close() {
            this.isVisiblePopup = false;
        }
    }
}
</script>

<template>
    <main>
        <section class="dep">
            <div class="center">
                <h2>Оформить вклад</h2>
            </div>
            <div id="formF">
                <Transition>
                    <popup v-if="isVisiblePopup" @close="close">{{ popup_text }}</popup>
                </Transition>
                <form class="f" @submit.prevent="sendFormF" method="POST" action="../php/deposit_form.php">
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
                    <div><label for="patronymic">Отчество</label>
                        <input @blur="v$.patronymic.$touch()" :class="{ error: v$.patronymic.$invalid && v$.patronymic.$dirty }"
                            v-model="patronymic" type="text" id="patronymic" class="text" placeholder="Петрович" name="patronymic">
                        <span class="error" v-if="v$.patronymic.$dirty && v$.patronymic.required.$invalid">{{ v$.patronymic.required.$message
                        }}</span>
                        <span class="error"
                            v-if="v$.patronymic.$dirty && (v$.patronymic.minLength.$invalid || v$.patronymic.maxLength.$invalid)">{{
                            v$.patronymic.minLength.$message }}</span>
                    </div>
                    <div class="range"><label for="period">Срок вклада (в месяцах)</label>
                        <input v-model="period" type="range" min="6" max="72" step="1" id="period" @input="getProfit()"
                            name="period">
                        <p id="srok">{{ period }}</p>
                    </div>
                    <div class="range"><label for="sum">Сумма кредита (руб)</label>
                        <input v-model="sum" type="range" min="10000" max="500000" step="1000" id="sum"
                            @input="getProfit()" name="sum">
                        <p id="summ">{{ sum }}</p>
                    </div>
                    <div class="center">
                        <p id="percent1">Доход:{{ profit }}</p>
                    </div>
                    <button type="submit" class="button submit" :disabled="loading">
                        <p v-if="!loading">Оформить</p>
                        <span class="loader" v-if="loading"></span>
                    </button>
                </form>
            </div>
        </section>
    </main>
</template>

<style scoped>
section {
    margin-top: 4rem;
}
</style>