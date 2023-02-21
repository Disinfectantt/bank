<script>
export default {
    data() {
        return {
            feedback: [],
            newsletters: [],
            credits: [],
            cards: [],
            deposits: [],
            userInfo: [],
            loading: false,
            resend: false,
        }
    },
    mounted() {
        this.getUserInfo();
    },
    methods: {
        getUserInfo() {
            this.loading = true;
            axios.get(`api/user`).then(response => {
                this.userInfo = response.data;
                this.getData();
            }).catch(error => {
                this.$router.push({ name: 'login' });
            });
        },
        getData() {
            axios.get(`api/newsletter/${this.userInfo.id}`).then(response => {
                this.newsletters = response.data;
            });
            axios.get(`api/feedback/${this.userInfo.id}`).then(response => {
                this.feedback = response.data;
            });
            axios.get(`api/credit/${this.userInfo.id}`).then(response => {
                this.credits = response.data;
            });
            axios.get(`api/card/${this.userInfo.id}`).then(response => {
                this.cards = response.data;
            });
            axios.get(`api/deposit/${this.userInfo.id}`).then(response => {
                this.deposits = response.data;
            });
            this.loading = false
        },
        resendEmail() {
            axios.post(`api/email/resend`).then(response => {
                this.resend = true;
                setTimeout(() => this.resend = false, 5000);
            }).catch(error => {
                console.log(error.data);
            });
        },
    }
}
</script>

<template>
    <div class="loader_wrapper margin" v-if="loading">
        <div class="loader" v-if="loading"></div>
    </div>
    <main v-if="this.$store.state.isAuth && !loading">
        <section>
            <div class="userInfo wrapper">
                <div class="card personal-card">
                    <p class="goodAlert" v-if="resend">Письмо с подтверждением отправлено</p>
                    <h3>Ваше имя: {{ userInfo.name }}</h3>
                    <p>Ваш email: {{ userInfo.email }}</p>
                    <p v-if="userInfo.email_verified_at != null" style="color: green">Email подтвержден</p>
                    <p v-else style="color:red; cursor: pointer;" @click="resendEmail">Email не подтвержден, нажмите, чтобы
                        получить новое письмо</p>
                    <p v-if="newsletters == ''">Вы не подписаны на рассылку. <router-link to="/#newsletter"
                            style="color:blue">Подписаться</router-link></p>
                    <p v-else>Вы подписаны на рассылку</p>
                    <div class="center"><router-link :to="{ name: 'forgot_password' }" style="color: blue;">Сменить
                            пароль</router-link></div>
                </div>
            </div>
        </section>

        <h2 class="center" v-if="feedback != ''">Ваши обращения</h2>
        <TransitionGroup tag="section" name="fade" class="container wrapper" v-if="feedback != ''">
            <div class="card personal-card" v-for="item in feedback" :key="item.id">
                <p><b>Регион:</b> {{ item.region }}</p>
                <p><b>Фамилия:</b> {{ item.surname }}</p>
                <p><b>Имя:</b> {{ item.name }}</p>
                <p><b>Отчество:</b> {{ item.patronymic }}</p>
                <p><b>Телефон:</b> {{ item.tel }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
                <p v-if="item.status == 'completed'" style="color: green;"><b>Статус:</b>Выполнено</p>
                <p v-if="item.status == 'waiting'"><b>Статус:</b>Ожидание</p>
            </div>
        </TransitionGroup>
        <div class="center" v-if="feedback == []">У вас нет обращений</div>

        <h2 class="center" v-if="credits != ''">Кредиты</h2>
        <TransitionGroup tag="section" name="fade" class="container wrapper" v-if="credits != ''">
            <div class="card personal-card" v-for="item in credits" :key="item.id">
                <p><b>Период:</b> {{ item.period }}</p>
                <p><b>Сумма:</b> {{ item.sum }}</p>
                <p><b>Страховка:</b> {{ item.insurance }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
            </div>
        </TransitionGroup>

        <h2 class="center" v-if="cards != ''">Карты</h2>
        <TransitionGroup tag="section" name="fade" class="container wrapper" v-if="cards != ''">
            <div class="card personal-card" v-for="item in cards" :key="item.id">
                <div v-if="item.card == 'card1'">
                    <h3>Bank black</h3>
                    <img src="img/png-transparent-payment-card-black-card-credit-card-visa-black-and-white-card-payment-black-and-white-card-black-card.png"
                        class="card-img" alt=" ">
                </div>
                <div v-if="item.card == 'card2'">
                    <h3>Bank red</h3>
                    <img src="img/visa_red_600x379_common_29-04-2020.png" class="card-img" alt=" ">
                </div>
                <div v-if="item.card == 'card3'">
                    <h3>Bank ultra platinum mega cool nagibator 3000</h3>
                    <img src="img/card-main.png" class="card-img" alt=" ">
                </div>
                <p><b>Карта:</b> {{ item.card }}</p>
                <p><b>Кредитный лимит:</b> {{ item.card_limit }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
            </div>
        </TransitionGroup>

        <h2 class="center" v-if="deposits != ''">Вклады</h2>
        <TransitionGroup tag="section" name="fade" class="container wrapper" v-if="deposits != ''">
            <div class="card personal-card" v-for="item in deposits" :key="item.id">
                <p><b>Срок:</b> {{ item.period }}</p>
                <p><b>Сумма:</b> {{ item.sum }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
            </div>
        </TransitionGroup>
    </main>
</template>