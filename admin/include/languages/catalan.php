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
	"insert"    => "Entrar una fitxa nova",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Cercar/modificar/esborrar fitxes",
	"insert_short"    => "Entrar",
	"search_short" => "Cercar",
	"insert_anyway"    => "Entrar igualment",
	"search"    => "Cercar fitxa",
	"update"    => "Desar",
	"ext_update"    => "Actualitzar el vostre perfil",
	"yes"    => "Sí",
	"no"    => "No",
	"go_back" => "Enrere",
	"edit" => "Editar",
	"delete" => "Esborrar",
	"details" => "Detalls",
	"change_table" => "Canviar de taula"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Mostrar totes les fitxes",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Sortir",
	"top" => "Dalt",
	"last_search_results" => "Darrers resultats de la cerca",
	"show_all" => "Totes les fitxes",
	"home" => "Inici de la taula",
	"select_operator" => "Seleccionau l\'operador:",
	"all_conditions_required" => "Cal que es compleixin totes les condicions",
	"any_conditions_required" => "Basta amb que es compleixi una condició",
	"all_contacts" => "Tots els contactes",
	"removed" => "suprimit",
	"please" => "Si us plau anau",
	"and_check_form" => "i reviseu el formulari.",
	"and_try_again" => "i proveu-ho un altre cop.",
	"none" => "cap",
	"are_you_sure" => "Estau segur?",
	"delete_all" => "esborrar-ho tot",
	"really?" => "De bon de veres?",
	"delete_are_you_sure" => "Esteu a punt d'esborrar la fitxa.  Segur?",
	"required_fields_missed" => "No heu emplenat alguns camps necessaris.",
	"alphabetic_not_valid" => "Heu teclejat números a un camp només alfabètic.",
	"numeric_not_valid" => "Heu teclejat lletres o símbols a un camp només numèric.",
	"email_not_valid" => "L\'adreça de correu entrada no és correcte.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "L\'adreça de plana web entrada no és correcte.",
	"phone_not_valid" => "El número de telèfon entrat no és correcte.<br>Si us plau, useu el format \"+(codi país)(codi àrea)(número)\" per exemple +34971855141.",
	"date_not_valid" => "Una data entrada no és correcte.",
	"similar_records" => "Les fitxes devall s\'assemblen molt a la que heu entrat.<br>Què voleu fer?",
	"no_records_found" => "Cap fitxa trobada.",
	"records_found" => "fitxes trobades",
	"number_records" => "Fitxes: ",
	"details_of_record" => "Detalls de la fitxa",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Editar la fitxa",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Previ",
	"next" => "Següent",
	"edit_profile" => "Actualitzar les dades del vostre perfil",
	"i_think_that" => "Em sembla que ",
	"is_similar_to" => " és assemblant a ",
	"page" => "Pàgina ",
	"of" => " de ",
	"records_per_page" => "registres per plana",
	"day" => "Dia",
	"month" => "Mes",
	"year" => "Any",
	"administration" => "Administració",
	"create_update_internal_table" => "Crear o actualitzar la taula interna",
	"other...." => "una altre ...",
	"insert_record" => "Entrar una fitxa nova",
	"search_records" => "Cercar fitxes",
	"exactly" => "exactament",
	"like" => "com",
	"required_fields_red" => "Els camps indispensables estan en vermell.",
	"insert_result" => "Entreu resultat:",
	"record_inserted" => "Fitxa desada correctament.",
	"update_result" => "Actualitzat el resultat:",
	"record_updated" => "Fitxa actualitzada correctament.",
	"profile_updated" => "El vostre perfil està actualitzat.",
	"delete_result" => "Resultat de l\'esborrat:",
	"record_deleted" => "Fitxa esborrada correctament.",
	"duplication_possible" => "La duplicació està permesa",
	"fields_max_length" => "Heu entrat massa text i un o més camps.",
	"current_upload" => "Arxiu actual",
	"delete" => "esborrar",
	"total_records" => "Fitxes",
	"confirm_delete?" => "Esborrar la fitxa?",
	"is_equal" => "és igual a",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "conté",
	"starts_with" => "comença amb",
	"ends_with" => "acaba amb",
	"greater_than" => "és més gran que",
	"less_then" => "és més petit que",
	"export_to_csv" => "Exportar a CSV",
	"new_insert_executed" => "Nova inserció feta",
	"new_update_executed" => "Nova actualització feta",
	"null" => "Null",
	"is_null" => "is null",
	"is_empty" => "és buit",
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Error: la base de dades interna està buida.",
	"get" => "Error al agafar les variables.",
	"no_functions" => "Error, cap funció seleccionada<br>Si us plau, torneu a la plana d\'inici.",
	"no_unique_key" => "Error, no teniu cap clau primària a la taula.",	
	"upload_error" => "Errors amb l\'enviament de l\'arxiu.",
	"no_authorization_update_delete" => "No esteu autoritzat per modificar o esborrar aquesta fitxa.",
	"no_authorization_view" => "No esteu autoritzat per veure aquesta fitxa.",
	"deleted_only_authorizated_records" => "Només podeu esborrar les fitxes per les que hi teniu autorització.",
	"record_from_which_you_come_no_longer_exists" => "El registre de partida no existeix.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Aquest registre és el darrer.",
	"this_record_is_the_first_one" => "Aquest registre és el primer."
	);



//login messages
$login_messages_ar = array(
	"username" => "Usuari",
	"password" => "Contrasenya",
	"please_authenticate" => "Necesitau identificació per a continuar",
	"login" => "entrada",
	"username_password_are_required" => "Cal nom d'usuari i contrasenya",
	"pwd_gen_link" => "crear contrasenya",
	"incorrect_login" => "Usuari o contrasenya incorrectes",
	"pwd_explain_text" =>"Entreu la paraula que usareu de contrasenya i polseu <b>Xifra-la!</b>.",
	"pwd_explain_text_2" =>"Premeu <b>Registrar</b> per a escriure-la en el formulari que hi ha devall",
	"pwd_suggest_email_sending"=>"Podeu demanar un missatge per a recordar la contrasenya",
	"pwd_send_link_text" =>"enviar missatge!",
	"pwd_encrypt_button_text" => "Xifra-la!",
	"pwd_register_button_text" => "Registrar contrasenya i sortir"
)
?>