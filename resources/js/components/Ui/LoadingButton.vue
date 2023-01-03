<template>
    <button :type="type" class="btn btn-primary btn_submit" :class="{'disabled': loading === true}" :style="style_" v-on:click="submit()">
        <span v-if="loading === true" class="spinner-border spinner-border-sm mr-2"></span>
        {{title}}
    </button>
</template>

<style scoped>
.btn_submit{
    padding-right: 30px;
    padding-left: 30px;
    color:#ffffff;
    /* display: flex;*/
    /* align-items: center;*/
    border-radius: 0 !important;
    width: inherit;
    text-align: center;
    text-transform: uppercase;
}
.btn_submit.disabled{
    cursor: not-allowed;
    pointer-events: none;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
</style>

<script>
export default{
    props: {
        title: {
            type: String,
            default: 'BUTTON'
        },
        loading: {
            type: Boolean,
            default: false
        },
        type :{
            type: String,
            default: "button"
        },
        style_: Object
    },
    created(){
        console.log("the loading is ", this.loading);
    },
    methods: {
        submit(){
            this.$emit('load');
        }
    }
}
</script>
