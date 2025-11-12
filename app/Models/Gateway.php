<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property bool $is_active
 * @property int $priority
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Transaction[] $transactions
 *
 * @method static Gateway find($id)
 * @method static Gateway findOrFail($id)
 * @method static Gateway first()
 * @method static Gateway firstOrFail()
 * @method static Builder where($column, $operator = null, $value = null)
 * @method static Gateway create(array $attributes = [])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @method bool save(array $options = [])
 */
class Gateway extends Model
{
    use HasFactory;

    protected $table = 'gateways';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_active',
        'priority',
        'alias'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the transactions for the gateway.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'gateway', 'id');
    }
}
