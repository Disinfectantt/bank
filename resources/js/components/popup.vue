<script>
export default {
    methods: {
        close() {
            this.$emit('close');
        }
    }
}
</script>

<template>
    <div class="wrapper_alert" @click.self="close">
        <div class="alert">
            <div class="close" @click="close">
                <span></span>
                <span></span>
            </div>
            <p id="alert_text">
                <slot></slot>
            </p>
        </div>
    </div>
</template>