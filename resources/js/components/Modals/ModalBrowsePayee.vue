<template>
    <div>
        <b-field>
            <b-input :value="valueItemName" expanded
                 icon="bank"
                 placeholder="Select Payee/Supplier/Account"
                 required readonly>
            </b-input>

            <p class="control">
                <b-button class="button is-primary" @click="openModal">...</b-button>
            </p>
        </b-field>


        <b-modal v-model="isModalActive" has-modal-card
                 trap-focus scroll="keep" aria-role="dialog" aria-modal>
            <div class="modal-card card-width">
                <header class="modal-card-head">
                    <p class="modal-card-title has-text-weight-bold is-size-4">SELECT PAYEE/BANK ACCOUNT/SUPPLIER</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false" />

                </header>

                <section class="modal-card-body">
                    <div>
                        <b-field label="Search" label-position="on-border" >
                            <b-input type="text"
                                v-model="search.payee"
                                    placeholder="Search Payee/Supplier/Account..."
                                    @keyup.native.enter="loadAsyncData"
                                    expanded auto-focus></b-input>
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

                                <b-table-column field="payee_id" label="ID" v-slot="props">
                                    {{props.row.payee_id}}
                                </b-table-column>

                                <b-table-column field="bank_account_payee" label="BANK ACCOUNT / PAYEE" v-slot="props">
                                    {{props.row.bank_account_payee}}
                                </b-table-column>

                                <b-table-column field="owner" label="OWNER" v-slot="props">
                                    {{props.row.owner}}
                                </b-table-column>

                                <b-table-column field="tin" label="TIN" v-slot="props">
                                    {{props.row.tin}}
                                </b-table-column>

                                <b-table-column field="" label="Action" v-slot="props">
                                    <div class="buttons">
                                        <b-button class="is-small is-info is-outlined is-rounded has-text-weight-bold"
                                            icon-right="arrow-right-thin" 
                                            @click="selectData(props.row)">
                                            SELECT</b-button>
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
        propName: {
            type: String,
            default: '',
        },

    },


    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'payee_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},

            item: {},

            search: {
                payee: ''
            },
        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `payee=${this.search.payee}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-modal-payee?${params}`)
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
            this.$emit('browsPayee', dataRow);
        }

    },

    computed: {
        valueItemName(){
            return this.propName;
        },




    },

}
</script>

<style scoped>
.card-width{
    width: 640px;
}
</style>
