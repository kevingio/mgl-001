<template>
    <v-container class="my-5">
        <v-stepper v-model="stepper">
            <v-stepper-header>
                <v-stepper-step :complete="stepper > 1" step="1">Member Data</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :complete="stepper > 2" step="2">Product</v-stepper-step>

            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <v-container grid-list-lg>
                        <v-form ref="formInfo" lazy-validation>
                            <v-layout wrap>
                                <v-flex xs12 sm4>
                                    <v-text-field
                                    label="Member Name"
                                    v-model="input.memberName.value"
                                    :rules="input.memberName.rules"
                                    clearable
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4>
                                    <v-text-field
                                    label="PIC Name"
                                    v-model="input.pic.value"
                                    :rules="input.pic.rules"
                                    clearable
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4>
                                    <v-select
                                    v-model="input.postingType.value"
                                    :items="postingTypes"
                                    :rules="input.postingType.rules"
                                    item-text="name"
                                    item-value="id"
                                    label="Type"
                                    ></v-select>
                                </v-flex>
                            </v-layout>
                        </v-form>
                        <v-btn color="primary" @click="stepper = $refs.formInfo.validate() ? 2 : stepper">Continue</v-btn>
                    </v-container>
                </v-stepper-content>

                <v-stepper-content step="2">
                    <v-container grid-list-lg>
                        <v-form ref="formProduct" @submit.prevent="addToDetails()" class="mb-4" lazy-validation>
                            <v-layout wrap>
                                <v-flex xs12 sm8>
                                    <v-select
                                    v-model="selectedProduct"
                                    :items="products"
                                    item-text="name"
                                    item-value="id"
                                    label="Select Product*"
                                    :rules="input.name.rules"
                                    return-object
                                    required
                                    >
                                        <template slot="item" slot-scope="data">
                                            {{ data.item.name }}
                                            <span class="green--text ml-1" v-if="data.item.qty > 4">
                                                ({{ data.item.qty }} pcs)
                                            </span>
                                            <span class="red--text ml-1" v-else>
                                                ({{ data.item.qty }} pcs)
                                            </span>
                                        </template>
                                    </v-select>
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
                            :headers="headers"
                            :items="input.products"
                            disable-initial-sort
                            :rows-per-page-items="[10]"
                            >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.name }}</td>
                                <td>{{ props.item.qty }}</td>
                                <td>{{ props.item.note }}</td>
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

                        <v-btn flat @click="stepper = 1">Back</v-btn>
                        <v-btn color="success" @click="newPosting()">Finish</v-btn>
                    </v-container>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>

    </v-container>
</template>

<script>
export default {
    data () {
        return {
            stepper: 0,
            products: [],
            postingTypes: [],
            selectedProduct: null,
            search: '',
            headers: [
                { text: 'Product Name', value: 'name' },
                { text: 'Quantity', value: 'qty', width: '15%' },
                { text: 'Note', value: 'note', width: '30%' },
                { text: 'Action', value: 'created_at', sortable: false, align: 'center', width: '10%' }
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
                        v => (!!v) || "Quantity can't be empty",
                        v => (/^[0-9]*$/.test(v)) || 'Quantity is not valid',
                    ],
                },
                memberName: {
                    value: "",
                    rules: [
                        v => !!v || "Member name can't be empty"
                    ]
                },
                pic: {
                    value: "",
                    rules: [
                        v => !!v || "PIC name can't be empty"
                    ]
                },
                products: [],
                postingType: {
                    value: 1,
                    rules: [
                        v => !!v || "Type can't be empty"
                    ]
                }
            }
        }
    },
    methods: {
        addToDetails() {
            if(this.$refs.formProduct.validate()) {
                var check = null
                var note = ''
                this.$refs.formProduct.resetValidation()
                this.input.products.map(product => {
                    if(product.product_id == this.selectedProduct.id) {
                        check = true
                        product.qty += this.input.qty.value
                        product.note = this.checkIndentProduct()
                    }
                })
                if(!check) {
                    note = this.checkIndentProduct()
                    this.input.products.push({ product_id: this.selectedProduct.id, name: this.selectedProduct.name, qty: this.input.qty.value, note: note })
                }
                this.products[this.getIndex()].qty -= this.input.qty.value
                this.input.qty.value = ''
                this.selectedProduct = null
            }
        },
        removeFromDetails(product) {
            const index = this.input.products.indexOf(product)
            this.input.products.splice(index, 1)
        },
        async newPosting() {
            if (this.input.products.length > 0) {
                const data = {
                    name: this.input.memberName.value,
                    pic: this.input.pic.value,
                    type_id: this.input.postingType.value,
                    details: this.input.products,
                    token: this.$getToken()
                }
                await axios.post('/api/posting', data)
                .then((response) => {
                    this.$successAlert('Posting ', 1)
                }).catch(() => {
                    this.$errorAlert()
                })
                this.clearInput()
            }
        },
        getIndex() {
            var idx = -1
            this.products.map((product, index) => {
                if(product.name === this.selectedProduct.name) {
                    idx = index
                    return
                }
            })
            return idx
        },
        checkIndentProduct() {
            const diff = this.products[this.getIndex()].qty - this.input.qty.value
            return diff >= 1 ? '-' : 'INDENT ' + Math.abs(diff) + ' PCS'
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
        async fetchPostingTypes() {
            return await axios.get('/api/posting-type' ,{
                headers: {
                    'Accept': 'application/json',
                    'Content-type': 'application/json',
                    'Authorization': 'Bearer ' + this.$getToken()
                }
            }).then((response) => {
                this.postingTypes = response.data.items
            })
        },
        clearInput() {
            this.input.memberName.value = this.input.pic.value = ''
            this.input.products = []
            this.$refs.formInfo.resetValidation()
            this.stepper = 1
        }
    },
    mounted() {
        this.fetchProducts()
        this.fetchPostingTypes()
    }
}
</script>

<style media="screen" scoped>
    .v-stepper__header {
        box-shadow: none;
    }
</style>
