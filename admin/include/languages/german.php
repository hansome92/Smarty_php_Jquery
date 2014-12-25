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
	"insert"    => "Neuen Satz einfügen",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Sätze suchen/aktualisieren/löschen",
	"insert_short"    => "Einfügen",
	"search_short" => "Suchen",
	"insert_anyway"    => "troztdem einfügen",
	"search"    => "Suche nach Satz",
	"update"    => "Speichern",
	"ext_update"  => "Ihr Profil aktualisieren",
	"yes"    => "Ja",
	"no"    => "Nein",
	"go_back" => "zurück",
	"edit" => "Editieren",
	"delete" => "Löschen",
	"details" => "Details",
	"change_table" => "Tabelle wechseln"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Eintrag einfügen",
	"show_all_records" => "Alle Sätze zeigen",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Abmelden",
	"top" => "Top",
	"last_search_results" => "Letzte Suchergebnisse",

	"show_all" => "Alles zeigen",
	"home" => "Startseite",
	"select_operator" => "Verknüpfung wählen:",
	"all_conditions_required" => "Alle Bedingungen müssen zutreffen",
	"any_conditions_required" => "Eine Bedingung muss zutreffen",
	"all_contacts" => "Alle Kontakte",
	"removed" => "entfernt",
	"please" => "Bitte",
	"and_check_form" => "Überprüfen Sie das Formular.",
	"and_try_again" => "und versuchen Sie es erneut.", 
	"none" => "keine",
	"are_you_sure" => "Sind Sie sicher?",
	"delete_all" => "Alle löschen", 
	"really?" => "Wirklich ?", 
	"delete_are_you_sure" => "Sie wollen den nachstehenden Satz löschen. Sind Sie sicher?",
	"required_fields_missed" => "Sie haben einige erforderliche Felder nicht ausgefüllt.<br>Bitte füllen Sie alle erforderlichen Felder aus.",
	"alphabetic_not_valid" => "Sie haben numerische Werte in ein Alfa-Feld eingegeben.",
	"numeric_not_valid" => "Sie haben nichtnumerische Zeichen in ein numerisches Feld eingetragen.",
	"email_not_valid" => "Die eingegebene/n E-Mail-Adresse/n ist/sind nicht gültig.",
	"timestamp_not_valid" => "Der/Die eingefügte/n Zeitstempel ist/sind nicht gültig.",
	"url_not_valid" => "Die eingegebene/n URL/s ist/sind nicht gültig.",
	"phone_not_valid" => "Die eingegebene/n Telefon-Nummer/n ist/sind nicht gültig.",
	"date_not_valid" => "Sie haben ungültige Datumsangaben eingetragen.", 
	"similar_records" => "Die untenstehenden Sätze ähneln dem Satz, den Sie einfügen möchten.<br>Was möchten Sie tun?",
	"no_records_found" => "Keine Sätze gefunden.",
	"records_found" => "Sätze gefunden",
	"number_records" => "Anzahl Sätze: ",
	"details_of_record" => "Details des Satzes:",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Satz editieren",
	"back_to_the_main_table" => "Zurück zur Haupttabelle",
	"previous" => "Vorheriger",
	"next" => "Nächster",
	"edit_profile" => "Ihre Profil-Information aktualisieren",
	"i_think_that" => "Ich denke ",
	"is_similar_to" => " ähnelt folgendem ",
	"page" => "Seite ",
	"of" => " von ",
	"records_per_page" => "Sätze pro Seite",
	"day" => "Tag",
	"month" => "Monat",
	"year" => "Jahr",
	"administration" => "Administration",
	"create_update_internal_table" => "Interne Tabelle erstellen/aktualisieren",
	"other...." => "andere....",
	"insert_record" => "Neuen Satz einfügen",
	"search_records" => "Suche nach Sätzen",
	"exactly" => "exakt",
	"like"    => "ähnlich",
	"required_fields_red" => "Erforderliche Felder sind rot markiert.",
	"insert_result" => "Einfüge-Ergebnis:",
	"record_inserted" => "Satz korrekt eingefügt.",
	"update_result" => "Aktualisierungs-Ergebnis:",
	"record_updated" => "Satz korrekt aktualisiert.",
	"profile_updated" => "Ihr Profil wurde korrekt aktualisiert.",
	"delete_result" => "Lösch-Ergebnis:",
	"record_deleted" => "Satz korrekt gelöscht.",
	"duplication_possible" => "Duplizierung möglich","fields_max_length" => "Sie haben zuviel Text in ein oder mehrere Feld/er eingegeben.",
	"change_profile_url"  =>  "Zur Änderung Ihrer Profil-Information besuchen Sie bitte diese Seite",
	"current_upload" => "aktuelle Datei ",
	"delete" => "Löschen",
	"total_records" => "Gesamtanzahl Sätze",
	"confirm_delete?" => "Löschung bestätigen ?",
	"is_equal" => "entspricht",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "enthält",
	"starts_with" => "beginnt mit",
	"ends_with" => "endet mit",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Als CSV-Datei ausgeben",
	"new_insert_executed" => "Neu-Einfügung ausgeführt",
	"new_update_executed" => "Neu-Aktualisierung ausgeführt",
	"null" => "Null",
	"is_null" => "ist null",
	"is_empty" => "ist leer",
	"continue" => "continue" // to change

	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Fehler, die interne Datenbank ist leer.",
	"get" => "Fehler, beim Variablen-Abruf.",
	"no_functions" => "Fehler, keine Funktionen gewählt.<br>Bitte gehen Sie zurück zur Startseite.",
	"no_unique_key" => "Fehler, Sie haben keinen Primärschlüssel in Ihrer Tabelle.",	
	"upload_error" => "Während der Datei-Übertragung trat ein Fehler auf." ,
	"no_authorization_update_delete" => "Sie haben keine Berechtigung zum Ändern/Löschen des Datensatzes.",
	"no_authorization_view" => "Sie haben keine Berechtigung zum Ansehen des Datensatzes.",
	"deleted_only_authorizated_records" => "Es wurden nur die Sätze gelöscht, für die Sie eine Berechtigung haben.",
	"record_from_which_you_come_no_longer_exists" => "Der zuletzt angezeigte Datensatz existiert nicht mehr.",
	"date_not_representable" => "Ein Datumswert in diesem Datensatz kann nicht mit DaDaBIKs Tag-Monat-Jahr-Listbox angezeigt werden, der Wert ist:  ",
	"this_record_is_the_last_one" => "Dieser Datensatz ist der letzte.",
	"this_record_is_the_first_one" => "Dieser Datensatz ist der erste."

	);

//login messages
$login_messages_ar = array(
	"username" => "benutzername",
	"password" => "passwort",
	"please_authenticate" => "Zur Fortsetzung müssen Sie identifiziert sein",
	"login" => "Anmeldung",
	"username_password_are_required" => "Benutzername/Passwort sind erforderlich",
	"pwd_gen_link" => "passwort erstellen", 
	"incorrect_login" => "Benutzername/Passwort ist falsch",
	"pwd_explain_text" =>"Geben Sie ein Wort als Passwort ein und drücken Sie <b>Verschlüsseln!</b>.",
	"pwd_explain_text_2" =>"Klicken Sie <b>Registrieren</b> um es in das Formular einzutragen", 
	"pwd_suggest_email_sending"=>"Sie können sich selbst eine Email mit dem Passwort als Erinnerung schicken", 
	"pwd_send_link_text" =>"Email senden!", 
	"pwd_encrypt_button_text" => "Verschlüsseln!", 
	"pwd_register_button_text" => "Passwort registrieren und beenden"
)
?>