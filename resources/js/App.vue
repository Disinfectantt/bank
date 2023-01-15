<template>
    <div class="all">
        <vue_header />
        <router-view />
        <vue_footer />
    </div>
</template>

<script>
import vue_header from './components/vue_header.vue';
import vue_footer from './components/vue_footer.vue';

export default {
    name: 'App',
    components: {
        vue_header,
        vue_footer
    },
}
</script>
  
<style lang="css">
@import '../css/app.css';
</style>