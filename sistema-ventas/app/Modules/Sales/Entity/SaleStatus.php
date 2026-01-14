<?php

namespace App\Modules\Sales\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase SaleStatus
 *
 * Representa el estado de una venta en el sistema.
 *
 * @package App\Modules\Sales\Entity
 */
class SaleStatus extends Model
{
    protected $table = 'sale_statuses';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'description'
    ];
}
