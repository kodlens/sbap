<template>
    <div>
        <b-field>
            <b-input :value="valueName" expanded
                 icon="format-list-checkbox"
                 placeholder="Select Priority Program"
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
                    <p class="modal-card-title has-text-weight-bold is-size-4">SELECT OBJECT OF EXPENDITURE</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false" />

                </header>

                <section class="modal-card-body">
                    <div>
                        <b-field label="Search..." label-position="on-border" >
                            <b-input type="text"
                                v-model="search.OOE"
                                placeholder="Search Object of Expenditure..."
                                @keyup.native.enter="loadAsyncData"
                                expanded auto-focus></b-input>

                          
                            <p class="control">
                                <b-button class="is-primary" icon-left="magnify" @click="loadAsyncData"></b-button>
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

                                <b-table-column field="object_expenditure_id" label="ID" v-slot="props">
                                    {{props.row.object_expenditure_id}}
                                </b-table-column>

                                <b-table-column field="object_expenditure" label="Object Of Expenditure" v-slot="props">
                                    {{ props.row.object_expenditure }}
                                </b-table-column>

                                <b-table-column field="allotment_class_code" label="Code" v-slot="props">
                                    {{ props.row.allotment_class_code }}
                                </b-table-column>

                                <b-table-column field="allotment_class" label="Allotment Class" v-slot="props">
                                    {{ props.row.allotment_class }}
                                </b-table-column>


                                <b-table-column field="" label="Action" v-slot="props">
                                    <div class="buttons">
                                        <b-button class="is-small has-text-weight-bold is-info is-outlined is-rounded" 
                                            icon-right="arrow-right-thin"
                                            @click="selectData(props.row)">SELECT</b-button>
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

        propFinancialYearId: {
            type: Number,
            default: 0
        },
        propObjectExpenditure: {
            type: String,
            default: '',
        },

  

    },


    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'object_expenditure_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},

            financialYears: [],

            search: {
                pp: '',
                code: '',
            },
        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `financial=${this.propFinancialYearId}`,
                `code=${this.search.code}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-modal-object-expenditures?${params}`)
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
            this.$emit('browseObjectExpenditure', dataRow);
        },



    },

    mounted(){
        //this.loadFinancialYears()
        this.loadAsyncData()
    },

    computed: {
        valueName(){
            return this.propObjectExpenditure;
        },

    },

}
</script>

<style scoped>
.card-width{
    width: 640px;
}
</style>
