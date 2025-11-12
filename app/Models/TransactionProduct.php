<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property int $transaction_id
 * @property int $product_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Product product
 * @property-read Transaction transaction
 *
 *
 * @method static Builder find($id)
 * @method static Builder findOrFail($id)
 * @method static Builder first()
 * @method static Builder firstOrFail()
 * @method static Builder where($column, $operator = null, $value = null)
 * @method static Builder create(array $attributes = [])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @method bool save(array $options = [])
 */
class TransactionProduct extends Model
{
    use HasFactory;

    protected $table = 'transaction_products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the transaction that owns the transaction product.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the product that owns the transaction product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
