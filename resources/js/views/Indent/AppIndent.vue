<template>
    <v-container class="my-5">
        <v-card>
            <v-container grid-list-lg>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-data-table
                :headers="headers"
                :items="postings"
                :search="search"
                disable-initial-sort
                :rows-per-page-items="[10]"
                >
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.pic }}</td>
                    <td>{{ props.item.type.name }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td class="px-0 text-xs-center">
                        <v-tooltip bottom>
                            <v-btn icon flat color="blue" slot="activator" @click="getDetail(props.item)">
                                <v-icon>info</v-icon>
                            </v-btn>
                            <span>Detail</span>
                        </v-tooltip>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
        </v-card>

        <!-- dialog detail -->
        <v-dialog v-model="dialogDetail" persistent max-width="900px">
            <v-card>
                <v-toolbar color="transparent" flat>
                    <v-toolbar-title class="headline">Indent Detail</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-data-table
                        :headers="headersPostingDetail"
                        :items="postingDetail.details"
                        disable-initial-sort
                        :rows-per-page-items="[10]"
                        >
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.product.name }}</td>
                            <td>
                                <v-edit-dialog
                                :return-value.sync="props.item.qty"
                                lazy
                                @save="updateIndent(props.item)"
                                v-if="props.item.accepted_by == '' || props.item.accepted_by == null"
                                >
                                    {{ props.item.qty }}
                                    <template v-slot:input>
                                        <v-text-field
                                        v-model="props.item.qty"
                                        :rules="qtyRules"
                                            label="Quantity"
                                        ></v-text-field>
                                    </template>
                                </v-edit-dialog>
                                <span v-else>{{ props.item.qty }}</span>
                            </td>
                            <td @click="tempAcceptedBy = props.item.accepted_by">
                                <v-edit-dialog
                                :return-value.sync="props.item.accepted_by"
                                lazy
                                @save="updateIndent(props.item)"
                                >
                                    <strong v-if="props.item.accepted_by == null || props.item.accepted_by == ''" class="red--text">Not Taken</strong>
                                    <strong v-else class="green--text">{{ props.item.accepted_by }}</strong>
                                    <template v-slot:input>
                                        <v-text-field
                                        v-model="tempAcceptedBy"
                                        label="Accepted By"
                                        ></v-text-field>
                                    </template>
                                </v-edit-dialog>
                            </td>
                            <td>{{ props.item.accepted_at == null ? '-' : props.item.accepted_at }}</td>
                        </template>
                    </v-data-table>
                </v-card-text>
                <v-card-actions class="pa-3">
                    <v-spacer></v-spacer>
                    <v-btn color="red" flat @click="dialogDetail = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>
export default {
    data () {
        return {
            dialogDetail: false,
            tempAcceptedBy: '',
            postings: [],
            postingDetail: {},
            search: '',
            headers: [
                { text: 'Member Name', value: 'name' },
                { text: 'PIC', value: 'pic' },
                { text: 'Type', value: 'type.name', width: '15%' },
                { text: 'Posting Date', value: 'created_at', width: '20%', sortable: false },
                { text: 'Action', value: 'created_at', sortable: false, align: 'center', width: '10%' }
            ],
            headersPostingDetail: [
                { text: 'Product Name', value: 'name', sortable: false },
                { text: 'Quantity', value: 'qty', sortable: false, width: '10%' },
                { text: 'Accepted By', value: 'accepted_by', sortable: false, width: '20%' },
                { text: 'Acceptance Date', value: 'accepted_by', sortable: false, width: '20%' },
            ],
            qtyRules: [
                v => (!!v) || "Quantity can't be empty",
                v => (/^[0-9]*$/.test(v)) || 'Quantity is not valid',
            ],
        }
    },
    methods: {
        async fetchPostings() {
            return await axios.get('/api/indent' ,{
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json',
                        'Authorization': 'Bearer ' + this.$getToken()
                    }
                }).then((response) => {
                    this.postings = response.data.items
                })
        },
        getDetail(posting) {
            this.postingDetail = posting.indent
            this.dialogDetail = true
        },
        updateIndent(product) {
            const index = this.postingDetail.details.indexOf(product)
            const oldAcceptedBy = product.accepted_by
            product.accepted_by = this.tempAcceptedBy
            axios.patch('/api/indent/' + product.indent_id, product,
                {
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json',
                        'Authorization': 'Bearer ' + this.$getToken()
                    }
                })
                .then((response) => {
                    this.postingDetail.details[index].qty = response.data.item.qty
                    this.postingDetail.details[index].accepted_by = response.data.item.accepted_by
                    this.postingDetail.details[index].accepted_at = response.data.item.accepted_at
                    this.$successAlert('Indent', 0)
                }).catch(() => {
                    this.postingDetail.details[index].accepted_by = oldAcceptedBy
                    this.$errorAlert()
                })
        }
    },
    mounted() {
        this.fetchPostings()
    }
}
</script>
