<template>

    <div>
        <section class="section">
            <div class="columns is-centered">
                <div class="column is-8-desktop">
                    <div class="box">
                        <span class="has-text-weight-bold">FILTER</span>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Financial Year"
                                    expanded
                                    :type="errors.financial_year_id ? 'is-danger':''"
                                    :message="errors.financial_year_id ? errors.financial_year_id[0] : ''">
                                    <b-select v-model="fields.financial_year_id" expanded
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
                            
                            <div class="column">
                                <modal-browse-office
                                    label="Office"
                                    :status-type="errors.office_id ? 'is-danger':''"
                                    :message="errors.office_id ? errors.office_id[0] : ''"
                                    @browseOffice="emitBrowseOffice"
                                    :prop-name="fields.office"
                                ></modal-browse-office>
                            </div>
                        </div> <!--cols-->

                        <div class="buttons">
                            <b-button label="Search"
                                @click="loadReport" icon-left="magnify"></b-button>
                        </div>


                        <table class="table">
                            <tr>
                                <th>Document</th>
                                <th>Allotment Class</th>
                                <th>Fund Source</th>
                                <th>Running Balance</th>
                                <th>Financial Budget</th>
                                <th>Utilized Budget</th>
                            </tr>

                            <tr v-for="(item, index) in reportData" :key="index">
                                <td>{{ item.doc_type }}</td>
                                <td>{{ item.allotment_class }}</td>
                                <td>{{ item.fund_source }}</td>
                                <td>{{ item.allotment_class_account_balance | numberWithCommas }}</td>
                                <td>{{ item.financial_budget | numberWithCommas }}</td>
                                <td>{{ item.amount | numberWithCommas }}</td>
                            </tr>
                        </table>

                    </div> <!--box-->
                </div>
            </div>
        </section>
    </div>
</template>

<script>

export default{
    data(){
        return {
            financialYears: [],

            office: {
                office: '',
                financial_year_id: null,

            },

            fields: {
                office: '',
            },
            errors: {},

            reportData: [],

        }
    },

    methods: {

        loadFinancialYears(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data
            })
        },

        emitBrowseOffice(row){
            this.office.office = row.office + ` (${row.description})`
            this.fields.office_id = row.office_id
            this.fields.office = row.office
        },


        loadReport(){
            axios.get('/load-report-transaction-by-office?fy=' + this.fields.financial_year_id + '&office='+ this.fields.office_id)
            .then(res=>{
                this.reportData = res.data
            }).catch(err=>{
            
            })
        }
    },

    mounted(){
        this.
        loadFinancialYears()
    }
}
</script>