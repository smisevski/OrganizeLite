<template>
    <v-layout>
        <!-- DATA ITTERATOR FOR PRODUCT DISPLAY PANEL -->
        <v-container fluid class="pa-0">
            <v-toolbar light>
                <v-toolbar-title class="headline">
                    <v-icon>label</v-icon>
                </v-toolbar-title>

                
                <v-spacer></v-spacer>                    
                    <v-text-field
                    label="Search products"
                    append-icon="search"
                    style="width: 50px"
                    >
                    </v-text-field>
            </v-toolbar>
            <v-layout
            fill-height
            >
                <v-container grid-list-md class="pt-1">
                    <v-layout row>
                        <v-flex sm10 md10 lg10>
                            <v-select 
                            :items="menuItems"
                            item-text="category_name"
                            item-value="category_id"
                            v-model="categoryFilterCondition.value"
                            label="Select a category"
                            @change="FilterProducts"
                            >
                            </v-select>
                        </v-flex>
                        <v-flex sm2 md2 lg2>
                            <v-btn
                            dark
                            color="blue darken-3"
                            @click="dialog = true"
                            >
                                Manage categories
                            </v-btn>
                        </v-flex>
                    </v-layout>
                    <v-data-iterator
                    :items="products"
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
                        @click.native="productDialog = true"
                        :hover="true"
                        >
                            <v-card-title>
                                <h6
                                style="overflow: hidden; position:absolute;"
                                >{{ props.item.product_description}}</h6>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-list dense>
                            <v-list-tile>
                                <v-list-tile-content>Code:</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ props.item.product_code }}</v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile>
                                <v-list-tile-content>Price:</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ props.item.product_unitprice }}</v-list-tile-content>
                            </v-list-tile>
                            </v-list>
                            <v-card-actions style="text-align: center">
                                <v-layout row align-content-center>
                                    <v-flex md12>
                                        <v-btn 
                                        icon
                                        @click="AddProductToFavorites"
                                        >
                                            <v-icon color="yellow darken-3">star</v-icon>
                                        </v-btn>
                                        <v-btn 
                                        icon
                                        @click="AddProductToCategory"
                                        >
                                            <v-icon color="blue darken-3">category</v-icon>
                                        </v-btn>
                                        <v-btn 
                                        icon
                                        @click="AddToSelectedInvoice"
                                        >
                                            <v-icon color="green darken-3">all_inclusive</v-icon>
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

    <!-- Add Category Name Dialog    -->
    <v-dialog
      v-model="dialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Manage categories</v-card-title>
        <v-card-text>
            <v-text-field
            label="Category name"
            v-model="addCategoryName"
            >
            </v-text-field>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="dialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="AddCategory"
          >
            Add
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- Add product to invoice/category dialog  -->
    <v-dialog
      v-model="productDialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Add product</v-card-title>
        <v-card-text>

        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="productDialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="AddCategory"
          >
            Add
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-layout>
</template>

<script>
import Product from '../../models/product.js';
import axios from 'axios';

const csrfToken = document.querySelector("meta[name=csrf-token]").content;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrf_token
};
axios.defaults.withCredentials = true;

export default {
    name: 'products',
    data() {
        return {
            products: [],
            rowsPerPageItems: [4, 8, 12],
            pagination: {
                rowsPerPage: 12
            },
            headers: [
                {
                    text: 'Product code',
                    aligh: 'left',
                    sortable: false,
                    value: 'product_code'
                },
                {
                    text: 'Description',
                    aligh: 'left',
                    sortable: false,
                    value: 'description'
                },
                {
                    text: 'VAT',
                    aligh: 'left',
                    sortable: false,
                    value: 'vat'
                },
                {
                    text: 'Unit price',
                    aligh: 'left',
                    sortable: false,
                    value: 'unit_price'
                },
                {
                    text: 'Amount',
                    aligh: 'left',
                    sortable: false,
                    value: 'amount'
                },
            ],
            menuItems: [
                {category_id: 0, category_name: ''}
            ],
            categoryFilterCondition: {
                field: 'category_id',
                operator: '=',
                value: 0
            },
            additionalMenuItems: [
                {title: 'Add category'}
            ],
            dialog: false,
            productDialog: false,
            addCategoryName: ''
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
        FilterProducts()
        {
            console.log('category id', this.categoryFilterCondition.value)
            axios.post(
                'getconditionalproducts',
                {
                    _method: 'POST',
                    withCredentials: true,
                    data: this.categoryFilterCondition
                }
            ).then(res => {
                this.products = res.data;
            }).catch(err => {
                console.error(err);
            });
        },
        GetCategories()
        {
            axios.get(
                'getcategories',
                {
                    _method: 'GET',
                    withCredentials: true,
                }
            ).then(res => {
                console.log('RESPONZ KATEGORIZ: ', res.data);
                for(let i = 0; i<res.data.length; i++){
                    this.menuItems= res.data;
                }
                console.log('KATEGORIIIIII', this.menuItems)
            }).catch(err => {
                console.error(err);
            });
        },
        AddCategory()
        {
            axios.post(
                'addcategory',
                {
                    _method: 'POST',
                    withCredentials: true,
                    data: this.addCategoryName
                }
            ).then(res => {
                this.dialog = false;
                this.GetCategories();
            }).catch(err => {
                console.error(err);
            });
        },
        AddProductToCategory(product_id, category_id)
        {
           axios.post(
                'addtocategory',
                {
                    _method: 'POST',
                    withCredentials: true,
                    data: {
                        category_id, product_id
                    }
                }
            ).then(res => {
                this.dialog = false;
                this.GetCategories();
            }).catch(err => {
                console.error(err);
            }); 
        }
    },
    created(){
        this.GetAllProducts();
        this.GetCategories();
    }
}
</script>


