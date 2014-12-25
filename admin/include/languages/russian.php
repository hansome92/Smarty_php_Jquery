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
	"insert"    => "Вставить новую запись",
	"quick_search"    => "Quick search", // to change
	"search/update/delete" => "Поиск/обновить/удалить",
	"insert_short"    => "Создать новый",
	"search_short" => "Поиск",
	"insert_anyway"    => "Вставить полюбому",
	"search"    => "Поиск записи",
	"update"    => "Сохранить",
	"ext_update"    => "Обновить профиль",
	"yes"    => "Да",
	"no"    => "Нет",
	"go_back" => "Назад",
	"edit" => "Правка",
	"delete" => "Удалить",
	"details" => "Детали",
	"change_table" => "Изменить таблицу"
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "You can't edit this record, the record is locked by user: ", // to change
	"lost_locked_not_safe_update" => "You dont't have or have lost your lock on this record, it is not safe to update, please start again the edit", // to change
	"insert_item" => "Создать новый",
	"show_all_records" => "Показать все записи",
	"show_records" => "Show items", // to change
	"remove_search_filter" => "remove search filter", // to change
	"logout" => "Выход",
	"top" => "Верх",
	"last_search_results" => "Последний поиск",
	"show_all" => "Показать все",
	"home" => "Назад",
	"select_operator" => "Выбрать оператор:",
	"all_conditions_required" => "Поиск по нескольким полям",
	"any_conditions_required" => "Поиск по одному полю",
	"all_contacts" => "Все контакты",
	"removed" => "удалено",
	"please" => "Пожалуйста",
	"and_check_form" => "и проверте форму.",
	"and_try_again" => "и попробуйте снова.",
	"none" => "нет",
	"are_you_sure" => "Вы уверены?",
	"delete_all" => "удалить все",
	"really?" => "Уверены?",
	"delete_are_you_sure" => "Вы собираетесь удалить запись, вы уверены?",
	"required_fields_missed" => "Вы не заполнили некоторые обязательные поля.",
	"alphabetic_not_valid" => "Вы вставили число в текстовую область",
	"numeric_not_valid" => "Вы вставили не цифровое значение в цифровое поле",
	"email_not_valid" => "Не верный адрес e-mail.",
	"timestamp_not_valid" => "Не верный timestamp",
	"url_not_valid" => "The url/s you have inserted is/are not valid.",
	"phone_not_valid" => "Неверный номер телефона.<br>Надо так \"+(код страны)(код области)(номер)\" например +79222359931.",
	"date_not_valid" => "Неверная дата",
	"similar_records" => "Есть совпадающие записи.<br>Что вы хотите?",
	"no_records_found" => "Не найдено записей",
	"records_found" => "записей найдено",
	"number_records" => "Номеров записей: ",
	"details_of_record" => "Детали записей",
	"details_of_record_just_inserted" => "Details of the record just inserted", // to change
	"edit_record" => "Редактировать запись",
	"back_to_the_main_table" => "Назад в главную таблицу",
	"previous" => "Предыдущий",
	"next" => "Следующий",
	"edit_profile" => "Обновить профиль",
	"i_think_that" => "Я думаю, что ",
	"is_similar_to" => " подробно ",
	"page" => "страница ",
	"of" => " из ",
	"records_per_page" => "записей на странице",
	"day" => "День",
	"month" => "Месяц",
	"year" => "год",
	"administration" => "Администрирование",
	"create_update_internal_table" => "Создать или обновить таблицу",
	"other...." => "другие...",
	"insert_record" => "Вставить новую запись",
	"search_records" => "Поиск записей",
	"exactly" => "точно",
	"like" => "содержит",
	"required_fields_red" => "Обязательные поля в красном",
	"insert_result" => "Вставить результат:",
	"record_inserted" => "Запись успешно добавлена.",
	"update_result" => "Обновить результат:",
	"record_updated" => "Запись удачно обновлена.",
	"profile_updated" => "Ваш профиль обновлен.",
	"delete_result" => "Удалить результаты:",
	"record_deleted" => "Запись благополучно удалена",
	"duplication_possible" => "Дублирование возможно",
	"fields_max_length" => "Слишком длинный текст.",
	"current_upload" => "Current file", // to change 
	"delete" => "удалить",
	"total_records" => "Всего записей",
	"confirm_delete?" => "Точно удалить?",
	"is_equal" => "полностью",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "часть",
	"starts_with" => "с начала",
	"ends_with" => "с конца",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Экспорт в CSV формат",
	"new_insert_executed" => "Запись добавлена",
	"new_update_executed" => "Запись обновлена",
	"null" => "Без записи",
	"is_null" => "без значения",
	"is_empty" => "пусто",
	"continue" => "continue" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Ошибка, внутренняя база данных пуста.",
	"get" => "Ошибка получения переменной",
	"no_functions" => "Выбранная функция<br>Вернитесь на главную страницу.",
	"no_unique_key" => "Нет ключа в таблице",	
	"upload_error" => "Ошибка загрузки файла",
	"no_authorization_update_delete" => "Нужна авторизация для правки и удаления записи",
	"no_authorization_view" => "Вы не авторизированы для просмотра этой записи",
	"deleted_only_authorizated_records" => "Записи, на которых у Вас есть разрешение, были удалены.",
	"record_from_which_you_come_no_longer_exists" => "Отчет, из которого Вы не происходите больше, существует.",
	"date_not_representable" => "Дата в этой записи не может быть представлена в поле со списком DaDaBIK, значение: ",
	"this_record_is_the_last_one" => "Этот отчет - последний.",
	"this_record_is_the_first_one" => "Этот отчет - первый."
	);

//login messages
$login_messages_ar = array(
	"username" => "Логин",
	"password" => "Пароль",
	"please_authenticate" => "Нужно авторизоваться",
	"login" => "Вход",
	"username_password_are_required" => "Требуется авторизация",
	"pwd_gen_link" => "создать пароль",
	"incorrect_login" => "Введите логин и пароль",
	"pwd_explain_text" =>"Напишите пароль и нажмите <b>Записать!</b>.",
	"pwd_explain_text_2" =>"Нажмите <b>Регистрация</b> для записи в форму",
	"pwd_suggest_email_sending"=>"Вы можете отправить себе email с паролем",
	"pwd_send_link_text" =>"Отправить почту!",
	"pwd_encrypt_button_text" => "Записать!",
	"pwd_register_button_text" => "Зарегистрировать пароль и выйти"
);
?>