<script>
export default {
    data() {
        return {
            period: 1,
            sum: 1000,
            percent: 1500
        }
    },
    methods: {
        sendFormC() {
            this.$router.push({
                name: 'credit',
                query: { sum: this.sum, period: this.period }
            });
        },
        getPercent() {
            this.percent = Math.round((this.sum * 1.5) / this.period);
        }
    },
}

</script>

<template>
    <main>
        <section class="check">
            <div class="center">
                <h2>Рассчитать кредит</h2>
            </div>
            <form class="c" action="credit.php" method="GET" @submit.prevent="sendFormC">
                <div class="crdt">
                    <div class="range"><label for="period">Срок кредита (в месяцах)</label>
                        <input type="range" min="1" max="36" step="1" id="period" name="period" v-model="period"
                            @input="getPercent()">
                        <p id="srok">{{ period }}</p>
                    </div>
                    <div class="range"><label for="sum">Сумма кредита (руб)</label>
                        <input type="range" min="1000" max="500000" step="1000" id="sum" v-model="sum" name="sum"
                            @input="getPercent()">
                        <p id="summ">{{ sum }}</p>
                    </div>
                </div>
                <div class="order">
                    <p id="percent">Ежемесячный платеж: {{ percent }}</p>
                    <input type="submit" class="button submit" value="Оформить сейчас">
                </div>
            </form>
        </section>
    </main>
</template>

<style scoped>
section{
    margin-top: 4rem;
}
</style>