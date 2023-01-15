<script>
import useVuelidate from '@vuelidate/core';
import { required, minLength, maxLength, numeric, helpers } from '@vuelidate/validators';
import popup from './popup.vue';
export default {
    components: { popup },
    data() {
        return {
            region: 'MO',
            surname: '',
            name: '',
            otc: '',
            document: '',
            date: this.$route.query.date || 1,
            sum: this.$route.query.sum || 1000,
            insurance: true,
            loading: false,
            isVisiblePopup: false,
            popup_text: 'Вы оставили заяку на оформление кредита!'
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
            }
        }
    },
    methods: {
        sendFormD() {
            if (this.v$.$invalid) {
                this.v$.document.$touch();
                this.v$.surname.$touch();
                this.v$.name.$touch();
                this.v$.otc.$touch();
                return;
            }
            this.loading = true;
            axios.post('api/credit', {
                surname: this.surname,
                name: this.name,
                otc: this.otc,
                document: this.document,
                reg: this.region,
                insurance: this.insurance.toString(),
                date: this.date,
                sum: this.sum
            }).then(response => {
                this.surname = '';
                this.name = '';
                this.otc = '';
                this.document = '';
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
        <section class="credit">
            <div class="center">
                <h2>Оформить кредит</h2>
            </div>
            <div id="formD">
                <Transition>
                    <popup v-if="isVisiblePopup" @close="close">{{ popup_text }}</popup>
                </Transition>
                <form class="d" @submit.prevent="sendFormD" action="../php/credit_form.php" method="POST">
                    <div class="wrapper">
                        <div>
                            <div>
                                <label for="reg">Регион</label>
                                <select id="reg" class="text" name="reg" v-model="region">
                                    <option value="MO">Москва и область</option>
                                    <option value="Len obl">Ленинградская область</option>
                                    <option value="Tveskaya obl">Тверская область</option>
                                </select>
                            </div>
                            <div><label for="surname">Фамилия</label>
                                <input @blur="v$.surname.$touch()"
                                    :class="{ error: v$.surname.$invalid && v$.surname.$dirty }" v-model="surname"
                                    type="text" id="surname" class="text" placeholder="Петров" name="surname">
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
                                <span class="error" v-if="v$.otc.$dirty && v$.otc.required.$invalid">{{
                                v$.otc.required.$message }}</span>
                                <span class="error"
                                    v-if="v$.otc.$dirty && (v$.otc.minLength.$invalid || v$.otc.maxLength.$invalid)">{{
                                    v$.otc.minLength.$message }}</span>
                            </div>
                        </div>
                        <div>
                            <div><label for="document">Паспорт</label>
                                <input :class="{ error: v$.document.$invalid && v$.document.$dirty }"
                                    @blur="v$.document.$touch()" v-model="document" type="text" id="document"
                                    class="text" placeholder="88005553535" name="document">
                                <span class="error" v-if="v$.document.$dirty && v$.document.numeric.$invalid">{{
                                v$.document.numeric.$message }}</span>
                                <span class="error"
                                    v-if="v$.document.$dirty && (v$.document.minLength.$invalid || v$.document.maxLength.$invalid)">{{
                                    v$.document.numeric.$message }}</span>
                                <span class="error" v-if="v$.document.$dirty && v$.document.required.$invalid">{{
                                v$.document.required.$message }}</span>
                            </div>
                            <div class="range"><label for="date">Срок кредита (в месяцах)</label>
                                <input v-model="date" type="range" min="1" max="36" step="1" id="date" name="date">
                                <p id="srok">{{ date }}</p>
                            </div>
                            <div class="range"><label for="sum">Сумма кредита (руб)</label>
                                <input v-model="sum" type="range" min="1000" max="500000" step="1000" id="sum"
                                    name="sum">
                                <p id="summ">{{ sum }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="addservices center">
                        <p class="dop">Доп услуги</p>
                        <label for="insurance">Страхование</label>
                        <input v-model="insurance" type="checkbox" value="insurance" id="insurance" name="insurance">
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
section{
    margin-top: 4rem;
}
</style>