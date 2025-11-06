<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
/**
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Transaction[] $transactions
 * @property-read Gateway gatewayClass
 * @property-read Client clientClass
 *
 *
 * @method static Transaction find($id)
 * @method static Transaction findOrFail($id)
 * @method static Transaction first()
 * @method static Transaction firstOrFail()
 * @method static Builder where($column, $operator = null, $value = null)
 * @method static Transaction create(array $attributes = [])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @method bool save(array $options = [])
 */
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client',
        'gateway',
        'external_id',
        'status',
        'amount',
        'card_last_numbers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the client that owns the transaction.
     */
    public function clientClass(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client', 'id');
    }

    /**
     * Get the gateway that processed the transaction.
     */
    public function gatewayClass(): BelongsTo
    {
        return $this->belongsTo(Gateway::class,'gateway', 'id');
    }

    /**
     * Get the products for the transaction.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'transaction_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
