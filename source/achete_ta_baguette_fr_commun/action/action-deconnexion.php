<?php
/**
 * Created by PhpStorm.
 * User: yonne
 * Date: 18/02/2019
 * Time: 16:44
 */
if(isset($_POST["action-deconnexion"])) {
    session_destroy();
    header("Location: /boutique");
}

?>