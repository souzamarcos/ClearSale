# API ClearSale

**To read this document in English, please access the [README.eng.md](README.eng.md) file.**

[![Build Status](https://travis-ci.org/lucasmro/ClearSale.png)](https://travis-ci.org/lucasmro/ClearSale)
[![Latest Stable Version](https://poser.pugx.org/lucasmro/clearsale/v/stable)](https://packagist.org/packages/lucasmro/clearsale)
[![Total Downloads](https://poser.pugx.org/lucasmro/clearsale/downloads)](https://packagist.org/packages/lucasmro/clearsale)
[![Latest Unstable Version](https://poser.pugx.org/lucasmro/clearsale/v/unstable)](https://packagist.org/packages/lucasmro/clearsale)
[![License](https://poser.pugx.org/lucasmro/clearsale/license)](https://packagist.org/packages/lucasmro/clearsale)

API de integração com a ClearSale.

## O que é ClearSale?

A Clearsale é uma empresa brasileira para gestão de risco de fraude que atua no mundo físico e virtual, com soluções
para e-commerce, crédito, cobrança e recuperação de vendas.

## O que é a solução Ticket da ClearSale?

É uma solução antifraude desenvolvida para vendas de ingresso online.

## Requisitos

PHP 5.3+

## Instalação

A maneira mais fácil de instalar a biblioteca é através do [Composer](http://getcomposer.org/).

```JSON
{
    "require": {
        "lucasmro/clearsale": "dev-master"
    }
}
```

## Fluxo de integração

Este fluxo é responsável por demonstrar a integração entre o cliente e a ClearSale:

    Loja                                                                 ClearSale
     |                                                                       |
     |----- (A) solicitação de análise de risco (sendOrders) --------------->|
     |                                                                       | (B) realiza processamento
     |<---- (C) envia resposta ----------------------------------------------|
     |                                                                       |
     |----- (D) realiza a cobrança / cancela a compra / tenta novamente ---->|

* (A) A loja realiza uma solicitação de análise de risco, informando os dados da compra e do comprador.
* (B) A ClearSale processa a requisição.
* (C) A ClearSale responde a requisição.
* (D) Caso a resposta de (C) seja aprovada, a loja deverá realizar a cobrança.
* (D) Caso a resposta de (C) seja reprovada, a loja não deverá realizar a cobrança.
* (D) Caso a resposta de (C) seja aguardando aprovação, a loja deverá realizar novas consultas na plataforma na
ClearSale até que o status da análise mude para aprovado ou reprovado.

## Utilização

Será necessário possuir o EntityCode fornecido pela ClearSale para poder realizar as requisições nos ambientes de
homologação e produção.

O trecho de código abaixo é um exemplo básico de como realizar a solicitação de análise de risco:

```PHP

try {

    // Criar Pedido
    $order = \ClearSale\Order::createEcommerceOrder(
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
    ->setShippingPrice($shippingPrice)
    ->setQuantityItems($quantityItems)
    ->setQuantityPaymentTypes($quantityPaymentTypes)
    ->setShippingType($shippingType)
    ->setStatus($status)
    ->setCountry($country);

    // Definir ambiente
    $environment = new \ClearSale\Environment\Sandbox('<CLEARSALE_ENTITY_CODE>');

    // Solicitação de análise
    $clearSale = new \ClearSale\ClearSaleAnalysis($environment);
    $response = $clearSale->analysis($order);

    // Salvar informações do pedido retornado após análise
    $packageStatus = $clearSale->getPackageStatus();

    // Resultado da análise
    switch ($response)
    {
        case \ClearSale\ClearSaleAnalysis::APROVADO:
            // Análise aprovou a cobrança, realizar o pagamento
            var_dump($packageStatus);
            break;

        case \ClearSale\ClearSaleAnalysis::REPROVADO:
            // Análise não aprovou a cobrança
            var_dump($packageStatus);
            break;

        case \ClearSale\ClearSaleAnalysis::AGUARDANDO_APROVACAO:
            // Análise pendente de aprovação manual
            var_dump($packageStatus);
            break;

        default:
            break;
    }

} catch (\Exception $e) {
    // Erro genérico da análise
}
```

Após realizar a requisição de cobrança, deve-se informar a ClearSale sobre o status do processamento do pagamento.

* Se a cobrança for autorizada:

```PHP
$clearSale->updateOrderStatusId($orderId, \ClearSale\ClearSaleAnalysis::APROVADO);
```

* Se a cobrança não for autorizada:

```PHP
$clearSale->updateOrderStatusId($orderId, \ClearSale\ClearSaleAnalysis::REPROVADO);
```

## Documentação

Você pode encontrar a documentação de integração da ClearSale Ticket no diretório [docs](docs).

## Exemplos

Você pode encontrar o exemplo pronto para uso no diretório [examples](examples).

* [Exemplo de pedido de Ticket](examples/ticket-example.php)
