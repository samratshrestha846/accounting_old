<template>
  <transition name="fade">
    <div v-if="show" :id="id" class="modals">
      <!-- modals content -->
      <div class="modals-dialog" :class="type">
        <div class="modals-content">
          <div v-if="hasHeaderSlot" class="modals-header">
            <slot name="header" />
            <span class="close" @click="closeModal">&times;</span>
          </div>
          <div class="modals-body">
            <slot name="body" />
          </div>
          <div v-if="hasFooterSlot" class="modals-footer text-right">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import $ from "jquery";

export default {
  props: {
    id: { type: String, default: "mymodals" },
    type: { type: String },
    item: { type: String },
  },
  data() {
    return {
      show: false,
    };
  },
  computed: {
    hasHeaderSlot() {
      return !!this.$slots.header;
    },
    hasFooterSlot() {
      return !!this.$slots.footer;
    },
  },
  mounted() {
    $(".modals").appendTo("#app");
    console.log(this.item);
  },
  methods: {
    closeModal() {
      if (!this.isEmpty(this.id)) {
        $("#" + this.id).hide();
      }
      this.show = false;
      document.querySelector("body").classList.remove("overflow-hidden");
    },
    openModal() {
      this.show = true;
      document.querySelector("body").classList.add("overflow-hidden");
    },
    isEmpty(value) {
      return value === undefined || value === null;
    },
  },
};
</script>


<style>
.modals {
  position: fixed; /* Stay in place */
  z-index: 10000; /* Sit on top */
  padding-top: 50px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0, 0, 0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

/* modals Content */
.modals-dialog {
  position: relative;
  margin: 0 auto;
  padding: 0.5rem;
  max-width: 800px;
}

.modals-dialog.modal-sm{
    max-width: 500px !important;
}

.modals-dialog .modals-content {
  /**min-height: 400px !important;**/
  width: 100% !important;
  background-color: #fefefe;
  border: 1px solid #888;
  border-radius: 0.5em;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s;
}

.modals-dialog.modals-small {
  max-width: 442px !important;
  margin: 0 auto;
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {
    top: -300px;
    opacity: 0;
  }
  to {
    top: 0;
    opacity: 1;
  }
}

@keyframes animatetop {
  from {
    top: -300px;
    opacity: 0;
  }
  to {
    top: 0;
    opacity: 1;
  }
}

/* The Close Button */
.close {
  color: #111111;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modals-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 1rem 1rem;
  border-bottom: 1px solid #dee2e6;
  border-top-left-radius: calc(0.3rem - 1px);
  border-top-right-radius: calc(0.3rem - 1px);
}

.modals-title {
  margin-bottom: 0;
  line-height: 1.6;
  font-size: 1.15rem;
  font-weight: 500;
}

.modals-body {
  padding: 2px 16px;
  padding-bottom: 20px;
  padding-top: 20px;
}

.modals-footer {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: end;
  -ms-flex-pack: end;
  justify-content: flex-end;
  padding: 1rem;
  border-top: 1px solid #e9ecef;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
