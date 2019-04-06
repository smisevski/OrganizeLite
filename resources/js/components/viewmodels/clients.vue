<template>
    <v-layout fill-height>
        <!-- CONTACTS LIST SECTION -->
        <v-flex md5 ref="clientList">
            <div
            style="box-shadow: 2px 1px #b7b7b7; height: 100%; background-color: white"
            ref="clientsToolbar">
                <v-toolbar    
                light>

                    <v-toolbar-title class="title">
                        <v-icon>group</v-icon>
                        Clients
                    </v-toolbar-title>

                    <v-spacer></v-spacer>
                    <!-- <v-icon color="blue darken-3" @click="AddNewContact">person_add</v-icon> -->
                    <v-spacer></v-spacer>
                    <v-btn 
                    :disabled="searchSubstring == '' ? true : false"
                    icon 
                    @click="ClearSearch">
                        <v-icon>clear</v-icon>
                    </v-btn>
                    <v-text-field
                        label="Search clients"
                        append-icon="search"
                        regular
                        light
                        v-model="searchSubstring"
                        @keyup.enter="Search"
                        @keyup.delete="NarrowSearch"
                    ></v-text-field>
                </v-toolbar> 
            
            <v-list style="max-height: 100vh ;overflow-y: auto">
                <v-list-tile
                    class=""
                    v-for="client in clients"
                    :key="client.contact_id"
                    avatar
                    active-class="active"
                    @click="ShowClientsDetails(client.contact_id)"
                >
                    
                    <v-list-tile-avatar>
                        <img 
                        :src="(client.contact_id >=1 
                        && client.contact_id <=7) ? `images/ci-${client.contact_id}.png` 
                        : CalcThumbs(client.contact_id)" 
                        alt="pic">
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                    <v-list-tile-title
                    v-text="client.contact_firstname 
                    + ' ' 
                    + client.contact_lastname"
                    ></v-list-tile-title>
                    </v-list-tile-content> 

                    <v-list-tile-action>
                        <v-btn icon @click="LinkToCurrentInvoice(client)">
                            <v-icon color="green">share</v-icon>
                        </v-btn>
                    </v-list-tile-action>  
                    <v-list-tile-action>
                        <v-btn icon @click="RemoveClient(client.contact_id)">
                            <v-icon color="red">delete</v-icon>
                        </v-btn>
                    </v-list-tile-action>                                   
                </v-list-tile>    
            </v-list>
            <v-btn 
            fab
            dark
            style="position: absolute; bottom: 3px" 
            color="blue darken-3"
            @click="AddNewContact"
            >
            <v-icon>person_add</v-icon>
            </v-btn>
            </div>
        </v-flex>
        <!-- CONTACT DETAILS DISPLAY SECTION -->
        <v-flex
        v-if="operationPanel == panelModes.edit" 
        md7 
        ref="ClientDetails" 
        style="max-height: 100vh ;overflow-y: auto"
        > 
            <v-container
            grid-list-md
            class="lighten-1"
            scrollable
            >   
            <div> 
                <v-layout
                row 
                fill-height
                justify-end
                align-baseline
                > 
                <v-layout
                v-if="displayContact.contact_firstname != undefined"
                row 
                justify-start
                class="title"
                >
                <v-list-tile>
                <v-list-tile-avatar>
                        <img 
                        :src="(displayContact.contact_id >=1 
                        && displayContact.contact_id <=7) ? `images/ci-${displayContact.contact_id}.png` 
                        : CalcThumbs(displayContact.contact_id)" 
                        alt="pic">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <div class="title">
                        {{ displayContact.contact_firstname + ' ' + displayContact.contact_lastname }}
                    </div> 
                </v-list-tile-content>
                </v-list-tile>
                </v-layout>
                    <v-btn v-if="displayContact.contact_id != null && editMode == false"
                    fab 
                    color="blue darken-3"
                    @click="EditContactDetails(displayContact.contact_id)"
                    >
                        <i 
                        style="color: white" 
                        class="material-icons"
                        >
                        edit
                        </i>
                    </v-btn>
                    <v-btn v-if="displayContact.contact_id != null && editMode == false"
                    fab
                    outline 
                    color="blue darken-3"
                    @click="GeneratePdf()"
                    >
                        <i  
                        class="material-icons"
                        >
                        print
                        </i>
                    </v-btn>
                </v-layout>

                <v-subheader>
                    <v-icon class="text-md-left">group</v-icon>
                    Personal
                </v-subheader>
                <v-divider></v-divider>
                <v-layout row fill-height>
                    <v-flex md6>
                        <v-text-field
                            label="First Name"
                            v-model="displayContact.contact_firstname"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Last Name"
                            v-model="displayContact.contact_lastname"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field
                            label="Gender"
                            v-model="displayContact.contact_gender"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Birth Date"
                            v-model="displayContact.contact_birthdate"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                    </v-flex>
                </v-layout>

                <v-subheader>
                    <v-icon class="text-md-left">group</v-icon>
                    Contact
                </v-subheader>
                <v-divider></v-divider>
                <v-layout row fill-height>
                    <v-flex md6>
                        <v-text-field
                            label="Street"
                            v-model="displayContact.contact_street"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="City"
                            v-model="displayContact.contact_city"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Fax"
                            v-model="displayContact.contact_fax"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Email"
                            v-model="displayContact.contact_email"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field
                            label="Province"
                            v-model="displayContact.contact_province"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Country"
                            v-model="displayContact.contact_country"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Phone"
                            v-model="displayContact.contact_phone"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Mobile"
                            v-model="displayContact.contact_mobile"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                    </v-flex>
                </v-layout>

                <v-subheader>
                    <v-icon class="text-md-left">group</v-icon>
                    Company
                </v-subheader>
                <v-divider></v-divider>
                <v-layout row fill-height>
                    <v-flex md6>
                        <v-text-field
                            label="Company name"
                            v-model="displayContact.contact_company_name"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Company address"
                            v-model="displayContact.contact_company_address"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Company email"
                            v-model="displayContact.contact_company_email"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Company Phone"
                            v-model="displayContact.contact_company_phone"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field
                            label="Company fax"
                            v-model="displayContact.contact_company_fax"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Company fiscal code"
                            v-model="displayContact.contact_company_fiscalcode"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                        <v-text-field
                            label="Clients work position"
                            v-model="displayContact.contact_company_position"
                            :readonly="editMode == true ? false : true"
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout justify-end v-if="editMode == true"> 
                    <v-btn 
                    flat 
                    justify-end 
                    outline 
                    color="blue darken-3" 
                    @click="CancelEditMode()"
                    >
                    Cancel
                    </v-btn>
                    <v-btn 
                    justify-end 
                    dark 
                    color="blue darken-3" 
                    @click="SaveEditedDetails()"
                    >
                    Save
                    </v-btn>
                </v-layout>
                </div>
            </v-container>      
        </v-flex>
        <v-flex
        v-else 
        sm8 md8 lg8 
        ref="ClientDetails" 
        style="max-height: 100vh ;overflow-y: auto"
        > 
            <v-container
            grid-list-md
            class="lighten-1"
            scrollable
            >   
                <v-layout 
                class="headline font-weight-medium"
                style="padding-left: 1vw"
                >
                    Add new contact
                </v-layout><br><br>
                <v-subheader>
                    <v-icon class="text-md-left">group</v-icon>
                    Personal
                </v-subheader>
                <v-divider></v-divider>
                <v-layout row fill-height>
                    <v-flex md6>
                        <v-text-field
                            autofocus
                            label="First Name"
                            v-model="newContact.contact_firstname"
                        ></v-text-field>
                        <v-text-field
                            label="Last Name"
                            v-model="newContact.contact_lastname"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field
                            label="Gender"
                            v-model="newContact.contact_gender"
                        ></v-text-field>
                        <v-text-field
                            label="Birth Date"
                            v-model="newContact.contact_birthdate"
                        ></v-text-field>
                        <v-text-field
                            label="ID card number"
                            v-model="newContact.contact_idcard"
                        ></v-text-field>
                    </v-flex>
                </v-layout>

                <v-subheader>
                    <v-icon class="text-md-left">group</v-icon>
                    Contact
                </v-subheader>
                <v-divider></v-divider>
                <v-layout row fill-height>
                    <v-flex md6>
                        <v-text-field
                            label="Street"
                            v-model="newContact.contact_street"
                        ></v-text-field>
                        <v-text-field
                            label="City"
                            v-model="newContact.contact_city"
                        ></v-text-field>
                        <v-text-field
                            label="Fax"
                            v-model="newContact.contact_fax"
                        ></v-text-field>
                        <v-text-field
                            label="Email"
                            v-model="newContact.contact_email"
                        ></v-text-field>
                        <v-text-field
                            label="ZIP code"
                            v-model="newContact.contact_zip_code"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field
                            label="Province"
                            v-model="newContact.contact_province"
                        ></v-text-field>
                        <v-text-field
                            label="Country"
                            v-model="newContact.contact_country"
                        ></v-text-field>
                        <v-text-field
                            label="Phone"
                            v-model="newContact.contact_phone"
                        ></v-text-field>
                        <v-text-field
                            label="Mobile"
                            v-model="newContact.contact_mobile"
                        ></v-text-field>
                    </v-flex>
                </v-layout>

                <v-subheader>
                    <v-icon class="text-md-left">group</v-icon>
                    Company
                </v-subheader>
                <v-divider></v-divider>
                <v-layout row fill-height>
                    <v-flex md6>
                        <v-text-field
                            label="Company name"
                            v-model="newContact.contact_company_name"
                        ></v-text-field>
                        <v-text-field
                            label="Company address"
                            v-model="newContact.contact_company_address"
                        ></v-text-field>
                        <v-text-field
                            label="Company email"
                            v-model="newContact.contact_company_email"
                        ></v-text-field>
                        <v-text-field
                            label="Company Phone"
                            v-model="newContact.contact_company_phone"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md6>
                        <v-text-field
                            label="Company fax"
                            v-model="newContact.contact_company_fax"
                        ></v-text-field>
                        <v-text-field
                            label="Company fiscal code"
                            v-model="newContact.contact_company_fiscalcode"
                        ></v-text-field>
                        <v-text-field
                            label="Clients work position"
                            v-model="newContact.contact_company_position"
                        ></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout justify-end v-if="operationPanel == panelModes.add">
                    <v-btn 
                    outline 
                    color="blue darken-3" 
                    justify-end
                    @click="operationPanel = panelModes.edit"
                    >Cancel
                    </v-btn>
                    <v-btn 
                    dark 
                    color="blue darken-3" 
                    justify-end
                    @click="SaveNewContact"
                    >
                    Save
                    </v-btn>
                </v-layout>
            </v-container>      
        </v-flex>
    </v-layout>
