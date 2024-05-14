<template>
    <div>
        <table>
            <tr>

                <th>College Accounting Updated</th>
                <th>Final Status</th>
                <th>Final Remarks</th>
                <th>Action</th>
            </tr>
            <tr>
                <td>
                    <span v-if="propRow.college_accounting_datetime_updated">
                        {{ new Date(propRow.college_accounting_datetime_updated).toLocaleString() }}
                    </span>
                </td>
              
                <td>
                    <span v-if="propRow.final_status">
                        {{ propRow.final_status }}
                    </span>
                </td>
                <td>
                    <span v-if="propRow.final_remarks">
                        {{ propRow.final_remarks }}
                    </span>
                </td>
                <td>
                    <b-dropdown aria-role="list">
                        <template #trigger="{ active }">
                            <b-button
                                label=""
                                size="is-small"
                                type="is-info"
                                :icon-right="active ? 'menu-up' : 'menu-down'" />
                        </template>
                        <b-dropdown-item aria-role="listitem" @click="confirmMark('college_updated', propRow.accounting_id)">Mark Updated</b-dropdown-item>

                        <b-dropdown-item aria-role="listitem" @click="openModalCollegeAccountingStatus">Mark Status</b-dropdown-item>
                        <b-dropdown-item aria-role="listitem" @click="openModalCollegeAccountingRemarks">Add Remarks</b-dropdown-item>
                    </b-dropdown>
                </td>
            </tr>
        </table>


        <!--modal create-->
        <b-modal v-model="modalCollegeAccountingStatus" has-modal-card
            trap-focus
            :width="640"
            aria-role="dialog"
            aria-label="Modal"
            aria-modal>

            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">STATUS</p>
                    <button
                        type="button"
                        class="delete"
                        @click="modalCollegeAccountingStatus = false"/>
                </header>

                <section class="modal-card-body">
                    <div class="">
                        <div class="columns">
                            <div class="column">
                                <b-field label="Status" label-position="on-border"
                                        :type="errors.status ? 'is-danger':''"
                                        :message="errors.status ? errors.status[0] : ''">
                                    <b-select v-model="fields.status"
                                        placeholder="Status" required>
                                        <option value="ACCOMPLISHED">ACCOMPLISHED</option>
                                        <option value="PAYABLE">PAYABLE</option>
                                        <option value="LIQUIDATED">LIQUIDATED</option>
                                        <option value="CANCELLED">CANCELLED</option>
                                        <option value="PENDING">PENDING</option>
                                        <option value="ON-PROCESS">ON-PROCESS</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="modalCollegeAccountingStatus=false"/>
                    <b-button
                        :class="btnClass"
                        @click="submit"
                        label="Save"
                        type="is-success">SAVE</b-button>
                </footer>
            </div>
        </b-modal>
        <!--close modal-->









        <!--modal create-->
        <b-modal v-model="modalCollegeAccountingRemarks" has-modal-card
            trap-focus
            :width="640"
            aria-role="dialog"
            aria-label="Modal"
            aria-modal>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">STATUS</p>
                    <button
                        type="button"
                        class="delete"
                        @click="modalCollegeAccountingRemarks = false"/>
                </header>

                <section class="modal-card-body">
                    <div class="">
                        <div class="columns">
                            <div class="column">
                                <b-field label="Status" label-position="on-border"
                                        :type="errors.remarks ? 'is-danger':''"
                                        :message="errors.remarks ? errors.remarks[0] : ''">
                                    <b-input v-model="fields.remarks" placeholder="Remarks">
                                        </b-input>
                                </b-field>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="modalCollegeAccountingRemarks=false"/>
                    <b-button
                        :class="btnClass"
                        @click="submitRemarks"
                        label="Save"
                        type="is-success">SAVE</b-button>
                </footer>
            </div>

        </b-modal>
        <!--close modal-->



        
    </div>
</template>

<script>

export default{
    props: {
        propRow: {
            type: Object,
            default: {}
        }
    },


    data(){
        return {
            modalCollegeAccountingStatus : false,
            modalCollegeAccountingRemarks : false,

            fields: {},
            errors: {},


            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },
        }
    },

    methods: {

         confirmMark(option, id) {
            this.$buefy.dialog.confirm({
                title: 'Forward?',
                type: 'is-info',
                message: 'Are you sure you want to mark forwarded?',
                cancelText: 'Cancel',
                confirmText: 'Ok',
                onConfirm: () => this.forwardSubmit(option, id)
            });
        },
        //execute delete after confirming
        forwardSubmit(option, id) {
            axios.post('/document-forward/' + option + '/' + id).then(res => {
                this.emitRefresh();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        submit(){
            axios.post('/document-college-accounting-status/' + this.propRow.accounting_id, this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Save.',
                        type: 'is-success',
                        message: 'Status saved.',
                        onConfirm: () => {
                            this.emitRefresh()
                            this.modalCollegeAccountingStatus = false
                            this.fields = {}
                            this.errors = {}
                        }
                    });
                }
            }).catch(err=>{
            
            })
        },


        submitRemarks(){
            axios.post('/document-college-accounting-remarks/' + this.propRow.accounting_id, this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Save.',
                        type: 'is-success',
                        message: 'Status saved.',
                        onConfirm: () => {
                            this.emitRefresh()
                            this.modalCollegeAccountingStatus = false
                            this.fields = {}
                            this.errors = {}
                        }
                    });
                }
            }).catch(err=>{
            
            })
        },


        openModalCollegeAccountingStatus(){
            this.modalCollegeAccountingStatus = true
        },
        openModalCollegeAccountingRemarks(){
            this.modalCollegeAccountingRemarks = true
        },

        emitRefresh(){
            this.$emit('emitRefresh');
        },





    },

    mounted(){

    }
}
</script>