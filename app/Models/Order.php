<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'create_at',
        'table_id',
        'order_status_id',
        'work_shift_id',
        'price',
    ];

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
