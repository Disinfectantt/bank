<template>
    <header>
        <router-link :to="{ name: 'home' }"><img src="img/logo.png" class="logo" alt="logo"></router-link>
        <h1 id="bank">Банк</h1>

        <nav>
            <router-link :to="{ name: 'home' }">Главная</router-link>
            <router-link to="/#services">Наши услуги</router-link>
            <router-link to="/#reviews">Отзывы</router-link>
            <router-link :to="{ name: 'check_credit' }">Рассчитать кредит</router-link>
            <router-link :to="{ name: 'credit' }">Оформить кредит</router-link>
            <router-link to="/#cards">Карты</router-link>
            <router-link :to="{ name: 'login' }" v-if="!$store.state.isAuth">Вход</router-link>
            <router-link :to="{ name: 'register' }" v-if="!$store.state.isAuth">Регистрация</router-link>
            <router-link :to="{ name: 'personal_account' }" v-if="$store.state.isAuth">Личный кабинет</router-link>
            <router-link :to="{ name: 'admin_panel' }" v-if="$store.state.isAdmin">Админ</router-link>
            <a @click.prevent="logout" v-if="$store.state.isAuth">Выйти</a>
        </nav>

        <div class="burger" @click="isActive = !isActive" :class="{ active: isActive }">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <ul class="nav" :class="{ active: isActive }">
        <li>
            <router-link :to="{ name: 'home' }">Главная</router-link>
        </li>
        <li>
            <router-link to="/#services">Наши услуги</router-link>
        </li>
        <li>
            <router-link to="/#reviews">Отзывы</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'check_credit' }">Рассчитать кредит</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'credit' }">Оформить кредит</router-link>
        </li>
        <li>
            <router-link to="/#cards">Карты</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'login' }" v-if="!$store.state.isAuth">Вход</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'register' }" v-if="!$store.state.isAuth">Регистрация</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'personal_account' }" v-if="$store.state.isAuth">Личный кабинет</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'admin_panel' }" v-if="$store.state.isAdmin">Админ</router-link>
        </li>
        <li>
            <a @click.prevent="logout" v-if="$store.state.isAuth">Выйти</a>
        </li>
    </ul>
</template>

<script>
export default {
    data() {
        return {
            isActive: false
        }
    },
    watch: {
        $route($to, from) {
            setTimeout(() => {
                this.isActive = false;
                if (location.hash != '') {
                    let id = document.querySelector(location.hash);
                    if (id != null) {
                        let scrollDiv = id.offsetTop;
                        window.scrollTo({ top: scrollDiv - 50, behavior: 'smooth' });
                    }
                }
            }, 50);
        }
    },
    methods: {
        main() {
            for (var i = 0; i < document.querySelectorAll('.nav a').length; ++i) {
                document.querySelectorAll('.nav a')[i].addEventListener('click', () => {
                    this.isActive = false;
                });
            }

            document.addEventListener('click', (e) => {
                if (document.querySelectorAll('.nav')[0].classList.contains('active') &&
                    !e.target.closest('.burger')) {
                    for (var i = 0; i < document.querySelectorAll('.burger,.nav').length; ++i) {
                        document.querySelectorAll('.burger,.nav')[i].classList.toggle('active');
                    }
                }
            });

            let scrollPrev = 0;
            document.querySelectorAll('.all')[0].addEventListener('scroll', () => {
                if (document.querySelectorAll('.burger')[0].classList.contains('active')) {
                    for (var i = 0; i < document.querySelectorAll('.burger,.nav').length; ++i) {
                        document.querySelectorAll('.burger,.nav')[i].classList.toggle('active');
                    }
                }
                let scrolled = document.querySelectorAll('.all')[0].scrollTop;
                if (scrolled > 100 && scrolled > scrollPrev) {
                    document.querySelectorAll('header')[0].classList.add('out');
                } else {
                    document.querySelectorAll('header')[0].classList.remove('out');
                }
                scrollPrev = scrolled;
            });

            window.addEventListener('scroll', () => {
                if (document.querySelectorAll('.burger')[0].classList.contains('active')) {
                    for (var i = 0; i < document.querySelectorAll('.burger,.nav').length; ++i) {
                        document.querySelectorAll('.burger,.nav')[i].classList.toggle('active');
                    }
                }
                let scrolled = window.scrollY;
                if (scrolled > 100 && scrolled > scrollPrev) {
                    document.querySelectorAll('header')[0].classList.add('out');
                } else {
                    document.querySelectorAll('header')[0].classList.remove('out');
                }
                scrollPrev = scrolled;
            });
        },
        isAuthenticated() {
            axios.get(`api/user`).then(response => {
                this.$store.state.isAuth = true;
                this.$store.state.email = response.data.email;
                if (response.data.role >= 5) {
                    this.$store.state.isAdmin = true;
                }
            }).catch(error => {
                this.$store.state.isAuth = false;
                this.$store.state.isAdmin = false;
            });
        },
        logout() {
            axios.post(`api/logout`).then(response => {
                this.$store.state.isAuth = false;
                this.$store.state.isAdmin = false;
                this.$store.state.email = '';
                this.$router.push({ name: 'home' });
            }).catch(error => {
                console.log(error);
            });
        },
    },
    mounted() {
        this.main();
        this.isAuthenticated();
    }
}
</script>