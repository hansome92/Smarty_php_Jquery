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
	"insert"    => "Wstaw nowy rekord",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Szukaj/zmien/usun rekordy",
	"insert_short"    => "Wstaw",
	"search_short" => "Szukaj",
	"insert_anyway"    => "Wstaw mimo wszystko",
	"search"    => "Szukaj rekordu",
	"update"    => "Zapisz",
	"ext_update"    => "Zmien swoj profil",
	"yes"    => "Tak",
	"no"    => "Nie",
	"go_back" => "Wroc",
	"edit" => "Popraw",
	"delete" => "Usun",
	"details" => "Szczegoly",
	"change_table" => "Zmien tablice"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Pokaz wszystkie rekordy",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Wyloguj",
	"top" => "Top", // to change
	"last_search_results" => "Last search results", // to change
	"show_all" => "Pokaz wszystko",
	"home" => "Strona domowa",
	"select_operator" => "Wybierz operator:",
	"all_conditions_required" => "Wszystkie warunki spelnione",
	"any_conditions_required" => "Dowolny warunek spelniony",
	"all_contacts" => "Wszystkie kontakty",
	"removed" => "usuniety",
	"please" => "Prosze",
	"and_check_form" => "i sprawdz formularz.",
	"and_try_again" => "i sprobuj ponownie.",
	"none" => "zaden",
	"are_you_sure" => "Jestes pewien?",
	"delete_all" => "Usun wszystko",
	"really?" => "Naprawde?",
	"delete_are_you_sure" => "Zamierzasz skasowac ponizszy rekord, jestes pewien?",
	"required_fields_missed" => "Nie wypelniles niektorych wymaganych pol.",
	"alphabetic_not_valid" => "Wpisales cyfry w polu literowym.",
	"numeric_not_valid" => "Wpisales niedozwolone znaki w polu cyfrowym.",
	"email_not_valid" => "Wpisany adres e-mail jest nieprawidlowy.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Wpisany URL jest nieprawidlowy.",
	"phone_not_valid" => "Wpisany numer telefonu jest nieprawidlowy.<br>Prosze uzyc formatu \"+(kod kraju)(kierunkowy)(numer)\" np +48711234567.",
	"date_not_valid" => "Wpisales jedna lub wiecej nieprawidlowych dat.",
	"similar_records" => "Rekordy ponizej wydaja sie podobne do tego, ktory wpisales.<br>Co chcesz zrobic?",
	"no_records_found" => "Nie znaleziono rekodow.",
	"records_found" => "rekordow znaleziono",
	"number_records" => "Ilosc rekordow: ",
	"details_of_record" => "Szczegoly rekordu",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Popraw rekord",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Previous", // to change
	"next" => "Next", // to change
	"edit_profile" => "Popraw swoj profil",
	"i_think_that" => "Mysle ze ",
	"is_similar_to" => " jest podobny do ",
	"page" => "Strona ",
	"of" => " z ",
	"records_per_page" => "records per page", // to change
	"day" => "Dzien",
	"month" => "Miesiac",
	"year" => "Rok",
	"administration" => "Administracja",
	"create_update_internal_table" => "Utworz lub zmien tablice wewnetrzna",
	"other...." => "inny....",
	"insert_record" => "Wstaw nowy rekord",
	"search_records" => "Szukaj rekordow",
	"exactly" => "dokladnie",
	"like" => "podobne do",
	"required_fields_red" => "Pola wymagane sa na czerwono.",
	"insert_result" => "Wynik wstawiania:",
	"record_inserted" => "Rekord wstawiony poprawnie.",
	"update_result" => "Wynik poprawiania:",
	"record_updated" => "Rekord zmieniony poprawnie.",
	"profile_updated" => "Zmiana profilu zakonczona poprawnie.",
	"delete_result" => "Wynik kasowania:",
	"record_deleted" => "Rekord skasowany poprawnie.",
	"duplication_possible" => "Powtarzanie mozliwe",
	"fields_max_length" => "Wpisales zbyt duzo informacji w jedno lub wiecej pol.",
	"current_upload" => "Biezacy plik",
	"delete" => "Usun",
	"total_records" => "Wszystkich rekordow",
	"confirm_delete?" => "Potwierdz usuniece?",
	"is_equal" => "jest rowny",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "zawiera",
	"starts_with" => "zaczyna sie od",
	"ends_with" => "konczy sie na",
	"greater_than" => "jest wieksze niz",
	"less_then" => "jest mniejsze niz",
	"export_to_csv" => "Wyslij do CSV",
	"new_insert_executed" => "New insert executed", // to change
	"new_update_executed" => "New update executed", // to change
	"null" => "Null", // to change
	"is_null" => "is null", // to change
	"is_empty" => "is empty", // to change
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Blad, wewnetrzna baza jest pusta.",
	"get" => "Blad przy czytaniu zmiennych.",
	"no_functions" => " Blad, nie wybrano fukncji.<br>Prosze wrocic do strony domowej.",
	"no_unique_key" => "Blad, w wybranej tablicy nie ma klucza glownego.",	
	"upload_error" => "Blad podczas ladowania pliku.",
	"no_authorization_update_delete" => "Nie masz uprawnien aby zmieniac/poprawiac rekord.",
	"no_authorization_view" => "Nie masz uprawnien, aby ogladac ten rekord.",
	"deleted_only_authorizated_records" => "Zostaly skasowane tylko te rekordy, do ktorych miales uprawnienie.",
	"record_from_which_you_come_no_longer_exists" => "The record from which you come no longer exists.", // to change
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "This record is the last one.", // to change
	"this_record_is_the_first_one" => "This record is the first one." // to change
	);



//login messages
$login_messages_ar = array(
	"username" => "Login",
	"password" => "Haslo",
	"please_authenticate" => "Musisz podac login i haslo aby przejsc dalej",
	"login" => "login", // to change
	"username_password_are_required" => "Username and password are required", // to change
	"pwd_gen_link" => "create password", // to change
	"incorrect_login" => "Login lub haslo nieprawidlowe",
	"pwd_explain_text" =>"Insert a word to be used as password and press <b>Crypt it!</b>.", // to change
	"pwd_explain_text_2" =>"Press <b>Register</b> to write it in the below form", // to change
	"pwd_suggest_email_sending"=>"You may want to send yourself a mail to remeber the password", // to change
	"pwd_send_link_text" =>"send mail!", // to change
	"pwd_encrypt_button_text" => "Cript it!", // to change
	"pwd_register_button_text" => "Register password and exit" // to change
)
?>