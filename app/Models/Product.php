<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'material',
        'tag',
        'image',
        'hover_image',
        'sizes',
        'featured',
        'in_stock',
        'stock_quantity',
        'stock_status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sizes' => 'array',
            'price' => 'decimal:2',
            'featured' => 'boolean',
            'in_stock' => 'boolean',
            'stock_quantity' => 'integer',
        ];
    }

    /**
     * Get computed stock badge details (label, sublabel, color, available).
     *
     * @return array{label: string, sublabel: string, color: string, available: bool}
     */
    public function getStockBadgeAttribute(): array
    {
        if ($this->stock_status === 'out_of_stock' || $this->stock_quantity <= 0 || ! $this->in_stock) {
            return [
                'label' => 'Out of Stock',
                'sublabel' => 'Bespoke Order Available',
                'color' => 'rose',
                'available' => false,
            ];
        }

        if ($this->stock_status === 'low_stock' || $this->stock_quantity <= 5) {
            return [
                'label' => 'Low in Stock',
                'sublabel' => "Only {$this->stock_quantity} left",
                'color' => 'amber',
                'available' => true,
            ];
        }

        return [
            'label' => 'In Stock',
            'sublabel' => "{$this->stock_quantity} in Atelier",
            'color' => 'emerald',
            'available' => true,
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
