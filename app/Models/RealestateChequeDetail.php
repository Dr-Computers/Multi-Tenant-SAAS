<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealestateChequeDetail extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id',    'lease_id',    'cheque_number', 'cheque_date',    'payee', 'amount', 'bank_name',    'bank_account_number', 'routing_number', 'cheque_image', 'status', 'notes'];


    public function chequeImage(){
        return $this->hasOne(MediaFile::class,'id','cheque_image');
    }

    public function tenant(){
        return $this->hasOne(User::class,'id','tenant_id');
    }
}
