<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\ContactAdresses;
use App\ContactCompany;
use App\ContactView;

class ContactsController extends Controller
{
    /** GetContacts controller is goint to retrieve the full entries list from 
     * mv_contacts view in database.
     */
    public function GetContacts()
    {
        $contacts = ContactView::all();
        return $contacts;
    }

    public function UpdateContact(Request $request)
    {
        $updateContact = Contact::where('contact_id', '=',$request->data['contact_id']);
        $updateContact->contact_firstname = $request->data['contact_firstname'];
        $updateContact->contact_lastname = $request->data['contact_lastname'];
        $updateContact->contact_gender = $request->data['contact_gender'];
        $updateContact->contact_birthdate = $request->data['contact_birthdate'];
        $updateContact->contact_phone = $request->data['contact_phone'];
        $updateContact->contact_mobile = $request->data['contact_mobile'];
        $updateContact->contact_fax = $request->data['contact_fax'];
        $updateContact->contact_email = $request->data['contact_email'];
        $updateContact->contact_status = 'active';
        
        // $contact->contact_idcard = $request->data['contact_idcard'];
        
        $updateContactAddress = ContactAdresses::where('contact_id', '=',$request->data['contact_id']);
        $updateContactAddress->street = $request->data['contact_street'];
        $updateContactAddress->city = $request->data['contact_city'];
        $updateContactAddress->province = $request->data['contact_province'];
        $updateContactAddress->country = $request->data['contact_country'];
        $updateContactAddress->zip_code = $request->data['contact_zip_code'];

        $updateContactCompany = ContactCompany::where('contact_id', '=',$request->data['contact_id']);
        $updateContactCompany->contact_company_name = $request->data['contact_company_name'];
        $updateContactCompany->contact_company_address = $request->data['contact_company_address'];
        $updateContactCompany->contact_company_email = $request->data['contact_company_email'];
        $updateContactCompany->contact_company_phone = $request->data['contact_company_phone'];
        $updateContactCompany->contact_company_fax = $request->data['contact_company_fax'];
        $updateContactCompany->contact_company_fiscalcode = $request->data['contact_company_fiscalcode'];
        $updateContactCompany->contact_company_position = $request->data['contact_company_position'];

        $updateContact->update(['contact_id' => $request->data['contact_id']]);
        $updateContactAddress->update(['contact_id' => $request->data['contact_id']]);
        $updateContactCompany->update(['contact_id' => $request->data['contact_id']]);
    }

    public function SaveContact(Request $request)
    {
        $contact = new Contact;
        $contact->contact_firstname = $request->data['contact_firstname'];
        $contact->contact_lastname = $request->data['contact_lastname'];
        $contact->contact_gender = $request->data['contact_gender'];
        $contact->contact_birthdate = $request->data['contact_birthdate'];
        $contact->contact_phone = $request->data['contact_phone'];
        $contact->contact_mobile = $request->data['contact_mobile'];
        $contact->contact_fax = $request->data['contact_fax'];
        $contact->contact_email = $request->data['contact_email'];
        $contact->contact_status = 'active';
        
        $contact->contact_idcard = $request->data['contact_idcard'];

        $contact_address = new ContactAdresses;
        if($request->data['contact_street'] == NULL) {
            $contact_address->contact_id = $request->data['contact_id'];
            $contact_address->street = "";
            $contact_address->city = "";
            $contact_address->province = "";
            $contact_address->country = "";
            $contact_address->zip_code = "";           
        } else {
            
            $contact_address->contact_id = $request->data['contact_id'];
            $contact_address->street = $request->data['contact_street'];
            $contact_address->city = $request->data['contact_city'];
            $contact_address->province = $request->data['contact_province'];
            $contact_address->country = $request->data['contact_country'];
            $contact_address->zip_code = $request->data['contact_zip_code']; 
        }

        $contact_company = new ContactCompany;
        if($request->data['contact_company_name'] == NULL) {
            $contact_company->contact_id = $request->data['contact_id'];
            $contact_company->contact_company_name = "";
            $contact_company->contact_company_address = "";
            $contact_company->contact_company_email = "";
            $contact_company->contact_company_phone = "";
            $contact_company->contact_company_fax = "";
            $contact_company->contact_company_fiscalcode = "";
            $contact_company->contact_company_position = "";            
        } else {

            $contact_company->contact_id = $request->data['contact_id'];
            $contact_company->contact_company_name = $request->data['contact_company_name'];
            $contact_company->contact_company_address = $request->data['contact_company_address'];
            $contact_company->contact_company_email = $request->data['contact_company_email'];
            $contact_company->contact_company_phone = $request->data['contact_company_phone'];
            $contact_company->contact_company_fax = $request->data['contact_company_fax'];
            $contact_company->contact_company_fiscalcode = $request->data['contact_company_fiscalcode'];
            $contact_company->contact_company_position = $request->data['contact_company_position'];
    
        }

        $contact->save();
        $contact_address->save();
        $contact_company->save();
    }

    public function RemoveContact(Request $request)
    {
        $contact = Contact::where('contact_id', '=', $request->data);
        $contactAddress = ContactAdresses::where('contact_id', '=', $request->data);
        $contactCompany = ContactCompany::where('contact_id', '=', $request->data);
        $contact->delete();
        $contactAddress->delete();
        $contactCompany->delete();
    }

}
