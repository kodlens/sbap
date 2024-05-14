
<template>
    <div>
        <b-button class="button is-small info mr-1"
                  icon-right="human-male-board"
                  @click="assignProcessor"></b-button>


        <!--modal create-->
        <b-modal v-model="modalAssignProcessor" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">SELECT PROCESSOR</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalAssignProcessor = false"/>
                    </header>

                    <section class="modal-card-body">

                        <b-field label="Search"
                                 type="is-danger">
                            <b-input v-model="search.name" expanded
                                     icon="chair-rolling"
                                     placeholder="Search...">
                            </b-input>

                            <p class="control">
                                <b-button class="button is-primary" @click="loadAsyncData" icon-right="magnify"></b-button>
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

                                <b-table-column field="user_id" label="ID" v-slot="props">
                                    {{props.row.user_id}}
                                </b-table-column>

                                <b-table-column field="name" label="NAME" v-slot="props">
                                    {{props.row.lname }}, {{ props.row.fname}} {{props.row.mname}}
                                </b-table-column>

                                <b-table-column field="sex" label="Sex" v-slot="props">
                                    {{props.row.sex}}
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
                    </section>
                    <footer class="modal-card-foot">
<!--                        <b-button-->
<!--                            label="Close"-->
<!--                            @click="modalAssignProcessor=false"/>-->
<!--                        <button-->
<!--                            :class="btnClass"-->
<!--                            type="is-success">SAVE</button>-->
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>


<script>
export default{
    props: {
        propsAccountingId: {
            type: Number,
            default: 0
        },

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
            sortField: 'user_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 20,
            defaultSortDirection: 'asc',

            modalAssignProcessor: false,

            accountingId: null,


            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            search: {
                name: '',
            }

        }
    },

    methods: {

        /*
       * Load async data
       */
        loadAsyncData() {
            const params = [
                `name=${this.search.name}`,
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-modal-processors?${params}`)
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

        assignProcessor(){
            this.loadAsyncData()

            this.modalAssignProcessor = true;
            this.accountingId = this.propsAccountingId
        },

        selectData(dataRow){
            this.modalAssignProcessor = false;
            dataRow.accounting_id = this.propsAccountingId
            this.$emit('browseProcessor', dataRow);
        }

    },

    mounted(){

    },

    computed: {
        valueItemName(){
            return this.propName;
        },

    },
}

</script>
<style scoped>

</style>
