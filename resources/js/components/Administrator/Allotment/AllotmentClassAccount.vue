<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">

                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">ALLOTMENT CLASS ACCOUNT</div>

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
                                    <b-field label="Financial Year">
                                        <b-select v-model="search.financial_year"
                                            @input="loadAsyncData"
                                            placeholder="Financial Year">
                                            <option 
                                                :value="item.financial_year_id" 
                                                v-for="(item, index) in financialYears" 
                                                :key="`f${index}`">
                                                {{ item.financial_year_code }} - ({{ item.financial_year_desc }})
                                            </option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>
                        </div>

                        <b-field label="Search">   
                            <b-input type="text"
                                v-model="search.allotment" placeholder="Search..."
                                @keyup.native.enter="loadAsyncData"/>
                            <p class="control">
                                <b-tooltip label="Search" type="is-success">
                                    <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                </b-tooltip>
                            </p>
                        </b-field>

                        <div class="buttons mt-3">
                            <b-button @click="openModal" icon-right="account-arrow-up-outline" class="is-success">NEW</b-button>
                        </div>


                        <b-table
                            :data="data"
                            :loading="loading"
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

                            <b-table-column field="allotment_class_account_id" label="ID" v-slot="props">
                                {{ props.row.allotment_class_account_id }}
                            </b-table-column>

                            <b-table-column field="financial_year" label="Financial Year" v-slot="props">
                                {{ props.row.allotment_class.financial_year.financial_year_code }}
                                -
                                {{ props.row.allotment_class.financial_year.financial_year_desc }}

                            </b-table-column>

                            <b-table-column field="allotment_class" label="Allotment Class" v-slot="props">
                                {{ props.row.allotment_class.allotment_class }}
                            </b-table-column>

                            <b-table-column field="allotment_class_account_code" label="Allotment Code" v-slot="props">
                                {{ props.row.allotment_class_account_code }}
                            </b-table-column>

                            <b-table-column field="allotment_class_account" label="Allotment Class Account" v-slot="props">
                                {{ props.row.allotment_class_account }}
                            </b-table-column>

                            <b-table-column field="allotment_class_account_budget" label="Budget" v-slot="props">
                                {{ props.row.allotment_class_account_budget | numberWithCommas }}
                            </b-table-column>

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <!-- <b-tooltip label="Edit" type="is-warning">
                                        <b-button class="button is-small is-warning mr-1" 
                                            tag="a" 
                                            icon-right="pencil" @click="getData(props.row.allotment_class_account_id)"></b-button>
                                    </b-tooltip> -->
                                    <b-tooltip label="Delete" type="is-danger">
                                        <b-button class="button is-small is-danger mr-1" 
                                            icon-right="delete" 
                                            @click="confirmDelete(props.row)"></b-button>
                                    </b-tooltip>

                                    <b-tooltip label="Add New To Financial Year" type="is-info">
                                        <b-button class="button is-small is-info mr-1" 
                                            icon-right="arrow-right" 
                                            @click="addNewToFinancialYear(props.row)"></b-button>
                                    </b-tooltip>
                                </div>
                            </b-table-column>
                        </b-table>

                        <div class="buttons mt-3">
                            <b-button @click="openModal" icon-right="account-arrow-up-outline" class="is-success">NEW</b-button>
                        </div>

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->



        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">ALLOTMENT CLASS</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">

                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Financial Year">
                                        <b-select v-model="fields.financial_year"
                                            @input="loadAllotmentClasses(fields.financial_year)"
                                            expanded
                                            placeholder="Financial Year">
                                            <option 
                                                :value="item.financial_year_id" 
                                                v-for="(item, index) in financialYears" 
                                                :key="`f${index}`">
                                                {{ item.financial_year_code }} - ({{ item.financial_year_desc }})
                                            </option>
                                        </b-select>
                                    </b-field>

                                    <b-field label="Select Allotment Class" label-position="on-border"
                                        expanded
                                        :type="errors.allotment_class ? 'is-danger':''"
                                        :message="errors.allotment_class ? errors.allotment_class[0] : ''">
                                        <b-select 
                                            v-model="fields.allotment_class"
                                            expanded
                                            @input="fields.allotment_class_budget = fields.allotment_class['allotment_class_budget']"
                                            placeholder="Select Allotment Class" required>
                                            <option v-for="(item, index) in allotmentClasses"
                                                :key="index" 
                                                :value="{ allotment_class_id: item.allotment_class_id, allotment_class_budget: item.allotment_class_budget }">{{ item.allotment_class }}</option>
                                        </b-select>
                                    </b-field>

                                    <b-field label="Allotment Class Budget" label-position="on-border"
                                            :type="errors.allotment_class_budget ? 'is-danger':''"
                                            :message="errors.allotment_class_budget ? errors.allotment_class_budget[0] : ''">
                                        <b-input v-model="fields.allotment_class_budget"
                                            readonly
                                            placeholder="Allotment Class Budget">
                                        </b-input>
                                    </b-field>

                                    <b-field label="Allotment Class Account Code" label-position="on-border"
                                            :type="errors.allotment_class_account_code ? 'is-danger':''"
                                            :message="errors.allotment_class_account_code ? errors.allotment_class_account_code[0] : ''">
                                        <b-input v-model="fields.allotment_class_account_code"
                                            placeholder="Allotment Class Account Code" required>
                                        </b-input>
                                    </b-field>

                                    <b-field label="Allotment Class Account" label-position="on-border"
                                            :type="errors.allotment_class_account ? 'is-danger':''"
                                            :message="errors.allotment_class_account ? errors.allotment_class_account[0] : ''">
                                        <b-input v-model="fields.allotment_class_account"
                                            placeholder="Allotment Class Account" required>
                                        </b-input>
                                    </b-field>

                                    <b-field label="Allotment Class Account Budget" label-position="on-border"
                                            :type="errors.allotment_class_account_budget ? 'is-danger':''"
                                            :message="errors.allotment_class_account_budget ? errors.allotment_class_account_budget[0] : ''">
                                        <b-numberinput controls-alignment="right" 
                                            controls-position="compact"
                                            v-model="fields.allotment_class_account_budget"
                                            placeholder="Allotment Class Account Budget" required>
                                        </b-numberinput>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>

                    <footer class="modal-card-foot">
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'allotment_class_account_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 20,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                allotment: '',
            },

            isModalCreate: false,

            fields: {
                allotment_class_id: null,
                allotment_class_code: null,
                allotment_class: {
                    allotment_class_id: null,
                    allotment_class_budget: null
                },
                allotment_class_account: null,
                allotment_class_account_budget: null

            },
            errors: {},

            financialYears: [],
            allotmentClasses: [],
  
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
                `allotment=${this.search.allotment}`,
                `financial=${this.search.financial_year}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-allotment-class-accounts?${params}`)
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

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },



        submit: function(){

            if(this.global_id > 0){
                //update
                axios.put('/allotment-class-accounts/' + this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/allotment-class-accounts', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });
            }
        },


        //alert box ask for deletion
        confirmDelete(row) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete',
                onConfirm: () => this.deleteSubmit(row)
            });
        },
        //execute delete after confirming
        deleteSubmit(row) {

            axios.delete('/allotment-class-accounts/' + row.allotment_class_account_id, row).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields.allotment_class_account_id = 0;
            this.fields.allotment_class_account_code = '';
            this.fields.allotment_class_account = '';
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            axios.get('/allotment-class-accounts/'+data_id).then(res=>{
                //this.fields = res.data;
                this.fields.financial_year = res.data.allotment_class.financial_year_id
                this.loadAllotmentClasses(this.fields.financial_year).then(()=>{
                    this.fields.allotment_class = {
                        allotment_class_id : res.data.allotment_class.allotment_class_id,
                        allotment_class_budget : res.data.allotment_class.allotment_class_budget,
                    }
                    this.fields.allotment_class_budget = res.data.allotment_class.allotment_class_budget
                })
                
               
                this.fields.allotment_class_account_code = res.data.allotment_class_account_code
                this.fields.allotment_class_account = res.data.allotment_class_account
                this.fields.allotment_class_account_budget = res.data.allotment_class_account_budget

                
                
            });
        },

        loadAllotmentClasses: async function(financial){
            await axios.get('/load-allotment-classes-by-financial/' + financial).then(res=>{
                this.allotmentClasses = res.data;
            })
        },

        loadFinancialYears: async function(){
            await axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data;
            })
        },

      
        addNewToFinancialYear(row){
            this.field = {}
            this.errors = {}

            // if(this.search.financial_year){
            //     this.$buefy.dialog.alert({
            //         title: 'EMPTY!',
            //         type: 'is-danger',
            //         message: 'Please select financial year first.'
            //     });

            //     return;
            // }
            this.loadAllotmentClasses()

            this.isModalCreate = true
            this.fields.allotment_class_account_code = row.allotment_class_account_code
            this.fields.allotment_class_account = row.allotment_class_account

        }


    },

    mounted() {
        this.loadAsyncData();
        this.loadAllotmentClasses()
        this.loadFinancialYears()
    }
}
</script>


<style scoped>
    .modal-card-title{
        font-weight: bold;
    }

</style>
