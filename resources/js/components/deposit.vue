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
            otc: '',
            date: 1,
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
            otc: {
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
                this.v$.otc.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/deposit', {
                surname: this.surname,
                name: this.name,
                otc: this.otc,
                date: this.date,
                sum: this.sum
            }).then(response => {
                this.surname = '';
                this.name = '';
                this.otc = '';
                this.v$.$reset();
                this.isVisiblePopup = true;
            }).catch(error => {
                console.log(error);
            }).finally(() => (this.loading = false));
        },
        getProfit() {
            this.profit = Math.round(this.sum * 1.5 * this.date);
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
                    <div><label for="otc">Отчество</label>
                        <input @blur="v$.otc.$touch()" :class="{ error: v$.otc.$invalid && v$.otc.$dirty }"
                            v-model="otc" type="text" id="otc" class="text" placeholder="Петрович" name="otc">
                        <span class="error" v-if="v$.otc.$dirty && v$.otc.required.$invalid">{{ v$.otc.required.$message
                        }}</span>
                        <span class="error"
                            v-if="v$.otc.$dirty && (v$.otc.minLength.$invalid || v$.otc.maxLength.$invalid)">{{
                            v$.otc.minLength.$message }}</span>
                    </div>
                    <div class="range"><label for="date">Срок вклада (в месяцах)</label>
                        <input v-model="date" type="range" min="6" max="72" step="1" id="date" @input="getProfit()"
                            name="date">
                        <p id="srok">{{ date }}</p>
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