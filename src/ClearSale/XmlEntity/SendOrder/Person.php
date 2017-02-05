<?php

namespace ClearSale\XmlEntity\SendOrder;

use ClearSale\Exception\RequiredFieldException;
use ClearSale\XmlEntity\XmlEntityInterface;
use InvalidArgumentException;
use XMLWriter;

class Person implements XmlEntityInterface
{
    private $name;
    private $legalDocument;

    /**
     * Criar Pessoa com campos obrigatórios preenchidos
     *
     * @param string $name - Nome da pessoa
     * @param string $legalDocument - Documento da pessoa
     *
     * @return Person
     */
    public static function create($name, $legalDocument)
    {
        $instance = new self();

        $instance->setName($name);
        $instance->setLegalDocument($legalDocument);

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

    public function getLegalDocument()
    {
        return $this->legalDocument;
    }

    public function setLegalDocument($legalDocument)
    {

        $this->legalDocument = $legalDocument;

        return $this;
    }

    public function toXML(XMLWriter $xml)
    {
        $xml->startElement("Person");

        if ($this->name) {
            $xml->writeElement("Name", $this->name);
        } else {
            throw new RequiredFieldException('Field Name of the Person object is required');
        }

        if ($this->legalDocument) {
            $xml->writeElement("LegalDocument", $this->legalDocument);
        }

        $xml->endElement();
    }
}
