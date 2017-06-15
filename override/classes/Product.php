<?php

class Product extends ProductCore
{
    public function getPriceWithoutReduct($notax = false, $id_product_attribute = false, $decimals = 6)
    {
        $wis3_notax = Configuration::get('WIS3FIRSTMODULE_DISPLAY_PRICE_WT') ? true : $notax;
        return parent::getPriceWithoutReduct($wis3_notax);
    }
    
    public static function getTaxCalculationMethod($id_customer = null)
    {
        if (Configuration::get('WIS3FIRSTMODULE_DISPLAY_PRICE_WT')) {
            self::$_taxCalculationMethod = PS_TAX_EXC;
            return (int)self::$_taxCalculationMethod;
        } else {
            return parent::getTaxCalculationMethod($id_customer);
        }
    }
}
