<?php

namespace ClearSale\XmlEntity\SendOrder;

use ClearSale\Exception\RequiredFieldException;
use ClearSale\XmlEntity\XmlEntityInterface;
use DateTime;
use InvalidArgumentException;
use XMLWriter;

class Event implements XmlEntityInterface
{
    private $id;
    private $name;
    private $local;
    private $date;
    private $quantityTicketSale;
    private $quantityEventHouse;

    /**
     * Criar Evento com campos obrigatórios preenchidos
     *
     * @param string $id - Código do Evento
     * @param string $name - Nome do Evento
     * @param string $local - Local do Evento
     * @param DateTime $date - Data
     * @param float $quantityTicketSale - Quantidade de Ingresso à venda
     * @param float $quantityEventHouse - Quantidade de vezes que o evento será realizado na casa.
     *
     * @return Event
     */

    public static function create($name, $local, DateTime $date)
    {
        $instance = new self();

        $instance->setName($name);
        $instance->setLocal($local);
        $instance->setDate($date);

        return $instance;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
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

    public function getLocal()
    {
        return $this->local;
    }

    public function setLocal($local)
    {
        if (empty($local)) {
            throw new InvalidArgumentException('Local is empty!');
        }

        $this->local = $local;

        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @param DateTime $date
     * @return Event
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getQuantityTicketSale()
    {
        return $this->quantityTicketSale;
    }

    /**
     *
     * @param float $quantityEventHouse
     * @return float
     */
    public function setQuantityTicketSale($quantityTicketSale)
    {
        $this->quantityTicketSale = $quantityTicketSale;

        return $this;
    }

    /**
     *
     * @return float
     */
    public function getQuantityEventHouse()
    {
        return $this->quantityEventHouse;
    }

    /**
     *
     * @param float $quantityEventHouse
     * @return float
     */
    public function setQuantityEventHouse($quantityEventHouse)
    {
        $this->quantityEventHouse = $quantityEventHouse;

        return $this;
    }

    public function toXML(XMLWriter $xml)
    {
        $xml->startElement("Event");

        if ($this->id) {
            $xml->writeElement("ID", $this->id);
        }

        if ($this->name) {
            $xml->writeElement("Name", $this->name);
        } else {
            throw new RequiredFieldException('Field Name of the Event object is required');
        }

        if ($this->local) {
            $xml->writeElement("Local", $this->local);
        } else {
            throw new RequiredFieldException('Field Local of the Event object is required');
        }

        if ($this->date) {
            $xml->writeElement("Date", $this->date->format(Order::DATE_TIME_FORMAT));
        } else {
            throw new RequiredFieldException('Field Date of the Event object is required');
        }

        if ($this->quantityTicketSale) {
            $xml->writeElement("QuantityTicketSale", $this->quantityTicketSale);
        }

        if ($this->quantityEventHouse) {
            $xml->writeElement("QuantityEventHouse", $this->quantityEventHouse);
        }

        $xml->endElement();
    }
}
