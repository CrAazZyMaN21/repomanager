/**
 *  Chargement des menus Select2
 */
loadProfilesSelect2();

/**
 *  Fonctions utiles
 */
/**
 *  Chargement de tous les Select2 de la pages des profils
 */
function loadProfilesSelect2()
{
    $('.select-repos').select2({
        closeOnSelect: false,
        placeholder: 'Add repo ✎'
    });
    $('.select-exclude-major').select2({
        closeOnSelect: false,
        placeholder: 'Select package 🖉',
        tags: true
    });
    $('.select-exclude').select2({
        closeOnSelect: false,
        placeholder: 'Select package 🖉',
        tags: true
    });
    $('.select-need-restart').select2({
        closeOnSelect: false,
        placeholder: 'Select service 🖉',
        tags: true
    });
}

/**
 *  Rechargement de la div des profils
 *  Recharge les menus select2 en même temps
 */
function reloadProfileDiv()
{
    $("#profilesDiv").load(" #profilesDiv > *",function () {
        /**
         *  Rechargement de tous les menus Select2
         */
        loadProfilesSelect2();
    });
}

/**
 *  Events listeners
 */

/**
 *  Event : Création d'un nouveau profil
 */
$(document).on('submit','#newProfileForm',function () {
    event.preventDefault();
    /**
     *  Récupération du nom de profil à créer dans l'input prévu à cet effet
     */
    var name = $("#newProfileInput").val();
    newProfile(name);

    return false;
});

/**
 *  Event : Suppression d'un profil
 */
$(document).on('click','.deleteProfileBtn',function () {
     var name = $(this).attr('profilename');
    deleteConfirm('Are you sure you want to delete profile <b>' + name + '</b>?', function () {
        deleteProfile(name)});
});

/**
 *  Event : Renommage d'un profil
 */
$(document).on('submit','.profileForm',function () {
    event.preventDefault();
    /**
     *  Récupération du nom actuel (dans <form>) et du nouveau nom (dans <input> contenant l'attribut profilename="name")
     */
    var name = $(this).attr('profilename');
    var newname = $('input[profilename=' + name + '].profileFormInput').val();
    renameProfile(name, newname);

    return false;
});

/**
 *  Event : duplication d'un profil
 */
$(document).on('click','.duplicateProfileBtn',function () {
    var name = $(this).attr('profilename');

    duplicateProfile(name);
});

/**
 *  Event : Afficher la configuration d'un profil
 */
$(document).on('click','.profileConfigurationBtn',function () {
    var name = $(this).attr('profilename');
    $("#profileConfigurationDiv-" + name).slideToggle(150);
});

/**
 *  Event : modifier la configuration serveur
 */
$(document).on('submit','#applyServerConfigurationForm',function () {
    event.preventDefault();

    var serverPackageType = $('#serverPackageTypeInput').val();
    var repoConfPrefix = $('#repoConfPrefix').val();

    if ($('#serverManageClientConf').is(':checked')) {
        var serverManageClientConf = 'yes';
    } else {
        var serverManageClientConf = 'no';
    }

    if ($('#serverManageClientRepos').is(':checked')) {
        var serverManageClientRepos = 'yes';
    } else {
        var serverManageClientRepos = 'no';
    }

    applyServerConfiguration(serverPackageType, serverManageClientConf, serverManageClientRepos, repoConfPrefix);

    return false;
});

/**
 *  Event : modifier la configuration d'un profil (repos, exclusions...)
 */
$(document).on('submit','.profileConfigurationForm',function () {
    event.preventDefault();
    /**
     *  Récupération du nom du groupe (dans <form>)
     *  de la liste des repos (dans le <select>)
     *  de la liste des exclusions
     *  des paramètres de mise à jour
     *  etc...
     */
    var name = $(this).attr('profilename');

    var reposList = $('select[profilename=' + name + '].select-repos').val();
    var packagesMajorExcluded = $('select[profilename=' + name + '].select-exclude-major').val();
    var packagesExcluded = $('select[profilename=' + name + '].select-exclude').val();
    var serviceNeedRestart = $('select[profilename=' + name + '].select-need-restart').val();

    if ($('#profile-conf-allow-overwrite[profilename=' + name + ']').is(':checked')) {
        var allowOverwrite = 'yes';
    } else {
        var allowOverwrite = 'no';
    }
    if ($('#profile-conf-allow-repos-overwrite[profilename=' + name + ']').is(':checked')) {
        var allowReposFilesOverwrite = 'yes';
    } else {
        var allowReposFilesOverwrite = 'no';
    }

    var notes = $('textarea[profilename=' + name + '].profile-conf-notes').val();

    configureProfile(name, reposList, packagesMajorExcluded, packagesExcluded, serviceNeedRestart, allowOverwrite, allowReposFilesOverwrite, notes);

    return false;
});


