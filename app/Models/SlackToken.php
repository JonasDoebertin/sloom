<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use League\OAuth2\Client\Token\AccessTokenInterface;

class SlackToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function fromAccessToken(AccessTokenInterface $token, User $user): self
    {
        return self::updateOrCreate(
            [
                'user_id'       => $user->id,
            ],
            [
                'access_token'  => $token->getToken(),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
