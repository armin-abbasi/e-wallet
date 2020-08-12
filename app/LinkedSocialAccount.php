<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'linked_social_accounts';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'provider_id',
        'provider_name',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
