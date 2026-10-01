<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    // Asignamos el nombre de la tabla
    protected $table = 'users';

    // Declaramos los compos rellenables
    protected $fillable = [
        'username',
        'email',
        'password'
    ];

    // Declaramos los campos que no
    // se devolverán al usuario
    protected $hidden = [
        'password'
    ];
}
