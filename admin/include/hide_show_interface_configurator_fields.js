function hide_show_interface_configurator_fields(field, field_position){

for (i=1;i<=20;i++){
	if (document.getElementById(i)){
	var tableRow = document.getElementById(i);

	tableRow.style.display = 'none';
	}
}




if(field.value == 'select_single'){

var tableRow = document.getElementById('1');

tableRow.style.display = 'table-row';
var tableRow = document.getElementById('2');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('3');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('4');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('5');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('6');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('7');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('9');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('14');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';
}

if(field.value == 'text' || field.value == 'textarea' || field.value == 'rich_editor'){

var tableRow = document.getElementById('10');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('11');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('12');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('14');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';

}

if(field.value == 'text' || field.value == 'textarea'){


var tableRow = document.getElementById('16');
tableRow.style.display = 'table-row';
}

if(field.value == 'text' || field.value == 'textarea' || field.value == 'select_single'){

var tableRow = document.getElementById('20');
tableRow.style.display = 'table-row';
}

if( field.value == 'textarea' || field.value == 'rich_editor'){


var tableRow = document.getElementById('13');
tableRow.style.display = 'table-row';
}

/*
if(field.value == 'password'){
var tableRow = document.getElementById('12');
tableRow.style.display = 'table-row';

var tableRow = document.getElementById('14');
tableRow.style.display = 'table-row';

}
*/

if(field.value == 'date' || field.value == 'date_time'){


var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';

}

if(field.value == 'generic_file' || field.value == 'image_file'){


var tableRow = document.getElementById('15');
tableRow.style.display = 'table-row';
var tableRow = document.getElementById('16');
tableRow.style.display = 'table-row';

}




}

