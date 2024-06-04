<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6-desktop is-10-tablet">
                    <div class="box">
                        <div class="has-text-weight-bold">ADD/EDIT AUGMENTATION RECORD</div>

                        <div class="mt-2">

                            <!-- <b-button
                                @click="debug"
                                icon-left="note-multiple-outline"
                                class="button is-info"
                                outlined
                                label="Debug">
                            </b-button> -->

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Financial Year"
                                        expanded
                                        :type="errors.financial_year_id ? 'is-danger':''"
                                        :message="errors.financial_year_id ? errors.financial_year_id[0] : ''">
                                        <b-select v-model="fields.financial_year_id" expanded
                                            required
                                            placeholder="Financial Year">
                                            <option v-for="(item, indx) in financialYears"
                                                :key="`fy${indx}`"
                                                :value="item.financial_year_id">
                                                {{ item.financial_year_code }}
                                                -
                                                {{ item.financial_year_desc }}
                                            </option>
                                        </b-select>
                                    </b-field>
                                </div>
                                
                            </div>

                            

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Remarks"
                                        :type="errors.remarks ? 'is-danger':''"
                                        :message="errors.remarks ? errors.remarks[0] : ''">
                                        <b-input type="text" placholder="Remarks"
                                            v-model="fields.remarks" required>
                                        </b-input>
                                    </b-field>
                                </div>
                                
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Select OOE From"
                                        :type="errors.object_expenditure_id_from ? 'is-danger':''"
                                        :message="errors.object_expenditure_id_from ? errors.object_expenditure_id_from[0] : ''">
                                        <modal-browse-object-expenditures
                                            :prop-financial-year-id="fields.financial_year_id"
                                            :prop-object-expenditure="fields.object_expenditure_from"
                                            @browseObjectExpenditure="emitObjectExpenditureFrom($event)"></modal-browse-object-expenditures>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Balance"
                                        :type="errors.balance ? 'is-danger':''"
                                        :message="errors.balance ? errors.balance[0] : ''">
                                        <b-input type="text" readonly
                                            v-model="fields.balance">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Select OOE To"
                                        :type="errors.object_expenditure_id_to ? 'is-danger':''"
                                        :message="errors.object_expenditure_id_to ? errors.object_expenditure_id_to[0] : ''">
                                        <modal-browse-object-expenditures
                                            :prop-financial-year-id="fields.financial_year_id"
                                            :prop-object-expenditure="fields.object_expenditure_to"
                                            @browseObjectExpenditure="emitObjectExpenditureTo($event)"></modal-browse-object-expenditures>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Amount To Transfer">
                                        <b-numberinput
                                            :controls="false"
                                            v-model="fields.amount_transfer">
                                        </b-numberinput>
                                    </b-field>
                                </div>
                            </div>

                

                            <div class="buttons mt-4">
                                <b-button
                                    @click="submit"
                                    icon-left="note-multiple-outline"
                                    class="button is-primary"
                                    label="Save Transaction">
                                </b-button>
                            </div>

                        </div> <!--body -->

                    </div> <!--box-->
                </div><!--col-->
            </div> <!--cols-->
        </div> <!--section-->
    </div> <!--root div -->
</template>

<script>


export default{

    props: {
        id: {
            type: Number,
            default: 0
        },

        propUser: {
            type: Object,
            default: ()=>{}
        }
    }, 


    data(){
        return {

            financialYears: [],

            fields: {
              
                financial_year_id: null,
                remarks: null,

                object_expenditure_id_from: 0,
                object_expenditure_from: '',
                approved_budget_from: 0,
                beginning_budget_from: 0,
                balance: 0,

                object_expenditure_id_to: 0,
                object_expenditure_to: '',
                approved_budget_to: 0,
                beginning_budget_to: 0,

                amount_transfer: 0

            },

            errors: {},

            transactionTypes: [],

            global_id: 0,

            payee: {
                payee_id: 0,
                bank_account_payee: '',
            },

       
            office: {
                office: ''
            },

            documentaryAttachments: [],
            object_expenditure: '',

        }
    },

    methods: {

        loadTransactionTypes(){
            axios.get('/load-transaction-types').then(res=>{
                this.transactionTypes = res.data
            })
        },

        loadDocumentaryAttachments(){
            axios.get('/load-documentary-attachments').then(res=>{
                this.documentaryAttachments = res.data
            }).catch(err=>{

            })
        },

        async loadObjectExpenditures(){
            await axios.get('/load-object-expenditures/' + this.fields.financial_year_id).then(res=>{
                this.objectExpenditures = res.data
            }).catch(err=>{

            })
        },


        loadFinancialYears(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data

                this.fields.financial_year_id = res.data.filter(fy => fy.active === 1)[0].financial_year_id;
            })
        },

        emitPayee(row){
            this.payee.payee_id = row.payee_id
            this.payee.bank_account_payee = row.bank_account_payee
            this.fields.payee_id = row.payee_id
        },


        emitObjectExpenditureFrom(row){
            console.log(row); 

            this.fields.object_expenditure_from = row.object_expenditure
            this.fields.object_expenditure_id_from = row.object_expenditure_id
            this.fields.approved_budget_from = row.approved_budget
            this.fields.beginning_budget_from = row.beginning_budget
            this.fields.balance = row.approved_budget - row.utilize_budget
        },

        emitObjectExpenditureTo(row){
            console.log(row); 

            this.fields.object_expenditure_to = row.object_expenditure
            this.fields.object_expenditure_id_to = row.object_expenditure_id
            this.fields.approved_budget_to = row.approved_budget
            this.fields.beginning_budget_to = row.beginning_budget
        },




        submit: function(){
            
            this.errors = {}
            
            if(this.id > 0){
                //update
                axios.post('/realignments-update/'+this.id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/realignments'
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
                axios.post('/realignments', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/realignments'
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;

                        this.$buefy.dialog.alert({
                            type: 'is-danger',
                            title: 'INVALID INPUT.',
                            message: 'Please check all inputs.'
                        })
                    }
                });
            }

        },



      

        getData(){

            axios.get('/realignments/' + this.id).then(res=>{
                const result = res.data
                

            })
        },




    },

    mounted(){
        this.loadFinancialYears()
        //this.loadFundSources()
        if(this.id > 0){
            this.getData()
        }
    },

    computed: {
        
    }
}
</script>
