<?php

use App\Models\DiscountModel;

if (!function_exists('get_active_discount')) {
    function get_active_discount()
    {
        $discountModel = new DiscountModel();
        $today = date('Y-m-d');
        $discount = $discountModel->where('tanggal', $today)->first();
        return $discount ? $discount['nominal'] : 0;
    }
}
