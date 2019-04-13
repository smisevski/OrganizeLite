export default class {
    constructor(){
        this.invoice_id = null,
        this.invoice_name = '',
        this.invoice_client = {
            id: 0,
            name: '',
            address: '',
            phone: '',
            email: '',
            fax: ''
        },
        this.invoice_sender = '',
        this.invoice_issued = '',
        this.invoice_items = [],
        this.invoice_total_price = 0.0,
        this.invoce_total_discount = 0.0
    }
}