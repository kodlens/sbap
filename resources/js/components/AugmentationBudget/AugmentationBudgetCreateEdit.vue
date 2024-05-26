<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6-desktop is-10-tablet">
                    <div class="box">
                        <div class="has-text-weight-bold">ADD/EDIT AUGMENTATION BUDGET RECORD</div>

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
                                        :type="errors.object_expenditure_id? 'is-danger':''"
                                        :message="errors.object_expenditure_id? errors.object_expenditure_id[0] : ''">
                                        <modal-browse-object-expenditures
                                            :prop-financial-year-id="fields.financial_year_id"
                                            :prop-object-expenditure="fields.object_expenditure"
                                            @browseObjectExpenditure="emitObjectExpenditure($event)"></modal-browse-object-expenditures>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Amount"
                                        :type="errors.amount_transfer ? 'is-danger':''"
                                        :message="errors.amount_transfer ? errors.amount_transfer[0] : ''">
                                        <b-numberinput :controls="false"
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
                object_expenditure_id: 0,
                object_expenditure: '',
                approved_budget: 0,
                amount_transfer: 0

            },
            errors: {},
            global_id: 0,

        }
    },

    methods: {

    

        loadFinancialYears(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data

                this.fields.financial_year_id = res.data.filter(fy => fy.active === 1)[0].financial_year_id;
            })
        },



        emitObjectExpenditure(row){
            console.log(row); 

            this.fields.object_expenditure= row.object_expenditure
            this.fields.object_expenditure_id= row.object_expenditure_id
            this.fields.approved_budget= row.approved_budget
        },




        submit: function(){
            
            this.errors = {}
            
            if(this.id > 0){
                //update
                axios.post('/augmentation-budgets-update/'+this.id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/augmentation-budgets'
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
                axios.post('/augmentation-budgets', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/augmentation-budgets'
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
