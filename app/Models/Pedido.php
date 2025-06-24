<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'producto', 'cantidad', 'direccion', 'telefono' , 'completado'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
