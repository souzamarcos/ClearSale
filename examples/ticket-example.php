<?php

date_default_timezone_set('America/Sao_Paulo');

require __DIR__.'/../vendor/autoload.php';

use ClearSale\ClearSaleAnalysis;
use ClearSale\Environment\Production;
use ClearSale\Environment\Sandbox;
use ClearSale\XmlEntity\SendOrder\Address;
use ClearSale\XmlEntity\SendOrder\AbstractCustomer;
use ClearSale\XmlEntity\SendOrder\CustomerBillingData;
use ClearSale\XmlEntity\SendOrder\CustomerShippingData;
use ClearSale\XmlEntity\SendOrder\FingerPrint;
use ClearSale\XmlEntity\SendOrder\Item;
use ClearSale\XmlEntity\SendOrder\Ticket;
use ClearSale\XmlEntity\SendOrder\Event;
use ClearSale\XmlEntity\SendOrder\Person;
use ClearSale\XmlEntity\SendOrder\Category;
use ClearSale\XmlEntity\SendOrder\Order;
use ClearSale\XmlEntity\SendOrder\Payment;
use ClearSale\XmlEntity\SendOrder\Phone;
use ClearSale\XmlEntity\Response\PackageStatus;

try {
    // Dados da Integração com a ClearSale
    $entityCode = '88E12F32-9350-4F62-970B-4B6574CA375C';
    $environment = new Sandbox($entityCode);

    $fingerPrint = new FingerPrint(createSessionId());
    $orderId = createOrderId();
    $date = new \DateTime();
    $email = 'cliente@clearsale.com.br';
    $totalItems = 10.0;
    $totalOrder = 17.5;
    $quantityItems = 1;
    $quantityPaymentTypes = 1;
    $ip = '127.0.0.1';
    $shippingType = Order::ENTREGA_IMPRESSAO;
    $country = "Brasil";
    $status = Order::STATUS_NOVO;
    $product = Order::TICKET_FULL;
    $origin = 'WEB';

    $customerBillingData = createCustomerBillingData();
    $customerShippingData = createCustomerShippingData();
    $payment = Payment::create(Payment::CARTAO_CREDITO, new \DateTime(), 380.50);

    $tickets = array(
        Ticket::create(
            Event::create('Festa X','Casa de show Y',new \DateTime('2018-01-01')),
            Person::create('Fulano da Silva','12345678909')
        )
    );

    // Criar Pedido
    $order = Order::createEcommerceOrder(
        $fingerPrint,
        $orderId,
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
    )
    ->setFingerPrint($fingerPrint)
    ->setShippingPrice(0)
    ->setQuantityItems(1)
    ->setQuantityPaymentTypes(1)
    ->setShippingType($shippingType)
    ->setStatus($status)
    ->setCountry($country);

    // Enviar pedido para análise
    $clearSale = new ClearSaleAnalysis($environment);
    $response = $clearSale->analysis($order);

    // retorno
    $packageStatus = $clearSale->getPackageStatus();

    // Resultado da análise
    switch ($response)
    {
        case ClearSaleAnalysis::APROVADO:
            // Análise aprovou a cobrança, realizar o pagamento
            echo "ID: ".$packageStatus->getOrder()->getId()."</br>";
            echo "Status: ".$packageStatus->getOrder()->getStatus()."</br>";
            echo "Score: ".$packageStatus->getOrder()->getScore()."</br>";
            echo "QuizUrl: ".$packageStatus->getOrder()->getQuizUrl()."</br>";

            echo 'Aprovado</br>' . PHP_EOL;
            break;
        case ClearSaleAnalysis::REPROVADO:
            // Análise não aprovou a cobrança
            echo "ID: ".$packageStatus->getOrder()->getId()."</br>";
            echo "Status: ".$packageStatus->getOrder()->getStatus()."</br>";
            echo "Score: ".$packageStatus->getOrder()->getScore()."</br>";
            echo "QuizUrl: ".$packageStatus->getOrder()->getQuizUrl()."</br>";

            echo 'Reprovado' . PHP_EOL;
            break;
        case ClearSaleAnalysis::AGUARDANDO_APROVACAO:
            // Análise pendente de aprovação manual
            echo "ID: ".$packageStatus->getOrder()->getId()."</br>";
            echo "Status: ".$packageStatus->getOrder()->getStatus()."</br>";
            echo "Score: ".$packageStatus->getOrder()->getScore()."</br>";
            echo "QuizUrl: ".$packageStatus->getOrder()->getQuizUrl()."</br>";
            
            echo 'Aguardando aprovação manual' . PHP_EOL;
            break;
    }

} catch (Exception $e) {
    // Erro genérico da análise
    echo $e->getMessage();
}

function createOrderId()
{
    return sprintf('TEST-%s', createSessionId());
}

function createSessionId()
{
    return md5(uniqid(rand(), true));
}

function createCustomerBillingData()
{
    $id = '1';
    $personType=AbstractCustomer::TYPE_PESSOA_FISICA;
    $legalDocument = '12345678909';
    $name = 'Fulano da Silva';
    $address = createAddress();
    $phone = createPhone();
    $birthDate = new \DateTime('1980-01-01');
    $email = 'cliente@clearsale.com.br';
    $gender = 'M';

    return CustomerBillingData::create(
        $id,
        $personType,
        $legalDocument,
        $name,
        $address,
        $phone,
        $birthDate
    )
    ->setEmail($email)
    ->setGender($gender);
}

function createCustomerShippingData()
{
    $id = '1';
    $personType=AbstractCustomer::TYPE_PESSOA_FISICA;
    $legalDocument = '12345678909';
    $name = 'Fulano da Silva';
    $address = createAddress();
    $phone = createPhone();
    $birthDate = new \DateTime('1980-01-01');
    $email = 'cliente@clearsale.com.br';
    $gender = 'M';

    return CustomerShippingData::create(
        $id,
        $personType,
        $legalDocument,
        $name,
        $address,
        $phone,
        $birthDate
    )
    ->setEmail($email)
    ->setGender($gender);
}

function createAddress()
{
    $street = 'Rua José de Oliveira Coutinho';
    $number = 151;
    $county = 'Barra Funda';
    $country = 'Brasil';
    $city = 'São Paulo';
    $state = 'SP';
    $zip = '01144020';
    $complement = 'Apartamento 1010';

    return Address::create(
        $street,
        $number,
        $county,
        $country,
        $city,
        $state,
        $zip
    )->setComplement($complement);
}

function createPhone(){
    
    $ddi = '55';
    $ddd = '24';

    $phoneNumber = '123456789';
    return Phone::create(
        Phone::RESIDENCIAL,
        $ddd,
        $phoneNumber
    )->setDDI($ddi);
}