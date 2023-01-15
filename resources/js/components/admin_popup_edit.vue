<template>
    <div class="wrapper_alert" @click.self="close">
        <div class="edit_close" @click="close">
            <span></span>
            <span></span>
        </div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    methods: {
        close() {
            this.$emit('close');
        }
    }
}
</script>

<style>
.edit_close {
    position: absolute;
    left: 90%;
    bottom: 90%;
    height: 40px;
    cursor: pointer;
}

.edit_close span:nth-child(1) {
    transform: rotate(-45deg);
}

.edit_close span:nth-child(2) {
    bottom: 5px;
    transform: rotate(45deg);
}

.edit_close span {
    display: block;
    position: relative;
    width: 30px;
    height: 5px;
    background-color: #000000;
    border-radius: 13px;
}
</style>