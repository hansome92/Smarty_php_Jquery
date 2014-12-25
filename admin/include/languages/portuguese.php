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
	"insert"    => "Inserir",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Buscar/atualizar/cancelar",
	"insert_short"    => "Inserir",
	"search_short" => "Buscar",
	"insert_anyway"    => "Inserir sempre",
	"search"    => "Buscar",
	"update"    => "Atualizar",
	"ext_update"    => "Estender atualização",
	"yes"    => "Sim",
	"no"    => "Nao",
	"go_back" => "Retornar",
	"edit" => "Editar",
	"delete" => "Cancelar",
	"details" => "Detalhes",
	"change_table" => "Modificar tabela"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Visualizar todos os dados",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Logout",
	"top" => "Top",
	"last_search_results" => "Resultados da última pesquisa",
	"show_all" => "Visualizar todos",
	"home" => "Início",
	"select_operator" => "Selecionar operador",
	"all_conditions_required" => "Todas as condições necessárias",
	"any_conditions_required" => "Quaisquer condições necessárias",
	"all_contacts" => "Todos os contatos",
	"removed" => "Cancelado",
	"please" => "Por favor",
	"and_check_form" => "E verificar a forma",
	"and_try_again" => "E tentar novamente",
	"none" => "Nenhum",
	"are_you_sure" => "Tem certeza?",
	"delete_all" => "Cancelar todos",
	"really?" => "Tem certeza?",
	"delete_are_you_sure" => "Tem certeza de que quer cancelar este dado?",
	"required_fields_missed" => "Faltam alguns campos necessários",
	"alphabetic_not_valid" => "alfabéticos não válidos",
	"numeric_not_valid" => "Você inseriu um/alguns caráter/es não numéricos em um campo numérico",
	"email_not_valid" => "Email não vãlido",
	"timestamp_not_valid" => "The timestamp/s you have inserted is/are not valid.", // to change
	"url_not_valid" => "Url não válida",
	"phone_not_valid" => "Numero de telefone não válido",
	"date_not_valid" => "Data não válida",
	"similar_records" => "Dados semelhantes",
	"no_records_found" => "Os dados não foram encontrados",
	"records_found" => "Dados encontrados",
	"number_records" => "Número de dados",
	"details_of_record" => "Detalhes do dado",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Modificar o dado",
	"previous" => "Anteriores",
	"next" => "Próximos",
	"back_to_the_main_table" => "Back to the main table", // to change
	"edit_profile" => "Editar o perfil",	
	"i_think_that" => "Eu acho que",
	"is_similar_to" =>" É parecido com ",
	"page" => "Página ",
	"of" => " de ",
	"records_per_page" => "registros por página",
	"day" => "Dia",	
	"month" => "Mês",
	"year" => "Ano",
	"administration" => "Administração",
	"create_update_internal_table" => "Criar ou atualizar a tabela interna",
	"other...." => "outro....",
	"insert_record" => "Inserir un novo dado",
	"search_records" => "Buscar dados",	
	"exactly" => "Exatamente",
	"like" => "parecido",
	"required_fields_red" => "Os campos necessários estão em vermelho",
	"insert_result" => "Inserir o resultado",
	"record_inserted" => "Dado inserido",
	"update_result" => "Resultado da atualização",
	"record_updated" => "Dado atualizado",
	"profile_updated" => "Perfil atualizado",
	"delete_result" => "Cancelar resultado",
	"record_deleted" => "Dado cancelado",
	"duplication_possible" => "Duplicaçao possível",
	"fields_max_length" => "Campos_máximo_comprimento",
	"current_upload" => "Upload corrente",
	"delete" => "Cancelar",
	"total_records" => "Dados totais",
	"confirm_delete?" => "Confirmar o cancelamento?",
	"is_equal" => "è igual a",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "Contém",
	"starts_with" => "Começa com",
	"ends_with" => "Termina com",
	"greater_than" => "Maior do que",
	"less_then" => "Menos que",
	"export_to_csv" => "Exportar a CSV",
	"new_insert_executed" => "Novos dados inseridos",
	"new_update_executed" => "Novas atualizações inseridas",
	"null" => "Nulo",
	"is_null" => "è nulo",
	"is_empty" => "è vazio",
	"continue" => "continue" // to change
);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "A base de dados interna está vazia",
	"get" => "obter",
	"no_functions" => "nenhuma função ",
	"no_unique_key" => "nenhuma tecla específica ",	
	"upload_error" => "erro de upload ",
	"no_authorization_update_delete" => "não foi autorizado, atualizar cancelamento ",
	"no_authorization_view" => "não existe visualização de autorização",
	"deleted_only_authorizated_records" => "somente cancelados dados autorizados ",
	"record_from_which_you_come_no_longer_exists" => "O registro de onde você vem não existe mais.",
	"date_not_representable" => "A date value in this record can't be represented with DaDaBIK day-month-year listboxes, the value is: ", // to change
	"this_record_is_the_last_one" => "Este registro é o último.",
	"this_record_is_the_first_one" => "Este registro é o primeiro."
	);
	
//login messages
$login_messages_ar = array(
	"username" => "nome do usuário",
	"password" => "senha",
	"please_authenticate" => "Por favor autenticar",
	"login" => "nome do usuário",
	"username_password_are_required" => "Username and password são necessárias",
	"pwd_gen_link" => "criar senha",
	"incorrect_login" => "nome do usuário incorreto.",
	"pwd_explain_text" =>"Inserir uma palavra para ser usada como senha e apertar <b>Crypt it!</b>.",
	"pwd_explain_text_2" =>"Apertar <b>Registrar</b> para escreve-la na forma abaixo",
	"pwd_suggest_email_sending"=>"Sugerir mensagem a ser enviada",
	"pwd_send_link_text" =>"enviar mensagem!",
	"pwd_encrypt_button_text" => "cifrar o proximo botão",
	"pwd_register_button_text" => "Registrar a senha e sair"
)
?>
