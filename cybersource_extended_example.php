<?php

class CybersourceExtended extends \CyberSource\CyberSource {

    public static $translation_fields = array(
        'product_name' => 'productName',
        'merchant_product_sku' => 'productSKU'
    );

    public static function factory ($merchant_id = null, $transaction_id = null, $environment = self::ENV_TEST) {
        $class = __CLASS__;
        $object = new $class( $merchant_id, $transaction_id, $environment );
        return $object;

    }

    protected function create_items ( $request ) {

        // there is no container for items, which annoys me
        $request->item = array();
        $i = 0;
        foreach ( $this->items as $item ) {
            $it = new stdClass();
            $it->unitPrice = $item['unitPrice'];
            $it->quantity = $item['quantity'];
            $it->id = $i;

            foreach ( self::$translation_fields as $k => $v ) {
                if ( isset( $item[$v] ) ) {
                    $it->$v = $item[$v];
                }
            }
            $request->item[] = $it;
            $i++;
        }

        return $request;

    }

    public function add_item ($price, $quantity = 1, $additional_fields = array()) {

        // we want to translate our custom fields 
        // without losing the ability for new ones to be passed in

        foreach ( self::$translation_fields as $k => $v ) {
            if ( isset( $additional_fields[ $k ] ) ) {
                // set the translated field
                $additional_fields[ $v ] = $additional_fields[ $k ];

                // unset the original field
                unset( $additional_fields[ $k ] );
            }
        }
        return parent::add_item($price, $quantity, $additional_fields);
    }

} 

// EOL
