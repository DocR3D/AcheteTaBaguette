<?php
/**
 * Created by PhpStorm.
 * User: AlexandreCM
 * Date: 2019-03-07
 * Time: 14:06
 */

class Panier
{
    public const EMAIL_CLIENT = "emailClient";
    public const PRIX_HT = "prixHT";
    public const PRIX_TTC = "prixTTC";

    private $emailClient;
    private $prixHT;
    private $prixTTC;
    private $listeProduit;

    public function __construct(object $attribut)
    {
        if(!is_object($attribut)) $attribut = (object)[];

        $this->setEmailClient($attribut->emailClient ?? "");
        $this->setListeProduit($attribut ?? null);
    }

    public function newFacture()
    {
        $attribut = (object)
        [
            "emailClient" => $this->getEmailClient(),
            self::PRIX_HT => $this->getPrixHT(),
            self::PRIX_TTC => $this->getPrixTTC()
        ];
        $facture = new Facture($attribut);
        foreach ($this->getListeProduit() as $article) {
            $item = (object)
            [
                "quantite" => $article->getQuantite(),
                "idProduit" => $article->getProduit()->getIdProduit()
            ];
            $facture->setListeProduit($item);
        }

        return $facture;
    }

    public function getEmailClient()
    {
        return $this->emailClient;
    }

    public function setEmailClient($emailClient)
    {
        $this->emailClient = filter_var($emailClient, FILTER_SANITIZE_STRING);
        return true;
    }

    public function getListeProduit()
    {
        return $this->listeProduit;
    }

    public function setListeProduit($article)
    {
        $article = new Article($article);
        $this->listeProduit[] = $article;
        $this->setPrixHT($article);
        $this->setPrixTTC();
    }

    public function getPrixHT()
    {
        return round($this->prixHT, 2);
    }

    public function setPrixHT(Article $article)
    {
        $this->prixHT += $article->getPrixTotal();
    }

    public function getPrixTTC()
    {
        return round($this->prixTTC, 2);
    }

    public function setPrixTTC()
    {
        $this->prixTTC = $this->getPrixHT() + ($this->getPrixHT() * (0.05 + 0.09975));
    }


}