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
// submit nupud
$submit_buttons_ar = array (
	"insert"    => "Lisa sissekanne",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Otsi/Uuenda/Kustuta",
	"insert_short"    => "Lisa",
	"search_short" => "Otsi",
	"insert_anyway"    => "Lisa ikkagi",
	"search"    => "Otsi sissekannet",
	"update"    => "Salvesta",
	"ext_update"    => "Uuenda oma profiili",
	"yes"    => "Jah",
	"no"    => "Ei",
	"go_back" => "Mine tagasi",
	"edit" => "Muuda",
	"delete" => "Kustuta",
	"details" => "Detailid",
	"change_table" => "Muuda tabelit"
);

// tavalised teated
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Näita kõiki sissekandeid",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Logi välja",
	"top" => "Algus",
	"last_search_results" => "Last search results", // to change
	"show_all" => "Näita kõiki",
	"home" => "Kodu",
	"select_operator" => "Vali operaator:",
	"all_conditions_required" => "Kõik tingimused peavad olema täidetud",
	"any_conditions_required" => "Üks kõik milline tingimustest peab olema täidetud",
	"all_contacts" => "Kõik kontaktid",
	"removed" => "eemaldatud",
	"please" => "palun",
	"and_check_form" => "kontrolli sisestatud andmeid.",
	"and_try_again" => "proovi uuesti.",
	"none" => "ühtegi",
	"are_you_sure" => "Oled sa kindel?",
	"delete_all" => "kustuta kõik",
	"really?" => "Tõesti?",
	"delete_are_you_sure" => "Sa oled kustutamas allolevat sissekannet, oled sa kindel et tahad seda?",
	"required_fields_missed" => "Sa ei ole täitnud kohustuslikke väljasid.",
	"alphabetic_not_valid" => "Sa oled sisestanud numbrid tähtede asemel.",
	"numeric_not_valid" => "Sa oled sisestanud mittenumbrilised tähemärgid numbrite asemel.",
	"email_not_valid" => "Sisestatud e-mail aadress/id ei kehti.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Sisestatud URL aadress/id ei kehti.",
	"phone_not_valid" => "Sisestatud telefoni number ei kehti.<br>Palun kasuta järmist formaati \"+(riigi kood)(piirkonna kood)(number)\" näiteks: +390523599318.",
	"date_not_valid" => "Sa oled sisestanud ebakorrektse kuupäeva.",
	"similar_records" => "Allolevad sissekanded tunduvad olevat sarnased sellega, mida püüad sisestada.<br>Mida sa tahad teha?",
	"no_records_found" => "Sissekandeid ei leitud.",
	"records_found" => "sissekannet leitud",
	"number_records" => "Sissekannete arv: ",
	"details_of_record" => "Sissekande detailid",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Muuda sissekannet",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Previous", // to change
	"next" => "Next", // to change
	"edit_profile" => "Uuenda oma profiil infot",
	"i_think_that" => "Ma arvan et ",
	"is_similar_to" => " on sarnane ",
	"page" => "Lehekülg ",
	"of" => " - ",
	"records_per_page" => "records per page", // to change
	"day" => "Päev",
	"month" => "Kuu",
	"year" => "Aasta",
	"administration" => "Administreerimine",
	"create_update_internal_table" => "Loo või uuenda sisemist tabelit",
	"other...." => "muu....",
	"insert_record" => "Sisesta uus sissekanne",
	"search_records" => "Otsi sissekandeid",
	"exactly" => "täpselt",
	"like" => "nagu",
	"required_fields_red" => "Kohustuslikud väljad on punased.",
	"insert_result" => "Sisestuse tulemus:",
	"record_inserted" => "Sissekanne korralikult lisatud.",
	"update_result" => "Uuenduse tulemus:",
	"record_updated" => "Sissekanne korralikult uuendatud.",
	"profile_updated" => "Sinu profiil korralikult uuendatud.",
	"delete_result" => "Kustutumise tulemus:",
	"record_deleted" => "Sissekanne on korralikult kustutatud.",
	"duplication_possible" => "Dubleerimine on võimalik",
	"fields_max_length" => "Oled mingisse välja sisestanud liiga palju teksti.",
	"current_upload" => "Praegune fail",
	"delete" => "kustuta",
	"total_records" => "Sissekandeid kokku",
	"confirm_delete?" => "Kinnita kustutamine?",
	"is_equal" => "on võrdne",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "sisaldab",
	"starts_with" => "algab",
	"ends_with" => "lõppeb",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Ekspordi CSV formaadis",
	"new_insert_executed" => "New insert executed", // to change
	"new_update_executed" => "New update executed", // to change
	"null" => "Null", // to change
	"is_null" => "is null", // to change
	"is_empty" => "is empty", // to change
	"continue" => "continue" // to change
	);

// veateated
$error_messages_ar = array (
	"int_db_empty" => "Viga, sisemine andmebaas on tühi.",
	"get" => "Viga andmebaasi muutujates.",
	"no_functions" => "Viga! Ühtegi funktsiooni pole valitud<br>Palun mine tagasi algusesse.",
	"no_unique_key" => "Viga! Sinu tabelis ei ole primaarvõtit.",	
	"upload_error" => "Faili üleslaadimisel tekkis viga.",
	"no_authorization_update_delete" => "Sul ei ole piisavalt õigusi sissekande muutmiseks/kustutamiseks.",
	"no_authorization_view" => "Sul ei ole piisavalt õigusi selle sissekande vaatamiseks.",
	"deleted_only_authorizated_records" => "Kustutatud on ainult need sissekanded millede kustutamiseks sul olid õigused.",
	"record_from_which_you_come_no_longer_exists" => "The record from which you come no longer exists.", // to change
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "This record is the last one.", // to change
	"this_record_is_the_first_one" => "This record is the first one." // to change
	);
	
// sisselogimise teated
$login_messages_ar = array(
	"username" => "kasutajanimi",
	"password" => "parool",
	"please_authenticate" => "Jätkamiseks peate ennast identifitseerima",
	"login" => "logi sisse",
	"username_password_are_required" => "Vajalikud on kasutajanimi ja parool",
	"pwd_gen_link" => "loo parool",
	"incorrect_login" => "Kasutajanimi või parool oli vale",
	"pwd_explain_text" =>"Sisesta sõna, mida hakkad kasutama paroolina ja vajuta <b>Krüpteeri!</b>.",
	"pwd_explain_text_2" =>"Vajuta <b>Registreeri</b> et seda allolevasse vormi kirjutada",
	"pwd_suggest_email_sending"=>"Sa võid endale soovi korral meili saata, et parool ei ununeks",
	"pwd_send_link_text" =>"saada mail!",
	"pwd_encrypt_button_text" => "Krüpteeri!",
	"pwd_register_button_text" => "Registreeri parool ja välju"
)
?>
