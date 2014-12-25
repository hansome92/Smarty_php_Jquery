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
	"insert"    => "Uuden tietueen lisäys",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Etsi/päivitä/poista tietueita",
	"insert_short"    => "Lisää",
	"search_short" => "Etsi",
	"insert_anyway"    => "Lisää kaikesta huolimatta",
	"search"    => "Etsi tietuetta",
	"update"    => "Tallenna",
	"ext_update"    => "Päivitä käyttäjätietosi",
	"yes"    => "Kyllä",
	"no"    => "Ei",
	"go_back" => "Palaa takaisin",
	"edit" => "Muokkaa",
	"delete" => "Poista",
	"details" => "Tiedot",
	"change_table" => "Vaihda taulua"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Näytä kaikki tietueet",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Kirjaudu ulos",
	"top" => "Ylös",
	"last_search_results" => "Viimeisimmän haun tulokset",
	"show_all" => "Näytä kaikki",
	"home" => "Aloitus",
	"select_operator" => "Valitse vertailutapa:",
	"all_conditions_required" => "Kaikkien ehtojen on täytyttävä",
	"any_conditions_required" => "Vähintään yhden ehdon on täytyttävä",
	"all_contacts" => "Kaikki yhteystiedot",
	"removed" => "poistettu",
	"please" => "Ole hyvä",
	"and_check_form" => "ja tarkista lomake.",
	"and_try_again" => "ja yritä uudelleen.",
	"none" => "ei lainkaan",
	"are_you_sure" => "Oletko varma?",
	"delete_all" => "poista kaikki",
	"really?" => "Todellako?",
	"delete_are_you_sure" => "Olet poistamassa oheista tietuetta, oletko aivan varma?",
	"required_fields_missed" => "Et ole täyttänyt kaikkia vaadittuja tietoja.",
	"alphabetic_not_valid" => "Sopimattomia merkkejä kentässä, johon hyväksytään vain aakkosia (a-ö).",
	"numeric_not_valid" => "Sopimattomia merkkejä kentässä, johon hyväksytään vain numeroita (0-9).",
	"email_not_valid" => "Antamasi sähköpostiosoite ei ole kelvollinen.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Antamasi url (www-osoite) ei ole kelvollinen.",
	"phone_not_valid" => "Antamasi puhelinnumero ei ole kelvollinen.<br>Käytä muotoa \"+(maatunnus)(aluetunnus)(numero)\" esim. +358401235678.",
	"date_not_valid" => "Antamasi päivämäärä ei ole kelvollinen.",
	"similar_records" => "Oheiset tietueet ovat yhteneväisiä antamiesi tietojen kanssa.<br>Mitä haluat tehdä?",
	"no_records_found" => "Yhtään tietuetta ei löytynyt.",
	"records_found" => "tietuetta löytyi",
	"number_records" => "Tietueiden lukumäärä: ",
	"details_of_record" => "Tietueen tiedot",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Muokkaa tietuetta",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Edellinen",
	"next" => "Seuraava",
	"edit_profile" => "Päivitä käyttäjätietosi",
	"i_think_that" => "Oletan, että ",
	"is_similar_to" => " on samanlainen kuin ",
	"page" => "Sivu ",
	"of" => " / ",
	"records_per_page" => "tietuetta sivulla",
	"day" => "Päivä",
	"month" => "Kuukausi",
	"year" => "Vuosi",
	"administration" => "Ylläpito",
	"create_update_internal_table" => "Luo tai päivitä sisäinen taulu",
	"other...." => "Muu, mikä? ...",
	"insert_record" => "Lisää uusi tietue",
	"search_records" => "Etsi tietueita",
	"exactly" => "täsmälleen",
	"like" => "melkein kuin",
	"required_fields_red" => "Vaaditut tiedot merkitty punaisella.",
	"insert_result" => "Tallennus:",
	"record_inserted" => "Tietue lisätty ongelmitta.",
	"update_result" => "Päivitys:",
	"record_updated" => "Tietue päivitetty ongelmitta.",
	"profile_updated" => "Käyttäjätietosi on päivitetty ongelmitta.",
	"delete_result" => "Poisto:",
	"record_deleted" => "Tietue poistettu ongelmitta.",
	"duplication_possible" => "Kahdentuminen on mahdollinen",
	"fields_max_length" => "Sisältö yhdessä tai useammassa kentässä on liian pitkä.",
	"current_upload" => "Nykyinen tiedosto",
	"delete" => "poista",
	"total_records" => "Tietueita yhteensä",
	"confirm_delete?" => "Vahvista poisto?",
	"is_equal" => "on yhtä kuin",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "sisältää",
	"starts_with" => "alussa",
	"ends_with" => "lopussa",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Vie CSV-muotoon",
	"new_insert_executed" => "Uusi lisäys suoritettu",
	"new_update_executed" => "Uusi päivitys suoritettu",
	"null" => "Tyhj&auml;",
	"is_null" => "on tyhj&auml;",
	"is_empty" => "arvoa ei asetettu",
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Virhe. Sisäinen tietokanta on tyhjä.",
	"get" => "Virhe haettaessa muuttujia.",
	
	
	"no_functions" => "Virhe. Toimintoja ei valittu.<br>Ole hyvä ja palaa takaisin kotisivulle.",
	"no_unique_key" => "Virhe. Sinulla ei ole yhtään \"primary key\"-kenttää taulussasi.",
	"upload_error" => "Tiedoston latauksessa tapahtui virhe.",
	"no_authorization_update_delete" => "Oikeutesi eivät riitä tämän tietueen muokkaukseen tai poistamiseen.",
	"no_authorization_view" => "Oikeutesi eivät riitä tämän tietueen tarkasteluun.",
	"deleted_only_authorizated_records" => "Vain ne tietueet poistettiin, joiden poistoon sinulla oli oikeudet.",
	"record_from_which_you_come_no_longer_exists" => "Hakemaasi tietuetta ei ole enää olemassa.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Tämä on viimeinen tietue.",
	"this_record_is_the_first_one" => "Tämä on ensimmäinen tietue."
	);

//login messages
$login_messages_ar = array(
	"username" => "Käyttäjätunnus",
	"password" => "Salasana",
	"please_authenticate" => "Kirjautuminen",
	"login" => "Kirjaudu sisään",
	"username_password_are_required" => "Käyttäjätunnus ja salasana vaaditaan",
	"pwd_gen_link" => "Luo salasana",
	"incorrect_login" => "Kirjautumistiedoissa virhe, tarkista tunnus ja salasana.",
	"pwd_explain_text" =>"Kirjoita haluamasi salasana ja paina <b>Salaa salasana!</b>.",
	"pwd_explain_text_2" =>"Paina <b>Rekisteröidy</b> ja kirjoita saamasi salattu salasana ao. kenttään.",
	"pwd_suggest_email_sending"=>"Haluatko että sinulle lähetetään salasana sähköpostitse?",
	"pwd_send_link_text" =>"Lähetä salasana sähköpostitse!",
	"pwd_encrypt_button_text" => "Salaa salasana!",
	"pwd_register_button_text" => "Rekisteröi salasana ja poistu"
);
?>