</template>

<script>
import Contact from '../../models/contact.js';
import axios from 'axios';

const csrfToken = document.querySelector("meta[name=csrf-token]").content;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrf_token
};

export default {
    name: "clients",
    data() {
        return {
            clients: [],
            auxilaryClients: [],
            panelModes: {
                edit: 'edit',
                add: 'add'
            },
            displayContact: {},
            newContact: {},
            a: 0,
            editMode: false,
            isThumbsSet: false,
            operationPanel: 'edit',
            searchSubstring: ''
        }
    },
    methods: {
        CalcThumbs(id) 
        {   
            let imgNumber = id;     
            do {
                imgNumber -= 7;
            } while (imgNumber > 7)  
            return `images/ci-${imgNumber}.png`        
        },
        GetAllClients()
        {
            this.clients = [];
            axios.get('getcontacts')
            .then((response) => {
                this.clients = response.data;
            }).catch((err) => {
                console.error(err);
            });
        },
        ShowClientsDetails(id) 
        {
            this.displayContact = new Contact();
            for(let i = 0; i < this.clients.length; i++) {
                if (this.clients[i].contact_id == id) {
                    this.displayContact.contact_id = this.clients[i].contact_id;
                    this.displayContact.contact_firstname = this.clients[i].contact_firstname;
                    this.displayContact.contact_lastname = this.clients[i].contact_lastname;
                    this.displayContact.contact_gender = this.clients[i].contact_gender;
                    this.displayContact.contact_birthdate = this.clients[i].contact_birthdate;
                    this.displayContact.contact_phone = this.clients[i].contact_phone;
                    this.displayContact.contact_mobile = this.clients[i].contact_mobile;
                    this.displayContact.contact_fax = this.clients[i].contact_fax;
                    this.displayContact.contact_email = this.clients[i].contact_email;
                    this.displayContact.contact_street = this.clients[i].street;
                    this.displayContact.contact_city = this.clients[i].city;
                    this.displayContact.contact_province = this.clients[i].province;
                    this.displayContact.contact_country = this.clients[i].country;
                    this.displayContact.contact_zip_code = this.clients[i].zip_code;
                    this.displayContact.contact_company_name = this.clients[i].contact_company_name;
                    this.displayContact.contact_company_address = this.clients[i].contact_company_address;
                    this.displayContact.contact_company_email = this.clients[i].contact_company_email;
                    this.displayContact.contact_company_phone = this.clients[i].contact_company_phone;
                    this.displayContact.contact_company_fax = this.clients[i].contact_company_fax;
                    this.displayContact.contact_company_fiscalcode = this.clients[i].contact_company_fiscalcode;
                    this.displayContact.contact_company_position = this.clients[i].contact_company_position;
                }
            }
        },
        EditContactDetails(id)
        {
            this.editMode = true;
            this.displayContact.contact_id = id;
        },
        SaveEditedDetails()
        {
            axios.post(
                'updatecontact',
                {
                    _method: 'PUT',
                    data: this.displayContact,
                    withCredentials: true
                }
            ).then(res => {
                console.log(res.data);
                this.GetAllClients();
            }).catch(err => {
                console.error(err)
            });
            this.editMode = false;
        },
        CancelEditMode()
        {
            this.editMode = false;
            console.log('edit? ', this.editMode)
        },
        GeneratePdf()
        {
            console.log('PDF GENERATED!');
        },
        AddNewContact()
        {
            this.newContact = new Contact();
            if (this.clients.length > 0)
            this.newContact.contact_id = this.clients[this.clients.length - 1].contact_id + 1;
            else this.newContact.contact_id = 1;
            this.operationPanel = 'add';
        },
        SaveNewContact()
        {
            axios.post(
                'savecontact',
                {
                    _method: 'POST',
                    data: this.newContact,
                    withCredentials: true
                }
            ).then(res => {
                console.log(res.data)
                this.GetAllClients();
            }).catch(err => {
                console.error(err);
            });
            this.operationPanel = this.panelModes.edit;
        },
        LinkToCurrentInvoice(client)
        {
            this.$emit('link-contact-to-invoice', client);
        },
        RemoveClient(id)
        {
            axios.post(
                'removecontact',
                {
                    _method: 'DELETE',
                    data: id,
                    withCredentials: true
                }
            ).then(res => {
                console.log(res.data)
                this.GetAllClients();
            }).catch(err => {
                console.error(err);
            });   
        },
        Search()
        {
            this.auxilaryClients = this.clients;
            if(this.searchSubstring != '') {
                let searchResults = [];
                for (let i = 0; i < this.clients.length; i++) {
                    if(this.clients[i].contact_firstname.toUpperCase().includes(this.searchSubstring.toUpperCase()) 
                    || this.clients[i].contact_lastname.toUpperCase().includes(this.searchSubstring.toUpperCase())){
                        searchResults.push(this.clients[i]);
                    }       
                }
                this.clients = searchResults;
            } else {
                this.GetAllClients();
            }
        },
        ClearSearch()
        {
            this.searchSubstring = '';
            this.clients = this.auxilaryClients;
            this.auxilaryClients = [];
        },
        NarrowSearch()
        {
            if(this.searchSubstring != '') {
                let searchResults = [];
                for (let i = 0; i < this.auxilaryClients.length; i++) {
                    if(this.auxilaryClients[i].contact_firstname.toUpperCase().includes(this.searchSubstring.toUpperCase()) 
                    || this.auxilaryClients[i].contact_lastname.toUpperCase().includes(this.searchSubstring.toUpperCase())){
                        searchResults.push(this.auxilaryClients[i]);
                    }       
                }
                this.clients = searchResults;
            } else {
                this.GetAllClients();
            }
        }
    },
    created() {
        this.GetAllClients();
    }
}
</script>



