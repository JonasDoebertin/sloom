<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

class ZoomToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function fromAccessToken(
        AccessTokenInterface $token,
        ResourceOwnerInterface $resourceOwner,
        User $user
    ): self {
        return self::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'resource_owner_id' => Str::lower($resourceOwner->getId()),
                'access_token'      => $token->getToken(),
                'refresh_token'     => $token->getRefreshToken(),
                'expires_at'        => Carbon::createFromTimestampUTC($token->getExpires()),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
