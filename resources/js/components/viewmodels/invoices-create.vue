<template>
    <v-layout>
        <v-container style="max-height: 100vh; overflow-y: auto" fluid class="pa-0">
            <v-toolbar>
                <v-toolbar-title class="title">
                    <v-icon>receipt</v-icon>
                    Create new invoice
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon @click="$emit('cancel-create-invoice')">
                    <v-icon>clear</v-icon>
                </v-btn>
            </v-toolbar>
            <v-layout>
                <v-container grid-list-md>
                    <v-layout row >
                        <v-flex md6>
                            <v-subheader>
                                Invoice sender info
                            </v-subheader>
                            <v-divider></v-divider>
                            <v-text-field label="Company name"></v-text-field>
                            <v-text-field label="Company address"></v-text-field>
                            <v-text-field label="Company phone"></v-text-field>
                            <v-text-field label="Company email"></v-text-field>
                        </v-flex>
                        <v-flex md6 style="overflow-y: visible; position: relative">
                            <v-subheader>
                                Invoice reciever info
                            </v-subheader>
                            <v-divider></v-divider>
                            <v-text-field 
                            label="Client name"
                            v-model="clientSearchSubstring" 
                            class="hide-textfield-details"
                            @keypress="SearchClients"
                            >
                            </v-text-field>
                            <div
                            class="container-fluid pa-0"
                            v-if="clientSearchSubstring != ''"  
                            style="background: white; position: absolute; z-index: 9; max-height: 300px; overflow-y: auto" 
                            >
                                <v-list class="mt-0">
                                    <v-list-tile 
                                    v-for="client in clients" 
                                    :key="client.contact_id"
                                    @click="AddClientToInvoice(client)"
                                    >
                                        <v-list-tile-title>
                                            {{ client.contact_firstname + ' ' + client.contact_lastname }}
                                        </v-list-tile-title>
                                    </v-list-tile>
                                </v-list>
                            </div>    
                            <v-text-field v-model="newInvoice.invoice_client.address" label="Client address"></v-text-field>
                            <v-text-field v-model="newInvoice.invoice_client.phone" label="Client phone"></v-text-field>
                            <v-text-field v-model="newInvoice.invoice_client.email" label="Client email"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout style="backgroud-color: white!important">
                        <v-flex md10 style="overflow-y: visible; position: relative">
                            <v-text-field 
                            v-model="productSearchSubstring" 
                            label="Search products"
                            class="hide-textfield-details"
                            @keypress="SearchProducts"
                            ></v-text-field>
                            <div
                            class="container-fluid pa-0"
                            v-if="productSearchSubstring != ''"  
                            style="background: white; position: absolute; z-index: 9; max-height: 300px; overflow-y: auto" 
                            >
                                <v-list class="mt-0">
                                    <v-list-tile 
                                    v-for="product in products" 
                                    :key="product.product_id"
                                    @click="OpenAddQuantityDialog(product)"
                                    >
                                        <v-list-tile-title>
                                            {{ product.product_description }}
                                        </v-list-tile-title>
                                    </v-list-tile>
                                </v-list>
                            </div>
                        </v-flex>
                        <v-flex md2>
                            <v-btn color="blue darken-3" dark>
                                + Apply discounts
                            </v-btn>
                        </v-flex>
                    </v-layout>
                    <v-data-table
                        :headers="headers"
                        :items="newInvoice.invoice_items"
                        hide-actions
                    >
                        <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.product_code }}</td>
                        <td class="text-xs-left">{{ props.item.product_description }}</td>
                        <td class="text-xs-left">{{ props.item.product_quantity }}</td>
                        <td class="text-xs-left">{{ props.item.product_unitprice }}</td>
                        <td class="text-xs-left">{{ props.item.product_total_price }}</td>
                        <td class="text-xs-left">
                            <v-btn icon>
                                <v-icon color="red darken-3">remove</v-icon>
                            </v-btn>
                        </td>
                        </template>
                    </v-data-table>
                    <br>
                    <v-layout row>
                        <v-spacer></v-spacer>
                        <div class="title">Total price:</div>
                        <div class="title ml-4">{{ newInvoice.invoice_total_price }}</div>
                    </v-layout>
                    <br>
                    <v-layout row justify-end>
                        <v-btn flat color="blue darken-3" @click="$emit('cancel-create-invoice')">Cancel</v-btn>
                        <v-spacer></v-spacer>                        
                        <v-btn outline color="blue darken-3">Save as draft</v-btn>
                        <v-btn dark color="blue darken-3">Save</v-btn>
                    </v-layout>
                </v-container>
            </v-layout>
        </v-container>
    <v-dialog
      v-model="addQuantityDialog"
      max-width="400"
    >
      <v-card>
        <v-card-title class="headline">Add product quantity</v-card-title>
        <v-card-text>
            <v-layout>
                <v-text-field 
                type="number" 
                v-model="productQuantity"
                label="Product quantity"
                ></v-text-field>
            </v-layout>
        </v-card-text>
        <v-card-actions>
        <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="addQuantityDialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="AddProductToInvoice()"
          >
            Add
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-layout>
</template>

<script>
import axios from 'axios';
import Invoice from '../../models/invoice.js';
import Product from '../../models/product.js';
import { Calculations } from '../../functions/calculations.js';

const csrfToken = document.querySelector("meta[name=csrf-token]").content;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': csrfToken
};
axios.defaults.withCredentials = true;

