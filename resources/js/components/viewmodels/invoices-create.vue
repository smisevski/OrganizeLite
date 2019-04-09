<template>
    <v-layout style="height: 100vh">
            <v-layout 
            row 
            >
                <v-list-tile>
                    <v-list-tile-content class="title">
                        Create new invoice
                    </v-list-tile-content>
                </v-list-tile>
            </v-layout>
    </v-layout>
</template>

<script>
import axios from 'axios';

const csrfToken = document.querySelector("meta[name=csrf-token]").content;
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrf_token
};
axios.defaults.withCredentials = true;

export default {
    name: 'invoices-create',
    props: ['invoice'],
    data(){
        return {
            invoiceProducts: []
        }
    }
}
</script>

