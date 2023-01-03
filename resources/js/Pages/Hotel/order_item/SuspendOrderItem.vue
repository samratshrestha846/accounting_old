<template>
    <div class='modal fade text-left' id='suspendedModal'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>Order Suspend</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <p>Please give reason for Suspend</p>
                    <hr>
                    <form @submit.prevent="submit" method='POST'>

                        <div class='form-group'>
                            <label for='reason'>Reason: <span class="text-danger">*</span></label>
                            <input type='text' name='reason' id='reason' v-model="form.reason" class='form-control' placeholder='Enter Reason for Suspend' required>
                        </div>
                        <div class='form-group'>
                            <label for='description'>Description: </label>
                            <textarea name='description' id='description' cols='30' rows='5' class='form-control' v-model="form.description" placeholder='Enter Detailed Reason' required></textarea>
                        </div>
                        <LoadingButton
                            type="submit"
                            title="Submit"
                            class="btn btn-danger w-auto"
                            :loading="submitLoading"
                        ></LoadingButton>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import $ from "jquery"
import LoadingButton from '@/components/Ui/LoadingButton'
export default {
    props: {
        order_id : {
            type: Number,
            required: true,
        },

    },
    components: {
        LoadingButton
    },
    data() {
        return {
            submitLoading: false,
            form : {
                order_id: this.order_id,
                reason : '',
                description: ''
            }
        }
    },
    methods: {
        openModal() {
            $('#suspendedModal').modal('show');
        },
        closeModal() {
            $('#suspendedModal').modal('hide');
        },
        submit() {
            this.submitLoading = true;
            this.$store.dispatch('SUSPEND_RESTAURANT_ORDER', this.form)
            .then(response => {
                this.$toast.open({
                    message: 'Order Item cancled successfully',
                    type: 'success',
                    position: 'top-right'
                });
                this.$emit('success');
                this.closeModal();
            })
            .catch(error => {
                this.$toast.open({
                    message: 'Something went wring',
                    type: 'success',
                    position: 'top-right'
                });
            })
            .finally(() =>{
                this.submitLoading = false;
            });
        }
    }
}
</script>