export default {
    name: 'invoices-create',
    props: ['invoice'],
    data(){
        return {
            newInvoice: {},
            products: [],
            clients: [],
            invoiceProducts: [],
            headers: [
                { text: 'Product code', align: 'left', value: 'code' },
                { text: 'Product description', value: 'description', align: 'left' },
                { text: 'Quantity', value: 'quantity', align: 'left' },
                { text: 'Unit price', value: 'unitprice', align: 'left' },
                { text: 'Amount', value: 'amount', align: 'left' },
                { text: 'Options', value: 'options', align: 'left' }
            ],
            auxilaryProduct: {},
            auxilaryProducts: [],
            auxilaryClients: [],
            addQuantityDialog: false,
            productSearchSubstring: '',
            clientSearchSubstring: '',
            productQuantity: 1,
            categoryFilterCondition: {
                field: 'category_id',
                operator: '=',
                value: 0
            }
        }
    },
    watch: {
        "newInvoice.invoice_items": {
            handler() {
                this.CalculateTotalInvoice();
            }
        }
    },
    methods: {
        GetAllProducts()
        {
            axios.get(
                'getproducts',
                {
                    _method: 'GET',
                    withCredentials: true,
                }
            ).then(res => {
                console.log('RESPONZ PRODUKTZ: ', res.data);
                this.products = res.data;
            }).catch(err => {
                console.error(err);
            });
        },
        GetAllClients()
        {
            this.clients = [];
            axios.get('getcontacts')
            .then((response) => {
                this.clients = response.data;
                console.log('Klienti ', response.data);
            }).catch((err) => {
                console.error(err);
            });
        },
        SearchProducts()
        {
            this.auxilaryProducts = this.products;
            if(this.productSearchSubstring != '') {
                let searchResults = [];

                for (let i = 0; i < this.products.length; i++) {
                    if(this.products[i].product_description.toUpperCase().includes(this.productSearchSubstring.toUpperCase())){
                        searchResults.push(this.products[i]);
                    }     
                }
                this.products = searchResults;
            } else {
                this.GetAllProducts();
            }         
        },
        NarrowSearchProducts()
        {
            if(this.productSearchSubstring != '') {
                let searchResults = [];

                for (let i = 0; i < this.auxilaryProducts.length; i++) {
                    if(this.auxilaryProducts[i].product_description.toUpperCase().includes(this.productSearchSubstring.toUpperCase())){
                        searchResults.push(this.auxilaryProducts[i]);
                    }     
                }
                this.products = searchResults;
            } else {
                this.GetAllProducts();
            }             
        },
        SearchClients()
        {
            this.auxilaryClients = this.clients;
            if(this.clientSearchSubstring != '') {
                let searchResults = [];

                for (let i = 0; i < this.clients.length; i++) {
                    if(this.clients[i].contact_firstname.toUpperCase().includes(this.clientSearchSubstring.toUpperCase())
                    || this.clients[i].contact_lastname.toUpperCase().includes(this.clientSearchSubstring.toUpperCase())
                    ){
                        searchResults.push(this.clients[i]);
                    }     
                }
                this.clients = searchResults;
            } else {
                this.GetAllClients();
            }  
        },
        NarrowSearchClients()
        {
            // PREPISHI LOGIKA ZA NARROWSEARCH, GLEJ DA PREKRATISH NE NATRUPUVAJ LOGIKA
        },
        AddClientToInvoice(client)
        {
            this.newInvoice.invoice_client.id = client.contact_id;
            this.newInvoice.invoice_client.name = client.contact_firstname + ' ' + client.contact_lastname;
            this.newInvoice.invoice_client.address = client.street + ', ' + client.city + ', ' + client.country;
            this.newInvoice.invoice_client.phone = client.contact_phone;
            this.newInvoice.invoice_client.email = client.email;
            this.clientSearchSubstring = '';
            console.log(this.newInvoice.invoice_client)
        },
        OpenAddQuantityDialog(product) 
        {
            this.auxilaryProduct = product;
            this.addQuantityDialog = true;
        },      
        AddProductToInvoice()
        {
            let invoiceProduct = new Product;
            invoiceProduct.product_id = this.auxilaryProduct.product_id;
            invoiceProduct.product_code = this.auxilaryProduct.product_code;
            invoiceProduct.product_description = this.auxilaryProduct.product_description;
            invoiceProduct.product_barcode = this.auxilaryProduct.product_barcode;
            invoiceProduct.product_status = this.auxilaryProduct.product_status;
            invoiceProduct.product_unitprice = this.auxilaryProduct.product_unitprice;
            invoiceProduct.product_vat_rate = 18.0;
            invoiceProduct.product_quantity = this.productQuantity;
            invoiceProduct.product_total_price = 
            Calculations.CalculateItemTotals(invoiceProduct.product_unitprice, invoiceProduct.product_quantity);
            this.newInvoice.invoice_items.push(invoiceProduct);
            this.productSearchSubstring = '';
            this.productQuantity = 1;
            this.auxilaryProduct = {};
            this.addQuantityDialog = false;
        },
        CalculateTotalInvoice()
        {
            this.newInvoice.invoice_total_price = Calculations.CalculateInvoiceTotals(this.newInvoice);
        }
    },
    created(){
        this.GetAllProducts();
        this.GetAllClients();
        this.newInvoice = new Invoice;
    }
}
</script>

