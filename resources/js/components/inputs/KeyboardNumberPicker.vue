<template>
    <div class="input-numeric-container">
        <input class="input-numeric" :id="inputId" type="nember" @click="toogleClass" v-model="inputNumber" readonly placeholder="0">
        <div class="numeric-list" :class="{'active': show}">
            <ul class="table-numeric">
            <li v-for="i in numbers" :key="'number_'+ i">
                <button type="button" class="key" @click="onclickKey(i)">{{i}}</button>
            </li>
            <li><button type="button" class="key-del" @click="deleteNumber" :disabled="hasNumber"><i class="las la-times"></i></button></li>
            <li><button type="button" class="key" @click="onclickKey(0)">0</button></li>
            <li><button type="button" class="key-clear" @click="clearNumber" :disabled="hasNumber">Clear</button></li>
        </ul> 
        </div>
    </div>
</template>
<script>
import $ from "jquery"
export default {
    props: ['inputId', 'value','maxValue',"errorMessage"],
    data(){
        return {
            inputNumber : this.value,
            show: false,
            numbers : [
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9
            ]
        }
    },
    computed: {
        hasNumber() {
            return this.inputNumber.length <= 0 ? true : false;
        }
    },
    watch: {
        // whenever question changes, this function will run
        value(newVal, oldVal) {
            this.inputNumber = this.value
        },
        inputNumber(newVal, oldVal) {

            if(this.inputNumber > this.maxValue) {
                alert(this.errorMessage);
                this.inputNumber = this.maxValue ? this.maxValue : this.inputNumber;
            }

            this.$emit('input', this.inputNumber );
            this.$emit('change');
        },
    },
    mounted(){
        let self = this;
         // Keyboard Js
        $( document ).ready(function(){
            $('body').click(function(event){
                if($(event.target).closest('.input-numeric-container').length <= 0) {
                    self.show = false;
                }
            });
        });
    // Keyboard Js End

    },
    methods: {
        toogleClass() {
            $(".numeric-list").removeClass('active');

            this.show = !this.show;

        },
        onclickKey(number) {
            this.inputNumber = Number(this.inputNumber.toString() + number);
        },
        deleteNumber() {
            this.inputNumber = Math.floor(this.inputNumber/10);
        },
        clearNumber() {
            this.inputNumber = 1;
        }
    }
}
</script>
