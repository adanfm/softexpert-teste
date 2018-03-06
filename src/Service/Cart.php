<?php

namespace SoftExpert\Service;

class Cart implements CartInterface
{
    /**
     * @var null|Session
     */
    private $session = null;

    const ID_SESSION_CART = 'sfEx_cart';

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        if (is_null($this->session)) {
            $this->session = new Session();
        }
    }

    /**
     * @param array $item
     * @return void
     */
    public function addItem(array $item)
    {
        $cartItem = $this->getAll();

        //Parameter function
        /**
         *      'product_id' => 14,
                'product_name' => 'Vai testar',
                'qtd' => 12,
                'price' => 14.50,
                'category_id' => 1,
                'category_name' => 'Carnes',
                'tax_percentage' => 18.00
         */
        // Session save
        /**
         * ID_PRODUTO => [
         *      'product_id' => 14,
                'product_name' => 'Vai testar',
                'qtd' => 12,
                'price' => 14.50,
                'category_id' => 1,
                'category_name' => 'Carnes',
                'tax_percentage' => 18.00
         * ]
         */

        if (!array_key_exists($item['product_id'], $cartItem)) {
            $cartItem[$item['product_id']] = $item;
        } else {
            $cartItem[$item['product_id']]['qtd'] += $item['qtd'];
        }

        $cartItem[$item['product_id']]['total_price'] = ($item['price'] * $cartItem[$item['product_id']]['qtd']);
        $this->session->set(self::ID_SESSION_CART, $cartItem);
    }

    /**
     * @param $id
     * @return array|boolean
     */
    public function getItem($id)
    {
        $cartItem = $this->getAll();

        if (array_key_exists($id, $cartItem)) {
            $item = $cartItem[$id];
            return $item;
        }

        return false;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function removeItem($id)
    {
        $cartItem = $this->getAll();

        if (array_key_exists($id, $cartItem)) {
            unset($cartItem[$id]);
            $this->session->set(self::ID_SESSION_CART, $cartItem);
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    public function removeAll()
    {
        $this->session->set(self::ID_SESSION_CART, []);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $data = $this->session->get(self::ID_SESSION_CART);
        return !is_null($data) ? $data : [];
    }
}
