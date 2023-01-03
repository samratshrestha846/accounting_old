<template>
    <div v-if="session" id="lockscreen">
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                <a href="index2.html"><b>Admin</b>LTE</a>
            </div>
            <!-- User name -->
            <div class="lockscreen-name">John Doe</div>

            <!-- START LOCK SCREEN ITEM -->
            <div class="lockscreen-item">
                <!-- lockscreen image -->
                <div class="lockscreen-image">
                <img src="" alt="User Image">
                </div>
                <!-- /.lockscreen-image -->

                <!-- lockscreen credentials (contains the form) -->
                <form class="lockscreen-credentials">
                <div class="input-group">
                    <input type="password" class="form-control" v-model="form.password" placeholder="password">

                    <div class="input-group-append">
                    <button type="button" class="btn" @click="login()">
                        <i class="fas fa-arrow-right text-muted"></i>
                    </button>
                    </div>
                </div>
                </form>
                <!-- /.lockscreen credentials -->

            </div>
            <!-- /.lockscreen-item -->
            <div class="help-block text-center">
                Enter your password to retrieve your session
            </div>
            <div class="text-center">
                <a href="login.html">Or sign in as a different user</a>
            </div>
            <div class="lockscreen-footer text-center">
                Copyright © 2014-2021 <b><a href="https://adminlte.io" class="text-black">AdminLTE.io</a></b><br>
                All rights reserved
            </div>
        </div>
    </div>
</template>
<style scoped>
#lockscreen {
    position: fixed;
    width: 100% !important;
    height: 100vh !important;
    overflow-x: hidden;
    overflow-y: auto;
    top: 0;
    left: 0;
    height: 0;
    width: 0;
    z-index: 1000000;
    bottom: 0;
    right: 0;
    background-color: #e9ecef;
}
</style>
<script>
export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
           session: false,
           timeout: null,
           form: {
               email: this.user.email,
               password: null,
           }
        }
    },
    mounted() {
        // Reset the timer
        // document.addEventListener('mousemove',  );
        // document.addEventListener('onclick', this.resetTimerSession);
        this.resetTimerSession();
    },
    unmounted() {
     // document.removeEventListener('mousemove', this.resetTimerSession)
    //   document.removeEventListener('onclick', this.resetTimerSession)
    },
    methods: {
        resetTimerSession() {
            let self = this;
            console.log(" i am here");
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                console.log("i am logout");
                this.session = true;
                this.showAlertMessage();
            }, 5000)
        },
        showAlertMessage(){
            alert("Your session has expired. Please reload the page");
        },
    }

}
</script>
