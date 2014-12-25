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
	"insert"    => "Inserisci un nuovo record",
	"quick_search"    => "Ricerca veloce",
	"search/update/delete" => "Cerca/aggiorna/cancella record",
	"insert_short"    => "Inserisci",
	"search_short" => "Cerca",
	"insert_anyway"    => "Inserisci comunque",
	"search"    => "Cerca record",
	"update"    => "Salva",
	"ext_update"    => "Aggiorna il tuo profilo",
	"yes"    => "Sì",
	"no"    => "No",
	"go_back" => "Torna indietro",
	"edit" => "Modifica",
	"delete" => "Cancella",
	"details" => "Dettagli",
	"change_table" => "Cambia tabella"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "Non è possibile modificare questo record, il record è bloccato dall'utente: ",
	"lost_locked_not_safe_update" => "Non hai il blocco oppure hai perso il blocco su questo recor, non  corretto effettuare un'update, devi ricominciare la modifica da capo",
	"insert_item" => "Inserisci item",
	"show_all_records" => "Visualizza tutti i records",
	"show_records" => "Mostra record", // to change
	"remove_search_filter" => "rimuovi filtro di ricerca", // to change
	"logout" => "Logout",
	"top" => "Top",
	"last_search_results" => "Risultati ultima ricerca",
	"show_all" => "Visualizza tutti",
	"home" => "Home",
	"select_operator" => "Seleziona operatore:",
	"all_conditions_required" => "Tutte le condizioni",
	"any_conditions_required" => "Almeno una condizione",
	"all_contacts" => "Tutti i contatti",
	"removed" => "cancellato/i",
	"please" => "",
	"and_check_form" => "e controlla il form.",
	"and_try_again" => "e prova ancora.",
	"none" => "nessuno",
	"are_you_sure" => "Sei sicuro?",
	"delete_all" => "cancella tutti",
	"really?" => "Veramente?",
	"delete_are_you_sure" => "Stai per cancellare questo record, sei sicuro?",
	"required_fields_missed" => "Non hai compilato alcuni campi obbligatori.",
	"alphabetic_not_valid" => "Hai inserito un/alcuni numero/i in un campo alfabetico.",
	"numeric_not_valid" => "Hai inserito un/alcuni carattere/i non numerici in un campo numerico.",
	"email_not_valid" => "Hai inserito uno o piu' indirizzi e-mail non validi.",
	"timestamp_not_valid" => "Hai inserito uno o piu' timestamp non validi.",
	"url_not_valid" => "Hai inserito uno o più URL non validi.",
	"phone_not_valid" => "Hai inserito uno o piu' numeri di telefono non validi.<br>Devi utilizzare il formato \"+(codice nazionale)(prefisso)(numero)\" es.. +390523599318.",
	"date_not_valid" => "Hai inserito una o più date non valide.",
	"similar_records" => "I seguenti record sembrano simili a quello che vuoi inserire.<br>Come vuoi procedere?",
	"no_records_found" => "Non è stato trovato nessun record.",
	"records_found" => "record trovati.",
	"number_records" => "Numero di record: ",
	"details_of_record" => "Dettagli del record",
	"details_of_record_just_inserted" => "Dettagli del record appena inserito",
	"edit_record" => "Modifica il record",
	"back_to_the_main_table" => "Torna alla tabella principale",
	"previous" => "Precedente",
	"next" => "Successivo",
	"edit_profile" => "Aggiorna le informazioni relative al tuo profilo",
	"i_think_that" => "Penso che  ",
	"is_similar_to" => " sia simile a ",
	"page" => "Pagina ",
	"of" => " di ",
	"records_per_page" => "record per pagina",
	"day" => "Giorno",
	"month" => "Mese",
	"year" => "Anno",
	"administration" => "Amministrazione",
	"create_update_internal_table" => "Crea o aggiorna la tabella interna",
	"other...." => "altro....",
	"insert_record" => "Inserisci un nuovo record",
	"search_records" => "Cerca record",	
	"exactly" => "uguale",
	"like" => "simile",
	"required_fields_red" => "I campi obbligatori sono in rosso.",
	"insert_result" => "Risultato dell'inserimento:",
	"record_inserted" => "Il record è stato inserito correttamente.",
	"update_result" => "Risultato dell'aggiornamento:",
	"record_updated" => "Il record è stato aggiornato correttamente.",
	"profile_updated" => "Il tuo profilo è stato correttamente aggiornato.",
	"delete_result" => "Risultato della cancellazione:",
	"record_deleted" => "Il record è stato cancellato correttamente.",
	"duplication_possible" => "E' possibile che si verifichi una duplicazione",
	"fields_max_length" => "Hai inserito troppo testo in uno o più caratteri.",
	"current_upload" => "File corrente",
	"delete" => "cancella",
	"total_records" => "Record totali",	
	"confirm_delete?" => "Confermi la cancellazione?",
	"is_equal" => "è uguale a",
	"is_different" => "è diverso da",
	"is_not_null" => "non è null",
	"is_not_empty" => "non è vuoto",
	"contains" => "contiene",
	"starts_with" => "inizia con",
	"ends_with" => "finisce con",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Esporta a CSV",
	"new_insert_executed" => "Nuovo inserimento eseguito",
	"new_update_executed" => "Nuovo aggiornamento eseguito",
	"null" => "Null",
	"is_null" => "è null",
	"is_empty" => "è vuoto",
	"continue" => "Continua"
);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Errore, il database interno è vuoto.",
	"get" => "Errore nelle variabili get.",
	
	
	"no_functions" => "Errore, non è stata selezionata alcuna funzione<br>Torna alla homepage.",
	"no_unique_key" => "Errore, non e' stata impostato nessuna chiave primaria nella tabella.",	
	"upload_error" => "Si è verificato un errore durante l'upload del file.",
	"no_authorization_update_delete" => "Non hai l'autorizzazione per modificare/cancellare questo record.",
	"no_authorization_view" => "Non hai l'autorizzazione per vedere questo record.",
	"deleted_only_authorizated_records" => "Sono stati cancellati solo i record che sei autorizzato a cancellare.",
	"record_from_which_you_come_no_longer_exists" => "Il record dal quale provieni non esiste più.",
	"date_not_representable" => "Una data presente in questo record non pu essere rappresentata con le listbox giorno-mese-anno, la data : ",
	"this_record_is_the_last_one" => "Questo record è l'ultimo.",
	"this_record_is_the_first_one" => "Questo record è il primo."
	);
	
//login messages
$login_messages_ar = array(
	"username" => "username",
	"password" => "password",
	"please_authenticate" => "Devi identificarti per procedere",
	"login" => "login",
	"username_password_are_required" => "Username e password sono obbligatori",
	"pwd_gen_link" => "crea password",
	"incorrect_login" => "Username o password errati",
	"pwd_explain_text" =>"Inserisci una parola da utilizzare come password e premi <b>Cripta!</b>.",
	"pwd_explain_text_2" =>"Premi <b>Registra</b> per scriverla nella form sottostante",
	"pwd_suggest_email_sending"=>"Puoi inviarti una mail come promemoria della password",
	"pwd_send_link_text" =>"invia mail!",
	"pwd_encrypt_button_text" => "Cripta!",
	"pwd_register_button_text" => "Registra password ed esci"
)
?>