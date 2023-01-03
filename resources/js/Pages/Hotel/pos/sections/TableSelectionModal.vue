<template>
    <div id="selectTableModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Select Table</h5>
                   <button type="button" class="close text-white" @click="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tables-tabs">
                        <ul class="nav nav-tabs" id="myTab3" role="tablist">
                            <li
                                v-for="floor in floors"
                                class="nav-item"
                                :key="'nav_'+floor.id"
                            >
                                <a
                                    class="nav-link"
                                    id="floor1-tab"
                                    data-toggle="tab"
                                    :class="{'active': selected.floor_id === floor.id }"
                                    :href="'#floor_'+floor.id"
                                    @click="selected.floor_id = floor.id"
                                >
                                        {{floor.name}}
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent3">
                            <div
                                v-for="floor in floors"
                                class="tab-pane fade show"
                                :id="'floor_' + floor.id"
                                :key="'nav_tab_'+floor.id"
                                :class="{'active': selected.floor_id === floor.id}"
                            >
                                <div class="tables-tabs-category">
                                    <div
                                        v-for="room in floor.rooms"
                                        class="tables-tabs-cols"
                                        :key="'room_'+room.id"
                                    >
                                        <h3>{{room.name}}</h3>
                                        <ul>
                                            <li
                                                v-for="table in room.tables"
                                                :key="'table_'+table.id"
                                                :class="{'Onreserved' : table.is_reserved , 'Onselect' : isTableSelected(table.id)}"
                                                @click="addSelectTableId(table.id)"
                                            >
                                                {{table.name}}
                                            </li>
                                            <!-- <li>Table 2</li>
                                            <li>Table 3</li>
                                            <li class="Onreserved">Table 4</li>
                                            <li>Table 5</li>
                                            <li>Table 6</li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="errorMessage" class="alert alert-danger">
                        {{errorMessage}}
                    </div>
                    <!-- <v-app id="inspire">
                        <div v-if="selectedTable" class="d-flex align-items-center">
                            <span class="badge badge-primary">
                                <ul class="list-inline mb-0 pl-0">
                                    <li class="list-inline-item">{{selectedTable.floor.name}}</li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-angle-right"></i>
                                    </li>
                                    <li class="list-inline-item">{{selectedTable.room.name}}</li>
                                    <li class="list-inline-item"><i class="fa fa-angle-right"></i></li>
                                    <li class="list-inline-item">{{selectedTable.name}}</li>
                                </ul>
                            </span>
                            <a href="javascript:void(0)" class="ml-2" @click="clearSelectedTable"><i class="fa fa-times"></i></a>
                        </div>
                        <v-list>
                            <v-list-group
                            v-if="!loading"
                            v-for="floor in floors"
                            :value="false"
                            :key="floor.name"
                            >
                                <template v-slot:activator>
                                    <v-list-item-title>{{floor.name}}(Floor)</v-list-item-title>
                                </template>

                                <v-list-group
                                    v-for="(room,i) in floor.rooms"
                                    :value="false"
                                    no-action
                                    sub-group
                                    :key="room.name"
                                >
                                    <template v-slot:activator>
                                        <v-list-item-content>
                                            <v-list-item-title>{{room.name}}(Room)</v-list-item-title>
                                        </v-list-item-content>
                                    </template>

                                    <v-list-item
                                    v-for="table in room.tables"
                                    :key="table.name"
                                    link
                                    >
                                        <v-list-item-action>
                                            <input
                                            type="checkbox"
                                            :value="table.id"
                                            v-model="checkedTableValues"
                                            color="primary"
                                            @change="uniqueCheck($event)"
                                            :disabled="table.is_reserved"
                                            >

                                        </v-list-item-action>

                                        <v-list-item-content>
                                            <v-list-item-title :class="{'text-muted': table.is_reserved}">
                                                {{table.name}} {{ table.is_reserved ? '(Reserved)' : ''}}
                                            </v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-group>
                            </v-list-group>
                        </v-list>
                        <div v-if="loading" class="loading text-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div v-if="!loading && tables && tables.length <= 0" class="no-content-found text-center">
                            <span class="">No tables were found</span>
                        </div>
                    </v-app> -->
                    <button type="button" class="btn btn-primary ml-auto mt-3" @click="submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Modal from "@/components/Modal"
import $ from "jquery"
export default {
    props: {
        tableid: {
            type: Number,
        }
    },
    components: {
        Modal
    },
    data: () => ({
        loading: false,
        errorMessage: null,
        floors: [],
        tables: [],
        active: false,
        checkedTableValues: [],
        selectedTables: [],
        selected: {
            floor_id: "",
        }
    }),
    methods: {
        openModal() {
            this.floors = [];
            this.fetchTableList();
            $('#selectTableModal').modal('show');
        },
        closeModal() {
            $('#selectTableModal').modal('hide');
        },
        addSelectTableId(tableId) {

            this.errorMessage = "";
            let table = this.tables.find(table => table.id == tableId);

            //if the table is reserved
            if(table.is_reserved) {
                this.errorMessage = "You cannot select a reserved table";
            }


            //if the table_id is selected then remove table id
            //otherwise add table_id to list
            if(this.isTableSelected(tableId)) {
                let index = this.selectedTables.indexOf(tableId);
                this.selectedTables.splice(index, 1);
            } else {
                this.selectedTables.push(tableId);
            }
        },
        clearSelectedTable() {
            this.checkedTableValues = [];
            this.selectedTables = null;
        },
        uniqueCheck(event){
            this.checkedTableValues = [];
            if (event.target.checked) {
                this.checkedTableValues.push(event.target.value);
                this.selectedTable = this.tables.find(item => item.id == event.target.value)
            } else {
                this.selectedTable = null;
            }
        },
        fetchTableList(){
            this.loading = true;
            this.$store.dispatch('GET_RESTAURANT_ORDER_TABLE_LIST')
            .then(response => {
                this.tables = response.data.data;
                this.loading = false;
                this.initData();

                if(this.tableid) {
                    this.checkedTableValues = [];
                    this.selectedTable = this.tables.find(item => item.id == this.tableid)
                    this.checkedTableValues.push(this.tableid);
                }


            })
            .catch(error => {

            });
        },
        submit() {
            this.$emit('change', this.selectedTables);
            this.closeModal();
        },
        initData() {

            let floorId = [];

            this.tables.forEach((table , index) => {
                console.log(floorId.includes(table.floor_id));
                if(floorId.includes(table.floor_id))
                    return;

                floorId.push(table.floor_id);

                if(!this.selected.floor_id) {
                    this.selected.floor_id = table.floor_id;
                }

                this.floors.push({
                    id: table.floor.id,
                    name: table.floor.name,
                    code: table.floor.code,
                    rooms: this.getFiteredRoomList(table.floor_id),
                });
            });
        },
        getFiteredRoomList(floor_id) {
            let roomId = [];

            let tables = [];

            this.tables.filter(item => {
                return item.floor_id == floor_id;
            }).forEach(item => {
                if(roomId.includes(item.room_id))
                    return;

                roomId.push(item.room_id);

                tables.push({
                    id: item.room.id,
                    name: item.room.name,
                    code: item.room.code,
                    floor_id: item.floor_id,
                    tables: this.tables.filter(item1 => item1.floor_id == floor_id && item1.room_id == item.room_id),
                });
            });

            return tables;
        },
        isTableSelected(tableId) {
            return this.selectedTables.includes(tableId);
        }
    }
}
</script>
