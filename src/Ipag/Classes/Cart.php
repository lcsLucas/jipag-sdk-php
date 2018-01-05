<?php

namespace Ipag\Classes;

use Ipag\Classes\Contracts\Emptiable;
use Ipag\Classes\Contracts\Serializable;
use Ipag\Classes\Traits\EmptiableTrait;

final class Cart implements Emptiable, Serializable
{
    use EmptiableTrait;

    /**
     * @var array of Product
     */
    private $products = [];

    /**
     * @return array of Product
     */
    public function getProducts()
    {
        if (empty($this->products)) {
            return [];
        }

        return $this->products;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @param array of Product $products
     */
    public function addProducts(array $products)
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }

        return $this;
    }

    public function serialize()
    {
        if ($this->isEmpty()) {
            return [];
        }

        return [
            'descricao_pedido' => urlencode(json_encode($this->serializeProducts())),
        ];
    }

    private function serializeProducts()
    {
        $_products = [];
        $productId = 1;

        foreach ($this->getProducts() as $product) {
            $_products[$productId++] = [
                'descr' => $product->getName(),
                'valor' => $product->getUnitPrice(),
                'quant' => $product->getQuantity(),
                'id'    => $product->getSku(),
            ];
        }

        return $_products;
    }
}
