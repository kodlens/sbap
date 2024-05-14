<template>
    <div>
        <table>
            <tr>

                <th>Bids & Award Forwarded</th>
                <th>Bids & Award Retrieved</th>
                <th>Bids & Award Status</th>
                <th>Bids & Award Remarks</th>
                <th>Action</th>
            </tr>
            <tr>
                <td>
                    <span v-if="propRow.bids_award_datetime_forwarded">
                        {{ new Date(propRow.bids_award_datetime_forwarded).toLocaleString() }}
                    </span>
                </td>
                <td>
                    <span v-if="propRow.bids_award_datetime_retrieved">
                        {{ new Date(propRow.bids_award_datetime_retrieved).toLocaleString() }}
                    </span>
                </td>
                <td>
                    <span v-if="propRow.bids_award_status">
                        {{ propRow.bids_award_status }}
                    </span>
                </td>
                <td>
                    <span v-if="propRow.bids_award_remarks">
                        {{ propRow.bids_award_remarks }}
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
                        <b-dropdown-item aria-role="listitem" @click="confirmMark('bid_award_forward', propRow.accounting_id)">Mark Forwarded</b-dropdown-item>
                        <b-dropdown-item aria-role="listitem" @click="confirmMark('bid_award_retrieve', propRow.accounting_id)">Mark Retrieved</b-dropdown-item>
                        <b-dropdown-item aria-role="listitem" @click="openModalBidAwardStatus">Mark Status</b-dropdown-item>
                        <b-dropdown-item aria-role="listitem" @click="openModalBidAwardRemarks">Add Remarks</b-dropdown-item>
                    </b-dropdown>
                </td>
            </tr>
        </table>


        <!--modal create-->
        <b-modal v-model="modalStatusBidAward" has-modal-card
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
                        @click="modalStatusBidAward = false"/>
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
                                        <option value="ON-PROCESS">ON-PROCESS</option>
                                        <option value="PENDING">PENDING</option>
                                        <option value="CANCELLED">CANCELLED</option>
                                        <option value="APPROVED">APPROVED</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="modalStatusBidAward=false"/>
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
        <b-modal v-model="modalRemarksBidAward" has-modal-card
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
                        @click="modalRemarksBidAward = false"/>
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
                        @click="modalRemarksBidAward=false"/>
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
            modalStatusBidAward : false,
            modalRemarksBidAward : false,

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
            axios.post('/document-bid-award-status/' + this.propRow.accounting_id, this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Save.',
                        type: 'is-success',
                        message: 'Status saved.',
                        onConfirm: () => {
                            this.emitRefresh()
                            this.modalStatusBidAward = false
                            this.fields = {}
                            this.errors = {}
                        }
                    });
                }
            }).catch(err=>{
            
            })
        },


        submitRemarks(){
            axios.post('/document-bid-award-remarks/' + this.propRow.accounting_id, this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Save.',
                        type: 'is-success',
                        message: 'Status saved.',
                        onConfirm: () => {
                            this.emitRefresh()
                            this.modalStatusBidAward = false
                            this.fields = {}
                            this.errors = {}
                        }
                    });
                }
            }).catch(err=>{
            
            })
        },


        openModalBidAwardStatus(){
            this.modalStatusBidAward = true
        },
        openModalBidAwardRemarks(){
            this.modalRemarksBidAward = true
        },

        emitRefresh(){
            this.$emit('emitRefresh');
        },





    },

    mounted(){

    }
}
</script>