<?php

define("ROOT", dirname(__FILE__, 4));

const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400;
const HTTP_METHOD_NOT_ALLOWED = 405;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
    require_once(ROOT . "/controllers/Autoloader.php");
    \Controllers\Autoloader::load();

    if (!empty($_POST['action'])) {
        /**
         *  Modifier la configuration serveur
         */
        if (
            $_POST['action'] == "applyServerConfiguration"
            and !empty($_POST['serverPackageType'])
            and !empty($_POST['serverManageClientConf'])
            and !empty($_POST['serverManageClientRepos'])
        ) {
            /**
             *  Récupération des paramètres envoyés
             */
            $serverPackageType = $_POST['serverPackageType'];
            $serverManageClientConf = $_POST['serverManageClientConf'];
            $serverManageClientRepos = $_POST['serverManageClientRepos'];

            /**
             *  Sauvegarde des paramètres en base de données
             */
            $myprofile = new \Controllers\Profile();

            /**
             *  Tentative de création du nouveau profil
             */
            try {
                $myprofile->setServerConfiguration($serverPackageType, $serverManageClientConf, $serverManageClientRepos);
            } catch (\Exception $e) {
                response(HTTP_BAD_REQUEST, $e->getMessage());
            }

            /**
             *  Si il n'y a pas eu d'erreur
             */
            response(HTTP_OK, "Server configuration has been saved");
        }

        /*
         *  Créer un nouveau profil
         */
        if ($_POST['action'] == "newProfile" and !empty($_POST['name'])) {
            $myprofile = new \Controllers\Profile();

            /**
             *  Tentative de création du nouveau profil
             */
            try {
                $myprofile->new($_POST['name']);
            } catch (\Exception $e) {
                response(HTTP_BAD_REQUEST, $e->getMessage());
            }

            /**
             *  Si il n'y a pas eu d'erreur
             */
            response(HTTP_OK, "<b>" . $_POST['name'] . "</b> profile has been created");
        }

        /**
         *  Supprimer un profil
         */
        if ($_POST['action'] == "deleteProfile" and !empty($_POST['name'])) {
            $myprofile = new \Controllers\Profile();

            /**
             *  Tentative de suppression du profil
             */
            try {
                $myprofile->delete($_POST['name']);
            } catch (\Exception $e) {
                response(HTTP_BAD_REQUEST, $e->getMessage());
            }

            /**
             *  Si il n'y a pas eu d'erreur
             */
            response(HTTP_OK, "<b>" . $_POST['name'] . "</b> profile has been deleted");
        }

        /**
         *  Renommer un profil
         */
        if ($_POST['action'] == "renameProfile" and !empty($_POST['name']) and !empty($_POST['newname'])) {
            $myprofile = new \Controllers\Profile();

            /**
             *  Tentative de renommage du profil
             */
            try {
                $myprofile->rename($_POST['name'], $_POST['newname']);
            } catch (\Exception $e) {
                response(HTTP_BAD_REQUEST, $e->getMessage());
            }

            /**
             *  Si il n'y a pas eu d'erreur
             */
            response(HTTP_OK, "<b>" . $_POST['name'] . "</b> profile has been renamed to <b>" . $_POST['newname'] . "</b>");
        }

        /**
         *  Dupliquer un profil
         */
        if ($_POST['action'] == "duplicateProfile" and !empty($_POST['name'])) {
            $myprofile = new \Controllers\Profile();

            /**
             *  Tentative de renommage du profil
             */
            try {
                $myprofile->duplicate($_POST['name']);
            } catch (\Exception $e) {
                response(HTTP_BAD_REQUEST, $e->getMessage());
            }

            /**
             *  Si il n'y a pas eu d'erreur
             */
            response(HTTP_OK, "<b>" . $_POST['name'] . "</b> profile has been duplicated");
        }

        /**
         *  Configurer un profil
         */
        if (
            $_POST['action'] == "configureProfile"
            and !empty($_POST['name'])
            and !empty($_POST['linupdateGetPkgConf'])
            and !empty($_POST['linupdateGetReposConf'])
        ) {
            $name = $_POST['name'];
            $linupdateGetPkgConf = $_POST['linupdateGetPkgConf'];
            $linupdateGetReposConf = $_POST['linupdateGetReposConf'];

            /**
             *  Si aucun repo n'a été transmis, cela signifie que l'utilisateur souhaite vider la liste, on set $reposList à vide
             */
            if (empty($_POST['reposList'])) {
                $reposList = array();
            } else {
                $reposList = $_POST['reposList'];
            }

            /**
             *  Si aucun paquet 'toute version' n'a été transmis, cela signifie que l'utilisateur souhaite vider la liste, on set $packagesExcluded à vide
             */
            if (empty($_POST['packagesExcluded'])) {
                $packagesExcluded = array();
            } else {
                $packagesExcluded = $_POST['packagesExcluded'];
            }

            /**
             *  Si aucun paquet 'majeur' n'a été transmis, cela signifie que l'utilisateur souhaite vider la liste, on set $packagesMajorExcluded à vide
             */
            if (empty($_POST['packagesMajorExcluded'])) {
                $packagesMajorExcluded = array();
            } else {
                $packagesMajorExcluded = $_POST['packagesMajorExcluded'];
            }

            /**
             *  Si aucun service 'à redémarrer' n'a été transmis, cela signifie que l'utilisateur souhaite vider la liste, on set $packagesExcluded à vide
             */
            if (empty($_POST['serviceNeedRestart'])) {
                $serviceNeedRestart = array();
            } else {
                $serviceNeedRestart = $_POST['serviceNeedRestart'];
            }

            if (empty($_POST['notes'])) {
                $notes = '';
            } else {
                $notes = $_POST['notes'];
            }

            $myprofile = new \Controllers\Profile();

            /**
             *  Tentative de configuration du profil
             */
            try {
                $myprofile->configure($name, $reposList, $packagesExcluded, $packagesMajorExcluded, $serviceNeedRestart, $linupdateGetPkgConf, $linupdateGetReposConf, $notes);
            } catch (\Exception $e) {
                response(HTTP_BAD_REQUEST, $e->getMessage());
            }

            /**
             *  Si il n'y a pas eu d'erreur
             */
            response(HTTP_OK, "<b>" . $_POST['name'] . "</b> profile configuration has been saved");
        }

        /**
         *  Si l'action ne correspond à aucune action valide
         */
        response(HTTP_BAD_REQUEST, 'Invalid action.');
    }

    response(HTTP_BAD_REQUEST, 'Missing parameter.');
} else {
    response(HTTP_METHOD_NOT_ALLOWED, 'Method not allowed');
}

function response($response_code, $message)
{
    header('Content-Type: application/json');
    http_response_code($response_code);

    $response = [
        "response_code" => $response_code,
        "message" => $message
    ];

    echo json_encode($response);

    exit;
}
