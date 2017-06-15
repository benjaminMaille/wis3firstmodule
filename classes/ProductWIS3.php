<?php

class ProductWIS3 extends ObjectModel
{
    public $id_product;
    public $comment;

    public static $definition = array(
        'table' => 'wis3firstmodule',
        'primary' => 'id_wis3firstmodule',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'comment' =>    array('type' => self::TYPE_STRING),
        )
    );

    public static function getProductWIS3($id_product)
    {
        $collection = new PrestaShopCollection('ProductWIS3');
        $collection->where('id_product', '=', (int)$id_product);
        $result = $collection->getFirst();
        if ($result == false) {
            $result = new self();
            $result->id_product = (int)$id_product;
        }
        return $result;
    }
}