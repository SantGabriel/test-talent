<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Transaction[] $transactions
 *
 * @method static Client find($id)
 * @method static Client findOrFail($id)
 * @method static Client first()
 * @method static Client firstOrFail()
 * @method static Builder where($column, $operator = null, $value = null)
 * @method static Client create(array $attributes = [])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @method bool save(array $options = [])
 */
class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the transactions for the client.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'client', 'id');
    }
}
