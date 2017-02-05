<?php

namespace ClearSale\XmlEntity\SendOrder;

use ClearSale\Exception\RequiredFieldException;
use ClearSale\Type\Currency;
use ClearSale\XmlEntity\XmlEntityInterface;
use DateTime;
use InvalidArgumentException;
use XMLWriter;

class Payment implements XmlEntityInterface
{
    const CARTAO_CREDITO           = 1;  //Cartão de Crédito
    const BOLETO_BANCARIO          = 2;  //Boleto Bancário
    const DEBITO_BANCARIO          = 3;  //Débito bancário
    const DEBITO_BANCARIO_DINHEIRO = 4;  //Débito Bancário – Dinheiro
    const DEBITO_BANCARIO_CHEQUE   = 5;  //Débito Bancário – Cheque
    const TRANSFERENCIA_BANCARIA   = 6;  //Transferência Bancária
    const SEDEX_A_COBRAR           = 7;  //Sedex a Cobrar
    const CHEQUE                   = 8;  //Cheque
    const DINHEIRO                 = 9;  //Dinheiro
    const FINANCIAMENTO            = 10; //Financiamento
    const FATURA                   = 11; //Fatura
    const CUPOM                    = 12; //Cupom
    const MULTICHEQUE              = 13; //Multicheque
    const OUTROS                   = 14; //Outros
    const VALE                     = 16; //Vale
    const DEBITO_PARCELADO         = 17; //Débito Parcelado
    const VALE_DESCONTO            = 18; //Vale Desconto

    private static $paymentTypes = array(
        self::CARTAO_CREDITO,
        self::BOLETO_BANCARIO,
        self::DEBITO_BANCARIO,
        self::DEBITO_BANCARIO_DINHEIRO,
        self::DEBITO_BANCARIO_CHEQUE,
        self::TRANSFERENCIA_BANCARIA,
        self::SEDEX_A_COBRAR,
        self::CHEQUE,
        self::DINHEIRO,
        self::FINANCIAMENTO,
        self::FATURA,
        self::CUPOM,
        self::MULTICHEQUE,
        self::OUTROS,
        self::VALE,
        self::DEBITO_PARCELADO,
        self::VALE_DESCONTO
    );

    const BANDEIRA_DINERS           = 1;
    const BANDEIRA_MASTERCARD       = 2;
    const BANDEIRA_VISA             = 3;
    const BANDEIRA_OUTROS           = 4;
    const BANDEIRA_AMERICAN_EXPRESS = 5;
    const BANDEIRA_HIPERCARD        = 6;
    const BANDEIRA_AURA             = 7;

    private static $cards = array(
        self::BANDEIRA_DINERS,
        self::BANDEIRA_MASTERCARD,
        self::BANDEIRA_VISA,
        self::BANDEIRA_OUTROS,
        self::BANDEIRA_AMERICAN_EXPRESS,
        self::BANDEIRA_HIPERCARD,
        self::BANDEIRA_AURA,
    );

    private $sequential;
    private $date;
    private $amount;
    private $type;
    private $qtyInstallments;
    private $interest;
    private $interestValue;
   
    private $cardNumber;
    private $cardBin;
    private $cardEndNumber;
    private $cardType;
    private $cardExpirationDate;

    private $name;
    private $legalDocument;
    private $address;
    private $nsu;
    private $currency;

    public static function create($type, DateTime $date, $amount)
    {
        $instance = new self();

        $instance
            ->setType($type)
            ->setDate($date)
            ->setAmount($amount);

        return $instance;
    }

    public function getSequential()
    {
        return $this->sequential;
    }

    public function setSequential($sequential)
    {
        $this->sequential = $sequential;

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
     * @return Payment
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

        public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if (!array_key_exists($type, self::$paymentTypes)) {
            throw new InvalidArgumentException(sprintf('Invalid payment type (%s)', $type));
        }

        $this->type = $type;

        return $this;
    }

    public function getQtyInstallments()
    {
        return $this->qtyInstallments;
    }

    public function setQtyInstallments($qtyInstallments)
    {
        $this->qtyInstallments = $qtyInstallments;

        return $this;
    }

    public function getInterest()
    {
        return $this->interest;
    }

    public function setInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    public function getInterestValue()
    {
        return $this->interestValue;
    }

    public function setInterestValue($interestValue)
    {
        $this->interestValue = $interestValue;

        return $this;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardBin()
    {
        return $this->cardBin;
    }

    public function setCardBin($cardBin)
    {
        $this->cardBin = $cardBin;

        return $this;
    }

    public function getCardEndNumber()
    {
        return $this->cardEndNumber;
    }

    public function setCardEndNumber($cardEndNumber)
    {
        $this->cardEndNumber = $cardEndNumber;

        return $this;
    }

    public function getCardType()
    {
        return $this->cardType;
    }

    public function setCardType($cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getCardExpirationDate()
    {
        return $this->cardExpirationDate;
    }

    public function setCardExpirationDate($cardExpirationDate)
    {
        $this->cardExpirationDate = $cardExpirationDate;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
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

    public function getNsu()
    {
        return $this->nsu;
    }

    public function setNsu($nsu)
    {
        $this->nsu = $nsu;

        return $this;
    }

    public function toXML(XMLWriter $xml)
    {
        $xml->startElement("Payment");

        if ($this->sequential) {
            $xml->writeElement("Sequential", $this->sequential);
        }

        if ($this->date) {
            $xml->writeElement("Date", $this->date->format(Order::DATE_TIME_FORMAT));
        } else {
            throw new RequiredFieldException('Field Date of the Payment object is required');
        }

        if ($this->amount) {
            $xml->writeElement("Amount", $this->amount);
        } else {
            throw new RequiredFieldException('Field Amount of the Payment object is required');
        }

        if ($this->type) {
            $xml->writeElement("PaymentTypeID", $this->type);
        } else {
            throw new RequiredFieldException('Field PaymentTypeID of the Payment object is required');
        }

        if ($this->qtyInstallments) {
            $xml->writeElement("QtyInstallments", $this->qtyInstallments);
        }

        if ($this->interest) {
            $xml->writeElement("Interest", $this->interest);
        }

        if ($this->interestValue) {
            $xml->writeElement("InterestValue", $this->interestValue);
        }

        if ($this->cardNumber) {
            $xml->writeElement("CardNumber", $this->cardNumber);
        }

        if ($this->cardBin) {
            $xml->writeElement("CardBin", $this->cardBin);
        }

        if ($this->cardEndNumber) {
            $xml->writeElement("CardEndNumber", $this->cardEndNumber);
        }

        if ($this->cardType) {
            $xml->writeElement("CardType", $this->cardType);
        }

        if ($this->cardExpirationDate) {
            $xml->writeElement("CardExpirationDate", $this->cardExpirationDate);
        }

        if ($this->name) {
            $xml->writeElement("Name", $this->name);
        }

        if ($this->legalDocument) {
            $xml->writeElement("LegalDocument", $this->legalDocument);
        }

        if ($this->address) {
            $this->address->toXML($xml);
        }

        if ($this->nsu) {
            $xml->writeElement("Nsu", $this->nsu);
        }

        if ($this->currency) {
            $xml->writeElement("Currency", $this->currency);
        }

        $xml->endElement();
    }
}
