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
	"insert"    => "Record toevoegen",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Zoek/update/wis records",
	"insert_short"    => "Toevoegen",
	"search_short" => "Zoek",
	"insert_anyway"    => "Altijd toevoegen",
	"search"    => "Zoek voor record",
	"update"    => "Update dit record",
	"ext_update"    => "Update je profiel",
	"yes"    => "Ja",
	"no"    => "Nee",
	"go_back" => "Terug",
	"edit" => "Edit",
	"delete" => "Delete",
	"details" => "Details",
	"change_table" => "Change table"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Item invoegen", // to change
	"show_all_records" => "Toon alle records",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Log uit",
	"top" => "Top",
	"last_search_results" => "Laatste zoekresultaten",
	"show_all" => "Toon Alles",
	"home" => "Thuis",
	"select_operator" => "Selecteer de operator:",
	"all_conditions_required" => "Alle vereiste condities",
	"any_conditions_required" => "de vereiste condities",
	"all_contacts" => "All contacten",
	"removed" => "verwijderd",
	"please" => "Gelieve",
	"and_check_form" => "en controleer het formulier.",
	"and_try_again" => "en probeer opnieuw.",
	"none" => "Geen",
	"are_you_sure" => "Weet U het zeker?",
	"delete_all" => "Alles wissen",
	"really?" => "Zeker?",
	"delete_are_you_sure" => "U gaat onderstaand record wissen, zeker ?",
	"required_fields_missed" => "Niet alle vereiste velden zijn ingevuld.",
	"alphabetic_not_valid" => "Getallen in een alleen-letters veld ?",
	"numeric_not_valid" => "Niet numerieke tekens in een alleen-cijfer veld.",
	"email_not_valid" => "Het/De emailadress(en) is/zijn ongeldig.",
	"timestamp_not_valid" => "Ingevoegde tijd is ongeldig.",
	"url_not_valid" => "Ongeldig webadres/URL.",
	"phone_not_valid" => "Telefoonnummer niet geldig.",
	"similar_records" => "Onderstaande record gelijken sterk op de toe te voegen record.<br>Wat wilt U doen?",
	"date_not_valid" => "Ongeldige datum.",
	"no_records_found" => "Geen records gevonden.",
	"records_found" => "records gevonden",
	"number_records" => "Aantal records: ",
	"details_of_record" => "Details van het record",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Edit het record",
	"back_to_the_main_table" => "Terug naar hoofdtabel",
	"previous" => "Vorige",
	"next" => "Volgende",
	"edit_profile" => "Update je profiel",
	"i_think_that" => "Ik denk dat ",
	"is_similar_to" => " gelijk is aan ",
	"page" => "Pagina ",
	"of" => " van ",
	"records_per_page" => "records per pagina",
	"day" => "Dag",
	"month" => "Maand",
	"year" => "Jaar",
	"administration" => "Administratie",
	"create_update_internal_table" => "Maak/update interne table",
	"other...." => "Andere....",
	"insert_record" => "New record toevoegen",
	"search_records" => "Zoek voor record",
	"exactly" => "precies",
	"like" => "bevattende",
	"required_fields_red" => "Vereiste velden zijn rood.",
	"insert_result" => "Toevoegen resultaat:",
	"record_inserted" => "Record correct toegevoegd.",
	"update_result" => "Update resultaat:",
	"record_updated" => "Record correctly geupdate.",
	"profile_updated" => "Uw profiel is correct geupdate.",
	"delete_result" => "Wis resultaat:",
	"record_deleted" => "Record correct verwijderd.",
	"duplication_possible" => "Duplicatie is mogelijk",
	"fields_max_length" => "Teveel tekst in 1 of meerdere velden.",
	"you are_going_unsubscribe" => "U gaat verwijderd worden van de mailinglijst. Verder gaan ?",
	"current_upload" => "Huidig bestand",
	"delete" => "delete",
	"total_records" => "Totaal records",
	"confirm_delete?" => "Bevestig wissen ?",
	"is_equal" => "is gelijk aan",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "bevat",
	"starts_with" => "begint met",
	"ends_with" => "eindigt met",
	"greater_than" => " groter dan ",
	"less_then" => " kleiner dan ",
	"export_to_csv" => "Export naar CSV",
	"new_insert_executed" => "Nieuwe insert doorgevoerd",
	"new_update_executed" => "Nieuwe update doorgevoerd",
	"null" => "Nul",
	"is_null" => "is nul",
	"is_empty" => "is leeg",
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Error, de interne database is leeg.",
	"get" => "Error, kan de variabelen niet bekomen.",
	"no_functions" => "Error, geen functies geselecteerd<br>Ga terug naar de homepagina.",
	"no_unique_key" => "Error, U heeft geen primaire sleutel in je tabel.",
	"upload_error" => "Er is een probleem met het uploaden van een bestand.",
	"no_authorization_update_delete" => "U heeft geen rechten om dit record te wijzigen/wissen.",
	"no_authorization_view" => "U heeft geen rechten om dit record te bekijken.",
	"deleted_only_authorizated_records" => "Enkel de records waar U rechten op heeft zijn verwijderd.",
	"record_from_which_you_come_no_longer_exists" => "Startrecord bestaat niet meer.",
	"date_not_representable" => "Een datumwaarde in dit record kan niet getoond worden met de DaDaBIK dag-maand-jaar lijstbox, de waarde is: ",
	"this_record_is_the_last_one" => "Dit is het laatste record.",
	"this_record_is_the_first_one" => "Dit is het eerste record."
	);

//login messages
$login_messages_ar = array(
	"username" => "gebruikersnaam",
	"password" => "paswoord",
	"please_authenticate" => "Geef Uw logingegevens",
	"login" => "login",
	"username_password_are_required" => "Gebruikersnaam en paswoord vereist",
	"incorrect_login" => "Gebruikersnaam of paswoord niet correct",
	"pwd_gen_link" => "maak paswoord",
	"pwd_explain_text" =>"Geef uw paswoord en druk <b>Versleutel!</b>.",
	"pwd_explain_text_2" =>"Klik <b>Register</b> om in onderstaande te plaatsen",
	"pwd_suggest_email_sending"=>"Je kan jezelf een mail sturen om het paswoord te onthouden",
	"pwd_send_link_text" =>"Verstuur email!",
	"pwd_encrypt_button_text" => "Versleutel!",
	"pwd_register_button_text" => "Registreer paswoord en be-eindig"
)
?>
