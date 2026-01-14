<?php

namespace App\Modules\Sales\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SaleDetail extends Model
{
    protected $table = 'sale_details';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
