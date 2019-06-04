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
                            Add Product
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-data-table
                :headers="headers"
                :items="products"
                :search="search"
                disable-initial-sort
                :rows-per-page-items="[10]"
                >
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.qty }}</td>
                    <td class="px-0 text-xs-center">
                        <v-tooltip bottom>
                            <v-btn icon flat color="orange" slot="activator" @click="editProduct(props.item)">
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <span>Edit</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <v-btn icon flat color="red" slot="activator" @click="deleteProduct(props.item)">
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
                        <v-toolbar-title class="headline">Add Product</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-lg>
                            <v-form ref="formAdd" @submit.prevent="addProduct" lazy-validation>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field
                                        label="Product Name*"
                                        v-model="input.name.value"
                                        :rules="input.name.rules"
                                        required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                        label="Quantity*"
                                        v-model.number="input.qty.value"
                                        :rules="input.qty.rules"
                                        required
                                        ></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                            <small class="red--text">* Required</small>
                        </v-container>
                    </v-card-text>
                    <v-card-actions class="pa-3">
                        <v-spacer></v-spacer>
                        <v-btn color="red" flat @click="dialogAdd= false">Close</v-btn>
                        <v-btn color="success" @click="addProduct">Add</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- dialog edit -->
            <v-dialog v-model="dialogEdit" persistent max-width="600px">
                <v-card>
                    <v-toolbar color="transparent" flat>
                        <v-toolbar-title class="headline">Edit Product</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-lg>
                            <v-form ref="formEdit" @submit.prevent="editItem" lazy-validation>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field
                                        label="Nama Barang*"
                                        v-model="editedProduct.name"
                                        :rules="input.name.rules"
                                        required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                        label="Quantity*"
                                        v-model.number="editedProduct.qty"
                                        :rules="input.qty.rules"
                                        required
                                        ></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                            <small class="red--text">* Required</small>
                        </v-container>
                    </v-card-text>
                    <v-card-actions class="pa-3">
                        <v-spacer></v-spacer>
                        <v-btn color="red" flat @click="dialogEdit = false">Close</v-btn>
                        <v-btn color="success" @click="updateProduct(editedProduct)">Save Changes</v-btn>
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
            products: [],
            dialogAdd: false,
            dialogEdit: false,
            editedProduct: {},
            search: '',
            headers: [
                { text: 'Product Name', value: 'name', width: '65%' },
                { text: 'Quantity', value: 'qty', width: '20%' },
                { text: 'Action', value: 'created_at', align: 'center', sortable: false, width: '15%' }
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
            }
        }
    },
    methods: {
        async init() {
            try {
                let res = await this.fetchProducts();
                this.products = res.data.items;
            } catch (error) {
                console.log(error);
            }
        },
        fetchProducts() {
            return axios.get('/api/product' ,{
                    headers: {
                        'Accept': 'application/json',
                        'Content-type': 'application/json',
                        'Authorization': 'Bearer ' + this.$getToken()
                    }
                })
        },
        async addProduct() {
            if (this.$refs.formAdd.validate()) {
                this.snackbar = true
                const data = {
                    name: this.input.name.value,
                    qty: this.input.qty.value,
                    token: this.$getToken()
                }
                this.dialogAdd = false
                await axios.post('/api/product', data)
                .then((response) => {
                    this.products.splice(0, 0, response.data.item)
                    this.$successAlert('Product', 1)
                }).catch(() => {
                    this.$errorAlert()
                })
                this.clearInput()
            }
        },
        editProduct(product) {
            this.editedProduct = JSON.parse(JSON.stringify(product))
            this.editedProduct.index = this.products.indexOf(product)
            this.dialogEdit = true
        },
        updateProduct() {
            if (this.$refs.formAdd.validate()) {
                axios.patch('/api/product/' + this.editedProduct.id, this.editedProduct,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json',
                            'Authorization': 'Bearer ' + this.$getToken()
                        }
                    })
                    .then((response) => {
                        this.dialogEdit = false
                        this.products.splice(this.editedProduct.index, 1, response.data.item)
                        this.$successAlert('Product', 0)
                    }).catch(() => {
                        this.$errorAlert()
                    })
            }
        },
        deleteProduct(item) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, it can't be undone",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    let index = this.products.indexOf(item)
                    axios.delete('/api/product/' + item.id, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json',
                            'Authorization': 'Bearer ' + this.$getToken()
                        }
                    })
                    .then((response) => {
                        this.products.splice(index, 1)
                        this.$successAlert('Product', -1)
                    }).catch(() => {
                        this.$errorAlert()
                    })
                }
            });
        },
        clearInput() {
            this.$refs.formAdd.resetValidation()
            this.input.name.value = this.input.qty.value = ''
        }
    },
    mounted() {
        this.init()
    },
}
</script>
