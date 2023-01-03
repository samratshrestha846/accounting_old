<template>
<div v-if="loading" class="overlay">
    <div class="content bg-primary text-white">
        <div class="spinner-border text-white mr-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span class="">{{text}}</span>
    </div>
</div>
</template>
<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: true
        },
        text: {
            type: String,
            default: 'Loading...',
        },
    }
}
</script>
<style scoped>
.overlay{
    background-color: rgba(0,0,0,0.5);
    position: fixed;
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    top:0;
    bottom: 0;
}

.overlay .content {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 10px;
}

.overlay .spinner-border{
    height: 10px;
    width: 10px;
}

.bg-primary{
    background: #dc3f26;
}
</style>
