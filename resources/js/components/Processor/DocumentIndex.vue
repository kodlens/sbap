<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10-widescreen is-12-desktop is-12-tablet">
                    <div class="box">

                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">ACCOUNTING DOCUMENTS</div>

                        <div class="level">
                            <div class="level-left">
                                <b-field label="Page">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                    <b-select v-model="sortOrder" @input="loadAsyncData">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>

                                    </b-select>
                                </b-field>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <b-field label="Search">
                                        <b-input type="text"
                                                 v-model="search.key" placeholder="Search..."
                                                 @keyup.native.enter="loadAsyncData"/>
                                        <p class="control">
                                             <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="magnify" @click="loadAsyncData"/>
                                             </b-tooltip>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="buttons mt-3 is-right">
                            <b-button tag="a"
                                href="/accounting/create"
                                icon-right="bank-outline"
                                class="is-primary">ADD RECORD</b-button>
                        </div> -->

                        <b-table
                            :data="data"
                            :loading="loading"
                            detailed
                            paginated
                            backend-pagination
                            :total="total"
                            :bordered="true"
                            :hoverable="true"
                            :per-page="perPage"
                            @page-change="onPageChange"
                            aria-next-label="Next page"
                            aria-previous-label="Previous page"
                            aria-page-label="Page"
                            aria-current-label="Current page"
                            backend-sorting
                            :default-sort-direction="defaultSortDirection"
                            @sort="onSort">

                            <b-table-column field="accounting_id" label="ID" v-slot="props">
                                {{ props.row.accounting_id }}
                            </b-table-column>

                            <b-table-column field="date_time" label="Date & Time" v-slot="props">
                                {{ props.row.date_time }}
                            </b-table-column>

                            <b-table-column field="fund_source" label="Fund/Source" v-slot="props">
                                <span v-if="props.row.fund_source">
                                    {{ props.row.fund_source.fund_source }}
                                </span>
                            </b-table-column>

                            <b-table-column field="transaction_no" label="Transaction No" v-slot="props">
                                {{ props.row.transaction_no }}
                            </b-table-column>

                            <b-table-column field="payee" label="Payee" v-slot="props">
                                <span v-if="props.row.payee"> {{ props.row.payee.bank_account_payee }}</span>
                            </b-table-column>

                            <b-table-column field="particulars" label="Particulars" v-slot="props">
                                {{ props.row.particulars }}
                            </b-table-column>

                            <b-table-column field="total_amount" label="Total Amount" v-slot="props">
                                {{ props.row.total_amount }}
                            </b-table-column>


                            <b-table-column field="received" label="Date Time Received" v-slot="props">
                                <span v-if="props.row.processor_datetime_received">
                                    {{ new Date(props.row.processor_datetime_received).toLocaleString() }}
                                </span>
                            </b-table-column>


                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Add Progress" type="is-info">

                                        <b-dropdown aria-role="list">
                                            <template #trigger="{ active }">
                                                <b-button
                                                    label=""
                                                    size="is-small"
                                                    type="is-info"
                                                    :icon-right="active ? 'menu-up' : 'menu-down'" />
                                            </template>

                                            <b-dropdown-item aria-role="listitem" @click="processorMarkReceive(props.row.accounting_id)">Mark Received</b-dropdown-item>

                                        </b-dropdown>
                                    </b-tooltip>
                                </div>
                            </b-table-column>

                            <template #detail="props">
                                <table-bid-award :propRow="props.row"
                                    @emitRefresh="loadAsyncData"></table-bid-award>
                                <br>
                                <table-city-budget :propRow="props.row"
                                    @emitRefresh="loadAsyncData"></table-city-budget>
                                <br>
                                <table-city-accounting :propRow="props.row"
                                    @emitRefresh="loadAsyncData"></table-city-accounting>
                                <br>
                                <table-city-treasurer :propRow="props.row"
                                    @emitRefresh="loadAsyncData"></table-city-treasurer>
                                <br>
                                <table-college-accounting :propRow="props.row"
                                    @emitRefresh="loadAsyncData"></table-college-accounting>
                            </template>
                        </b-table>

                        <div class="buttons">
                            <download-excel
                                class="button is-primary"
                                size="is-small"
                                :fetch="fetchData"
                                type="csv"
                                name="filename.xls"
                            >
                                Download to Excel
                            </download-excel>
                        </div>

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->





    </div>
</template>

<script>

export default{

    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'accounting_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 20,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                key: '',
            },

         
            modalRemarksBidAward: false,


            fields: {},
            errors: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `key=${this.search.key}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-documents?${params}`)
                .then(({data}) => {
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
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage() {
            this.loadAsyncData()
        },

        openModal() {
            this.isModalCreate = true;
            this.clearFields()
            this.errors = {};
        },

        async fetchData(){
            const res = await axios.get('/fetch-accountings')
            return res.data
        },

        emitBrowserProcessor(row){
            console.log(row)

            axios.post('/accounting-assign-processor', row).then(res=>{
                if(res.data.status === 'assigned'){
                    this.$buefy.dialog.alert({
                        title: 'ASSIGNED!',
                        message: 'Successfully assigned.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.loadAsyncData()
                        }
                    })
                }
            })

        },

        processorMarkReceive(id){
            axios.post('/document-mark-receive/' + id).then(res=>{
                if(res.data.status === 'received'){
                    this.$buefy.dialog.alert({
                        title: 'RECEIVED!',
                        message: 'Successfully received.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.loadAsyncData()
                        }
                    })
                }
            }).catch(err=>{
            
            })
        }


      
    },


    mounted() {
        this.loadAsyncData();

    }
}
</script>


<style>


</style>
