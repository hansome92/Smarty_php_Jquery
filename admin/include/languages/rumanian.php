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
	"insert"    => "Insereaza o alta înregistrare",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Cauta/actualizeaza/sterge înregistrarea",
	"insert_short"    => "Insereaza",
	"search_short" => "Cauta",
	"insert_anyway"    => "Insereaza totusi",
	"search"    => "Cauta înregistrarea",
	"update"    => "Salveaza",
	"ext_update"    => "Actualizeaza-ti profilul",
	"yes"    => "Da",
	"no"    => "Nu",
	"go_back" => "Mergi înapoi",
	"edit" => "Modifica",
	"delete" => "Sterge",
	"details" => "Detalii",
	"change_table" => "Modifica tabelul"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Vizualizeaza toate înregistrarile",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Logout",
	"top" => "Top",
	"last_search_results" => "Ultimele rezultate ale cautarii",
	"show_all" => "Vizualizeaza tot",
	"home" => "Home",
	"select_operator" => "Selecteaza operatorul:",
	"all_conditions_required" => "Toate conditiile cerute",
	"any_conditions_required" => "Cel putin o conditie",
	"all_contacts" => "Toate contactele",
	"removed" => "Sters",
	"please" => "",
	"and_check_form" => "si controleaza formularul.",
	"and_try_again" => "si încearca înca o data.",
	"none" => "nici unul",
	"are_you_sure" => "Esti sigur?",
	"delete_all" => "sterge tot",
	"really?" => "Sigur?",
	"delete_are_you_sure" => "Esti sigur ca vrei sa stergi?",
	"required_fields_missed" => "Nu ai completat câteva câmpuri obligatorii.",
	"alphabetic_not_valid" => "Ai inserat cifre în loc de litere.",
	"numeric_not_valid" => "Ai inserat litere în loc de cifre.",
	"email_not_valid" => "Ai inserat una sau mai multe adrese e-mail incorecte.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Ai inserat una sau mai multe adrese URL incorecte.",
	"phone_not_valid" => "Ai inserat unul sau mai multe numere de telefon incorecte.<br>Trebuie sa folosesti formatul \"+(codul national)(prefix)(numar)\" es.. +390523599318.",
	"date_not_valid" => "Ai inserat una sau mai multe date incorecte.",
	"similar_records" => "Înregistrarile care urmeaza par asemanatoare cu cele pe care vrei sa le inserezi.<br>Cum vrei sa procedezi?",
	"no_records_found" => "N-a fost gasita nici o înregistrare.",
	"records_found" => "Înregistrari gasite.",
	"number_records" => "Numarul înregistrarilor: ",
	"details_of_record" => "Detaliile înregistrarii",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Modifica înregistrarea",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Precedenta",
	"next" => "Urmatoare",
	"edit_profile" => "Actualizeaza-ti informatiile cu privire la profil",
	"i_think_that" => "Cred ca",
	"is_similar_to" => "seamana cu",
	"page" => "Pagina",
	"of" => "din",
	"records_per_page" => "înregistrarile pe pagina",
	"day" => "ziua",
	"month" => "luna",
	"year" => "anul",
	"administration" => "Administrare",
	"create_update_internal_table" => "Creaza sau actualizeaza tabelul intern",
	"other...." => "alt...",
	"insert_record" => "Insereaza o înregistrare noua",
	"search_records" => "Cauta o înregistrare",	
	"exactly" => "identica",
	"like" => "asemanatoare",
	"required_fields_red" => "Câmpurile obligatorii sunt rosii.",
	"insert_result" => "Rezultatul inserarii:",
	"record_inserted" => "Înregistrarea a fost inserata corect.",
	"update_result" => "Rezultatul actualizarii:",
	"record_updated" => "Înregistrarea a fost actualizata corect.",
	"profile_updated" => "Profilul a fost actualizat corect.",
	"delete_result" => "Rezultatul stergerii:",
	"record_deleted" => "Înregistrarea a fost stearsa corect.",
	"duplication_possible" => "Exista posibilitatea unei dublari",
	"fields_max_length" => "Ai atins lungimea maxima pentru acest câmp.",
	"current_upload" => "Fisierul actual",
	"delete" => "sterge",
	"total_records" => "Înregistrarile totale",	
	"confirm_delete?" => "Confirmi stergerea?",
	"is_equal" => "e identic cu",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "contine",
	"starts_with" => "începe cu",
	"ends_with" => "se termina cu",
	"greater_than" => "mai mult decât",
	"less_then" => "mai putin decât",
	"export_to_csv" => "Exporta ca CSV",
	"new_insert_executed" => "noua inserare a fost savârsita",
	"new_update_executed" => "noua actualizare a fost savârsita",
	"null" => "Null",
	"is_null" => "este NULL",
	"is_empty" => "este gol",
	"continue" => "continue" // to change
);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Eroare, baza de date interna e goala.",
	"get" => "Eroare în variabile get.",
	"no_functions" => "Eroare, nici o functie n-a fost selectata<br>Întoarce-te la homepage.",
	"no_unique_key" => "Eroare, nici o cheie unica n-a fost selectata în tabel.",	
	"upload_error" => "În timpul de încarcare al fisierului s-a produs o eroare.",
	"no_authorization_update_delete" => "Nu ai autorizatia sa modifici/stergi aceasta înregistrare.",
	"no_authorization_view" => "Nu ai autorizatia sa vizualizezi aceasta înregistrare.",
	"deleted_only_authorizated_records" => "Numai înregistrarile pentru care ai o autorizatie au fost sterse.",
	"record_from_which_you_come_no_longer_exists" => "Înregistrarea de care ati venit nu mai exista.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Aceasta înregistrare e ultima.",
	"this_record_is_the_first_one" => "Aceasta înregistrare e cea dintâi."
	);
	
//login messages
$login_messages_ar = array(
	"username" => "username",
	"password" => "parola",
	"please_authenticate" => "Trebuie sa te identifici pentru a proceda",
	"login" => "login",
	"username_password_are_required" => "Puneti username si parola",
	"pwd_gen_link" => "Create password",
	"incorrect_login" => "Numele sau parola sunt gresite",
	"pwd_explain_text" =>"Insereaza un cuvânt ca sa fie folosit drept parola a acestui utilizator si apasa <b>Cripteaza!</b> ca sa fie criptat.",
	"pwd_explain_text_2" =>"Apasa <b>Înregistreaza</b> ca sa fie scris în formularul urmator",
	"pwd_suggest_email_sending"=>"daca vrei poti sa-ti trimiti un mesaj ca sa-ti amintesti parola fara criptare",
	"pwd_send_link_text" =>"Trimite!",
	"pwd_encrypt_button_text" => "Cripteaza!",
	"pwd_register_button_text" => "Înregistreaza parola si închide fereastra"
)
?>
