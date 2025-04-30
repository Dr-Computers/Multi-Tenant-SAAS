<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestateInvoice extends Model
{
    use HasFactory;
    protected $table = 'realestate_invoices';

    protected $fillable = [
        'invoice_id',
        'property_id',
        'unit_id',
        'invoice_month',
        'end_date',
        'status',
        'notes',
        'parent_id',
        'invoice_period',
        'invoice_period_end_date',
        'created_in_month',
        'tenant_id',
        'invoice_type',
    ];
    public static $status = [
        'open' => 'Open',
        'paid' => 'Paid',
        'partial_paid' => 'Partial Paid',
    ];
    public function properties()
    {
        return $this->hasOne('App\Models\Property', 'id', 'property_id');
    }
    public function tenant()
    {
        return $this->hasOne('App\Models\user', 'id', 'tenant_id');
    }

    public function units()
    {
        return $this->hasOne('App\Models\PropertyUnit', 'id', 'unit_id');
    }

    public function types()
    {
        return $this->hasMany('App\Models\RealestateInvoiceItem', 'invoice_id', 'id');
    }
   

    public function payments()
    {
        return $this->hasMany('App\Models\InvoicePayment', 'invoice_id', 'id');
    }

    public function getInvoiceTotalWithoutVatAmount()
    {
        $invoiceTotalWithouVat = 0;
        foreach ($this->types as $type) {
            $invoiceTotalWithouVat += $type->amount;
        }
        return $invoiceTotalWithouVat;
    }
    public function getInvoiceVatAmount()
    {
        $invoiceVatAmount = 0;
        foreach ($this->types as $type) {
            $invoiceVatAmount += $type->tax_amount;
        }
        return $invoiceVatAmount;
    }
    public function getInvoiceSubTotalAmount()
    {
        $invoiceSubTotal = 0;
        foreach ($this->types as $type) {
            $invoiceSubTotal += $type->grand_amount;
        }
        return $invoiceSubTotal;
    }
    public function getInvoiceSubAmount()
    {
        $invoiceSubTotal = 0;
        foreach ($this->types as $type) {
            $invoiceSubTotal += $type->amount;
        }
        return $invoiceSubTotal;
    }
    public function getInvoiceSubTaxAmount()
    {
        $invoiceSubTotal = 0;
        foreach ($this->types as $type) {
            $invoiceSubTotal += $type->tax_amount;
        }
        return $invoiceSubTotal;
    }
    

    public function getInvoiceDueAmount()
    {
        $invoiceDue = 0;
        foreach ($this->payments as $payment) {
            $invoiceDue += $payment->amount;
        }
        return $this->getInvoiceSubTotalAmount() - $invoiceDue;
    }

    public function getInvoicePaidAmount()
    {
        $invoicePaid = 0;
        foreach ($this->payments as $payment) {
            $invoicePaid += $payment->amount;
        }
        return $invoicePaid;
    }
    public static function statusChange($invoice_id, $status)
    {
        $invoice = RealestateInvoice::find($invoice_id);
        $invoice->status = $status;
        $invoice->save();
        return $invoice;
    }

    // public static function addPayment($data)
    // {
    //     $payment = new InvoicePayment();
    //     $payment->invoice_id = $data['invoice_id'];
    //     $payment->transaction_id =$data['transaction_id'];
    //     $payment->payment_type = $data['payment_type'];
    //     $payment->amount = $data['amount'];
    //     $payment->payment_date = date('Y-m-d');
    //     $payment->receipt = !empty($data['receipt']) ? $data['receipt'] : '';
    //     $payment->notes = $data['notes'];;
    //     $payment->parent_id = parentId();
    //     $payment->save();
    //     $invoice = Invoice::find($data['invoice_id']);
    //     if ($invoice->getInvoiceDueAmount() <= 0) {
    //         $status = 'paid';
    //     } else {
    //         $status = 'partial_paid';
    //     }
    //     Invoice::statusChange($invoice->id, $status);
    // }
    // public function checkDetails()
    // {
    //     return $this->hasMany(CheckDetail::class, 'invoice_id'); // Replace 'invoice_id' with the actual foreign key in check_details table
    // }

}
