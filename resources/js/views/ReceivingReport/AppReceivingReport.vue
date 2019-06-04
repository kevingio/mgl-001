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
                    <v-spacer></v-spacer>
                    <v-flex text-xs-right>
                        <v-btn color="primary" dark @click="dialogAdd = true">
                            Add Report
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-data-table
                :headers="headers"
                :items="receivingReports"
                :search="search"
                disable-initial-sort
                :rows-per-page-items="[10]"
                >
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.shipping_date }}</td>
                    <td>{{ props.item.received_at }}</td>
                    <td>{{ props.item.received_by ? props.item.received_by : '-'  }}</td>
                    <td class="px-0 text-xs-center">
                        <v-tooltip bottom>
                            <v-btn icon flat color="blue" slot="activator" @click="getDetail(props.item)">
                                <v-icon>info</v-icon>
                            </v-btn>
                            <span>Detail</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <v-btn icon flat color="red" slot="activator" @click="deleteReport(props.item)">
                                <v-icon>delete</v-icon>
                            </v-btn>
                            <span>Delete</span>
                        </v-tooltip>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>

            <!-- dialog add -->
            <v-dialog v-model="dialogAdd" persistent max-width="600px">
                <v-card>
                    <v-toolbar color="transparent" flat>
                        <v-toolbar-title class="headline">Add Receiving Report</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-lg class="pa-0">
                            <v-stepper v-model="stepper" vertical>
                                <v-stepper-step :complete="stepper > 1" step="1" editable>
                                    Receiving Report Information
                                </v-stepper-step>

                                <v-stepper-content step="1">
                                    <v-form ref="formInfo" lazy-validation>
                                        <v-layout wrap>
                                            <v-flex xs12 sm6>
                                                <v-menu
                                                :close-on-content-click="false"
                                                v-model="shippingMenu"
                                                :nudge-right="150"
                                                width="100%"
                                                lazy transition="scale-transition"
                                                >
                                                    <v-text-field
                                                    slot="activator"
                                                    label="Shipping Date"
                                                    v-model="shippingDatestring"
                                                    persistent-hint
                                                    prepend-icon="event"
                                                    readonly
                                                    ></v-text-field>
                                                    <v-date-picker
                                                    v-model="shippingDate"
                                                    :show-current="false" @input="shippingMenu = false"
                                                    :max="new Date().toISOString().substr(0, 10)" type="date" scrollable></v-date-picker>
                                                </v-menu>
                                            </v-flex>
                                            <v-flex xs12 sm6>
                                                <v-menu
                                                ref="receivingMenu"
                                                :close-on-content-click="false"
                                                v-model="receivingMenu"
                                                :nudge-right="150"
                                                lazy transition="scale-transition"
                                                >
                                                    <v-text-field
                                                    slot="activator"
                                                    label="Receiving Date"
                                                    v-model="receivingDatestring"
                                                    persistent-hint
                                                    prepend-icon="event"
                                                    readonly
                                                    ></v-text-field>
                                                    <v-date-picker
                                                    v-model="receivingDate"
                                                    :show-current="false" @input="receivingMenu = false"
                                                    :max="new Date().toISOString().substr(0, 10)" type="date" scrollable></v-date-picker>
                                                </v-menu>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-text-field
                                                label="Recipient's Name"
                                                v-model="input.recipient"
                                                ></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-form>
                                    <v-btn color="red" flat @click="dialogAdd= false">Close</v-btn>
                                    <v-btn color="primary" @click="stepper = $refs.formInfo.validate() ? 2 : stepper">Continue</v-btn>
                                </v-stepper-content>

                                <v-stepper-step :complete="stepper > 2" step="2" editable>Receiving Report Detail</v-stepper-step>

                                <v-stepper-content step="2">
                                    <v-form ref="formDetail" @submit.prevent="addToDetails()" class="mb-4" lazy-validation>
                                        <v-layout wrap>
                                            <v-flex xs12 sm8>
                                                <v-combobox
                                                v-model="selectedProduct"
                                                :items="products"
                                                item-text="name"
                                                item-value="id"
                                                label="Select Product*"
                                                :rules="input.name.rules"
                                                required
                                                ></v-combobox>
                                            </v-flex>
                                            <v-flex xs12 sm4>
                                                <v-text-field
                                                label="Quantity*"
                                                v-model.number="input.qty.value"
                                                :rules="input.qty.rules"
                                                required
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex class="pa-0">
                                                <v-btn color="orange" @click="addToDetails()">Add to table</v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-form>
                                    <v-data-table
                                        :headers="headersProduct"
                                        :items="input.details"
                                        disable-initial-sort
                                        :rows-per-page-items="[10]"
                                        >
                                        <template slot="items" slot-scope="props">
                                            <td>{{ props.item.name }}</td>
                                            <td>{{ props.item.qty }}</td>
                                            <td class="px-0 text-xs-center">
                                                <v-tooltip bottom>
                                                    <v-btn icon flat color="red" slot="activator" @click="removeFromDetails(props.item)">
                                                        <v-icon>delete</v-icon>
                                                    </v-btn>
                                                    <span>Remove</span>
                                                </v-tooltip>
                                            </td>
                                        </template>
                                    </v-data-table>
                                    <v-btn color="red" flat @click="dialogAdd= false">Close</v-btn>
                                    <v-btn color="success" @click="addReport()">Finish</v-btn>
                                </v-stepper-content>
                            </v-stepper>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <!-- dialog detail -->
            <v-dialog v-model="dialogDetail" persistent max-width="600px">
                <v-card>
                    <v-toolbar color="transparent" flat>
                        <v-toolbar-title class="headline">Report Detail</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-data-table
                            :headers="headersReportDetail"
                            :items="reportDetail.details"
                            disable-initial-sort
                            :rows-per-page-items="[10]"
                            >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.product.name }}</td>
                                <td>{{ props.item.qty }}</td>
                            </template>
                        </v-data-table>
                    </v-card-text>
                    <v-card-actions class="pa-3">
                        <v-spacer></v-spacer>
                        <v-btn color="red" flat @click="dialogDetail = false">Close</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card>

    </v-container>
