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
	"insert"    => "Inserte un nuevo registro",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Buscar/actualizar/borrar registros",
	"insert_short"    => "Insertar", 
	"search_short" => "Buscar",
	"insert_anyway"    => "Insertar de todos modos",
	"search"    => "Buscar registro",
	"update"    => "Actualizar",
	"ext_update"    => "Actualizar su perfil",
	"yes"    => "Si",
	"no"    => "No",
	"go_back" => "Atrás",
	"edit" => "Editar",
	"delete" => "Borrar",
	"details" => "Detalles",
	"change_table" => "Cambiar tabla" 
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Mostrar todos los registros",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Cerrar sesión",
	"top" => "Tope",
	"last_search_results" => "últimos resultados",
	"show_all" => "Mostrar todo", 
	"home" => "Principal",
	"select_operator" => "Seleccione el operador:",
	"all_conditions_required" => "Todas las condiciones requeridas",
	"any_conditions_required" => "Cualquiera de las condiciones requeridas",
	"all_contacts" => "Todos los contactos", 
	"removed" => "removido",
	"please" => "Por Favor",
	"and_check_form" => "y revise la forma.",
	"and_try_again" => "e intente de nuevo.",
	"none" => "ninguno",
	"are_you_sure" => "¿Esta seguro?",
	"delete_all" => "borrar todo",
	"really?" => "Seguro?",
	"delete_are_you_sure" => "Esta a punto de borrar el registro inferior, esta seguro?",
	"required_fields_missed" => "No ha llenado algunos de los campos requeridos.",
	"alphabetic_not_valid" => "Ha insertado números en un campo alfabético.",
	"numeric_not_valid" => "Ha insertado caracteres no numéricos en un campo numérico.",
	"email_not_valid" => "La dirección de e-mail que especificó no es valida.",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "la(s) URL(s) especificadas no es/son valida(s)",
	"phone_not_valid" => "El numero telefonico insertado no es valido.",
	"date_not_valid" => "Ha insertado una o mas fechas erradas.",
	"similar_records" => "Los registros inferiores son similares a los que esta a puntop de agregar.<br>¿Qué desea hacer?",
	"no_records_found" => "No se encontraron registos.",
	"records_found" => "registros encontrados",
	"number_records" => "Numero de registros: ",
	"details_of_record" => "Detalles del registro",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Editar el registro",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Anterior",
	"next" => "Siguiente",
	"edit_profile" => "Editar su perfil",
	"i_think_that" => "Creo que ",
	"is_similar_to" => " es similar a ",
	"page" => "Página ",
	"of" => " de ",
	"records_per_page" => "registros por página",
	"day" => "Día",
	"month" => "Mes",
	"year" => "Año",
	"administration" => "Administración",
	"create_update_internal_table" => "Crear o modificar tabla interna",
	"other...." => "other....",
	"insert_record" => "Agregar nuevo registro",
	"search_records" => "Buscar registros",
	"exactly" => "exacto",
	"like" => "similar",
	"required_fields_red" => "Los campos requeridos estan en rojo.",
	"insert_result" => "Insertar resultado:",
	"record_inserted" => "registro exitosamente agregado.",
	"update_result" => "Actualizar resultado:",
	"record_updated" => "Reistro actualizado.",
	"profile_updated" => "Su perfil ha sido actualizado.",
	"delete_result" => "Borrar resultado:",
	"record_deleted" => "Reistro eliminado.",
	"duplication_possible" => "Es posible duplicar",
	"fields_max_length" => "Ha insertado demasiado texto en uno o mas campos.",
	"current_upload" => "Archivo actual",
	"delete" => "Borrar",
	"total_records" => "Total de registros",
	"confirm_delete?" => "Confirma el borrado?",
	"is_equal" => "es igual a",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "contiene",
	"starts_with" => "comienza con",
	"ends_with" => "termina con",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Exportar a CSV",
	"new_insert_executed" => "Inserción ejecutada",
	"new_update_executed" => "modificación ejecutada",
	"null" => "Null", // to change
	"is_null" => "is null", // to change
	"is_empty" => "is empty", // to change
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Error, la base de datos interna esta vacia.",
	"get" => "Error obteniendo variables.",
	"no_functions" => "Error, no se eligieron funciones<br>Por favor vuelva a la pagina principal.",
	"no_unique_key" => "Error, no se determino clave primaria en su tabla.",
	"upload_error" => "Un error ha ocurrido mientras se subia el archivo.",
	"no_authorization_update_delete" => "No tienes autorización para modificar/eliminar este registro.",
	"no_authorization_view" => "No tienes autorización para consultar este registro.",
	"deleted_only_authorizated_records" => "Han sido borrados los registros sobre los cuales tienes autorización.",
	"record_from_which_you_come_no_longer_exists" => "El registro del que proviene ya no existe.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Este es el último registro.",
	"this_record_is_the_first_one" => "Este es el primer registro."
	);

//login messages
$login_messages_ar = array(
	"username" => "usuario",
	"password" => "clave",
	"please_authenticate" => "Necesitas identificarte para continuar",
	"login" => "login",
	"username_password_are_required" => "Se requiere Usuario y Clave",
	"pwd_gen_link" => "crear clave",
	"incorrect_login" => "Usuario o clave incorrectos",
	"pwd_explain_text" =>"Inserte una palabra a ser usada como clave y presione <b>Encriptar!</b>",
	"pwd_suggest_email_sending"=>"Si lo desea puede enviar un mensaje usted mismo para recordar la clave",
	"pwd_send_link_text" =>"enviar mensaje!",
	"pwd_encrypt_button_text" => "Encriptar!",
	"pwd_register_button_text" => "Registrar clave y salir"
)
?>