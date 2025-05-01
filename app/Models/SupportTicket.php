<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;
      
    public function users(){ 
    	return $this->hasOne(User::class,'id','user_id');
    }
    public function company(){ 
    	return $this->hasOne(User::class,'id','company_id');
    }

    public function attachments(){ 
    	return $this->hasMany(SupportTicketAttachment::class,'ticket_id');
    }
    
}
