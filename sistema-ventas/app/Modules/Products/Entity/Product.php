<?php

namespace App\Modules\Products\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\ProductCategories\Entity\ProductCategory;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'price',
        'stock',
        'product_category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
