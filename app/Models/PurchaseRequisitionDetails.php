<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_requisition_id',
        'product_category_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function productlist()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'product_id', 'id');
    }
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
