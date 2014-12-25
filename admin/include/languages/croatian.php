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
	"insert"    => "Umetni novi podatak",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Trai/uredi/poniti podatak",
	"insert_short"    => "Umetni",
	"search_short" => "Trai",
	"insert_anyway"    => "Svejedno umetni",
	"search"    => "Trai podatak",
	"update"    => "Auriraj podatak",
	"ext_update"    => "Auriraj svoje podatke",
	"yes"    => "Da",
	"no"    => "Ne",
	"go_back" => "Natrag",
	"edit" => "Uredi",
	"delete" => "Poniti",
	"details" => "Detalji",
	"change_table" => "Promjeni tablicu"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Prikai sve podatke", 
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Logout",
	"top" => "Na vrh",
	"last_search_results" => "zadnji rezultat pretrage", //to change 
	"show_all" => "Prikai sve",
	"home" => "Poèetna",
	"select_operator" => "Izaberi operatora:",
	"all_conditions_required" => "Svi uvjeti",
	"any_conditions_required" => "Bilo koji uvjet",
	"all_contacts" => "Svi kontakti",
	"removed" => "Poniteno",
	"please" => "Molimo vas",
	"and_check_form" => "i provjeri obrazac.",
	"and_try_again" => "pokuaj ponovno",
	"none" => "Niti jedan",
	"are_you_sure" => "Da li ste sigurni?",
	"delete_all" => "Poniti sve",
	"really?" => "Zaista?",
	"delete_are_you_sure" => "Ponititi æe te ovaj podatak, da li ste sigurni?",
	"required_fields_missed" => "Niste ispunili obavezna polja.",
	"alphabetic_not_valid" => "Umetnuli ste znamenke u polje za slova.",
	"numeric_not_valid" => "Umetnuli ste slova u numerièko polje.",
	"email_not_valid" => "Umetnuli ste jednu ili vie neispravnih email adresa.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Umetnuli ste jednu ili vie neispravnih URL adresa.",
	"phone_not_valid" => "Umetnuli ste jedan ili vie neispravnih telefonskih brojeva. <br>Molimo vas da koristite format \"+(country code)(area code)(number)\" npr. +3850523599318.",
	"date_not_valid" => "Umetnuli ste jedan ili vie neispravnih datuma.",
	"similar_records" => "Podaci ispod slièni su podacima koje elite umetnuti. <br>to elite uèiniti?",
	"no_records_found" => "Nije pronaðen niti jedan podatak.",
	"records_found" => "Pronaðeni podaci.",
	"number_records" => "Ukupno podataka: ",
	"details_of_record" => "Detalji podatka",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Auriraj podatak",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "prijasnji", 
	"next" => "sljedeci", 
	"edit_profile" => "Aurirajte informacije o vaem profilu",
	"i_think_that" => "Mislim da  ",
	"is_similar_to" => "slièno je ",
	"page" => "Stranica  ",
	"of" => " od ",
	"records_per_page" => "podaci po stranici",
	"day" => "Dan ",
	"month" => "Mjesec",
	"year" => "Godina",
	"administration" => "Administracija",
	"create_update_internal_table" => "Kreirajte ili aurirajte internu tablicu",
	"other...." => "drugo....",
	"insert_record" => "Umetni novi podatak",
	"search_records" => "Trai podatke",	
	"exactly" => "toèno kao",
	"like" => "kao",
	"required_fields_red" => "Obavezna polja su oznaèena crvenom bojom.",
	"insert_result" => "Rezultati umetanja:",
	"record_inserted" => "Podatak ispravno umetnut.",
	"update_result" => "Rezultat auriranja:",
	"record_updated" => "Podatak je ispravno auriran.",
	"profile_updated" => "Va profil ispravno je auriran.",
	"delete_result" => "Rezultat ponitavanja:",
	"record_deleted" => "Podatak je ispravno poniten.",
	"duplication_possible" => "Duplikacija je moguæa",
	"fields_max_length" => "Upisali ste previe teksta u jedno ili vie polja.",
	"current_upload" => "Sadanji dokument",
	"delete" => "Izbrii",
	"total_records" => "Ukupno podataka",
	"confirm_delete?" => "Potvrditi brisanje?",
	"is_equal" => "jednak je",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "sadrava",
	"starts_with" => "pocinje s",
	"ends_with" => "zavrsava s",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Eksportiraj u CSV",
	"new_insert_executed" => "novi podatak izvrsen", 
	"new_update_executed" => "izvrseno novo azuriranje",
	"null" => "Null", // to change
	"is_null" => "is null", // to change
	"is_empty" => "is empty", // to change
	"continue" => "continue" // to change
);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Pogreka, baza podataka prazna je.",
	"get" => "Pogreka u varijabli get.",
	"no_functions" => "Pogreka, nije izabrana niti jedna funkcija. Vratite se na poèetnu stranicu.",
	"no_unique_key" => "Pogreka, nemate niti jedan primarni kljuè u vaoj tablici.",	
	"upload_error" => "Dolo je do pogreke prilikom upload-a dokumenta.",
	"no_authorization_update_delete" => "Nemate autorizaciju za promjene/ponitavanje ovog podatka.",
	"no_authorization_view" => "Nemate autorizaciju za pregledavanje ovog podatka.",
	"deleted_only_authorizated_records" => "Poniteni su samo podaci za koje imate autorizaciju.",
	"record_from_which_you_come_no_longer_exists" => "registar odakle dolazite vise ne postoji",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "ovo je zadnji podatak", 
	"this_record_is_the_first_one" => "ovo je prvi podatak" 
	);

//login messages - new -
$login_messages_ar = array(
	"username" => "korisnik",
	"password" => "lozinka",
	"please_authenticate" => "Morate biti identificirani da bi ste nastavili",
	"login" => "login",
	"username_password_are_required" => "Trebate korisnièko ime i lozinku",
	"pwd_gen_link" => "Kreirajte lozinku",
	"incorrect_login" => "Korisnièko ime ili lozinka neispravni",
	"pwd_explain_text" =>"Upiite rijeè koja æe biti vaa lozinka i pritisnite <b>Kriptiraj!</b>.",
	"pwd_explain_text_2" =>"Pritisnite <b>Registriraj</b> kako bi ste ju upisali u obrazac ispod",
	"pwd_suggest_email_sending"=>"Moete si poslati email poruku s podacima o lozinki",
	"pwd_send_link_text" =>"poaljite poruku",
	"pwd_encrypt_button_text" => "Kriptiraj!",
	"pwd_register_button_text" => "Registrirajte lozinku i izaðite"
)
?>
