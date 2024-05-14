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

                                        }">
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
                            <b-field label="Document Type" label-position="on-border"
                                expanded>
                                <b-select v-model="search.doc"
                                    expanded>
                                    <option value="ALL">ALL</option>
                                    <option value="ACCOUNTING">ACCOUNTING</option>
                                    <option value="BUDGETING">BUDGETING</option>
                                    <option value="PROCUREMENT">PROCUREMENT</option>
                                </b-select>
                            </b-field>
                        </div>
                        <div class="column">
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
                        </div>

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
                                <strong>APPROVED BUDGET:</strong> {{ search.financial_year['approved_budget'] | numberWithCommas }}
                            </div>
                            <div>
                                <strong>END BUDGET:</strong> {{ computedEndBudget | numberWithCommas }} 
                            </div>
                            <div>
                                <strong>BUDGET UTILIZE: </strong> {{ search.financial_year['utilize_budget'] | numberWithCommas }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="columns">
                        <div class="column">
                            
                            <!-- <div class="mb-2"><strong>UTILIZED BUDGET:</strong> {{ budgetUtilize | numberWithCommas }}</div>
 -->

                            

                            <div class="table-container">
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
                                            <span v-if="training_control_no">
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
                                                <span>{{i.allotment_class_code}}</span>
                                                <span v-if="ix < item.accounting_expenditures.length - 1">, </span>
                                            </span>
                                        
                                        </td>
                                    </tr>
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
                },
                allotment_class: '',
                doc: 'ALL'
            },

            data: [],

            financialYears: [],
           

        }
    },

    methods: {

        loadReportDashboard(){
            const params = [
               
               `fy=${this.search.financial_year['financial_year_id']}`,
               `allotment=${this.search.allotment_class}`,
               `doc=${this.search.doc}`
           ].join('&')


           axios.get(`/load-report-dashboard?${params}`).then(res=>{
                this.data = res.data
            })

        },



        loadFinancialYears(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data
            })
        },

        loadAccountingUtilizations(){
            axios.get('/load-accounting-utilizations/' + this.search.financial_year['financial_year_id'] + '/?doc=' + this.search.doc).then(res=>{
                this.accountingUsedBudget = res.data
            })
        },

   

    },


    computed: {
        totalUtilizations(){
            return 0
            return this.accountingUsedBudget

           
            //
        },

        computedEndBudget(){

            return (this.search.financial_year['beginning_budget'] - this.search.financial_year['utilize_budget'])
        }
    }

}

</script>