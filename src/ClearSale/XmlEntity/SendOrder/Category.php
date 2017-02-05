<?php

namespace ClearSale\XmlEntity\SendOrder;

use ClearSale\Exception\RequiredFieldException;
use ClearSale\XmlEntity\XmlEntityInterface;
use InvalidArgumentException;
use XMLWriter;

class Category implements XmlEntityInterface
{
    private $name;
    private $quantity;
    private $categoryValue;

    /**
     * Criar Categoria com campos obrigatórios preenchidos
     *
     * @param string $name - Descrição da categoria do Ingresso comprado.
     * @param integer $quantity - Quantidade de ingressos comprados na categoria
     * @param float $categoryValue - Valor únitário do ingresso na categoria.
     *
     * @return Category
     */

    public static function create($name, $quantity, $categoryValue)
    {
        $instance = new self();

        $instance->setName($name);
        $instance->setQuantity($quantity);
        $instance->setCategoryValue($categoryValue);

        return $instance;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name is empty!');
        }

        $this->name = $name;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        if (!is_int($quantity)) {
            throw new InvalidArgumentException(sprintf('Invalid quantity', $quantity));
        }

        $this->quantity = $quantity;

        return $this;
    }

    public function getCategoryValue()
    {
        return $this->categoryValue;
    }

    public function setCategoryValue($categoryValue)
    {
        if (!is_float($categoryValue)) {
            throw new InvalidArgumentException(sprintf('Invalid categoryValue', $categoryValue));
        }

        $this->categoryValue = $categoryValue;

        return $this;
    }

    public function toXML(XMLWriter $xml)
    {
        $xml->startElement("Category");

        if ($this->name) {
            $xml->writeElement("Name", $this->name);
        }

        if ($this->quantity) {
            $xml->writeElement("Quantity", $this->quantity);
        }

        if ($this->categoryValue) {
            $xml->writeElement("CategoryValue", $this->categoryValue);
        }

        $xml->endElement();
    }
}
