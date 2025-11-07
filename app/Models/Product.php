<?php

namespace App\Models;

use App\Enums\Role;
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
 * @property float $amount
 * @property bool $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Transaction[] $transactions
 *
 * @method static Product find($id)
 * @method static Product findOrFail($id)
 * @method static Product first()
 * @method static Product firstOrFail()
 * @method static Builder<Product> where($column, $operator = null, $value = null)
 * @method static Product create(array $attributes = [])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @method bool save(array $options = [])
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'amount',
        'active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'active' => 'bool',
    ];

    /**
     * Get the transaction products for the product.
     */
    public function transactionProducts(): HasMany
    {
        return $this->hasMany(TransactionProduct::class);
    }
}
