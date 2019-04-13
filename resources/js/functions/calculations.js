export class Calculations {

    static CalculateItemTotals(unitprice, quantity)
    {
        let itemTotal = unitprice*quantity;
        return itemTotal;
    } 
    
    static CalculateInvoiceTotals(invoice)
    {
        let invoiceTotal = 0;
        for(let i = 0; i < invoice.invoice_items.length; i++) {
            invoiceTotal += invoice.invoice_items[i].product_unitprice * invoice.invoice_items[i].product_quantity;
        }  
        return invoiceTotal; 
    }
}