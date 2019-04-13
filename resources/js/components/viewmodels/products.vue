<template>
    <v-layout>
        <v-container fluid class="pa-0">
            <v-toolbar light>
                <v-toolbar-title class="title">
                    <v-icon>label</v-icon>
                    Products
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn 
                icon
                @click="advancedSearchDialog = true"
                >
                    <v-icon>filter_none</v-icon>
                </v-btn>
                <v-btn 
                icon
                @click="ClearSearch"
                :disabled="searchSubstring == '' ? true : false"
                >
                    <v-icon>clear</v-icon>
                </v-btn>
                <v-text-field
                label="Search products"
                append-icon="search"
                style="width: 50px"
                v-model="searchSubstring"
                @keyup.enter="Search"
                @keyup.delete="NarrowSearch"
                >
                </v-text-field>
            </v-toolbar>
            <v-layout
            fill-height
            >
        <!-- DATA ITERATOR FOR PRODUCT DISPLAY PANEL -->
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
                                        @click="AddProductToCategory(props.item.product_id, 2)"
                                        >
                                            <v-icon color="yellow darken-3">star</v-icon>
                                        </v-btn>
                                        <v-btn 
                                        v-if="categorisedProductsDisplay == false"
                                        icon
                                        @click="SetForCategorisation(props.item.product_id)"
                                        >
                                            <v-icon color="blue darken-3">category</v-icon>
                                        </v-btn>
                                        <v-btn 
                                        v-else
                                        icon
                                        @click="RemoveFromCategory(props.item.product_id, categoryFilterCondition.value)"
                                        >
                                            <v-icon color="red darken-3">category</v-icon>
                                        </v-btn>
                                        <v-btn 
                                        icon
                                        @click="AddToSelectedInvoice(props.item)"
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

    <!-- MANAGE CATEGORY NAME DIALOG -->
    <v-dialog
      v-model="dialog"
      max-width="350"
    >
      <v-card>
        <v-card-title class="headline">Manage categories</v-card-title>
        <v-card-text>
                <v-data-table
                    hide-actions
                    hide-headers
                    :items="menuItems"
                    class="elevation-1"
                >
                    <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.category_name }}</td>
                    <td class="text-xs-right">
                        <v-btn icon @click="RemoveCategory(props.item.category_id)">
                            <v-icon color="red darken-3">cancel</v-icon>
                        </v-btn>
                    </td>
                    </template>
                </v-data-table>
                <v-layout align-content-center>
                    <v-btn 
                    color="blue darken-3" 
                    dark 
                    @click="addCategoryDialog = true"
                    >
                    + Add new category
                    </v-btn>
                </v-layout>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="dialog = false"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- ADD NEW CATEGORY NAME DIALOG -->
    <v-dialog
      v-model="addCategoryDialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Add new category</v-card-title>
        <v-card-text>
            <v-text-field 
            v-model="addCategoryName"
            label="Category name"
            >
            </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="addCategoryDialog = false"
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

    <!-- ADD PRODUCT TO CATEGORY DIALOG  -->
    <v-dialog
      v-model="addToCategoryDialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Add to category</v-card-title>
        <v-card-text>
            <v-select 
            v-model="categorySelected"
            label="Select category"
            :items="menuItems"
            item-text="category_name"
            item-value="category_id"
            >
            </v-select>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="addToCategoryDialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="blue darken-3"
            flat="flat"
            @click="AddProductToCategory(productForCategorisation, categorySelected)"
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
    <!-- ADVANCED SEARCH DIALOG -->
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
                        <v-text-field label="Product description"></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-select label="Product category"></v-select>
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
import Product from '../../models/product.js';
import axios from 'axios';

const csrfToken = document.querySelector("meta[name=csrf-token]").content;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': csrfToken
};
axios.defaults.withCredentials = true;

export default {
    name: 'products',
    data() {
        return {
            products: [],
            auxilaryProducts: [],
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
            addCategoryDialog: false,
            advancedSearchDialog: false,
            addToCategoryDialog: false,
            addCategoryName: '',
            manageCategoriesPanel: '',
            categoriesPanelMode: {
                listCategories: 'list categories',
                addCategory: 'add category',
                editCategory: 'edit category'
            },
            editedCategory: {
                category_id: null,
                category_name: ''
            },
            categorisedProductsDisplay: false,
            productForCategorisation: 0,
            categorySelected: 0, 
            searchSubstring: ''
        }
    },
    watch: {
        'manageCategoriesPanel': {
            handler(){
                if (this.manageCategoriesPanel == this.categoriesPanelMode.listCategories) {
                    this.GetCategories();   
                }
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
        FilterProducts()
        {
            axios.post(
                'getconditionalproducts',
                {
                    _method: 'POST',
                    withCredentials: true,
                    data: this.categoryFilterCondition
                }
            ).then(res => {
                this.products = res.data;
                if(this.categoryFilterCondition.value != 1 
                && this.categoryFilterCondition.value != 0)
                    this.categorisedProductsDisplay = true;
                    else
                        this.categorisedProductsDisplay = false;
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
            this.addCategoryDialog = false;
        },
        SetForCategorisation(product_id)
        {
            this.productForCategorisation = product_id;
            this.addToCategoryDialog = true;
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
                this.addToCategoryDialog = false;
                this.GetCategories();
                this.GetAllProducts();
            }).catch(err => {
                console.error(err);
            }); 
        },
        RemoveFromCategory(product_id, category_id)
        {
            axios.post(
                'removefromcategory',
                {
                    _method: 'DELETE',
                    withCredentials: true,
                    data: {
                        product_id, category_id
                    }
                }
            ).then(res => {
                console.log('PRODUCT ReMOvED FROM CATEGORY. ')
                this.FilterProducts();
            }).catch(err => {
                console.error(err)
            });
        },
        RemoveCategory(category_id)
        {
            axios.post(
                'removecategory',
                {
                    _method: 'DELETE',
                    withCredentials: true,
                    data: category_id
                }
            ).then(res => {
                console.log('Category removed. ')
                this.GetCategories();
            }).catch(err => {
                console.error(err)
            });
        },
        Search()
        {
            this.auxilaryProducts = this.products;
            if(this.searchSubstring != '') {
                let searchResults = [];

                for (let i = 0; i < this.products.length; i++) {
                    if(this.products[i].product_description.toUpperCase().includes(this.searchSubstring.toUpperCase())){
                        searchResults.push(this.products[i]);
                    }     
                }
                this.products = searchResults;
            } else {
                this.GetAllProducts();
            }
        },
        ClearSearch()
        {
            this.searchSubstring = '';
            this.products = this.auxilaryProducts;
            this.auxilaryProducts = [];
        },
        NarrowSearch()
        {
            if(this.searchSubstring != '') {
                let searchResults = [];

                for (let i = 0; i < this.auxilaryProducts.length; i++) {
                    if(this.auxilaryProducts[i].product_description.toUpperCase().includes(this.searchSubstring.toUpperCase())){
                        searchResults.push(this.auxilaryProducts[i]);
                    }     
                }
                this.products = searchResults;
            } else {
                this.GetAllProducts();
            }            
        }
        
    },
    AddToSelectedInvoice()
    {

    },
    created(){
        this.GetAllProducts();
        this.GetCategories();
    }
}
</script>