/**
 * Ajax: Modifier la configuration serveur
 */
// function applyServerConfiguration(serverOsFamily, serverOsName, serverOsVersion, serverPackageType, serverPackageOsVersion, serverManageClientConf, serverManageClientRepos, repoConfPrefix)
function applyServerConfiguration(serverPackageType, serverManageClientConf, serverManageClientRepos, repoConfPrefix)
{
    $.ajax({
        type: "POST",
        url: "controllers/profiles/ajax.php",
        data: {
            action: "applyServerConfiguration",
            serverPackageType: serverPackageType,
            serverManageClientConf: serverManageClientConf,
            serverManageClientRepos: serverManageClientRepos,
            repoConfPrefix: repoConfPrefix
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            /**
             *  Affichage d'une alerte success et rechargement des profils
             */
            printAlert(jsonValue.message, 'success');
            reloadProfileDiv();
        },
        error : function (jqXHR, textStatus, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}

/**
 * Ajax: Créer un nouveau profil
 * @param {string} name
 */
function newProfile(name)
{
    $.ajax({
        type: "POST",
        url: "controllers/profiles/ajax.php",
        data: {
            action: "newProfile",
            name: name
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            /**
             *  Affichage d'une alerte success et rechargement des profils
             */
            printAlert(jsonValue.message, 'success');
            reloadProfileDiv();
        },
        error : function (jqXHR, textStatus, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}

/**
 * Ajax : Supprimer un profil
 * @param {string} name
 */
function deleteProfile(name)
{
    $.ajax({
        type: "POST",
        url: "controllers/profiles/ajax.php",
        data: {
            action: "deleteProfile",
            name: name
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            /**
             *  Affichage d'une alerte success et rechargement des profils
             */
            printAlert(jsonValue.message, 'success');
            reloadProfileDiv();
        },
        error : function (jqXHR, ajaxOptions, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}

/**
 * Ajax: Renommer un profil
 * @param {string} name
 */
function renameProfile(name, newname)
{
    $.ajax({
        type: "POST",
        url: "controllers/profiles/ajax.php",
        data: {
            action: "renameProfile",
            name: name,
            newname : newname
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            /**
             *  Affichage d'une alerte success et rechargement des profils
             */
            printAlert(jsonValue.message, 'success');
            reloadProfileDiv();
        },
        error : function (jqXHR, textStatus, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}

/**
 * Ajax: Dupliquer un profil
 * @param {string} name
 */
function duplicateProfile(name)
{
    $.ajax({
        type: "POST",
        url: "controllers/profiles/ajax.php",
        data: {
            action: "duplicateProfile",
            name: name
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            /**
             *  Affichage d'une alerte success et rechargement des profils
             */
            printAlert(jsonValue.message, 'success');
            reloadProfileDiv();
        },
        error : function (jqXHR, textStatus, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}

/**
 * Ajax: Modifier la configuration d'un profil
 */
function configureProfile(name, reposList, packagesMajorExcluded, packagesExcluded, serviceNeedRestart, allowOverwrite, allowReposFilesOverwrite, notes)
{
    $.ajax({
        type: "POST",
        url: "controllers/profiles/ajax.php",
        data: {
            action: "configureProfile",
            name: name,
            reposList: reposList,
            packagesMajorExcluded: packagesMajorExcluded,
            packagesExcluded: packagesExcluded,
            serviceNeedRestart: serviceNeedRestart,
            allowOverwrite: allowOverwrite,
            allowReposFilesOverwrite: allowReposFilesOverwrite,
            notes: notes
        },
        dataType: "json",
        success: function (data, textStatus, jqXHR) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            /**
             *  Affichage d'une alerte success et rechargement des groupes et de la liste des repos
             */
            printAlert(jsonValue.message, 'success');
        },
        error : function (jqXHR, textStatus, thrownError) {
            jsonValue = jQuery.parseJSON(jqXHR.responseText);
            printAlert(jsonValue.message, 'error');
        },
    });
}