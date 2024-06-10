<template>
    <div>

        <div class="section">

            <div class="columns is-centered">
                <div class="column is-10-widescreen is-11-desktop is-11-tablet">

                    <div class="columns">
                        <div class="column">
                            <b-field label="Financial Year" label-position="on-border"
                                expanded>
                                <b-select v-model="search.financial_year" expanded
                                    @change="loadReportDashboard"
                                    placeholder="Financial Year">
                                    <option v-for="(item, index) in financialYears"
                                        :key="`fy${index}`"
                                        :value="{
                                            financial_year_id: item.financial_year_id,
                                            financial_year_code: item.financial_year_code,
                                            financial_year_desc: item.financial_year_desc,
                                            approved_budget: item.approved_budget,
                                            beginning_budget: item.beginning_budget,
                                            utilize_budget: item.utilize_budget,
                                            active: item.active,
                                            created_at: item.created_at,
                                            updated_at: item.updated_at,
                                        }">
                                        {{ item.financial_year_code }}
                                        -
                                        {{ item.financial_year_desc }}
                                    </option>
                                </b-select>
                            </b-field>
                        </div>
                        <div class="column">
                            <b-field label="Document Type" label-position="on-border"
                                expanded>
                                <b-select v-model="search.doc"
                                    @input="loadReportDashboard"
                                    expanded>
                                    <option value="ALL">ALL</option>
                                    <option value="ACCOUNTING">ACCOUNTING</option>
                                    <option value="BUDGETING">BUDGETING</option>
                                    <option value="PROCUREMENT">PROCUREMENT</option>
                                </b-select>
                            </b-field>
                        </div>
                    </div>

                    <div class="columns">
                        
                        <!-- <div class="column">
                            <b-field label="Allotment Class" label-position="on-border"
                                expanded>
                                <b-select v-model="search.allotment_class"
                                    expanded>
                                    <option value="">ALL</option>
                                    <option v-for="(allot, ix) in allotmentClasses"
                                        :key="`allotclass${ix}`"
                                        :value="allot.allotment_class">{{ allot.allotment_class }}</option>
                                </b-select>
                            </b-field>
                        </div> -->

                        <div class="column">
                            <div class="buttons is-right">
                                <b-button type="is-primary" icon-right="magnify"
                                    @click="loadReportDashboard" label="Search"></b-button>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div>
                                <strong>APPROVED BUDGET:</strong> 
                                <span>
                                    {{ search.financial_year['approved_budget'] | numberWithCommas }}
                                </span>
                            </div>
                            
                            <div>
                                <strong>BUDGET UTILIZE: </strong> 
                                <span>
                                    {{ search.financial_year['utilize_budget'] | numberWithCommas }}
                                </span>
                            </div>
                            <div>
                                <strong>END BUDGET:</strong> 
                                <span>
                                    {{ computedEndBudget | numberWithCommas }} 
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="columns">
                        <div class="column">
                            
                            <!-- <div class="table-container">
                                <table class="table is-narrow is-fullwidth">
                                    <tr>
                                        <th>Date Transaction</th>
                                        <th>Voucher/Payroll No.</th>
                                        <th>Payee</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Charge To</th>
                                        
                                    </tr>
                                    <tr v-for="(item, index) in data" :key="`allotment${index}`">
                                        <td>
                                            {{ item.doc_type }}
                                        </td>
                                        <td>
                                            <span>{{ item.transaction_no }}</span> 
                                            <span v-if="item.training_control_no">
                                                /
                                                {{ item.training_control_no }}</span>
                                        </td>
                                        <td>
                                            <span v-if="item.payee">{{ item.payee.bank_account_payee }}</span> 
                                        </td>
                                        <td>
                                            <span v-if="item.particulars">{{ item.particulars }}</span>
                                        </td>
                                        <td>
                                            {{ item.total_amount | numberWithCommas }}
                                        </td>
                                        <td>
                                            <span v-for="(i,ix) in item.accounting_expenditures" :key="`obj${ix}`">
                                                <span>{{i.allotment_class_code}} ({{ i.allotment_class}}) - {{ i.object_expenditure.object_expenditure }}</span>
                                                <span v-if="ix < item.accounting_expenditures.length - 1">, </span>
                                            </span>
                                        
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div> -->
                        </div>
                    </div>

                    <div class="allotments">
                        <div class="box" v-for="(item,index) in allotmentClasses" :key="`all${index}`">
                            <div class="has-text-weight-bold">{{ item.allotment_class }}</div>
                            <div class="has-text-weight-bold">{{ search.doc }}</div>

                            <div>
                                RUNNING BALANCE: {{ (Number(item.total_approved_budget) - Number(item.utilize_budget)) | numberWithCommas }}
                            </div>
                            <div>
                                TOTAL ALLOTMENT:  {{ item.total_approved_budget | numberWithCommas }}
                            </div>
                            <div>
                                UTILIZE BUDGET:  {{ item.utilize_budget | numberWithCommas }}
                            </div>
                           
                            
                            <div>
                                <table class="table">
                                    <thead>
                                        <th>Code</th>
                                        <th>Object Expenditure</th>
                                        <th>Priority Program</th>
                                        <th>Amount</th>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(i, ix) in item.details" :key="`oe${ix}`">
                                            <td>
                                                {{ i.account_code }}
                                            </td>
                                            <td>
                                                <span v-if="i.object_expenditure">
                                                    {{ i.object_expenditure }}
                                                </span>
                                            </td>
                                            <td>
                                                <span v-if="i.priority_program">
                                                    {{ i.priority_program }}
                                                </span>
                                            </td>
                                            <td>
                                                <span>
                                                    {{ i.amount | numberWithCommas }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div> <!--col-->
            </div><!--cols-->
        </div>
    </div>
</template>

<script>
export default{

    mounted(){
        this.loadFinancialYears()
        //this.loadReportByAllotments()
        //this.loadFundSources()

    },


    data(){
        return{
            search: {
                financial_year: {
                    financial_year_id: 0,
                    financial_year_code: '',
                    financial_year_desc: '',
                    approved_budget: 0,
                    beginning_budget: 0,
                    utilize_budget: 0,
                    active: 0,
                    created_at: null,
                    updated_at: null
                },
                allotment_class: '',
                doc: 'ALL'
            },

            data: [],

            financialYears: [],
            allotmentClasses: [],
            objectExpenditures: []

        }
    },

    methods: {

        loadReportDashboard(){
            const params = [
               `fy=${this.search.financial_year['financial_year_id']}`,
               `doc=${this.search.doc}`
           ].join('&')

           axios.get(`/load-report-by-allotment-classes?${params}`).then(res=>{
                this.allotmentClasses = res.data
            })

        },



        loadFinancialYears(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data
                const item = res.data.filter(fy => fy.active === 1)[0];

                this.search.financial_year['financial_year_id'] = item.financial_year_id;
                this.search.financial_year['financial_year_code'] = item.financial_year_code;
                this.search.financial_year['financial_year_desc'] = item.financial_year_desc;
                this.search.financial_year['approved_budget'] = item.approved_budget;
                this.search.financial_year['beginning_budget'] = item.beginning_budget;
                this.search.financial_year['utilize_budget'] = item.utilize_budget;
                this.search.financial_year['active'] = item.active;
                this.search.financial_year['created_at'] = item.created_at;
                this.search.financial_year['updated_at'] = item.updated_at;

            })
        },

        // loadAccountingUtilizations(){
        //     axios.get('/load-accounting-utilizations/' + this.search.financial_year['financial_year_id'] + '/?doc=' + this.search.doc).then(res=>{
        //         this.accountingUsedBudget = res.data
        //     })
        // },

        // loadReportByAllotments(){
        //     axios.get('/load-report-by-allotment-classes').then(res=>{
        //         this.allotmentClasses = res.data
        //     })
        // },

        getDetails(item){
            console.log(item)
            return ['1','2','3']
        },

        computeUtilize(arr){
            let sum = 0
            arr.forEach(item => {
                sum += item.amount
            });
            return sum;
        },

        
        computeApprovedBudget(arr){
            let sum = 0
            arr.forEach(item => {
                sum += item.approved_budget
            });
            return sum;
        }
    },


    computed: {
     

        computedEndBudget(){
            return (this.search.financial_year['beginning_budget'] - this.search.financial_year['utilize_budget'])
        },

        
    }

}

</script>

<style scoped>
    .allotments {
        display: flex;
        flex-wrap: wrap;
        gap: 1;
        justify-content: center;
    }

    .allotments > .box {
        margin: 6px;
        min-width: 460px;
    }
   
</style>