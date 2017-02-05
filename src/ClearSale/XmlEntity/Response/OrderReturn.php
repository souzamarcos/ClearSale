<?php

namespace ClearSale\XmlEntity\Response;

class OrderReturn
{
    /* Pedido foi aprovado automaticamente segundo parâmetros definidos na regra de aprovação automática. */
    const STATUS_SAIDA_APROVACAO_AUTOMATICA    = 'APA';
    /* Pedido aprovado manualmente por tomada de decisão de um analista. */
    const STATUS_SAIDA_APROVACAO_MANUAL        = 'APM';
    /* Pedido Reprovado sem Suspeita por falta de contato com o cliente dentro do período acordado e/ou políticas restritivas de CPF (Irregular, SUS ou Cancelados). */
    const STATUS_SAIDA_REPROVADA_SEM_SUSPEITA  = 'RPM';
    /* Pedido está em fila para análise */
    const STATUS_SAIDA_ANALISE_MANUAL          = 'AMA';
    /* Ocorreu um erro na integração do pedido, sendo necessário analisar um possível erro no XML enviado e após a correção reenvia-lo. */
    const STATUS_SAIDA_ERRO                    = 'ERR';
    /* Pedido importado e não classificado Score pela analisadora (processo que roda o Score de cada pedido). */
    const STATUS_SAIDA_NOVO                    = 'NVO';
    /* Pedido Suspenso por suspeita de fraude baseado no contato com o “cliente” ou ainda na base ClearSale. */
    const STATUS_SAIDA_SUSPENSAO_MANUAL        = 'SUS';
    /* Cancelado por solicitação do cliente ou duplicidade do pedido. */
    const STATUS_SAIDA_CANCELADO_PELO_CLIENTE  = 'CAN';
    /* Pedido imputado como Fraude Confirmada por contato com a administradora de cartão e/ou contato com titular do cartão ou CPF do cadastro que desconhecem a compra. */
    const STATUS_SAIDA_FRAUDE_CONFIRMADA       = 'FRD';
    /* Pedido Reprovado Automaticamente por algum tipo de Regra de Negócio que necessite aplicá-la (Obs: não usual e não recomendado). */
    const STATUS_SAIDA_REPROVACAO_AUTOMATICA   = 'RPA';

    /*Pedido não foi aprovado automaticamente e o comprador deve preencher um questionário de autovalidação de identididade.*/
    const STATUS_SAIDA_PENDENTE_AUTOVALIDACAO  = 'PAV';

    /*Pedido aprovado pelo questionário de autovalidação. Indica que o Quiz foi respondido corretamente pelo comprador. Utilizado para os produtos “Tickets Online com Gestão CS” e “Tickets Online sem Gestão CS”, quando o pedido utilizar o Quiz de autovalidação*/
    const STATUS_SAIDA_APROVADO_QUESTIONARIO   = 'APQ';

    /*Pedido reprovado pelo questionário de autovalidação. Indica que o Quiz foi respondido incorretamente pelo comprador. Utilizado para os produtos “Tickets Online com Gestão CS” e “Tickets Online sem Gestão CS”, quando o pedido utilizar o Quiz de autovalidação.*/
    const STATUS_SAIDA_REPROVADO_QUESTIONARIO  = 'RPQ';

    /*Pedido reprovado quando não existem dados suficientes para gerar o Quiz no momento em que o comprador acessar o questionário.*/
    const STATUS_SAIDA_QUESTIONARIO_NAO_GERADO = 'QNG';

    /* Pedido reprovado automaticamente por política estabelecida pelo cliente ou ClearSale. */
    const STATUS_SAIDA_REPROVACAO_POR_POLITICA = 'RPP';

    private $id;
    private $status;
    private $score;
    private $quizUrl;

    public function __construct($id, $status, $score)
    {
        $this->id     = $id;
        $this->status = $status;
        $this->score  = $score;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getScore()
    {
        return $this->score;
    }
    public function setQuizUrl($quizUrl)
    {
        $this->quizUrl = $quizUrl;
    }
    public function getQuizUrl()
    {
        return $this->quizUrl;
    }
}
