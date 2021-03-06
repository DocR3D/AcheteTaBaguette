<?php

require_once CHEMIN_RACINE_COMMUN . "/accesseur/BaseDeDonnee.php";
require_once CHEMIN_RACINE_COMMUN . "/modele/Categorie.php";

class AccesseurCategorie
{
    private const SUBSTITUT_ID_CATEGORIE = ":" . Categorie::ID_CATEGORIE;
    private const SUBSTITUT_LABEL = ":" . Categorie::LABEL;
    private const SUBSTITUT_DESCRIPTION = ":" . Categorie::DESCRIPTION;

    private static $RECUPERER_CATEGORIE_PAR_ID =
    "SELECT * " .
    "FROM CATEGORIE " .
    "WHERE " .
    Categorie::ID_CATEGORIE . "=" . self::SUBSTITUT_ID_CATEGORIE+";"
    ;

    private static $RECUPERER_LISTE_CATEGORIE =
        "SELECT * " .
        "FROM CATEGORIE;";

    private static $connexion = null;

    public function __construct()
    {
        if (!self::$connexion) {
            self::$connexion = BaseDeDonnee::getConnexion();
        }
    }

    public function recupererCategorieParId($idCategorie)
    {

        $requete = self::$connexion->prepare(self::$RECUPERER_CATEGORIE_PAR_ID);

        $requete->bindValue(
            self::SUBSTITUT_ID_CATEGORIE,
            $idCategorie,
            PDO::PARAM_INT);

        $requete->execute();

        if ($requete->rowCount() > 0) {
            $reponse = $requete->fetch();
            return $reponse->label;
        } else {
            echo "Aucune données trouvés !";
            return $reponse = ["isConnected" => false];
        }

    }

    public function recupererListeCategorie()
    {
        $listeCategorie = [];

        $requete = self::$connexion->prepare(self::$RECUPERER_LISTE_CATEGORIE);

        $requete->execute();

        $listeEnregistrement = $requete->fetchAll(PDO::FETCH_OBJ);

        foreach ($listeEnregistrement as $enregistrement) {

            $listeCategorie[] = new Categorie($enregistrement);

        }

        return $listeCategorie;
    }

}
