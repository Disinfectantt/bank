<template>
    <header>
        <router-link :to="{ name: 'home' }"><img src="img/logo.png" class="logo" alt="logo"></router-link>
        <h1 id="bank">Банк</h1>
        <nav v-if="homeHeader">
            <a href="#">Главная</a>
            <a href="#cervices">Наши услуги</a>
            <a href="#reviews">Отзывы</a>
            <router-link :to="{ name: 'check_credit'}">Рассчитать кредит</router-link>
            <router-link :to="{ name: 'credit'}">Оформить кредит</router-link>
            <a href="#cards">Карты</a>
        </nav>

        <nav v-if="!homeHeader">
            <router-link :to="{ name: 'home' }">Главная</router-link>
            <router-link to="/#services">Наши услуги</router-link>
            <router-link to="/#reviews">Отзывы</router-link>
            <router-link :to="{ name: 'check_credit'}">Рассчитать кредит</router-link>
            <router-link :to="{ name: 'credit' }">Оформить кредит</router-link>
            <router-link to="/#cards">Карты</router-link>
        </nav>

        <div class="burger" @click="isActive = !isActive" :class="{active:isActive}">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <ul class="nav" v-if="homeHeader" :class="{active:isActive}">
        <li><a href="#">Главная</a></li>
        <li><a href="#services">Наши услуги</a></li>
        <li><a href="#reviews">Отзывы</a></li>
        <li>
            <router-link :to="{ name: 'check_credit'}">Рассчитать кредит</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'credit'}">Оформить кредит</router-link>
        </li>
        <li><a href="#cards">Карты</a></li>
    </ul>

    <ul class="nav" v-if="!homeHeader" :class="{active:isActive}">
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
            <router-link :to="{ name: 'check_credit'}">Рассчитать кредит</router-link>
        </li>
        <li>
            <router-link :to="{ name: 'credit' }">Оформить кредит</router-link>
        </li>
        <li>
            <router-link to="/#cards">Карты</router-link>
        </li>
    </ul>
</template>

<script>
export default {
    data() {
        return {
            homeHeader: false,
            isActive: false
        }
    },
    watch: {
        $route($to, from) {
            if ($to.path == '/') {
                this.homeHeader = true;
            }
            if ($to.path != '/') {
                this.homeHeader = false;
            }
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
        }
    },
    mounted() {
        this.main();
    }
}
</script>