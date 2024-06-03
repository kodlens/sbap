<template>

    <div>
        <section class="section">
            <div class="columns is-centered">
                <div class="column is-10-desktop">
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
                                <th>Account Code</th>
                                <th>Allotment Class</th>
                                <th>Object Expenditures</th>
                                <th>Financial Budget</th>
                                <th>Utilized Budget</th>
                                <th>Running Balance</th>
                            </tr>

                            <tr v-for="(item, index) in reportData" :key="index">
                                <td>{{ item.doc_type }}</td>
                                <td>{{ item.account_code }}</td>
                                <td>{{ item.allotment_class }}</td>
                                <td>{{ item.object_expenditure }}</td>
                                <td>{{ item.approved_budget | numberWithCommas }}</td>
                                <td>{{ item.total_utilize | numberWithCommas }}</td>
                                 <td>{{ (Number(item.approved_budget) - Number(item.total_utilize)) | numberWithCommas }}</td>
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
                //this.financialYears = res.data
                this.financialYears = res.data
                const item = res.data.filter(fy => fy.active === 1)[0];
                this.fields.financial_year_id = item.financial_year_id;
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