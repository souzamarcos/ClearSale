<?php

namespace ClearSale\XmlEntity\SendOrder;

use ClearSale\Exception\RequiredFieldException;
use ClearSale\XmlEntity\XmlEntityInterface;
use InvalidArgumentException;
use XMLWriter;

class Ticket implements XmlEntityInterface
{
    private $convenienceFeeValue;
    private $quantityFull;
    private $quantityHalf;
    private $event;
    private $people;
    private $categories;

    /**
     * Criar Item com campos obrigatórios preenchidos
     *
     * @param integer $convenienceFeeValue - Código do Produto
     * @param integer $quantityFull - Quantidade de ingressos com valor integral.
     * @param integer $quantityHalf - Quantidade de ingresso com desconto (meia entrada).
     * @param Event $event - Evento do ticket
     * @param Person[] $person - Pessoa que comprou o ticket
     * @param Category[] $category - Cateogrias
     *
     * @return Item
     */

    public static function create($event, $person) {
        $instance = new self();

        $instance->setEvent($event);
        $instance->addPerson($person);

        return $instance;
    }

    public function getConvenienceFeeValue()
    {
        return $this->convenienceFeeValue;
    }

    public function setConvenienceFeeValue($convenienceFeeValue)
    {
        if (!is_float($convenienceFeeValue)) {
            throw new InvalidArgumentException(sprintf('Invalid convenienceFeeValue', $convenienceFeeValue));
        }

        $this->convenienceFeeValue = $convenienceFeeValue;

        return $this;
    }

    public function getQuantityFull()
    {
        return $this->quantityFull;
    }

    public function setQuantityFull($quantityFull)
    {
        if (!is_int($quantityFull)) {
            throw new InvalidArgumentException(sprintf('Invalid quantityFull', $quantityFull));
        }

        $this->quantityFull = $quantityFull;

        return $this;
    }

    public function getQuantityHalf()
    {
        return $this->quantityHalf;
    }

    public function setQuantityHalf($quantityHalf)
    {
        if (!is_int($quantityHalf)) {
            throw new InvalidArgumentException(sprintf('Invalid quantityFull', $quantityHalf));
        }

        $this->quantityHalf = $quantityHalf;

        return $this;
    }

    /**
     *
     * @param Event $event
     * @return Ticket
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     *
     * @param Person $person
     * @return Ticket
     */
    public function addPerson(Person $person)
    {
        $this->people[] = $person;

        return $this;
    }

    /**
     *
     * @param Category $category
     * @return Ticket
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }


    public function toXML(XMLWriter $xml)
    {
        $xml->startElement("Ticket");

        if ($this->convenienceFeeValue) {
            $xml->writeElement("ConvenienceFeeValue", $this->convenienceFeeValue);
        }

        if ($this->quantityFull) {
            $xml->writeElement("QuantityFull", $this->quantityFull);
        }

        if ($this->quantityHalf) {
            $xml->writeElement("QuantityHalf", $this->quantityHalf);
        }

        if ($this->event) {
            $this->event->toXML($xml);
        }

        if (count($this->people) > 0) {
            $xml->startElement("People");

            foreach ($this->people as $person) {
                $person->toXML($xml);
            }

            $xml->endElement();
        }

        if (count($this->categories) > 0) {
            $xml->startElement("Categories");

            foreach ($this->categories as $category) {
                $category->toXML($xml);
            }

            $xml->endElement();
        }

        $xml->endElement();
    }
}
