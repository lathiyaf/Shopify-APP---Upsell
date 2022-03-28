<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcAutomationBodyText extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'shop_id',
        'automation_id',
        'body_text',
        'unsubscribe_text',
        'subject',
        'email_preview',
        'header',
        'before_cart_body',
        'after_cart_body',
        'discount_banner_text',
        'footer',
        'cart_title',
        'product_description',
        'product_price',
        'product_qty',
        'product_total',
        'cart_total',
        'button_text',
        'headline',
        'url	',
    ];
}
