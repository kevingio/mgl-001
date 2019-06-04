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
        <v-dialog v-model="dialogDetail" persistent max-width="600px">
            <v-card>
                <v-toolbar color="transparent" flat>
                    <v-toolbar-title class="headline">Posting Detail</v-toolbar-title>
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

    </v-container>
</template>

<script>
export default {
    data () {
        return {
            dialogDetail: false,
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
                { text: 'Quantity', value: 'qty', sortable: false, width: '20%' },
            ],
        }
    },
    methods: {
        async fetchPostings() {
            return await axios.get('/api/posting' ,{
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
            this.postingDetail = posting
            this.dialogDetail = true
        },
    },
    mounted() {
        this.fetchPostings()
    }
}
</script>
