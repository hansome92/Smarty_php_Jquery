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
	"insert"    => "Infoga ny datapost",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Sök/uppdatera/radera dataposter",
	"insert_short"    => "Infoga",
	"search_short" => "Sök",
	"insert_anyway"    => "Infoga ändå",
	"search"    => "Sök",
	"update"    => "Spara",
	"ext_update"    => "Uppdatera din profil",
	"yes"    => "Ja",
	"no"    => "Nej",
	"go_back" => "gå tillbaka",
	"edit" => "Editera",
	"delete" => "Radera",
	"details" => "Detaljer",
	"change_table" => "Ändra tabell"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Visa alla poster",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Logout",
	"top" => "Top",
	"last_search_results" => "Senaste sökningen", 
	"show_all" => "Visa alla",
	"home" => "Hem",
	"select_operator" => "Välj operator:",
	"all_conditions_required" => "Alla villkor sanna",
	"any_conditions_required" => "Något vilkor sant",
	"all_contacts" => "Alla kontakter",
	"removed" => "borttaget",
	"please" => "Vänligen",
	"and_check_form" => "och kolla formuläret.",
	"and_try_again" => "och prova igen.",
	"none" => "ingen",
	"are_you_sure" => "Är du säker?",
	"delete_all" => "radera allt",
	"really?" => "Verkligen?",
	"delete_are_you_sure" => "Du kommer att radera nedanstående datapost, är du säker?",
	"required_fields_missed" => "Du har inte fyllt i vissa nödvändiga fält.",
	"alphabetic_not_valid" => "Du har infogat nummer i ett textfält.",
	"numeric_not_valid" => "Du har infogat nonnumeriska tecken i ett nummerfält.",
	"email_not_valid" => "Den infogade utskicksadressen är ogiltig.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Den infogade url är oglitig.",
	"phone_not_valid" => "Det infogade telefonnumret är ogiltigt.<br>Använd formatet \"+(landskod)(riktnummer)(nummer)\" formatet d.v.s +390523599318.",
	"date_not_valid" => "Du har infogat ett ogiltigt datum.",
	"similar_records" => "Dataposterna nedanför liknar den du vill infoga.<br>Vad vill du göra?",
	"no_records_found" => "Hittar inga dataposter.",
	"records_found" => "dataposter hittats",
	"number_records" => "Antal dataposter: ",
	"details_of_record" => "Detaljer för datapost",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Ändra datapost",
	"back_to_the_main_table" => "Back to the main table", // to change
	"last_search_results" => "Senaste sökningen",
	"previous" => "Previous", // to change
	"next" => "Nästa",
	"edit_profile" => "Uppdatera din profil",
	"i_think_that" => "Jag tror att ",
	"is_similar_to" => " liknar ",
	"page" => "Sida ",
	"of" => " av ",
	"records_per_page" => "poster / sida",
	"day" => "dag",
	"month" => "månad",
	"year" => "år",
	"administration" => "Administration",
	"create_update_internal_table" => "Skapa eller ändra intern tabell",
	"other...." => "annan....",
	"insert_record" => "Infoga ny datapost",
	"search_records" => "Sök dataposter",
	"exactly" => "exakt",
	"like" => "liknar",
	"required_fields_red" => "Nödvändiga fält är röda.",
	"insert_result" => "Infoga resultat:",
	"record_inserted" => "Datapost infogad.",
	"update_result" => "Uppdatera resultatet:",
	"record_updated" => "Datapost uppdaterad.",
	"profile_updated" => "Din profil har uppdaterats.",
	"delete_result" => "Radera resultatet:",
	"record_deleted" => "Datapost raderad.",
	"duplication_possible" => "En möjlig dubblering",
	"fields_max_length" => "För mycket text i något fält.",
	"current_upload" => "Aktuell fil",
	"delete" => "radera",
	"total_records" => "Totalt antal dataposter",
	"confirm_delete?" => "Bekräfta radering?",
	"is_equal" => "är lika med",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "innehåller",
	"starts_with" => "börjar med",
	"ends_with" => "slutar med",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Export till CSV",
	"new_insert_executed" => "Ny infogning klar",
	"new_update_executed" => "Ny uppdatering klar",
	"null" => "Null",
	"is_null" => "ä null",
	"is_empty" => "är tom",
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Fel, interna databasen är tom.",
	"get" => "Fel i get variables.",
	"no_functions" => "Fel, ingen funktion vald<br>Vänligen gå tillbaka till hemsidan.",
	"no_unique_key" => "Fel, du har ingen primärnyckel i tabellen.",	
	"upload_error" => "Fel uppstod under uppladdning av filen.",
	"no_authorization_update_delete" => "Du är inte auktoriserad för att ändra/radera denna datapost.",
	"no_authorization_view" => "Du är inte auktoriserad för att se denna datapost.",
	"deleted_only_authorizated_records" => "Endast dataposter för vilka du har auktorisation har raderats.",
	"record_from_which_you_come_no_longer_exists" => "Den ursprungliga posten finns inte kvar.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Detta är den sista posten.",
	"this_record_is_the_first_one" => "Detta är den första posten."
	);

//login messages
$login_messages_ar = array(
	"username" => "användarnamn",
	"password" => "passord",
	"please_authenticate" => "Du måste identifiera dig för att kunna gå vidare",
	"login" => "login",
	"username_password_are_required" => "Användarnamn och passord krävs",
	"pwd_gen_link" => "skapa passord",
	"incorrect_login" => "Användarnamn eller passord felaktiga",
	"pwd_explain_text" =>"Mata in ett ord som ska användas till passord och tryck <b>Kryptera!</b>.",
	"pwd_explain_text_2" =>"Tryck <b>Registrera</b> för att skriva in det i formuläret nedan",
	"pwd_suggest_email_sending"=>"Du kanske vill skicka dig själv ett epost meddelande för att minnas passordet",
	"pwd_send_link_text" =>"skicka epost!",
	"pwd_encrypt_button_text" => "Kryptera!",
	"pwd_register_button_text" => "Registrera passordet och avsluta"
);
?>