<?php
/**
 * Created by PhpStorm.
 * User: AlexandreCM
 * Date: 2019-03-17
 * Time: 20:15
 */

require_once(CHEMIN_RACINE_COMMUN . "/modele/Panier.php");
require_once(CHEMIN_RACINE_COMMUN . "/modele/Facture.php");
require_once(CHEMIN_RACINE_COMMUN . "/accesseur/AccesseurPanier.php");
require_once(CHEMIN_RACINE_COMMUN . "/accesseur/AccesseurProduit.php");
require_once(CHEMIN_RACINE_COMMUN . "/accesseur/AccesseurFacture.php");
require_once(CHEMIN_RACINE_UTILISATEUR .'/vendor/autoload.php');

$page = (object)
[
    "titre" => "Merci",
    "message" => ""
];


$ids = require(CHEMIN_RACINE_UTILISATEUR .'/configuration/configuration-paypal.php');
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $ids['id'],
        $ids['secret']
    )
);


$payment = \PayPal\Api\Payment::get($_GET['paymentId'], $apiContext);

$execution = (new \PayPal\Api\PaymentExecution())
    ->setPayerId($_GET['PayerID'])
    ->setTransactions($payment->getTransactions());

try {
    $payment->execute($execution, $apiContext);
    $page->message = 'Merci pour votre paiement';
//    print_r(json_decode($payment));
} catch (\PayPal\Exception\PayPalConnectionException $e) {
    header('HTTP 500 Internal Server Error', true, 500);
    var_dump(json_decode($e->getData()));
}

$accesseurPanier = new AccesseurPanier();
$panier = $accesseurPanier->recupererPanier($_SESSION[Client::EMAIL]);
//print_r($panier);

$facture = $panier->newFacture();
//print_r($facture);
$accesseurFacture = new AccesseurFacture();
if ($facture = $accesseurFacture->ajouterFacture($facture)) $accesseurPanier->ViderPanier($panier);
//print_r($facture);