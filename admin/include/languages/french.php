<?php
/*
***********************************************************************************
This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). At a glance, you can use this program and you can modifiy it to create a database application for you or for other people but you cannot redistribute the program files in any format. All the details, including examples, in dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact eugenio.tacchini@gmail.com
***********************************************************************************
*/
?>
<?php
// submit buttons
$submit_buttons_ar = array (
	"insert"    => "Insérer un nouvel enregistrement",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Chercher/Mettre à jour/Effacer un enregistrement",
	"insert_short"    => "Insérer",
	"search_short" => "Chercher",
	"insert_anyway"    => "Insérer quand même",
	"search"    => "Chercher un enregistrement",
	"update"    => "Mettre à jour un enregistrement",
	"ext_update"    => "Mettre à jour votre profil",
	"yes"    => "Oui",
	"no"    => "Non",
	"go_back" => "Retour",
	"edit" => "Editer",
	"delete" => "Effacer",
	"details" => "Détails",
	"change_table" => "Changer de table"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insrer un lment",
	"show_all_records" => "Montrer tous les enregistrements",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Se deconnecter",
	"top" => "Haut",
	"last_search_results" => "Résultats de la dernière recherche",
	"show_all" => "Tout Montrer",
	"home" => "Accueil",
	"select_operator" => "Sélectionner l'opérateur:",
	"all_conditions_required" => "Toutes conditions requises",
	"any_conditions_required" => "N'importe quelle condition requise",
	"all_contacts" => "Tous les contacts",
	"removed" => "retiré",
	"please" => "SVP",
	"and_check_form" => "et contrôler le formulaire.",
	"and_try_again" => "et essayer à nouveau.",
	"none" => "aucun",
	"are_you_sure" => "Etes-vous sûr?",
	"delete_all" => "Effacer tout",
	"really?" => "Vraiment?",
	"delete_are_you_sure" => "Effacer l'enregistrement ci-dessous, êtes-vous sûr?",
	"required_fields_missed" => "Vous n'avez pas rempli tous les champs requis.",
	"alphabetic_not_valid" => "Vous avez inséré un/plusieurs nombre(s) dans un champ lettre.",
	"numeric_not_valid" => "Vous avez inséré un/plusieurs caractères non numériques dans un champ numérique.",
	"email_not_valid" => "L'e-mail inséré n'est pas valide.",
	"timestamp_not_valid" => "L'horodatage insr n'est pas valide.",
	"url_not_valid" => "Le(s) URL insérée(s) ne sont pas valides.",
	"phone_not_valid" => "Numéro de téléphone non valide.",
	"date_not_valid" => "Date(s) non valide(s)",
	"similar_records" => "Les enregistrements ci-dessous semblent être identiques à celui que vous voulez inséré.<br>Que voulez-vous faire?",
	"no_records_found" => "Pas d'enregistrement trouvé.",
	"records_found" => "enregistrements trouvés",
	"number_records" => "Nombre d'enregistrement: ",
	"details_of_record" => "Détails de l'enregistrement",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Editer l'enregistrement",
	"back_to_the_main_table" => "Retour  la table principale",
	"previous" => "Précédent",
	"next" => "Suivant",
	"edit_profile" => "Mettre à jour vos informations de profil",
	"i_think_that" => "Je pense que ",
	"is_similar_to" => " est identique à ",
	"page" => "Page ",
	"of" => " de ",
	"records_per_page" => "enregistrements par page",
	"day" => "Jour",
	"month" => "Mois",
	"year" => "Année",
	"administration" => "Administration",
	"create_update_internal_table" => "Créer ou mettre à jour une table interne",
	"other...." => "autres....",
	"insert_record" => "Insérer un nouvel enregistrement",
	"search_records" => "Chercher les enregistrements",
	"exactly" => "exactement",
	"like" => "comme",
	"required_fields_red" => "Les champs requis sont en rouge.",
	"insert_result" => "Insérer le résultat:",
	"record_inserted" => "Enregistrement correctement inséré.",
	"update_result" => "Mettre à jour le résultat:",
	"record_updated" => "Enregistrement correctement mis à jour.",
	"profile_updated" => "Votre profil a correctement été mis à jour.",
	"delete_result" => "Effacer le résultat:",
	"record_deleted" => "Enregistrement correctement éffacé.",
	"duplication_possible" => "Duplication possible",
	"fields_max_length" => "Vous avez inséré trop de texte dans un/plusieur(s) champs.",
	"current_upload" => "Fichier en cours",
	"delete" => "Effacer",
	"total_records" => "Total Enregistrements",
	"confirm_delete?" => "Confirmer la supression ?",
	"is_equal" => "est égal à",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "contient",
	"starts_with" => "commence par",
	"ends_with" => "se termine par",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Exporte en format CSV",
	"new_insert_executed" => "Nouvelle insertion effectuée",
	"new_update_executed" => "Nouvelle mise à jour effectuée",
	"null" => "Null",
	"is_null" => "est NULL",
	"is_empty" => "est vide",
	"continue" => "continue" // to change

	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Erreur, la base interne est vide.",
	"get" => "Erreur dans la récupération des variables.",
	
	
	"no_functions" => "Erreur, pas de fonction sélectionnée.<br>Retournez à l'accueil.",
	"no_unique_key" => "Erreur, vous n'avez pas de clé primaire dans votre base.",	
	"upload_error" => "Une erreur est intervenue durant le transfert.",
	"no_authorization_update_delete" => "Vous n'avez pas l'autorisation de modifier / detruire cet enregistrement.",
	"no_authorization_view" => "Vous n'avez pas l'autorisation de voir cet enregistrement.",
	"deleted_only_authorizated_records" => "Seuls les enregistrements pour lesquels vous etes autorise ont ete detruits.",
	"record_from_which_you_come_no_longer_exists" => "L'enregistrement d'où vous venez n'existe plus.",
	"date_not_representable" => "Une date dans cet enregistrement ne correspond pas au format 'Jour / Mois / Anne' de DaDaBIK. Sa valeur est :  ", // to change
	"this_record_is_the_last_one" => "Cet enregistrement est le dernier.",
	"this_record_is_the_first_one" => "Cet enregistrement est le premier."
	);

//login messages
$login_messages_ar = array(
	"username" => "nom d'utilisateur",
	"password" => "mot de passe",
	"please_authenticate" => "Vous devez vous identifier pour continuer",
	"login" => "login", 
	"username_password_are_required" => "nom d'utilisateur et mot de passe sont obligatoires", 
	"pwd_gen_link" => "créer un mot de passe",
	"incorrect_login" => "Nom d'utilisateur ou Mot de passe incorrect",
	"pwd_explain_text" =>"Tapez un mot à utiliser comme mot de passe et pressez <b>Cryptage !</b>.",
	"pwd_explain_text_2" =>"Pressez <b>Enregistrer le Mot de passe et Quitter</b> pour l'écrire sur le formulaire ci-dessous", 
	"pwd_suggest_email_sending"=>"Vous pouvez vous envoyer un email pour mémoriser le mot de passe", 
	"pwd_send_link_text" =>"Envoi courrier!",
	"pwd_encrypt_button_text" => "Cryptage !", 
	"pwd_register_button_text" => "Enregistrer le Mot de passe et Quitter" 
)
?>