</template>

<script>
export default {
    data () {
        return {
            shippingMenu: false,
            receivingMenu: false,
            shippingDate: new Date().toISOString().substr(0, 10),
            receivingDate: new Date().toISOString().substr(0, 10),
            receivingReports: [],
            reportDetail: {},
            products: [],
            selectedProduct: null,
            dialogAdd: false,
            dialogDetail: false,
            edittedItem: {},
            stepper: 0,
            search: '',
            headers: [
                { text: 'Shipping Date', value: 'shipping_date', width: '30%', sortable: false },
                { text: 'Receiving Date', value: 'received_at', width: '30%', sortable: false },
                { text: 'Received By', value: 'received_by', sortable: false, width: '25%' },
                { text: 'Action', value: 'created_at', sortable: false, align: 'center', width: '15%' }
            ],
            headersProduct: [
                { text: 'Product Name', value: 'name' },
                { text: 'Quantity', value: 'qty', width: '20%' },
                { text: 'Action', value: 'created_at', sortable: false, width: '10%' }
            ],
            headersReportDetail: [
                { text: 'Product Name', value: 'name', sortable: false },
                { text: 'Quantity', value: 'qty', sortable: false, width: '20%' },
            ],
            input: {
                name: {
                    value: "",
                    rules: [
                        v => !!v || "Product name can't be empty",
                    ],
                },
                qty: {
                    value: "",
                    rules: [
                        v => (!!v) || "Quantity name can't be empty",
                        v => (/^[0-9]*$/.test(v)) || 'Quantity is not valid',
                    ],
                },
                recipient: '',
                details: []
            }
        }
    },
    methods: {
        addToDetails() {
            if(this.$refs.formDetail.validate()) {
                var check = null
                this.$refs.formDetail.resetValidation()
                this.input.details.map(product => {
                    if(product.product_id == this.selectedProduct.id) {
                        check = true
                        product.qty += this.input.qty.value
                    }
                })
                if(!check) {
                    this.input.details.push({ product_id: this.selectedProduct.id, name: this.selectedProduct.name, qty: this.input.qty.value })
                }
                this.input.qty.value = ''
                this.selectedProduct = null
            }
        },
        removeFromDetails(product) {
            const index = this.input.details.indexOf(product)
            this.input.details.splice(index, 1)
        },
        async addReport() {
            if (this.$refs.formInfo.validate() && this.input.details.length > 0) {
                this.snackbar = true
                const data = {
                    shipping_date: this.shippingDate,
                    received_at: this.receivingDate,
                    received_by: this.input.recipient,
                    details: this.input.details,
                    token: this.$getToken()
                }
                this.dialogAdd = false
                await axios.post('/api/receiving-report', data)
                .then((response) => {
                    this.receivingReports.splice(0, 0, response.data.item)
                    this.$successAlert('Receiving Report', 1)
                }).catch(() => {
                    this.$errorAlert()
                })
                this.clearInput()
            }
        },
        deleteReport(report) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, it can't be undone",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    let index = this.receivingReports.indexOf(report)
                    axios.delete('/api/receiving-report/' + report.id, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json',
                            'Authorization': 'Bearer ' + this.$getToken()
                        }
                    })
                    .then((response) => {
                        this.receivingReports.splice(index, 1)
                        this.$successAlert('Receiving Report', -1)
                    }).catch(() => {
                        this.$errorAlert()
                    })
                }
            });
        },
        getDetail(report) {
            this.reportDetail = report
            this.dialogDetail = true
        },
        formatDate (date) {
            if (!date) return null
            let temp = date.split(' ')
            const [year, month, day] = temp[0].split('-')
            return `${day}/${month}/${year}`
        },
        clearInput() {
            this.input.details = []
            this.input.recipient = ''
            this.stepper = 1
            this.shippingDate = this.receivingDate = new Date().toISOString().substr(0, 10)
        },
        async fetchProducts() {
            return await axios.get('/api/product' ,{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json',
                    'Authorization': 'Bearer ' + this.$getToken()
                }
            }).then((response) => {
                this.products = response.data.items
            })
        },
        async fetchReports() {
            return await axios.get('/api/receiving-report' ,{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json',
                    'Authorization': 'Bearer ' + this.$getToken()
                }
            }).then((response) => {
                this.receivingReports = response.data.items
            })
        }
    },
    mounted() {
        this.fetchProducts()
        this.fetchReports()
    },
    computed: {
        shippingDatestring () {
            return this.formatDate(this.shippingDate)
        },
        receivingDatestring () {
            return this.formatDate(this.receivingDate)
        },
    },
}
</script>

<style scoped>
    .v-stepper, .v-stepper__header {
        box-shadow: none;
    }
</style>
