<template>
    <div>
        <b-field>
            <b-input :value="valueItemName" expanded icon-pack="fa"
                     icon="user" placeholder="SELECT ITEM NAME" required readonly>
            </b-input>

            <p class="control">
                <b-button class="button is-primary" @click="openModal">...</b-button>
            </p>
        </b-field>


        <b-modal v-model="this.isModalActive" has-modal-card
                 trap-focus scroll="keep" aria-role="dialog" aria-modal>
            <div class="modal-card card-width">
                <header class="modal-card-head">
                    <p class="modal-card-title">SELECT ITEM</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false" />

                </header>

                <section class="modal-card-body">
                    <div>
                        <b-field label="Search" label-position="on-border" >
                            <b-input type="text" v-model="search.barcode" placeholder="Search Item Barcode..." @keyup.native.enter="loadAsyncData" expanded auto-focus></b-input>
                            <b-input type="text" v-model="search.itemname" placeholder="Search Item Name..." @keyup.native.enter="loadAsyncData" expanded auto-focus></b-input>
                            <p class="control">
                                <b-button class="is-primary" icon-pack="fa" icon-left="search" @click="loadAsyncData"></b-button>
                            </p>
                        </b-field>

                        <div class="table-container">
                            <b-table
                                :data='data'
                                :loading="loading"
                                paginated
                                backend-pagination
                                :total='total'
                                :per-page="perPage"
                                @page-change="onPageChange"
                                detail-transition=""
                                aria-next-label="Next page"
                                aria-previous-label="Previouse page"
                                aria-page-label="Page"
                                :show-detail-icon=true
                                aria-current-label="Current page"
                                default-sort-direction="defualtSortDirection"
                                @sort="onSort">

                                <b-table-column field="item_id" label="ID" v-slot="props">
                                    {{props.row.item_id}}
                                </b-table-column>

                                <b-table-column field="barcode" label="Barcode" v-slot="props">
                                    {{props.row.barcode}}
                                </b-table-column>

                                <b-table-column field="item_name" label="Item Name" v-slot="props">
                                    {{props.row.item_name}}
                                </b-table-column>

                                <b-table-column field="item_description" label="Description" v-slot="props">
                                    {{props.row.item_description}}
                                </b-table-column>

                                <b-table-column field="" label="Action" v-slot="props">
                                    <div class="buttons">
                                        <b-button class="is-small is-warning" @click="selectData(props.row)">
                                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;SELECT</b-button>
                                    </div>
                                </b-table-column>
                            </b-table>
                        </div>

                    </div>
                </section>

                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="isModalActive=false"></b-button>
                </footer>
            </div>
        </b-modal>


    </div>
</template>

<script>
export default {
    props: {
        propItem: {
            type: String,
            default: '',
        },

    },


    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'item_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},

            item: {},

            search: {
                itemname: '',
                barcode: '',
                serial: '',
            },
        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `itemname=${this.search.itemname}`,
                `barcode=${this.search.barcode}`,
                `serial=${this.search.serial}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-items?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },

        onPageChange(page) {
            this.page = page;
            this.loadAsyncData();
        },

        onSort(field, order) {
            this.sortfield = field;
            this.sortOrder = order;
            this.loadAsyncData();
        },

        setPerPage() {
            this.loadAsyncData();
        },

        openModal(){
            this.loadAsyncData();
            this.isModalActive = true;
        },

        selectData(dataRow){
            this.isModalActive = false;
            this.$emit('browseItem', dataRow);
        }

    },

    computed: {
        valueItemName(){
            return this.propItem;
        },




    },

}
</script>

<style scoped>
.card-width{
    width: 640px;
}
</style>
