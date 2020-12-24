<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuthData
 *
 * @property int $id
 * @property string $domain
 * @property string $auth_token
 * @property string $client_id
 * @property string $client_secret
 * @property string $refresh_token
 * @property string $redirect_uri
 * @property string $expires_in
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereRedirectUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthData whereUpdatedAt($value)
 * @method static firstOrNew(array $array)
 * @mixin \Eloquent
 */
class AuthData extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'accessToken', 'refreshToken', 'expires', 'baseDomain'];
}
