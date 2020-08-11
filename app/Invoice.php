<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const TYPES = ['debit', 'credit'];

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'wallet_id',
        'amount',
        'type',
        'description',
    ];
}
