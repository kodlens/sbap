<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8-desktop is-10-tablet">
                    <div class="box">
                        <div class="has-text-weight-bold">ADD/EDIT BUDGETING RECORD</div>

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
                                            @input="loadAllotmentClasses"
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
                                    <b-field label="Fund Source"
                                        expanded
                                        :type="errors.fund_source_id ? 'is-danger':''"
                                        :message="errors.fund_source_id ? errors.fund_source_id[0] : ''">
                                        <b-select v-model="fields.fund_source_id" expanded
                                            @input="clearChargeTo"
                                            required
                                            placeholder="Fund Source">
                                            <option v-for="(item,index) in fundSources"
                                                :key="`fund${index}`" :value="item.fund_source_id">
                                                {{ item.fund_source }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Date Time"
                                        :type="errors.date_time ? 'is-danger':''"
                                        :message="errors.date_time ? errors.date_time[0] : ''">
                                        <b-datetimepicker v-model="fields.date_time" required></b-datetimepicker>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Transaction No."
                                             :type="errors.transaction_no ? 'is-danger':''"
                                             :message="errors.transaction_no ? errors.transaction_no[0] : ''">
                                        <b-input type="text" placholder="Transaction No."
                                             v-model="fields.transaction_no" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="TAD Control No."
                                             :type="errors.training_control_no ? 'is-danger':''"
                                             :message="errors.training_control_no ? errors.training_control_no[0] : ''">
                                        <b-input type="text" placholder="Training Control No."
                                                 v-model="fields.training_control_no" required>
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Transaction Type" expanded
                                             :type="errors.transaction_type_id ? 'is-danger':''"
                                             :message="errors.transaction_type_id ? errors.transaction_type_id[0] : ''">
                                        <b-select placholder="Transaction Type" expanded
                                                  v-model="fields.transaction_type_id">
                                            <option :value="item.transaction_type_id" v-for="(item, index) in transactionTypes"
                                                :key="index">{{ item.transaction_type }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Supplier/Payee"
                                        :type="errors.payee_id ? 'is-danger':''"
                                        :message="errors.payee_id ? errors.payee_id[0] : ''">
                                        <modal-browse-payee
                                            @browsPayee="emitPayee"
                                            :prop-name="payee.bank_account_payee"></modal-browse-payee>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Particulars/Activity Title"
                                        :type="errors.particulars ? 'is-danger':''"
                                        :message="errors.particulars ? errors.particulars[0] : ''">
                                        <b-input type="text" placholder="Particulars"
                                             v-model="fields.particulars">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>


                            <div class="columns">
                                <div class="column">

                                    <b-field label="Documentary Attachments">
                                        <div class="m-2">
                                            <div class="my-2" v-for="(item, ixdoc) in fields.documentary_attachments" :key="`doc${ixdoc}`">
                                                <div class="columns">
                                                    <div class="column">
                                                        <b-field label="Attachment" label-position="on-border" expanded
                                                            :type="id > 0 ? 'is-primary' : ''"
                                                            :message="id > 0 ? 'To update file, delete first the old one and upload a newer version.' : ''">
                                                            <b-select v-model="item.documentary_attachment_id" expanded required>
                                                                <option v-for="(doc, ix) in documentaryAttachments"
                                                                    :key="`idoc${ix}`"
                                                                    :value="doc.documentary_attachment_id">
                                                                        {{ doc.documentary_attachment }}
                                                                </option>
                                                            </b-select>
                                                        </b-field>
                                                    </div>
                                                    <div class="column" v-if="!item.budgeting_documentary_attachment_id">
                                                        <b-field class="file is-primary" :class="{'has-name': !!item.file_upload}">
                                                            <b-upload v-model="item.file_upload" class="file-label">
                                                            <span class="file-cta">
                                                                <b-icon class="file-icon" icon="upload"></b-icon>
                                                                <span class="file-label">Click to upload</span>
                                                            </span>
                                                                <span class="file-name" v-if="item.file_upload">
                                                                {{ item.file_upload.name }}
                                                            </span>
                                                            </b-upload>
                                                        </b-field>

                                                    </div>


                                                    <div class="column is-1">
                                                        <b-button icon-left="delete-outline"
                                                            @click="removeDoctAttchment(ixdoc)"
                                                            class="is-danger">
                                                        </b-button>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="buttons mt-2">
                                                <b-button @click="newDocAttachment"
                                                    icon-left="plus"
                                                    class="button is-small is-outlined is-primary">
                                                    NEW ATTACHMENT
                                                </b-button>
                                            </div>
                                        </div>
                                    </b-field>
                                </div>
                            </div>


                            <div v-if="fields.fund_source_id === 1">
                                <div class="has-text-weight-bold mb-4">CHARGE TO</div>
                                <div class="ml-4" v-for="(item, index) in fields.allotment_classes" :key="`acc${index}`">
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Allotment Class" label-position="on-border"
                                                     expanded
                                                     :type="errors.allotment_class_id ? 'is-danger':''"
                                                     :message="errors.allotment_class_id ? errors.allotment_class_id[0] : ''">
                                                <b-select v-model="item.allotment_class_id"
                                                          expanded>
                                                    <option v-for="(allot, ix) in allotmentClasses"
                                                            :key="`allotclass${ix}`"
                                                            :value="allot.allotment_class_id">{{ allot.allotment_class }}</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Account" label-position="on-border"
                                                     :type="errors.allotment_class_account_id ? 'is-danger':''"
                                                     :message="errors.allotment_class_account_id ? errors.allotment_class_account_id[0] : ''">
                                                <modal-browse-allotment-class-account
                                                    :prop-class-id="item.allotment_class_id"
                                                    :prop-allotment-account="item.allotment"
                                                    @browseAllotmentAccount="emitAllotmentAccount(index, $event)"></modal-browse-allotment-class-account>
                                            </b-field>
                                        </div>
                                    </div>

                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Amount" label-position="on-border"
                                                     :type="errors.amount ? 'is-danger':''"
                                                     :message="errors.amount ? errors.amount[0] : ''">
                                                <b-numberinput
                                                    v-model="item.amount"
                                                    :controls="false"
                                                    step="0.0001">
                                                </b-numberinput>
                                            </b-field>
                                        </div>
                                    </div>
                                    <hr>
                                </div><!-- ccount loop-->
                                <div class="buttons mt-2">
                                    <b-button @click="newAllotmentClass"
                                              icon-left="plus"
                                              class="button is-small is-outlined is-primary">
                                        NEW ALLOTMENT CLASS
                                    </b-button>
                                </div>
                            </div><!--container div for loop -->



                            <div class="columns">
                                <div class="column">
                                    <b-field label="Total Amount"
                                            :type="errors.total_amount ? 'is-danger':''"
                                            :message="errors.total_amount ? errors.total_amount[0] : ''">
                                        <b-numberinput placholder="Total Amount"
                                            :controls="false" step="0.0001"
                                            readonly
                                            :value="computedTotalAmount">
                                        </b-numberinput>
                                    </b-field>
                                </div>
                            </div>


                            <div class="columns">
                                <div class="column">
                                    <b-field label="Priority Program"
                                        :type="errors.priority_program_id ? 'is-danger':''"
                                        :message="errors.priority_program_id ? errors.priority_program_id[0] : ''">
                                        <modal-browse-priority-program
                                            :prop-priority-program="fields.priority_program"
                                            @browsePriorityProgram="emitPriorityProgram"></modal-browse-priority-program>
                                    </b-field>
                                </div>
                            </div>

                            <!-- <div class="columns">
                                <div class="column">
                                    <b-field label="Supplemental Budget"
                                        :type="errors.supplemental_budget ? 'is-danger':''"
                                        :message="errors.supplemental_budget ? errors.supplemental_budget[0] : ''">
                                        <b-input type="text" v-model="fields.supplemental_budget"
                                                 placeholder="Supplemental Budget"
                                                 required>
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Capital Outlay"
                                        :type="errors.capital_outlay ? 'is-danger':''"
                                        :message="errors.capital_outlay ? errors.capital_outlay[0] : ''">
                                        <b-input type="text" v-model="fields.capital_outlay"
                                                 placeholder="Capital Outlay"
                                                 required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Accounts Payable"
                                        :type="errors.account_payable ? 'is-danger':''"
                                        :message="errors.account_payable ? errors.account_payable[0] : ''">
                                        <b-input type="text" v-model="fields.account_payable"
                                            placeholder="Accounts Payable"
                                            required>
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="TES Trust Fund"
                                        :type="errors.tes_trust_fund ? 'is-danger':''"
                                        :message="errors.tes_trust_fund ? errors.tes_trust_fund[0] : ''">
                                        <b-input type="text" v-model="fields.tes_trust_fund"
                                            placeholder="TES Trust Fund"
                                            required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>-->

                            <div class="columns">
                                <div class="column">
                                    <modal-browse-office
                                        label="Office"
                                        :status-type="errors.office_id ? 'is-danger':''"
                                        :message="errors.office_id ? errors.office_id[0] : ''"
                                        @browseOffice="emitBrowseOffice"
                                        :prop-name="fields.office"
                                    ></modal-browse-office>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Others">
                                        <b-input type="text" v-model="fields.others"
                                            placeholder="Others">
                                        </b-input>
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
        }
    },


    data(){
        return {

            financialYears: [],

            fundSources: [],

            fields: {
                budgeting_id: 0,
                financial_year_id: null,
                fund_source: null,
                date_time: new Date(),
                transaction_no: null,
                training_control_no : null,
                transaction_type_id: null,
                payee_id: null,
                payee: null,
                particulars: null,
                documentary_attachments: [],
                allotment_classes: [],
                total_amount: 0,
                priority_program_id: null,
                priority_program: null,

                office_id: null,
                office: null,
                others: null
            },

            errors: {},

            transactionTypes: [],

            global_id: 0,

            payee: {
                payee_id: 0,
                bank_account_payee: '',
            },

            priority_program: {
                priority_program: ''
            },
            office: {
                office: ''
            },

            documentaryAttachments: [],
            allotmentClasses: [],

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

        async loadAllotmentClasses(){
            await axios.get('/load-allotment-classes-by-financial/' + this.fields.financial_year_id).then(res=>{
                this.allotmentClasses = res.data
            }).catch(err=>{

            })
        },

        loadFinancialYears(){
            axios.get('/load-financial-years').then(res=>{
                this.financialYears = res.data
            })
        },

        loadFundSources(){
            axios.get('/load-fund-sources').then(res=>{
                this.fundSources = res.data
            })
        },


        emitPayee(row){
            this.payee.payee_id = row.payee_id
            this.payee.bank_account_payee = row.bank_account_payee
            this.fields.payee_id = row.payee_id
        },

        emitAllotmentAccount(index, row){
            this.fields.allotment_classes[index].allotment = '(' + row.allotment_class_account_code + ') ' + row.allotment_class_account
            this.fields.allotment_classes[index].allotment_class_id = row.allotment_class_id
            this.fields.allotment_classes[index].allotment_class_account_id = row.allotment_class_account_id
        },

        emitPriorityProgram(row){

            this.fields.priority_program = "(" + row.priority_program_code + ") " + row.priority_program
            this.fields.priority_program_id = row.priority_program_id
        },


        emitBrowseOffice(row){
            this.office.office = row.office + ` (${row.description})`
            this.fields.office_id = row.office_id
            this.fields.office = row.office
        },


        //attaching documents
        newDocAttachment(){
            this.fields.documentary_attachments.push({
                documentary_attachment_id: 0,
                file_upload: null
            })
        },
        removeDoctAttchment(ix){
            this.$buefy.dialog.confirm({
                title: 'DELETE?',
                message: 'Are you sure you want to remove this attachment? This cannot be undone.',

                onConfirm: ()=>{
                    let nId = this.fields.documentary_attachments[ix].budgeting_documentary_attachment_id;

                    if(nId > 0){
                        axios.post('/budgeting-documentary-attachment-delete/' + nId).then(res=>{
                            if(res.data.status === 'deleted'){
                                this.$buefy.toast.open({
                                    message: `Attachment deleted successfully.`,
                                    type: 'is-primary'
                                })
                            }
                        });
                    }
                    this.fields.documentary_attachments.splice(ix, 1)

                }
            });

        },


        //attaching documents
        newAllotmentClass(){
            this.fields.allotment_classes.push({
                accounting_allotment_class_id: 0,
                allotment_class_id: 0,
                allotment_class_account_id: 0,
                amount: 0,
                allotment: null
            })
        },
        removeAllotmentClass(ix){
            this.$buefy.dialog.confirm({
                title: 'DELETE?',
                message: 'Are you sure you want to remove this attachment? This cannot be undone.',

                onConfirm: ()=>{
                    let nId = this.fields.accounts[ix].account_id;
                    if(nId > 0){
                        axios.delete('//' + nId).then(res=>{
                            if(res.data.status === 'deleted'){
                                this.$buefy.toast.open({
                                    message: `Account deleted successfully.`,
                                    type: 'is-primary'
                                })
                            }
                        });
                    }

                    this.fields.accounts.splice(ix, 1)

                }
            });

        },




        submit: function(){
            //format the date

            let formData = new FormData();
            formData.append('budgeting_id', this.id);
            formData.append('financial_year_id', this.fields.financial_year_id ? this.fields.financial_year_id : '');
            formData.append('fund_source_id', this.fields.fund_source_id ? this.fields.fund_source_id : '');

            formData.append('date_time', this.fields.date_time ? this.$formatDateAndTime(this.fields.date_time) : '');
            formData.append('transaction_no', this.fields.transaction_no ? this.fields.transaction_no : '');
            formData.append('training_control_no', this.fields.training_control_no ? this.fields.training_control_no : '');
            formData.append('transaction_type_id', this.fields.transaction_type_id ? this.fields.transaction_type_id : '');
            formData.append('payee_id', this.fields.payee_id ? this.fields.payee_id : '');
            formData.append('particulars', this.fields.particulars ? this.fields.particulars : '');
            formData.append('total_amount', this.fields.total_amount ? this.fields.total_amount : '');

            //doc attachment
            if(this.fields.documentary_attachments){
                this.fields.documentary_attachments.forEach((doc, index) =>{
                    formData.append(`documentary_attachments[${index}][documentary_attachment_id]`, doc.documentary_attachment_id);
                    formData.append(`documentary_attachments[${index}][file_upload]`, doc.file_upload);
                });
            }
            //will be code later
            if(this.fields.allotment_classes){
                this.fields.allotment_classes.forEach((item, index) =>{
                    formData.append(`allotment_classes[${index}][budgeting_allotment_class_id]`, item.accounting_allotment_class_id);
                    formData.append(`allotment_classes[${index}][allotment_class_id]`, item.allotment_class_id);
                    formData.append(`allotment_classes[${index}][allotment_class_account_id]`, item.allotment_class_account_id);
                    formData.append(`allotment_classes[${index}][amount]`, item.amount);
                });
            }

            formData.append('priority_program_id', this.fields.priority_program_id ? this.fields.priority_program_id : '');
            formData.append('others', this.fields.others ? this.fields.others : '');
            formData.append('office_id', this.fields.office_id ? this.fields.office_id : '');


            if(this.id > 0){
                //update
                axios.post('/budgeting-update/'+this.id, formData).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/budgeting'
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
                axios.post('/budgeting', formData).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/budgeting'
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;

                        this.$buefy.dialog.alert({
                            type: 'is-danger',
                            title: 'EMPTY FIELDS.',
                            message: 'Please fill out all required fields.'
                        })
                    }
                });
            }

        },

        refreshAccountAllotment(){
            this.allotment.allotment = null
            this.fields.allotment_class_account_id = null
            this.allotment_class_account = null
            this.allotment_class_account_code = null

        },


        clearChargeTo(){
            console.log('clear');
            this.fields.allotment_classes = []
        },

        debug(){

            this.fields.financial_year_id = 1
            this.fields.fund_source = 1

            this.fields.date_time = new Date();
            this.fields.transaction_no = '23-01-0001'
            this.fields.training_control_no = 'TD-1234-22-1122'
            this.fields.transaction_type_id = 1

            this.fields.particulars = 'Sample particulars'
        
            //this.fields.amount = 12000
            // this.fields.supplemental_budget = 'sample supplemental'
            // this.fields.capital_outlay = 'sample capital outlay'
            // this.fields.account_payable = 'sample ap'
            // this.fields.tes_trust_fund = 'tes trust fund'
            this.fields.others = 'sample others'
        },




        getData(){

            axios.get('/budgeting/' + this.id).then(res=>{
                const result = res.data

                this.fields.budgeting = result.budgeting
                this.fields.financial_year_id = result.financial_year_id
                this.fields.fund_source_id = result.fund_source_id

                this.fields.date_time = new Date(result.date_time)
                this.fields.transaction_no = result.transaction_no
                this.fields.training_control_no = result.training_control_no
                this.fields.transaction_type_id = result.transaction_type_id

                this.payee.bank_account_payee = result.payee.bank_account_payee
                this.fields.payee_id = result.payee_id

                this.fields.particulars = result.particulars
                this.fields.total_amount = result.total_amount

                //attachments
                result.accounting_documentary_attachments.forEach(item => {
                    this.fields.documentary_attachments.push({
                        documentary_attachment_id: item.documentary_attachment_id,
                        accounting_documentary_attachment_id: item.accounting_documentary_attachment_id,
                        accounting_id: item.accounting_id,
                    });
                })


                //async call
                this.loadAllotmentClasses().then(()=>{
                    result.accounting_allotment_classes.forEach(item => {
                        this.fields.allotment_classes.push({
                            accounting_allotment_class_id: item.accounting_allotment_class_id,
                            allotment_class_id: item.allotment_class_id,
                            allotment_class_account_id: item.allotment_class_account_id,
                            amount: item.amount,
                            //for viewing only
                            allotment: '(' + item.allotment_class_account.allotment_class_account_code + ') ' + item.allotment_class_account.allotment_class_account
                        });
                    })
                })


                if(result.priority_program){
                    this.fields.priority_program = "(" + result.priority_program.priority_program_code + ") " + result.priority_program.priority_program
                    this.fields.priority_program_id = result.priority_program_id
                }


                this.fields.office_id = result.office_id
                this.fields.office = '(' + result.office.office + ') ' + result.office.description
                this.fields.others = result.others

            })
        },



    },

    mounted(){
        this.loadFinancialYears()
        this.loadFundSources()
        if(this.id > 0){
            this.getData()
        }


        this.loadTransactionTypes()
        this.loadDocumentaryAttachments()
        //this.loadAllotmentClasses()
    },


    computed: {
        computedTotalAmount(){
            let total = 0;

            this.fields.allotment_classes.forEach((item, index) =>{
               total += item.amount
            });

            this.fields.total_amount = total
            return total;
        }
    }
}

</script>
