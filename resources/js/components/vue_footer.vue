<script>
export default {
    mounted() {
        this.isAuthenticated();
    },
    methods: {
        isAuthenticated() {
            axios.get(`api/isAuthenticated`).then(response => {
                if (response.data.status) {
                    this.$store.state.isAuth = true;
                }
                if (response.data.role == 'admin') {
                    this.$store.state.isAdmin = true;
                }
            }).catch(error => {
                this.$store.state.isAuth = false;
                this.$store.state.isAdmin = false;
                console.log(error);
            });
        },
        logout() {
            axios.post(`api/logout`).then(response => {
                this.$router.push({ name: 'home' });
                this.$store.state.isAuth = false;
                this.$store.state.isAdmin = false;
            }).catch(error => {
                console.log(error);
            });
        },
    }
}
</script>
<template>
    <footer>
        <div>
            <p>8-800-555-35-35<br>
                ул. Пушкина дом Колотушкина<br>
                mylo@gmail.com
            </p>
            <p class="aga">
                Владельцы сайта ни в коем случае не хотят отобрать ваши деньги всеми возможными способами.</p>
        </div>
        <div v-if="$store.state.isAuth">
            <button @click="logout" class="logout button">Logout</button>
        </div>
        <div v-if="$store.state.isAdmin">
            <button @click="$router.push({ name: 'admin_panel' });" class="logout button">Admin panel</button>
        </div>
        <div v-if="!$store.state.isAuth">
            <button @click="$router.push({ name: 'login' });" class="logout button">Login</button>
        </div>
        <div class="socials">
            <a href="https://vk.com/" target="_blank"><img src="img/vk-256x256.png" class="social" alt=" "></a>
            <a href="https://twitter.com/" target="_blank"><img src="img/Twitter_bird_logo_2012.svg.png" class="social"
                    alt=" "></a>
            <a href="https://instagram.com/" target="_blank"><img src="img/2048px-Instagram_icon.png" class="social"
                    alt=" "></a>
        </div>
    </footer>
</template>