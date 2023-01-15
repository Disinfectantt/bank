<script>
import formA from './formA.vue';
import formB from './formB.vue';
export default {
    components: { formA, formB }
}
</script>

<template>
    <main>
        <section class="info" id="bank">
            <p>Считая себя ответственными за благосостояние тех, кто доверил нам управление своими капиталами, мы
                предлагаем разнообразный набор современных банковских услуг с использованием передовых технологий
                клиентского обслуживания. Индивидуальный подход к каждому клиенту отражается в разработке и
                реализации
                персональных банковских продуктов, позволяющих оптимизировать взаиморасчеты с партнерами, снизить
                расходы и увеличить отдачу от вложенных денежных средств.</p>
            <a href="#conn" class="allo smooth">Обратная связь</a>
        </section>
        <section class="services">
            <div class="center" id="services">
                <h2>Наши услуги</h2>
            </div>
            <div class="wrapper">
                <div class="service" id="cervices">
                    <h3>Расчет кредита</h3>
                    <img src="img/calc.jpg" class="service-img" alt="Калькулятор с деньгами">
                    <p>Калькулятор рассчета</p>
                    <router-link :to="{ name: 'check_credit' }" class="button">Рассчитать</router-link>
                </div>
                <div class="service">
                    <h3>Оформление кредита</h3>
                    <img src="img/pwa_vertical_1024_f07bd3ef976cb2aa58c8c56518739088.jpg" class="service-img"
                        alt="Доллары">
                    <p>Заполнить форму кредита и получить 100% одобрение</p>
                    <router-link :to="{ name: 'credit' }" class="button">Оформить</router-link>
                </div>
                <div class="service">
                    <h3>Выпуск карт</h3>
                    <img src="img/2.3.jpg" class="service-img" alt=" ">
                    <p>Оформите карты с кэшбеком</p>
                    <router-link :to="{ name: 'card' }" class="button smooth">Заказать</router-link>
                </div>
                <div class="service">
                    <h3>Вклад</h3>
                    <img src="img/66_schet_aktivnyi_ili_passivnyi.jpg" class="service-img" alt=" ">
                    <p>Вклад с выгодным процентом, ваши деньги в полной безопасности</p>
                    <router-link :to="{ name: 'deposit' }" class="button">Оформить</router-link>
                </div>
            </div>
        </section>
        <section class="cards">
            <div class="center" id="cards">
                <h2>Карты</h2>
            </div>
            <div class="wrapper">
                <div class="card">
                    <h3>Bank black</h3>
                    <img src="img/png-transparent-payment-card-black-card-credit-card-visa-black-and-white-card-payment-black-and-white-card-black-card.png"
                        class="card-img" alt=" ">
                    <p>Крутая карта с кэшбеком</p>
                    <router-link :to="{ name: 'card', query: { card: 'card1' } }" class="button">Заказать</router-link>
                </div>
                <div class="card">
                    <h3>Bank red</h3>
                    <img src="img/visa_red_600x379_common_29-04-2020.png" class="card-img" alt=" ">
                    <p>Ещё более крутая карта с кэшбеком</p>
                    <router-link :to="{ name: 'card', query: { card: 'card2' } }" class="button">Заказать</router-link>
                </div>
                <div class="card">
                    <h3>Bank ultra platinum mega cool nagibator 3000</h3>
                    <img src="img/card-main.png" class="card-img" alt=" ">
                    <p>Самая крутая карта с невероятным кэшбеком</p>
                    <router-link :to="{ name: 'card', query: { card: 'card3' } }" class="button">Заказать</router-link>
                </div>
            </div>
        </section>
        <section class="conn">
            <div class="center" id="conn">
                <h2>Обратная связь</h2>
            </div>
            <formA id="conn"></formA>
        </section>
        <section class="reviews">
            <div class="center" id="reviews">
                <h2>Отзывы</h2>
            </div>
            <div class="wrapper">
                <div class="review">
                    <div class="center">
                        <h3>Петров Петр Петрович</h3><img src="img/ffcda1ddf83fe41924b1481d0ad1ccee.png" class="img-rev"
                            alt=" ">
                    </div>
                    <p>Одна из лучших компаний. всегда вежливые операторы. отвечают грамотно на все вопросы. Лучше
                        них
                        пока никого не было у меня!</p>
                </div>
                <div class="review">
                    <div class="center">
                        <h3>Василий Васильевич Васильев</h3><img src="img/1568622546_2.png" class="img-rev" alt=" ">
                    </div>
                    <p>Четкая контора, всегда выручают</p>
                </div>
                <div class="review">
                    <div class="center">
                        <h3>Иван Иванов Иванович</h3><img src="img/large.png" class="img-rev" alt=" ">
                    </div>
                    <p>Очень выгодные вклады, обещают доходность 100% годовых! Я уверен, что это не пирамида и мои
                        деньги в полной сохранности!</p>
                </div>
            </div>
        </section>
        <section class="update">
            <div class="center">
                <h2>Подписаться на обновления</h2>
            </div>
            <formB></formB>
        </section>
    </main>
</template>