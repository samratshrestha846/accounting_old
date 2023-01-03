<template>
    <ul class="" :class="{'dropdown-menu' : !ismobile, 'mm-collapse' : ismobile}">
        <li v-for="outlet in outlets">
            <a
                class="dropdown-item"
                :class="{'disabled': outlet.id === selected_outlet.id}"
                href="javascript:void(0)"
                @click="actionSwitchOutlet(outlet)"
            >
                <i v-if="outlet.id === selected_outlet.id" class="fa fa-check text-muted mr-2"></i>
                {{outlet.name}}
            </a>
        </li>
    </ul>
</template>
<style scoped>
    a.disabled{
        pointer-events: none;
        cursor: default;
    }
</style>
<script>
export default {
    props: {
        ismobile: {
            type: Boolean,
            default: false
        },
        selected_outlet: {
            type: Object,
            required: true,
        },
        outlets: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {

        }
    },
    mounted(){

    },
    methods: {
        actionSwitchOutlet(outlet) {
            this.$swal.fire({
                title: `Are you sure you want to switch to ${outlet.name} ?`,
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '/pos/switch-outlet'

                    $('<form action="'+url+'" method="POST"></form>')
                        .append('<input type="hidden" name="outlet_id" value="' + outlet.id + '" />')
                        .append('<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'" />')
                        .appendTo($('body'))
                        .submit();
                        }
            });
        }
    }
}
</script>
