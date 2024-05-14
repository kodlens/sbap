<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">

                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">
                            SERVICES</div>

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

                        <div class="columns">
                            <div class="column">
                                <b-field label="Search">
                                    <b-input type="text"
                                                v-model="search.service" placeholder="Search..."
                                                @keyup.native.enter="loadAsyncData"/>
                                    <p class="control">
                                            <b-tooltip label="Search" type="is-success">
                                        <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                            </b-tooltip>
                                    </p>
                                </b-field>
                            </div>
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

                            <b-table-column field="service_id" label="ID" v-slot="props">
                                {{ props.row.service_id }}
                            </b-table-column>

                            <b-table-column field="financial_year_code" label="Code" v-slot="props">
                                {{ props.row.financial_year.financial_year_code }}
                            </b-table-column>

                            <b-table-column field="name" label="Description" v-slot="props">
                                {{ props.row.financial_year.financial_year_desc }}
                            </b-table-column>

                            <b-table-column field="service" label="Service" v-slot="props">
                                {{ props.row.service }}
                            </b-table-column>

                            <b-table-column field="financial_budget" label="Financial Budget" v-slot="props">
                                <span>{{ numberWithCommas(props.row.budget) }}</span>
                            </b-table-column>

                            <b-table-column field="balance" label="Balance" v-slot="props">
                                <span>{{ numberWithCommas(props.row.balance) }}</span>
                            </b-table-column>

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Edit" type="is-warning">
                                        <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" 
                                            @click="getData(props.row.service_id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-danger">
                                        <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.service_id)"></b-button>
                                    </b-tooltip>
                                </div>
                            </b-table-column>
                        </b-table>

                        <div class="buttons mt-3">
                            <b-button @click="openModal" icon-right="calendar" class="is-success">NEW</b-button>
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
                        <p class="modal-card-title">Service Budget</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Financial Year" label-position="on-border"
                                        :type="errors.financial_year ? 'is-danger':''"
                                        :message="errors.financial_year ? errors.financial_year[0] : ''">
                                        <b-select v-model="fields.financial_year"
                                            placeholder="Financial Year" required
                                            @input="assignBudget">
                                            <option 
                                                :value="{ financial_year_id: item.financial_year_id, financial_budget: item.financial_budget }" 
                                                v-for="(item, index) in financialYears" 
                                                :key="`f${index}`">
                                                {{ item.financial_year_code }} - ({{ item.financial_year_desc }})
                                            </option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Financial Budget" label-position="on-border"
                                        :type="errors.budget ? 'is-danger':''"
                                        :message="errors.budget ? errors.budget[0] : ''">
                                        <b-input v-model="fields.budget" readonly
                                            placeholder="Financial Budget" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Financial Balance" label-position="on-border"
                                        :type="errors.balance ? 'is-danger':''"
                                        :message="errors.balance ? errors.balance[0] : ''">
                                        <b-input v-model="fields.balance"
                                            placeholder="Financial Balance" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Service" label-position="on-border"
                                        :type="errors.service ? 'is-danger':''"
                                        :message="errors.service ? errors.service[0] : ''">
                                        <b-select v-model="fields.service"
                                            placeholder="Service" required>
                                            <option value="ACCOUNTING">ACCOUNTING</option>
                                            <option value="BUDGETING">BUDGETING</option>
                                            <option value="PROCUREMENT">PROCUREMENT</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>

                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>
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
            sortField: 'service_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 20,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                financial_year: null,
                service: '',
            },

            isModalCreate: false,

            fields: {
                financial_year: {
                    financial_year_id: null,
                    financial_budget: null,
                },
                service: null,
                budget : null,
                balance: null
            },

            errors: {},
            
            financialYears: [],


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
                `financial=${this.search.financial_year}`,
                `service=${this.search.service}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-services?${params}`)
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
            this.clearFields()
            this.errors = {};
        },



        submit: function(){

            let obj = {
                service: this.fields.service,
                financial_year_id: this.fields.financial_year.financial_year_id,
                budget: this.fields.budget,
            }

            if(this.global_id > 0){
                //update
                let obj = {
                    service: this.fields.service,
                    financial_year_id: this.fields.financial_year.financial_year_id,
                    budget: this.fields.budget,
                    balance: this.fields.balance,

                }
                axios.put('/services/'+this.global_id, obj).then(res=>{
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
                let obj = {
                    service: this.fields.service,
                    financial_year_id: this.fields.financial_year.financial_year_id,
                    budget: this.fields.budget,
                }
               
                axios.post('/services', obj).then(res=>{
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
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/services/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.global_id = 0;
            this.fields.financial_year.financial_year_id = null;
            this.fields.financial_year.financial_budget = null;

            this.fields.service = null;
            this.fields.balance = null;
            this.fields.budget = null;

        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;

            axios.get('/services/'+ data_id).then(res=>{
                //this.fields = res.data;

                console.log(res.data);
                this.fields.financial_year.financial_year_id = res.data.financial_year.financial_year_id
                this.fields.financial_year.financial_budget = res.data.financial_year.financial_budget;

                this.fields.budget = res.data.budget
                this.fields.balance = res.data.balance
                this.fields.service = res.data.service
                
            });
        },

        numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        loadFinancialYears: function(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data;
            })
        },

        assignBudget(){
            //console.log(thiis.field);
            this.fields.budget = this.fields.financial_year.financial_budget;
        }




    },

    mounted() {
        this.loadFinancialYears()
        this.loadAsyncData();

    }
}
</script>


<style>


</style>
