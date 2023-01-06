<?php

    namespace App\Models;

    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class AppointmentDocuments extends Authenticatable
    {
  
        protected $table = 'appointment_documents';
        public $timestamps = false;
        

       protected $primaryKey = 'id';
      
    
    }
