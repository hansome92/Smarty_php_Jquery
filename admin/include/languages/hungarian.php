<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) http://www.dadabik.org/
Copyright (C) 2001-2012  Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). At a glance, you can use this program and you can modifiy it to create a database application for you or for other people but you cannot redistribute the program files in any format. All the details, including examples, in dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact eugenio.tacchini@gmail.com
***********************************************************************************
*/
?>
<?php
// submit buttons
$submit_buttons_ar = array (
	"insert"    => "Új bejegyzés hozzáadása",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Bejegyzés(ek) keresése/frissítése/törlése",
	"insert_short"    => "Hozzáad",
	"search_short" => "Keres",
	"insert_anyway"    => "Mégiscsak illessze be",
	"search"    => "Bejegyzés(ek) keresése",
	"update"    => "Mentés",
	"ext_update"    => "Profil frissítése",
	"yes"    => "Igen",
	"no"    => "Nem",
	"go_back" => "Vissza",
	"edit" => "Szerkesztés",
	"delete" => "Törlés",
	"details" => "Részletek",
	"change_table" => "Tábla hozzáadása"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Create new item", // to change
	"show_all_records" => "Összes bejegyzés megjelenítése",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Kijelentkezés",
	"top" => "Tetejére",
	"last_search_results" => "Legutóbbi keresés eredménye",
	"show_all" => "Összes",
	"home" => "Kezdõlap",
	"select_operator" => "Válasszon elválasztót:",
	"all_conditions_required" => "Minden feltételnek feleljen meg",
	"any_conditions_required" => "Bármely feltételnek megfelehet",
	"all_contacts" => "Minden kapcsolat",
	"removed" => "eltávolításra került",
	"please" => "Kérem",
	"and_check_form" => "és nézze át a táblát.",
	"and_try_again" => "és próbálja újra.",
	"none" => "egy sem",
	"are_you_sure" => "Biztos benne?",
	"delete_all" => "összes törlése",
	"really?" => "Valóban?",
	"delete_are_you_sure" => "Kitörli az összes alábbi bejegyzést? Biztos ebben?",
	"required_fields_missed" => "Nem töltött ki egy/néhány kötelezõ mezõt.",
	"alphabetic_not_valid" => "Számokat írt egy mezõbe, amelybe betûk valók.",
	"numeric_not_valid" => "Helytelen betûket írt egy mezõbe, melybe számok valók.",
	"email_not_valid" => "Az email cím(ek), amely(ek)et megadott helytelen(ek).",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Az URL(ek), amely(ek)et megadott, helytelen(ek).",
	"phone_not_valid" => "A telefonszám(ok), amely(ek)et megadott, helytelen(ek).<br>Használja az alábbi formátumot: \"+(ország kód)(területi kód)(telefonszám)\" pl. +36301234567.",
	"date_not_valid" => "Egy, vagy több megadott dátum helytelen formátumú.",
	"similar_records" => "Az alábbi bejegyzések hasonlóak ahhoz, amelyet hozzá szeretne adni.<br>Mit szeretne tenni?",
	"no_records_found" => "Nincs megfelelõ bejegyzés.",
	"records_found" => "megfelelõ bejegyzés található",
	"number_records" => "Bejegyzések száma: ",
	"details_of_record" => "Bejegyzés részletei",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Bejegyzés szerkesztése",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Elõzõ",
	"next" => "Következõ",
	"edit_profile" => "Profil adatok szerkesztése",
	"i_think_that" => "Azt gondolom, hogy ",
	"is_similar_to" => " hasonlít ehhez: ",
	"page" => "Oldalszám: ",
	"of" => " összes oldal: ",
	"records_per_page" => "Oldalankénti bejegyzés",
	"day" => "Nap",
	"month" => "Hónap",
	"year" => "Év",
	"administration" => "Adminisztráció",
	"create_update_internal_table" => "Belsõ tábla létrehozása/frissítése.",
	"other...." => "más....",
	"insert_record" => "Új bejegyzés hozzáadása",
	"search_records" => "Bejegyzés keresése",
	"exactly" => "ugyanolyan",
	"like" => "hasonló",
	"required_fields_red" => "A kötelezõ mezõk pirosak.",
	"insert_result" => "Hozzáadás eredménye:",
	"record_inserted" => "Bejegyzés sikeresen hozzáadva.",
	"update_result" => "Frissítés eredménye:",
	"record_updated" => "Bejegyzés sikeresen frissítve.",
	"profile_updated" => "Profil sikeresen frissítve.",
	"delete_result" => "Törlés eredménye:",
	"record_deleted" => "Bejegyzés sikeresen törölve.",
	"duplication_possible" => "Ismételt elõfordulás megengedett",
	"fields_max_length" => "Túl sok adatot vitt be valamelyik beviteli mezõbe.",
	"current_upload" => "Aktuális feltöltendõ fájl",
	"delete" => "törlés",
	"total_records" => "Összes bejegyzés",
	"confirm_delete?" => "Megerõsíti a törlést?",
	"is_equal" => "azonos",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "tartalmazza",
	"starts_with" => "kezdõdik",
	"ends_with" => "végzõdik",
	"greater_than" => "nagyobb",
	"less_then" => "kisebb",
	"export_to_csv" => "CSV exporálás",
	"new_insert_executed" => "Beillesztés végrehajtve",
	"new_update_executed" => "Frissítés végrehajtva",
	"null" => "Null",
	"is_null" => "nulla",
	"is_empty" => "&uuml;res",
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Hiba: a belsõ adatbázis üres.",
	"get" => "Hiba: a változók lekérdezése közben.",
	"no_functions" => "Hiba: válasszon a leehtõségek közül<br>Kérem menjen vissza a kiiduló lapra.",
	"no_unique_key" => "Hiba: nincsen elsõdleges azonosító kulcs az adatbázisban.",	
	"upload_error" => "Hiba jelentkezett a fájl feltöltése során.",
	"no_authorization_update_delete" => "Nincs meg a jogosultsága a bejegyzés frissítéséhez/törléséhez.",
	"no_authorization_view" => "Nincs joga, hogy megnézze a bejegyzést.",
	"deleted_only_authorizated_records" => "Csak azon bejegyzések törlõdtek, melyekhez jogosultsággal rendelkezett.",
	"record_from_which_you_come_no_longer_exists" => "A bejegyzés már nem létezik.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Ez az utolsó bejegyzés.",
	"this_record_is_the_first_one" => "Ez az elsõ bejegyzés."
	);

//login messages
$login_messages_ar = array(
	"username" => "Felhasználó",
	"password" => "Jelszó",
	"please_authenticate" => "Folytatáshoz be kell jelentkeznie",
	"login" => "bejelentkezés",
	"username_password_are_required" => "Username and password are required",
	"pwd_gen_link" => "jelszó létrehozása",
	"incorrect_login" => "Helytelen felhasználó, vagy jelszó",
	"pwd_explain_text" =>"Írja be a kívánt jelszót és kattintson a <b>Titkosítás!</b> gombra.",
	"pwd_explain_text_2" =>"Nyomja meg a <b>Regisztrálás</b> gombot, hogy átmásolódjon a megfelelõ mezõbe.",
	"pwd_suggest_email_sending"=>"Elküldheti magának a jelszót egy emailben, ha kívánja.",
	"pwd_send_link_text" =>"email küldése!",
	"pwd_encrypt_button_text" => "Titkosítás!",
	"pwd_register_button_text" => "A jelszó regisztrálása és kilépés"
);
?>