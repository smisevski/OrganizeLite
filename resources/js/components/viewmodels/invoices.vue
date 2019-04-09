<template>
    <v-layout>
        <v-container v-if="viewMode == 'invoicesPanelView'" fluid class="pa-0">
            <v-toolbar>
                <v-toolbar-title class="title">
                    <v-icon>receipt</v-icon>
                    Invoices
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn 
                icon
                @click="advancedSearchDialog = true"
                >
                    <v-icon>filter_none</v-icon>
                </v-btn>
                <v-text-field 
                append-icon="search" 
                label="Search invoices"
                >
                </v-text-field>
                <v-btn icon>
                    <v-icon>clear</v-icon>
                </v-btn>
            </v-toolbar>
            <v-layout fill-height>
                <v-container grid-list-md class="pt-1">
                    <v-layout row>
                        <!-- Dopolnitelni funkciski elementi tuka -->
                        <v-spacer></v-spacer>
                        <v-btn 
                        dark 
                        color="green darken-3"
                        @click="OpenInvoiceCreate"
                        >
                        + CREATE NEW INVOICE
                        </v-btn>
                        <v-btn dark color="blue darken-3">OPEN DRAFTS</v-btn>
                    </v-layout>
                    <v-data-iterator
                    :items="invoices"
                    :rows-per-page-items="rowsPerPageItems"
                    :pagination.sync="pagination"
                    content-tag="v-layout"
                    wrap
                    class="fixed-pagination"
                    >
                    <template v-slot:item="props">
                        <v-flex
                        md2
                        lg2
                        xl2
                        >
                        <v-card
                        :hover="true"
                        >
                            <v-card-title>
                                <h6
                                style="overflow: hidden; position:absolute;"
                                >{{ props.item.invoice_name}}</h6>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-list dense>
                            <v-list-tile>
                                <v-list-tile-content>Code:</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ props.item.invoice_code }}</v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile>
                                <v-list-tile-content>Client:</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ props.item.invoice_client }}</v-list-tile-content>
                            </v-list-tile>
                            </v-list>
                            <v-card-actions style="text-align: center">
                                <v-layout row align-content-center>
                                    <v-flex md12>
                                        <v-btn 
                                        icon
                                        >
                                            <v-icon color="yellow darken-3">local_printshop</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                        </v-flex>
                    </template>
                    </v-data-iterator>                    
                </v-container>
            </v-layout>
        </v-container>
        <v-container v-if="viewMode == 'singleInvoiceView'">
            <component 
            :is="dynamicPanel.component"
            :invoice="dynamicPanel.props"
            ></component>
        </v-container>
    <!-- ADVANCED SEARCH DIALOG  -->
    <v-dialog
      v-model="advancedSearchDialog"
      max-width="400"
    >
      <v-card>
        <v-card-title class="headline">Advanced search</v-card-title>
        <v-card-text>
            <v-container grid-list-md>
                <v-layout row>
                    <v-flex md6>
                        <v-text-field label="Invoice name"></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field label="Invoice code"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row>
                    <v-flex md6>
                        <v-text-field type="number" label="Minimum price"></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field type="number" label="Maximum price"></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="advancedSearchDialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="Search"
          >
          <v-icon>search</v-icon>
            Search
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-layout>
</template>

<script>
import Invoice from '../../models/invoice.js';
import axios from 'axios';

const csrfToken = document.querySelector("meta[name=csrf-token]").content;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrf_token
};
axios.defaults.withCredentials = true;
export default {
    name: 'invoices',
    data() {
        return {
            invoices : [],
            searchSubstring: '',
            advancedSearchDialog: false,
            rowsPerPageItems: [4, 8, 12],
            pagination: {
                rowsPerPage: 6
            },
            viewMode: 'invoicesPanelView',
            dynamicPanel: {
                component: null,
                props: null
            }
        }
    },
    methods: {
        GetAllInvoices()
        {
            axios.get(
                'getinvoices',
                {
                    _method : 'GET',
                    withCredentials: true
                }
            ).then(res => {
                console.log('INVOJSIIIZ: ', res.data);
                this.invoices = res.data;
            }).catch(err => {
                console.error(err);
            })
        },
        OpenInvoiceCreate(){
            let newInvoice = new Invoice;
            this.dynamicPanel.props = newInvoice;
            this.dynamicPanel.component = 'invoices-create';
            this.viewMode = 'singleInvoiceView';
        },
        Search()
        {

        },
        NarrowSearch()
        {

        },
        ClearSearch()
        {

        }
    },
    created(){
        this.GetAllInvoices();
    }
}
</script>

