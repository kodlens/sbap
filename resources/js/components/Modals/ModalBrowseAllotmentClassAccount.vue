<template>
    <div>
        <b-field>
            <b-input :value="valueName" expanded
                 icon="format-list-checkbox"
                 placeholder="Select Allotment Account"
                 required readonly>
            </b-input>

            <p class="control">
                <b-button class="button is-primary" 
                    @click="openModal">...</b-button>
            </p>
        </b-field>


        <b-modal v-model="isModalActive" has-modal-card
                 trap-focus scroll="keep" aria-role="dialog" aria-modal>
            <div class="modal-card card-width">
                <header class="modal-card-head">
                    <p class="modal-card-title has-text-weight-bold is-size-4">SELECT ALLOTMENT ACCOUNT</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false" />

                </header>

                <section class="modal-card-body">
                    <div>
                        <b-field label="Search..." label-position="on-border" >
                            <b-input type="text"
                                v-model="search.code"
                                placeholder="Search Allotment Account Code..."
                                @keyup.native.enter="loadAsyncData"
                                expanded auto-focus></b-input>

                            <b-input type="text"
                                 v-model="search.account"
                                 placeholder="Search Allotment Account..."
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

                                <b-table-column field="allotment_class_account_id" label="ID" v-slot="props">
                                    {{props.row.allotment_class_account_id}}
                                </b-table-column>

                                <b-table-column field="allotment_class" label="Allotment Class" v-slot="props">
                                    {{ props.row.allotment_class.allotment_class }}
                                </b-table-column>

                                <b-table-column field="allotment_class_account_code" label="Allotment Account Code" v-slot="props">
                                    {{ props.row.allotment_class_account_code }}
                                </b-table-column>

                                <b-table-column field="allotment_class_account" label="Allotment Account" v-slot="props">
                                    {{ props.row.allotment_class_account }}
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
        propAllotmentAccount: {
            type: String,
            default: '',
        },

        propClassId: {
            type: Number,
            default: 0,
        }

    },


    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'allotment_class_account_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},


            search: {
                account: '',
                code: '',
            },
        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `classid=${this.propClassId}`,
                `account=${this.search.account}`,
                `code=${this.search.code}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-modal-allotment-class-accounts?${params}`)
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
            this.$emit('browseAllotmentAccount', dataRow);
        }

    },

    computed: {
        valueName(){
            return this.propAllotmentAccount;
        },




    },

}
</script>

<style scoped>
.card-width{
    width: 640px;
}
</style>
