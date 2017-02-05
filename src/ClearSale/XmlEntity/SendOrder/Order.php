<?php

namespace ClearSale\XmlEntity\SendOrder;

use ClearSale\Exception\RequiredFieldException;
use DateTime;
use InvalidArgumentException;
use XMLWriter;

class Order
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';

    const ENTREGA_OUTROS = 0; //Outros
    const ENTREGA_NORMAL = 1; //Entrega Normal
    const ENTREGA_GARANTIDA = 2; //Entrega Garantida
    const ENTREGA_EXPRESSA_BRASIL = 3; //Entrega Expressa no Brasil
    const ENTREGA_EXPRESSA_SAO_PAULO = 4; //Entrega Expressa em São Paulo
    const ENTREGA_ANALISE_PASSAGEM_AEREA = 5; //Criticidade da Análise do Pedido utilizado para Passagens Aéreas
    const ENTREGA_ECONOMICA = 6; //Entrega Econômica
    const ENTREGA_AGENDADA = 7; //Entrega Agendada
    const ENTREGA_EMAIL = 8; //Entrega por e-mail
    const ENTREGA_IMPRESSAO = 9; //Entrega via impressão
    const ENTREGA_APLICATIVO = 10; //Entrega via aplicativo
    const ENTREGA_CORREIO = 11; //Entrega física via correio
    const ENTREGA_MOTOBOY = 12; //Entrega física via motoboy
    const ENTREGA_BILHETERIA = 13; //Retirada na bilheteria
    const ENTREGA_LOJA_PARCERIA = 14; //Retirada em loja parceira
    const ENTREGA_CARTAO_CREDITO = 15; //Próprio cartão de crédito é o ingresso

    private static $listShippingTypes = array(
        self::ENTREGA_OUTROS,
        self::ENTREGA_NORMAL,
        self::ENTREGA_GARANTIDA,
        self::ENTREGA_EXPRESSA_BRASIL,
        self::ENTREGA_EXPRESSA_SAO_PAULO,
        self::ENTREGA_ANALISE_PASSAGEM_AEREA,
        self::ENTREGA_ECONOMICA,
        self::ENTREGA_AGENDADA,
        self::ENTREGA_EMAIL,
        self::ENTREGA_IMPRESSAO,
        self::ENTREGA_APLICATIVO,
        self::ENTREGA_CORREIO,
        self::ENTREGA_MOTOBOY,
        self::ENTREGA_BILHETERIA,
        self::ENTREGA_LOJA_PARCERIA,
        self::ENTREGA_CARTAO_CREDITO
    );

    const STATUS_NOVO = 0; //Novo (Será Analisado pela ClearSale)
    const STATUS_APROVADO = 9; //Aprovado (irá ao ClearSale já aprovado e não será analisado)
    const STATUS_CANCELADO = 41; //Cancelado pelo Cliente (irá para ClearSale já cancelado e não será analisado)
    const STATUS_REPROVADO = 45; //Reprovado (Irá para ClearSale já reprovado e não será analisado)

    private static $listStatusTypes = array(
        self::STATUS_NOVO,
        self::STATUS_APROVADO,
        self::STATUS_CANCELADO,
        self::STATUS_REPROVADO
    );

    const TICKET_FULL = 12;
    const TICKET_ONLINE_COM_CS = 13;
    const TICKET_ONLINE_SEM_CS = 14;
    const TICKET_ONLINE_SEM_QUIZ_COM_CS = 15;
    const TICKET_ONLINE_SEM_QUIZ_SEM_CS = 16;

    private static $listProducts = array(
        self::TICKET_FULL,
        self::TICKET_ONLINE_COM_CS,
        self::TICKET_ONLINE_SEM_CS,
        self::TICKET_ONLINE_SEM_QUIZ_COM_CS,
        self::TICKET_ONLINE_SEM_QUIZ_SEM_CS
    );

    private $id;
    private $fingerPrint;
    private $date;
    private $email;
    private $shippingPrice;
    private $totalItems;
    private $totalOrder;
    private $quantityItems;
    private $quantityPaymentTypes;
    private $ip;
    private $shippingType; //novo
    private $giftMessage;
    private $obs;
    private $status;
    private $origin;
    private $country;
    private $product;
    private $customerBillingData;
    private $customerShippingData;
    private $payments;
    private $tickets;

    /**
     * @param int $id
     * @param DateTime $date
     * @param string $email
     * @param float $totalItems
     * @param float $totalOrder
     * @param string $ip
     * @param string $origin
     * @param CustomerBillingData $customerBillingData
     * @param CustomerShippingData $customerShippingData
     * @param Payment $payment
     * @param Ticket $ticket
     * @return Order
     */

    public static function createEcommerceOrder(
        FingerPrint $fingerPrint,
        $id,
        DateTime $date,
        $email,
        $totalItems,
        $totalOrder,
        $ip,
        $origin,
        $product,
        CustomerBillingData $customerBillingData,
        CustomerShippingData $customerShippingData,
        Payment $payment,
        $tickets
    ) {
        return static::create(
            $fingerPrint,
            $id,
            $date,
            $email,
            $totalItems,
            $totalOrder,
            $ip,
            $origin,
            $product,
            $customerBillingData,
            $customerShippingData,
            $payment,
            $tickets
        );
    }

    private static function create(
        FingerPrint $fingerPrint,
        $id,
        DateTime $date,
        $email,
        $totalItems,
        $totalOrder,
        $ip,
        $origin,
        $product,
        CustomerBillingData $customerBillingData,
        CustomerShippingData $customerShippingData,
        Payment $payment,
        $tickets
    ) {
        $instance = new self();

        $instance->setFingerPrint($fingerPrint);
        $instance->setId($id);
        $instance->setDate($date);
        $instance->setEmail($email);
        $instance->setTotalItems($totalItems);
        $instance->setTotalOrder($totalOrder);
        $instance->setIp($ip);
        $instance->setOrigin($origin);
        $instance->setProduct($product);
        $instance->setBillingData($customerBillingData);
        $instance->setShippingData($customerShippingData);
        $instance->addPayment($payment);
        $instance->setTickets($tickets);
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

    public function getFingerPrint()
    {
        return $this->fingerPrint;
    }

    public function setFingerPrint(FingerPrint $fingerPrint)
    {
        $this->fingerPrint = $fingerPrint;

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
     * @return Order
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice($shippingPrice)
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }

    public function getTotalItems()
    {
        return $this->totalItems;
    }

    public function setTotalItems($totalItems)
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function getTotalOrder()
    {
        return $this->totalOrder;
    }

    public function setTotalOrder($totalOrder)
    {
        $this->totalOrder = $totalOrder;

        return $this;
    }

    public function getQuantityItems()
    {
        return $this->quantityItems;
    }

    public function setQuantityItems($quantityItems)
    {
        $this->quantityItems = $quantityItems;

        return $this;
    }

    public function getQuantityPaymentTypes()
    {
        return $this->quantityPaymentTypes;
    }

    public function setQuantityPaymentTypes($quantityPaymentTypes)
    {
        $this->quantityPaymentTypes = $quantityPaymentTypes;

        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    public function getShippingType()
    {
        return $this->shippingType;
    }

    public function setShippingType($shippingType)
    {
        $this->shippingType = $shippingType;

        return $this;
    }

    public function getGiftMessage()
    {
        return $this->giftMessage;
    }

    public function setGiftMessage($giftMessage)
    {
        $this->giftMessage = $giftMessage;

        return $this;
    }

    public function getObs()
    {
        return $this->obs;
    }

    public function setObs($obs)
    {
        $this->obs = $obs;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (!in_array($status, self::$listStatusTypes)) {
            throw new InvalidArgumentException(sprintf('Invalid status (%s)', $status));
        }

        $this->status = $status;

        return $this;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        if (!in_array($product, self::$listProducts)) {
            throw new InvalidArgumentException(sprintf('Invalid product type (%s)', $product));
        }

        $this->product = $product;

        return $this;
    }

    /**
     *
     * @return CustomerBillingData
     */
    public function getBillingData()
    {
        return $this->customerBillingData;
    }

    /**
     *
     * @param CustomerBillingData $customerBillingData
     * @return Order
     */
    public function setBillingData(CustomerBillingData $customerBillingData)
    {
        $this->customerBillingData = $customerBillingData;

        return $this;
    }

    /**
     *
     * @return CustomerShippingData
     */
    public function getShippingData()
    {
        return $this->customerShippingData;
    }

    /**
     *
     * @param CustomerShippingData $customerShippingData
     * @return Order
     */
    public function setShippingData(CustomerShippingData $customerShippingData)
    {
        $this->customerShippingData = $customerShippingData;

        return $this;
    }

    /**
     *
     * @return Payment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     *
     * @param int $index
     * @return Payment
     */
    public function getPayment($index)
    {
        return $this->payments[$index];
    }

    /**
     *
     * @param Payment[] $payments
     * @return Order
     */
    public function setPayments($payments)
    {
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }

        return $this;
    }

    /**
     *
     * @param Payment $payment
     * @return Order
     */
    public function addPayment(Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     *
     * @return Ticket[]
     */
    public function getTickets()
    {
        return $this->items;
    }

    /**
     *
     * @param Ticket[] $tickets
     * @return Order
     */
    public function setTickets($tickets)
    {
        foreach ($tickets as $ticket) {
            $this->addTicket($ticket);
        }

        return $this;
    }

    /**
     *
     * @param Ticket $ticket
     * @return Order
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        return $this;
    }

    public function toXML($prettyPrint = false)
    {
        $xml = new XMLWriter;
        $xml->openMemory();
        $xml->setIndent($prettyPrint);

        $xml->startElement("ClearSale");
        $xml->startElement("Orders");
        $xml->startElement("Order");

        if ($this->id) {
            $xml->writeElement("ID", $this->id);
        } else {
            throw new RequiredFieldException('Field ID of the Order object is required');
        }

        if ($this->fingerPrint) {
            $this->fingerPrint->toXML($xml);
        } else {
            throw new RequiredFieldException('Field FingerPrint of the Order object is required');
        }

        if ($this->date) {
            $xml->writeElement("Date", $this->date->format(Order::DATE_TIME_FORMAT));
        } else {
            throw new RequiredFieldException('Field Date of the Order object is required');
        }

        if ($this->email) {
            $xml->writeElement("Email", $this->email);
        } else {
            throw new RequiredFieldException('Field E-mail of the Order object is required');
        }

        if ($this->shippingPrice) {
            $xml->writeElement("ShippingPrice", $this->shippingPrice);
        }

        if ($this->totalItems) {
            $xml->writeElement("TotalItems", $this->totalItems);
        } else {
            throw new RequiredFieldException('Field TotalItems of the Order object is required');
        }

        if ($this->totalOrder) {
            $xml->writeElement("TotalOrder", $this->totalOrder);
        } else {
            throw new RequiredFieldException('Field TotalOrder of the Order object is required');
        }

        if ($this->quantityItems) {
            $xml->writeElement("QtyItems", $this->quantityItems);
        }

        if ($this->quantityPaymentTypes) {
            $xml->writeElement("QtyPaymentTypes", $this->quantityPaymentTypes);
        }

        if ($this->ip) {
            $xml->writeElement("IP", $this->ip);
        } else {
            throw new RequiredFieldException('Field IP of the Order object is required');
        }

        if ($this->shippingType) {
            $xml->writeElement("ShippingType", $this->shippingType);
        }

        if ($this->giftMessage) {
            $xml->writeElement("GiftMessage", $this->giftMessage);
        }

        if ($this->obs) {
            $xml->writeElement("Obs", $this->obs);
        }

        if ($this->status) {
            $xml->writeElement("Status", $this->status);
        }

        if ($this->origin) {
            $xml->writeElement("Origin", $this->origin);
        } else {
            throw new RequiredFieldException('Field Origin of the Order object is required');
        }

        if ($this->country) {
            $xml->writeElement("Country", $this->country);
        }

        if ($this->product) {
            $xml->writeElement("Product", $this->product);
        } else {
            throw new RequiredFieldException('Field BillingData of the Order object is required');
        }

        if ($this->customerBillingData) {
            $this->customerBillingData->toXML($xml);
        } else {
            throw new RequiredFieldException('Field BillingData of the Order object is required');
        }

        if ($this->customerShippingData) {
            $this->customerShippingData->toXML($xml);
        } else {
            throw new RequiredFieldException('Field ShippingData of the Order object is required');
        }

        if (count($this->payments) > 0) {
            $xml->startElement("Payments");

            foreach ($this->payments as $payment) {
                $payment->toXML($xml);
            }

            $xml->endElement();
        } else {
            throw new RequiredFieldException('Field Payments of the Order object is required');
        }

        if (count($this->tickets) > 0) {
            $xml->startElement("Tickets");

            foreach ($this->tickets as $ticket) {
                $ticket->toXML($xml);
            }

            $xml->endElement();
        } else {
            throw new RequiredFieldException('Field Tickets of the Order object is required');
        }

        $xml->endElement(); // Order
        $xml->endElement(); // Orders
        $xml->endElement(); // ClearSale

        return $xml->outputMemory(true);
    }
}