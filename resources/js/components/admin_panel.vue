<script>
import admin_popup_edit from './admin_popup_edit.vue';
export default {
    components: {
        admin_popup_edit
    },
    data() {
        return {
            activeItem: 'feedback',
            data: '',
            loading: false,
            isEditVisible: false,
            someData: ''
        }
    },
    mounted() {
        this.getData(this.activeItem);
        this.whatChannel(this.activeItem);
    },
    methods: {
        isActive(menuItem) {
            return this.activeItem === menuItem;
        },
        setActive(menuItem) {
            this.data = '';
            this.activeItem = menuItem;
            this.getData(menuItem);
            this.whatChannel(menuItem);
        },
        close() {
            this.isEditVisible = false;
        },
        edit(someData) {
            this.someData = someData;
            this.isEditVisible = true;
        },
        getData(item) {
            this.loading = true;
            axios.get(`api/${item}`).then(response => {
                this.data = response.data;
                this.$store.state.isAuth = true;
                this.$store.state.isAdmin = true;
            }).catch(error => {
                this.$router.push({ name: 'login' });
                this.$store.state.isAuth = false;
                this.$store.state.isAdmin = false;
            }).finally(() => (this.loading = false));
        },
        deleteData(item, id) {
            axios.delete(`api/${item}/${id}`).then(response => {
                for (let i = 0; i < this.data.length; i++) {
                    if (this.data[i].id == id) {
                        this.data.splice(i, 1);
                        break;
                    }
                }
            }).catch(error => {
                console.log(error);
            }).finally(() => (this.loading = false));
        },
        editData(item, id) {
            this.loading = true;
            axios.put(`api/${item}/${id}`, this.getFormValues()).then(response => {
                this.close();
            }).catch(error => {
                console.log(error);
            }).finally(() => (this.loading = false));
        },
        getFormValues() {
            let inputs = document.querySelectorAll(`#editForm input`);
            let formValues = {};
            for (let i = 0; i < inputs.length; i++) {
                formValues[inputs[i].id] = inputs[i].value;
            }
            return formValues;
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
        whatAction(action, broadcastData) {
            switch (action) {
                case 'new':
                    this.data.unshift(broadcastData);
                    break;
                case 'update':
                    for (let i = 0; i < this.data.length; i++) {
                        if (this.data[i].id == broadcastData[0].id) {
                            this.data.splice(i, 1, broadcastData[0]);
                            break;
                        }
                    }
                    break;
                case 'destroy':
                    for (let i = 0; i < this.data.length; i++) {
                        if (this.data[i].id == broadcastData) {
                            this.data.splice(i, 1);
                            break;
                        }
                    }
                    break;
            }
        },
        whatChannel(channel) {
            Echo.leave(channel);
            Echo.private(channel)
                .listen(`${channel}Change`, (e) => {
                    this.whatAction(e.action, e.data);
                });
        }
    }
}
</script>

<template>
    <admin_popup_edit v-if="isEditVisible" @close="close">
        <form action="" @submit.prevent="editData(activeItem, someData.id)" v-if="activeItem == 'feedback'"
            id="editForm" class="editForm">
            <div>
                <label for="reg">Регион</label>
                <input class="text" type="text" id="reg" :value="someData.region">
            </div>
            <div>
                <label for="tel">Телефон</label>
                <input class="text" type="text" id="tel" :value="someData.tel">
            </div>
            <div>
                <label for="surname">Фамилия</label>
                <input class="text" type="text" id="surname" :value="someData.surname">
            </div>
            <div>
                <label for="name">Имя</label>
                <input class="text" type="text" id="name" :value="someData.name">
            </div>
            <div>
                <label for="otc">Отчество</label>
                <input class="text" type="text" id="otc" :value="someData.patronymic">
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Изменить</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>

        <form action="" @submit.prevent="editData(activeItem, someData.id)" v-if="activeItem == 'newsletter'"
            id="editForm" class="editForm">
            <div>
                <label for="email">Email</label>
                <input class="text" type="text" id="email" :value="someData.email">
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Изменить</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>

        <form action="" @submit.prevent="editData(activeItem, someData.id)" v-if="activeItem == 'credit'" id="editForm"
            class="editForm">
            <div>
                <label for="reg">Регион</label>
                <input class="text" type="text" id="reg" :value="someData.region">
            </div>
            <div>
                <label for="surname">Фамилия</label>
                <input class="text" type="text" id="surname" :value="someData.surname">
            </div>
            <div>
                <label for="name">Имя</label>
                <input class="text" type="text" id="name" :value="someData.name">
            </div>
            <div>
                <label for="otc">Отчество</label>
                <input class="text" type="text" id="otc" :value="someData.patronymic">
            </div>
            <div>
                <label for="document">Паспорт</label>
                <input class="text" type="text" id="document" :value="someData.passport">
            </div>
            <div>
                <label for="date">Период</label>
                <input class="text" type="text" id="date" :value="someData.period">
            </div>
            <div>
                <label for="sum">Сумма</label>
                <input class="text" type="text" id="sum" :value="someData.sum">
            </div>
            <div>
                <label for="insurance">Страховка</label>
                <input class="text" type="text" id="insurance" :value="someData.insurance">
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Изменить</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>

        <form action="" @submit.prevent="editData(activeItem, someData.id)" v-if="activeItem == 'card'" id="editForm"
            class="editForm">
            <div>
                <label for="surname">Фамилия</label>
                <input class="text" type="text" id="surname" :value="someData.surname">
            </div>
            <div>
                <label for="name">Имя</label>
                <input class="text" type="text" id="name" :value="someData.name">
            </div>
            <div>
                <label for="otc">Отчество</label>
                <input class="text" type="text" id="otc" :value="someData.patronymic">
            </div>
            <div>
                <label for="document">Паспорт</label>
                <input class="text" type="text" id="document" :value="someData.passport">
            </div>
            <div>
                <label for="card">Карта</label>
                <input class="text" type="text" id="card" :value="someData.card">
            </div>
            <div>
                <label for="tel">Телефон</label>
                <input class="text" type="text" id="tel" :value="someData.tel">
            </div>
            <div>
                <label for="credit">Кредитный лимит</label>
                <input class="text" type="text" id="credit" :value="someData.card_limit">
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Изменить</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>

        <form action="" @submit.prevent="editData(activeItem, someData.id)" v-if="activeItem == 'deposit'" id="editForm"
            class="editForm">
            <div>
                <label for="surname">Фамилия</label>
                <input class="text" type="text" id="surname" :value="someData.surname">
            </div>
            <div>
                <label for="name">Имя</label>
                <input class="text" type="text" id="name" :value="someData.name">
            </div>
            <div>
                <label for="otc">Отчество</label>
                <input class="text" type="text" id="otc" :value="someData.patronymic">
            </div>
            <div>
                <label for="date">Период</label>
                <input class="text" type="text" id="date" :value="someData.period">
            </div>
            <div>
                <label for="sum">Сумма</label>
                <input class="text" type="text" id="sum" :value="someData.sum">
            </div>
            <button type="submit" class="button submit" :disabled="loading">
                <p v-if="!loading">Изменить</p>
                <span class="loader" v-if="loading"></span>
            </button>
        </form>
    </admin_popup_edit>
    <section class="admin">
        <button class="logout button" @click="logout">Logout</button>
        <div class="wrapper">
            <div class="w_a wrapper">
                <a class="tab-link" @click.prevent="setActive('feedback')"
                    :class="{ tabLinkActive: isActive('feedback') }">Обратная связь</a>
                <a class="tab-link" @click.prevent="setActive('newsletter')"
                    :class="{ tabLinkActive: isActive('newsletter') }">Emails</a>
                <a class="tab-link" @click.prevent="setActive('credit')"
                    :class="{ tabLinkActive: isActive('credit') }">Кредиты</a>
                <a class="tab-link" @click.prevent="setActive('deposit')"
                    :class="{ tabLinkActive: isActive('deposit') }">Вклады</a>
                <a class="tab-link" @click.prevent="setActive('card')"
                    :class="{ tabLinkActive: isActive('card') }">Карты</a>
            </div>
        </div>

        <div class="loader_wrapper" v-if="loading">
            <div class="loader2"></div>
        </div>

        <TransitionGroup tag="div" name="fade" class="container wrapper i" v-if="activeItem == 'feedback'">
            <div class="admin_item" v-for="item in data" :key="item.id">
                <p><b>Регион:</b> {{ item.region }}</p>
                <p><b>Фамилия:</b> {{ item.surname }}</p>
                <p><b>Имя:</b> {{ item.name }}</p>
                <p><b>Отчество:</b> {{ item.patronymic }}</p>
                <p><b>Телефон:</b> {{ item.tel }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
                <div class="adm_btns">
                    <button title="Удалить" @click="deleteData(activeItem, item.id)">❌</button>
                    <button title="Редактировать" @click="edit(item)">✍</button>
                </div>
            </div>
        </TransitionGroup>

        <TransitionGroup tag="div" name="fade" class="container wrapper i" v-if="activeItem == 'newsletter'">
            <div class="admin_item" v-for="item in data" :key="item.id">
                <p><b>Email:</b> {{ item.email }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
                <div class="adm_btns">
                    <button title="Удалить" @click="deleteData(activeItem, item.id)">❌</button>
                    <button title="Редактировать" @click="edit(item)">✍</button>
                </div>
            </div>
        </TransitionGroup>

        <TransitionGroup tag="div" name="fade" class="container wrapper i" v-if="activeItem == 'credit'">
            <div class="admin_item" v-for="item in data" :key="item.id">
                <p><b>Регион:</b> {{ item.region }}</p>
                <p><b>Фамилия:</b> {{ item.surname }}</p>
                <p><b>Имя:</b> {{ item.name }}</p>
                <p><b>Отчество:</b> {{ item.patronymic }}</p>
                <p><b>Паспорт:</b> {{ item.passport }}</p>
                <p><b>Период:</b> {{ item.period }}</p>
                <p><b>Сумма:</b> {{ item.sum }}</p>
                <p><b>Страховка:</b> {{ item.insurance }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
                <div class="adm_btns">
                    <button title="Удалить" @click="deleteData(activeItem, item.id)">❌</button>
                    <button title="Редактировать" @click="edit(item)">✍</button>
                </div>
            </div>
        </TransitionGroup>

        <TransitionGroup tag="div" name="fade" class="container wrapper i" v-if="activeItem == 'card'">
            <div class="admin_item" v-for="item in data" :key="item.id">
                <p><b>Фамилия:</b> {{ item.surname }}</p>
                <p><b>Имя:</b> {{ item.name }}</p>
                <p><b>Отчество:</b> {{ item.patronymic }}</p>
                <p><b>Карта:</b> {{ item.card }}</p>
                <p><b>Телефон:</b> {{ item.tel }}</p>
                <p><b>Кредитный лимит:</b> {{ item.card_limit }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
                <div class="adm_btns">
                    <button title="Удалить" @click="deleteData(activeItem, item.id)">❌</button>
                    <button title="Редактировать" @click="edit(item)">✍</button>
                </div>
            </div>
        </TransitionGroup>

        <TransitionGroup tag="div" name="fade" class="container wrapper i" v-if="activeItem == 'deposit'">
            <div class="admin_item" v-for="item in data" :key="item.id">
                <p><b>Фамилия:</b> {{ item.surname }}</p>
                <p><b>Имя:</b> {{ item.name }}</p>
                <p><b>Отчество:</b> {{ item.patronymic }}</p>
                <p><b>Срок:</b> {{ item.period }}</p>
                <p><b>Сумма:</b> {{ item.sum }}</p>
                <p><b>Дата:</b> {{ item.created_at.replace('T', ' ').slice(0, -8) }}</p>
                <div class="adm_btns">
                    <button title="Удалить" @click="deleteData(activeItem, item.id)">❌</button>
                    <button title="Редактировать" @click="edit(item)">✍</button>
                </div>
            </div>
        </TransitionGroup>

        <div class="empty" v-if="!loading && data == ''">
            <p>Ничего нет</p>
        </div>
    </section>
</template>

<style>
.admin {
    margin-top: 8rem;
}

.w_a {
    gap: 3rem;
    background-color: white;
    padding: 2rem;
    border-radius: 2rem;
    margin-bottom: 2rem;
    margin-top: 2rem;
}

.adm_btns {
    display: flex;
    justify-content: space-between;
}

.adm_btns button {
    border: 1px solid lightgray;
    margin: auto;
    background-color: white;
    font-size: 1.5rem;
    cursor: pointer;
    border-radius: 1rem;
    padding: .5rem;
    transition: all .4s;
}

.adm_btns button:hover {
    background-color: rgb(239, 239, 239);
    transform: scale(1.2);
}

.admin_item {
    border: 1px solid rgb(194, 194, 194);
    border-radius: 10px;
    box-shadow: 10px 0px 10px 5px rgba(211, 211, 211, 1);
    text-align: center;
    padding: 1rem;
    background: white;
    width: 20rem;
    transition: all .4s;
}

.tabLinkActive {
    color: rgb(46, 89, 0);
    text-decoration: underline;
}

.tab-link {
    cursor: pointer;
}

.i {
    gap: 2rem;
    flex-wrap: wrap;
}

.editForm {
    display: grid;
    width: 85%;
    height: 75vh;
    max-width: 80rem;
    margin: auto;
    background-color: white;
    display: grid;
    box-shadow: 10px 0px 10px 5px rgba(211, 211, 211, 1);
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

.editForm div {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 0 auto;
    width: 70%;
    max-width: 300px;
}

.loader_wrapper {
    display: flex;
    justify-content: center;
}

.loader2 {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    animation: spin .5s linear infinite;
    width: 2rem;
    height: 2rem;
}

.empty {
    text-align: center;
    background-color: white;
    border-radius: 2rem;
    padding: .5rem;
}
</style>