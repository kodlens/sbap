<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8-desktop is-10-tablet">
                    <div class="box">
                        <div class="has-text-weight-bold">ADD/EDIT RECORD</div>

                        <div class="mt-2">

                            <b-button
                                @click="debug"
                                icon-left="note-multiple-outline"
                                class="button is-info"
                                outlined
                                label="Debug">
                            </b-button>

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
                                    <b-field label="Date Time"
                                        :type="errors.date_transaction ? 'is-danger':''"
                                        :message="errors.date_transaction ? errors.date_transaction[0] : ''">
                                        <b-datepicker v-model="fields.date_transaction" required></b-datepicker>
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
                                    <b-field label="Training Control No."
                                        :type="errors.training_control_no ? 'is-danger':''"
                                        :message="errors.training_control_no ? errors.training_control_no[0] : ''">
                                        <b-input type="text" placholder="Training Control No."
                                            v-model="fields.training_control_no" required>
                                        </b-input>
                                    </b-field>
                                </div>
                                
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Bank Account/Payee"
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
                                    <modal-browse-office
                                        label="Requesting Office"
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


                            <div class="columns">
                                <div class="column">

                                    <b-field label="Charge To"></b-field>
                                    <div class="buttons">
                                        <b-button @click="newObjectExpenditure"
                                            icon-left="plus"
                                            class="button is-small is-outlined is-primary">
                                            NEW OOE
                                        </b-button>
                                    </div>

                                    <div v-for="(item, index) in fields.objectExpenditures" :key="`ooe${index}`">

                                        <div class="columns">
                                            <div class="column">
                                                <b-field label="Select OOE" label-position="on-border" class="mb-2 ml-4"
                                                    :type="errors.object_expenditure ? 'is-danger':''"
                                                    :message="errors.object_expenditure ? errors.object_expenditure[0] : ''">
                                                    <modal-browse-object-expenditures
                                                        :prop-financial-year-id="fields.financial_year_id"
                                                        :prop-object-expenditure="item.object_expenditure"
                                                        @browseObjectExpenditure="emitObjectExpenditure($event, index)"></modal-browse-object-expenditures>
                                                </b-field>
                                            </div>

                                            <div class="column">
                                                <b-numberinput v-model="item.amount" @input="computeTotalAmount" :controls="false"/>
                                            </div>

                                            <div class="column">
                                                <b-button class="ml-1" type="is-danger" icon-right="delete" @click="removeObjectExpenditure(index)"></b-button>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>

                            
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Total Amount"
                                        :type="errors.total_amount ? 'is-danger':''"
                                        :message="errors.total_amount ? errors.total_amount[0] : ''">
                                        <b-numberinput placholder="Total Amount"
                                            :controls="false" step="0.0001"
                                            v-model="fields.total_amount">
                                        </b-numberinput>
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
                                                    <div class="column" v-if="!item.acctg_doc_attachment_id">
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


    },


    data(){
        return {

            financialYears: [],

            fields: {
                accounting_id: 0,
                financial_year_id: null,
                date_time: new Date(),
                transaction_no: null,
                training_control_no : null,
                transaction_type_id: null,
                payee_id: null,
                payee: null,
                particulars: null,

                documentary_attachments: [],
                objectExpenditures: [],

                total_amount: 0,
              

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

       
            office: {
                office: ''
            },

            documentaryAttachments: [],

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
            })
        },

        emitPayee(row){
            this.payee.payee_id = row.payee_id
            this.payee.bank_account_payee = row.bank_account_payee
            this.fields.payee_id = row.payee_id
        },


        emitObjectExpenditure(row, index){
            console.log(row, index); 
            this.fields.objectExpenditures[index]['object_expenditure'] = row.object_expenditure
            this.fields.objectExpenditures[index]['object_expenditure_id'] = row.object_expenditure_id
            this.fields.objectExpenditures[index]['allotment_class'] = row.allotment_class
            this.fields.objectExpenditures[index]['allotment_class_code'] = row.allotment_class_code
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
                    let nId = this.fields.documentary_attachments[ix].acctg_doc_attachment_id;

                    if(nId > 0){
                        axios.delete('/accounting-documentary-attachment-delete/' + nId).then(res=>{
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

        newObjectExpenditure(){
            this.fields.objectExpenditures.push({
                object_expenditure_id: 0,
                financial_year_id: 0,
                object_expenditure: null,
                allotment_class: null,
                allotment_class_code: null,
                amount: 0,
            })
        },
        removeObjectExpenditure(ix){
            this.$buefy.dialog.confirm({
                title: 'DELETE?',
                message: 'Are you sure you want to remove this OOE? This cannot be undone.',

                onConfirm: ()=>{
                    let nId = this.fields.objectExpenditures[ix].accounting_expenditure_id;
                    if(nId > 0){
                        axios.delete('/accounting-expenditures/' + nId).then(res=>{
                            if(res.data.status === 'deleted'){
                                this.$buefy.toast.open({
                                    message: `OOE successfully removed.`,
                                    type: 'is-primary'
                                })
                            }
                        });
                    }

                    this.fields.objectExpenditures.splice(ix, 1)

                }
            });

        },




        submit: function(){
            //format the date

            let formData = new FormData();
            formData.append('accounting_id', this.id);
            formData.append('financial_year_id', this.fields.financial_year_id ? this.fields.financial_year_id : '');

            formData.append('date_transaction', this.fields.date_transaction ? this.$formatDate(this.fields.date_transaction) : '');
            formData.append('transaction_type_id', this.fields.transaction_type_id ? this.fields.transaction_type_id : '');
            formData.append('transaction_no', this.fields.transaction_no ? this.fields.transaction_no : '');
            formData.append('training_control_no', this.fields.training_control_no ? this.fields.training_control_no : '');
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
            // //will be code later
            if(this.fields.objectExpenditures){
                this.fields.objectExpenditures.forEach((item, index) =>{
                    formData.append(`object_expenditures[${index}][accounting_expenditure_id]`, item.accounting_expenditure_id ? item.accounting_expenditure_id : 0);
                    formData.append(`object_expenditures[${index}][object_expenditure_id]`, item.object_expenditure_id ? item.object_expenditure_id : 0);
                    formData.append(`object_expenditures[${index}][allotment_class_code]`, item.allotment_class_code);
                    formData.append(`object_expenditures[${index}][allotment_class]`, item.allotment_class);
                    formData.append(`object_expenditures[${index}][amount]`, item.amount);
                });
            }
            //formData.append('priority_program_id', this.fields.priority_program_id ? this.fields.priority_program_id : '');
            formData.append('others', this.fields.others ? this.fields.others : '');
            formData.append('office_id', this.fields.office_id ? this.fields.office_id : '');


            if(this.id > 0){
                //update
                axios.post('/accounting-update/'+this.id, formData).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/accounting'
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
                axios.post('/accounting', formData).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/accounting'
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



        clearChargeTo(){
            this.fields.allotment_classes = []
        },

        debug(){

            this.fields.financial_year_id = 2
            this.fields.fund_source = 1

            this.fields.date_transaction = new Date();

            this.fields.transaction_type_id = 1
            this.fields.transaction_no = '23-01-0001'
            this.fields.training_control_no = 'TD-1234-22-1122'
            this.fields.particulars = 'Sample particulars'
            this.fields.others = 'sample others'
        },




        getData(){

            axios.get('/accounting/' + this.id).then(res=>{
                const result = res.data
                console.log(result.accounting_allotment_classes);

                this.fields.accounting_id = result.accounting_id
                this.fields.financial_year_id = result.financial_year_id
                this.fields.fund_source_id = result.fund_source_id

                this.fields.date_time = new Date(result.date_time)
                this.fields.transaction_no = result.transaction_no
                this.fields.training_control_no = result.training_control_no
                this.fields.transaction_type_id = result.transaction_type_id

                this.payee.bank_account_payee = result.payee.bank_account_payee
                this.fields.payee_id = result.payee_id

                this.fields.particulars = result.particulars
                this.fields.total_amount = Number(result.total_amount)

                //attachments
                if(result.accounting_documentary_attachments.length > 0){
                    result.acctg_documentary_attachments.forEach(item => {
                        this.fields.documentary_attachments.push({
                            documentary_attachment_id: item.documentary_attachment_id,
                            acctg_doc_attachment_id: item.acctg_doc_attachment_id,
                            accounting_id: item.accounting_id,
                        });
                    })
                }
               


                this.fields.office_id = result.office.office_id
                this.fields.office = '(' + result.office.office + ') ' + result.office.description
                this.fields.others = result.others

            })
        },

        computeTotalAmount(){
            let total = 0;

            this.fields.objectExpenditures.forEach((item, index) =>{
               total += item.amount
            });

            this.fields.total_amount = total
            
        }


    },

    mounted(){
        this.loadFinancialYears()
        //this.loadFundSources()
        if(this.id > 0){
            this.getData()
        }


        this.loadTransactionTypes()
        this.loadDocumentaryAttachments()
        //this.loadAllotmentClasses()
    },

    computed: {
        
    }
}
</script>
