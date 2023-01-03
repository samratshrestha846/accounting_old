<template>
    <div id="customerModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Customer</h5>
                    <button type="button" class="close text-white" @click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form">
                        <form action="" method="get" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="company-name">Customer Type</label> -->
                                        <select
                                            class="form-control"
                                            v-model="form.client_type"
                                        >
                                            <option value="" selected disabled>Select Customer Type </option>
                                            <option
                                                v-for="customerType in customerTypes"
                                                :value="customerType"
                                            >
                                            {{customerType}}
                                            </option>
                                        </select>
                                        <span
                                            v-if="form.errors && form.errors['client_type']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['client_type'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="f-name">Full Name</label> -->
                                        <input
                                            type="text"
                                            name="f-name"
                                            placeholder="Full Name"
                                            class="form-control"
                                            v-model="form.name"
                                        >
                                        <span
                                            v-if="form.errors && form.errors['name']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['name'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="address">Address</label> -->
                                        <input
                                            type="text"
                                            name="address"
                                            placeholder="Address"
                                            class="form-control"
                                            v-model="form.local_address"
                                        >
                                        <span
                                            v-if="form.errors && form.errors['local_address']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['local_address'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="email">Email</label> -->
                                        <input
                                            type="email"
                                            name="email"
                                            placeholder="Email"
                                            class="form-control"
                                            v-model="form.email"
                                        >
                                        <span
                                            v-if="form.errors && form.errors['email']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['email'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="phone">Phone</label> -->
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Phone"
                                            v-model="form.phone"
                                        >
                                        <span
                                            v-if="form.errors && form.errors['phone']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['phone'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="city">Provience</label> -->
                                        <select
                                            class="form-control"
                                            v-model="form.province"
                                        >
                                            <option value="" selected disabled>Select Province </option>
                                            <option value="" selected disabled>--</option>
                                            <option v-for="provience in provinces" :value="provience.id">{{provience.eng_name}}</option>
                                        </select>
                                        <span
                                            v-if="form.errors && form.errors['province']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['province'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <label for="city">District</label> -->
                                        <select
                                            class="form-control"
                                            v-model="form.district"
                                        >
                                            <option value="" selected disabled>Select District </option>
                                            <option value="" selected disabled>--</option>
                                            <option v-for="district in districts" :value="district.id">{{district.dist_name}}</option>
                                        </select>
                                        <span
                                            v-if="form.errors && form.errors['district']"
                                            class="invalid-error"
                                        >
                                            {{form.errors['district'][0]}}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                        <LoadingButton
                                            type="button"
                                            class="btn btn-primary"
                                            :loading="submitCutomerLoading"
                                            title="Add Customer"
                                            @load="submit()"
                                        ></LoadingButton>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
import LoadingButton from '@/components/Ui/LoadingButton'
export default {
    props: {
        customerTypes: {
            type: Array
        }
    },
    components: {
        LoadingButton
    },
    data(){
        return {
            submitCutomerLoading: false,
            form: {
                name: "",
                client_type: "",
                local_address: "",
                phone: "",
                email: "",
                district: "",
                province: "",
                errors: null,
            }
        }
    },
    watch: {
        'form.province': function (newVal, oldVal){

            if(!newVal) return;

            this.fetchDistrictList();
        },
    },
    computed: {
        ...mapGetters(['provinces','districts']),
    },
    methods: {
        openModal(){
            $('#customerModal').modal('show');
        },
        closeModal(){
            $('#customerModal').modal('hide');
        },
        submit(){
            this.submitCutomerLoading = true;
            this.$store.dispatch('CREATE_CUSTOMER', this.form)
            .then((response) =>{
                this.resetCustomerFormFields();
                this.closeModal();
                this.submitCutomerLoading = false;
                this.form.errors = null;
                this.$emit('success', response.data.data);

                this.$swal.fire({
                    title: "Customer Added Successfully!!!",
                    showDenyButton: false,
                    confirmButtonText: `Ok`,
                });
            })
            .catch(err => {
                this.submitCutomerLoading = false;
                if (err.response.status === 422){
                    this.form.errors = err.response.data.errors;
                }
            });
        },
        fetchDistrictList(){
            this.$store.dispatch('GET_DISTRICT_LIST', {
                params: {
                    province_id: this.form.province
                }
            }).then(response => {
                // console.log("the district is ", this.districts);
            });
        },
        resetCustomerFormFields(){
            this.form.client_type = "";
            this.form.name = "";
            this.form.email = "";
            this.form.phone = "";
            this.form.local_address = "";
            this.form.provience = "";
            this.form.district = "";
        }

    }
}
</script>
