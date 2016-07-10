
function imc1() { 
	peso=eval(document.getElementById('peso_actual').value);
	talla1=eval(document.getElementById('talla').value);
	
	cal1 = talla1 * talla1;
	cal2 = peso / cal1;	
	
	document.getElementById('imc').value=cal2; 
}

 
function pesoIdeal() { 
	talla1=eval(document.getElementById('talla').value);
	constitucion=document.getElementById("constitucion_corporal").options[document.getElementById("constitucion_corporal").selectedIndex].value;
	
	if ( constitucion == "Pequenia")
	{
		val1 = talla1 - 100;
		val2 = val1 * 0.1;
		total = val1 - val2;
	}
	else if( constitucion == "Mediana")
	{
		total = talla1 - 100;
	}
	else if( constitucion == "Grande")
	{
		val1 = talla1 - 100;
		val2 = val1 * 0.1;
		total = val1 - val2;
	}
	
	document.getElementById('peso_ideal').value=total; 
} 


function grasaF() { 
	trici=eval(document.getElementById('tricipital').value);
	sexo1=document.getElementById("sexo").value;
	
	if ( sexo1 == "Masculino")
	{
		val1 = trici * 100;
		total = val1 / 12.5;
	}
	else if( sexo1 == "Femenino")
	{
		val1 = trici * 100;
		total = val1 / 16.5;
	}
	
	
	document.getElementById('grasa').value=total;
} 

function cmbF() { 
	trici=eval(document.getElementById('tricipital').value);
	per=eval(document.getElementById('per_braquial').value);
	sexo1=document.getElementById("sexo").value;
	
	reserva = 3.1415 * trici;
	reser = per - reserva; 
	
	if ( sexo1 == "Masculino")
	{		
		val1 = reser * 100;
		total = val1 / 25.3;
	}
	else if( sexo1 == "Femenino")
	{
		val1 = reser * 100;
		total = val1 / 23.2;
	}
	
	document.getElementById('cmb').value=total;
}

	
	
	
function observacionesF() { 

	/*peso optimo*/

	talla1=eval(document.getElementById('talla').value);
	trici=eval(document.getElementById('tricipital').value);
	sexo1=document.getElementById("sexo").value;
	edad=document.getElementById('edad').value;
	constitucion=document.getElementById("constitucion_corporal").options[document.getElementById("constitucion_corporal").selectedIndex].value;
	per=eval(document.getElementById('per_braquial').value);
	
	
	/* ssdsdsd */
	
	if ( constitucion == "Pequenia")
	{
		val1 = talla1 - 100;
		val2 = val1 * 0.1;
		peso = val1 - val2;
	}
	else if( constitucion == "Mediana")
	{
		peso = talla1 - 100;
	}
	else if( constitucion == "Grande")
	{
		val1 = talla1 - 100;
		val2 = val1 * 0.1;
		peso = val1 - val2;
	}
	
	/* porcentaje tricipital*/
	
	if ( sexo1 == "Masculino")
	{
		val1 = trici * 100;
		totall = val1 / 12.5;
	}
	else if( sexo1 == "Femenino")
	{
		val1 = trici * 100;
		totall = val1 / 16.5;
	}
	
	
	if ( totall < 60 )
	{
		msj = "DEPLECION SEVERA: " + totall + "%";
	}
	
	else if ( totall >= 60 && totall <= 90 )
	{
		msj = "DEPLECION MODERADA: " + totall + "%";
	}
	
	else if ( totall > 90 && totall <= 110 )
	{
		msj = "DEPLECION LEVE: " + totall + "%";
	}
	
	else if ( totall > 110 )
	{
		msj = "RESERVAS GRASAS ELEVADAS: " + totall + "%";
	}

	/* *-------------------*/
	
	reserva = 3.1415 * trici;
	reserr = per - reserva; 
	
	if ( sexo1 == "Masculino")
	{		
		val1 = reserr * 100;
		totalll = val1 / 25.3;
	}
	else if( sexo1 == "Femenino")
	{
		val1 = reserr * 100;
		totalll = val1 / 23.2;
	}

	if ( totalll > 90 )
	{
		msjj = "Normal " + totalll + "%";
	}
	
	else if ( totalll >= 80  && totalll <= 90)
	{
		msjj = "DESGASTE LEVE " + totalll + "%";
	}
	
	else if ( totalll >= 60  && totalll <= 79)
	{
		msjj = "DESGASTE MODERADO " + totalll + "%";
	}
	
	else if ( totalll < 60 )
	{
		msjj = "DESGASTE SEVERO " + totalll + "%";
	}
	
	
	total = "Paciente de edad: " + edad + " de contextura: " + constitucion + "\nDebe tener un peso optimo de: " + peso + "kg.\n" + "Estado de reserva de Grasas: " + msj+ "\n Estado de reservas de proteina: " + msjj ;

	document.getElementById('observaciones').value=total;
}

