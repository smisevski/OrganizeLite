<template>
    <v-app light> 
            <v-layout row>
                    <navigation-drawer
                    @open-panel="OpenPanel"
                    ></navigation-drawer> 
                    <v-container 
                    fluid
                    class="pa-0" 
                    style="height: 100%;">
                            <component 
                            :is="dynamicPanel.component"
                            v-bind="dynamicPanel.props"
                            @link-contact-to-invoice="LinkContactToInvoice"
                            ></component>
                    </v-container>
            </v-layout>    
    </v-app>
</template>

<script>
import Vuetify from 'vuetify';
Vue.use(Vuetify);
import axios from 'axios';
axios.defaults.xsrfHeaderName = "X-CSRFToken";
axios.defaults.xsrfCookieName = 'csrftoken';

export default {
    data() {
        return {
            dynamicPanel: {
                component: null,
                props: null
            },
        }
    },
    methods: {
        OpenPanel(panelName) 
        {
            this.dynamicPanel.component = panelName;
        },
        LinkContactToInvoice(client) 
        {
            console.log('Invoice receipient: ', 
            client.contact_firstname+ ' ' + client.contact_lastname);
        }
    },
    mounted(){

    }
}
</script>
