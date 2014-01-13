function setNeedToConfirm()
{
	needToConfirm = true;
}

function releaseNeedToConfirm()
{
	needToConfirm = false;
}

function confirmExit()
{
	if (needToConfirm){
		return "Wenn Sie die Seite verlassen gehen alle Angaben verloren.";
	}
}

window.onload = function(){
	
	// das formular vom arzt eintragen erst mal verstecken
	$(".arztForm").hide();
	$("#a_finsih").hide();
}

function getDenForm(){
	$("#arztForm").html("<label>Name:</label><input type=\"text\" name=\"a_name\" id=\"a_name\" value=\"\" /><br />");
	$("#arztForm").append("<label>Telefon:</label><input type=\"text\" name=\"a_telefon\" id=\"a_telefon\" value=\"\" /><br />");
	$("#arztForm").append("<label>Strasse:</label><input type=\"text\" name=\"a_strasse\" id=\"a_strasse\" value=\"\" /><br />");
	$("#arztForm").append("<label for=\"plz\" >PLZ / Ort:</label>");
	$("#arztForm").append("<input maxlength=\"5\" type=\"text\" name=\"plz\" id=\"plz\" size=\"5\" value=\"\" onchange=\"insert_ort('plz','a_ort',0) />");
	$("#arztForm").append("<span id=\"a_ort\"><select id='a_ort' name='a_ort' class=\"a_ort\"><option value=''>Bitte geben Sie eine Plz ein!</option></select></span><br />");
	$("#arztForm").append("<br />");
	$("#arztForm").append("<label>&nbsp;</label><input type=\"button\" name=\"a_save\" value=\"Arzt Anlegen\" onclick=\"saveDen()\" />");	

	$(".arztForm").toggle();
}

function getClassForm(el){
	$("#classForm").html("<label>&nbsp;</label>");
	$("#classForm").append("<input type='text' name='className' id='className' />");
	$("#classForm").append("<br /><label>&nbsp;</label><input type='button' value='Klasse anlegen' onclick='saveClass(\""+el+"\");' />");
	
	$("#classForm").toggle();
}

function saveClass(el){

	$.post("Content/ajax/saveClass.php", 
			{name: $("#className").val(),schul_id:  $("#schul_id").val()},
			function(data){
				$("#"+el).append("<option value=\""+ data +"\">"+$("#className").val()+"</option>");
					
				$("#"+el+" > option").each(function(){
					$(this).removeAttr("selected");
				});
				
				$("#"+el+" > option").each(function(){
					if(this.value == data)
						$(this).attr("selected","selected");
				});
				
				$("#classForm").hide();
			}
	);
}

function saveDen(){
	$.post("Content/ajax/saveDen.php", 
		  { name: $("#a_name").val(), telefon : $("#a_telefon").val() , strasse : $("#a_strasse").val() , plz : $(".a_ort").val(), schul_id : $("#schul_id").val()},
		   function(data){
				// combo box den neuen wert hinzufügen
				$("#arzt").append("<option value=\""+ data +"\">"+$("#a_name").val()+"</option>");
				 
				// "Eintragen" out faden
				$("#a_action").hide();
				$("#a_finsih").show();
				$("#a_finsih").fadeOut(5000);
				
				// den hinzufügen Link wieder einbauen
				$("#a_action").show("slow");
				$("#a_action").removeAttr("style");
				
				$("#arzt > option").each(function(){
					$(this).removeAttr("selected");
				});
				
				$("#arzt > option").each(function(){
					if(this.value == data)
						$(this).attr("selected","selected");
				});
		    }
	);
	$(".arztForm").hide();
}

function insert_ort(input_plz, input_ort, error){	
	$.post("Content/ajax/get_plz.php", 
		{ plz: $('#'+input_plz).val(), ort:input_ort, error: error },
		function(data){ 
			$('#'+input_ort).html(data); 
		}
	);	
}

function getBank(that){	
	$("#lastschrift").addClass("hidden");
	$("#ueberweisung").addClass("hidden");
	$("#sonstige").addClass("hidden");
	$("#"+that).removeClass("hidden");
}

function calc(){
	$.post("Content/ajax/getKinderfreibetrag.php",
		function(data){
		if (calc_status == "jahresausgleich"){
			jahreseinkommen = parseFloat($("#einkommens").val()) ;
			if ($("#kinder").val() > 2){
                jahreseinkommen = jahreseinkommen - (($("#kinder").val() -2 ) * data);
			}
		}else if(calc_status == "berechnung"){
			jahreseinkommen = 0;
			
			if( $("#verdienst").val() != "" ){
				jahreseinkommen += parseInt($("#verdienst").val()) * 12;
			}
	
			if( $("#unterhalt").val() != "" ){
				jahreseinkommen += parseInt($("#unterhalt").val()) * 12;
			}
		
			if( $("#urlaubsgeld").val() != "" ){
				jahreseinkommen += parseInt($("#urlaubsgeld").val()); 
			}
	
			if( $("#vermietung").val() != "" ){
				jahreseinkommen += parseInt($("#vermietung").val()) * 12; 
			}
			
			if( $("#sonst_einkuenfte").val() != "" ){
				jahreseinkommen += parseInt($("#sonst_einkuenfte").val()) * 12; 
			}
			
			if( $("#werbungskosten").val() != "" ){
				jahreseinkommen -= parseInt($("#werbungskosten").val()); 
			}			
			
			if ($("#kinder").val() > 2){
                jahreseinkommen = jahreseinkommen - (($("#kinder").val() -2 ) * data);
			}
		}else{
			if( document.getElementById("hartz4").checked || document.getElementById("schulbuch").checked ){
				jahreseinkommen = 0;
			} else{
				jahreseinkommen = parseFloat("");
			}
		}
		if(jahreseinkommen < 0)
				jahreseinkommen = 0;	
		// in das feld schreiben
		$("#calcEinkommen").val(jahreseinkommen);
		$("#einkommens2").val(jahreseinkommen);
	});
}

function calc_alt(){
	// init jahreseinkommen
	$.post("Content/ajax/getKinderfreibetrag.php",
			function(data){
				var kinder = data;
						
				var jahreseinkommen = 0;
				
				// falls eine checkbox angehackt is, fallen keine bühren, daher wert 0
				if( document.getElementById("hartz4").checked || document.getElementById("wohngeld").checked || document.getElementById("schulbuch").checked ){
					jahreseinkommen = 0;
				}
				else if( $("#verdienst").val() != "" || $("#unterhalt").val() != "" ||  $("#urlaubsgeld").val() != ""  != "" || $("#vermietung").val() != "" || $("#werbungskosten").val() != "" || $("#sonst_einkuenfte").val() != "" ){
					jahreseinkommen = 0;
			
					if( $("#verdienst").val() != "" ){
						jahreseinkommen += parseInt($("#verdienst").val()) * 12;
					}
			
					if( $("#unterhalt").val() != "" ){
						jahreseinkommen += parseInt($("#unterhalt").val()) * 12;
					}
				
					if( $("#urlaubsgeld").val() != "" ){
						jahreseinkommen += parseInt($("#urlaubsgeld").val()); 
					}
			
					if( $("#vermietung").val() != "" ){
						jahreseinkommen += parseInt($("#vermietung").val()) * 12; 
					}
					
					if( $("#sonst_einkuenfte").val() != "" ){
						jahreseinkommen += parseInt($("#sonst_einkuenfte").val()) * 12; 
					}
					
					if( $("#werbungskosten").val() != "" ){
						jahreseinkommen -= parseInt($("#werbungskosten").val()); 
					}
					
					if(document.getElementById("kinderreich").checked){
						jahreseinkommen -= kinder;
					}
				}
				if(jahreseinkommen < 0)
						jahreseinkommen = 0;	
				// in das feld schreiben
				$("#calcEinkommen").val(jahreseinkommen);
				$("#einkommens2").val(jahreseinkommen);	
		});
}

function deActEinkommen(el){

	if( document.getElementById("hartz4").checked || document.getElementById("wohngeld").checked ||	document.getElementById("schulbuch").checked ){
		$(".sozial").css("display","inline");
	}else{
		$(".sozial").css("display","none");
	} 
	
	if( (el.checked && el.value != "") || (el.value != "" && el.type == "text")){
		$("#einkommens").attr("disabled","disabled");
		$("#einkommens").css("background-color","#c0c0c0");
		calc();
	}else{
		if( !document.getElementById("hartz4").checked && 
			!document.getElementById("wohngeld").checked && 
			!document.getElementById("schulbuch").checked && 
			$("#werbungskosten").val() == "" && 
			$("#urlaubsgeld").val() == "" &&
			$("#verdienst").val() == "" && 
			$("#vermietung").val() == "" &&
			$("#sonst_einkuenfte").val() == "" &&
			!document.getElementById("kinderreich").checked
			){
			document.getElementById("einkommens").disabled = false;
			$("#einkommens").css("background-color","white");
		}else{
			calc();
		}
	}
}

function deActRest(e){
	if(e.value != ""){
		$("#einkommens2").val($("#einkommens").val());
		document.getElementById("verdienst").disabled 		= true;
		document.getElementById("unterhalt").disabled 		= true;
		document.getElementById("urlaubsgeld").disabled 	= true;
		document.getElementById("kinderreich").disabled 	= true;
		document.getElementById("vermietung").disabled 		= true;
		document.getElementById("sonst_einkuenfte").disabled= true;		
		document.getElementById("werbungskosten").disabled 	= true;
		document.getElementById("hartz4").disabled 			= true;
		document.getElementById("wohngeld").disabled 		= true;		
		document.getElementById("schulbuch").disabled 		= true;		
		
		
		$("#verdienst").css("background-color","#c0c0c0");
		$("#unterhalt").css("background-color","#c0c0c0");
		$("#urlaubsgeld").css("background-color","#c0c0c0");
		$("#kinderreich").css("background-color","#c0c0c0");
		$("#vermietung").css("background-color","#c0c0c0");
		$("#sonst_einkuenfte").css("background-color","#c0c0c0");
		$("#werbungskosten").css("background-color","#c0c0c0");
		$("#hartz4").css("background-color","#c0c0c0");
		$("#wohngeld").css("background-color","#c0c0c0");
		$("#schulbuch").css("background-color","#c0c0c0");
	}else{
		document.getElementById("verdienst").disabled 		= false;
		document.getElementById("unterhalt").disabled 		= false;
		document.getElementById("urlaubsgeld").disabled 	= false;
		document.getElementById("kinderreich").disabled 	= false;
		document.getElementById("vermietung").disabled 		= false;
		document.getElementById("sonst_einkuenfte").disabled= false;
		document.getElementById("werbungskosten").disabled 	= false;
		document.getElementById("hartz4").disabled 			= false;
		document.getElementById("wohngeld").disabled 		= false;		
		document.getElementById("schulbuch").disabled 		= false;		
	
		$("#verdienst").css("background-color","white");
		$("#unterhalt").css("background-color","white");
		$("#urlaubsgeld").css("background-color","white");
		$("#kinderreich").css("background-color","white");
		$("#vermietung").css("background-color","white");
		$("#sonst_einkuenfte").css("background-color","white");
		$("#werbungskosten").css("background-color","white");
		$("#hartz4").css("background-color","white");
		$("#wohngeld").css("background-color","white");
		$("#schulbuch").css("background-color","white");
	}
}

function getVerdienstInput(){
	$(".add").toggle();
	$(".showVerdienstTr").show();
}

function saveVerdienstInput(){
	$(".add").toggle();
	
	if ($("#essen:checked").val() == 1){
		essen = 1;
	}else{
		essen = 0;
	}
	$.post("Content/ajax/saveVerdienst.php", { from:$("#from").val(),verdienst:$("#verdienst").val(),beitrag:$("#beitrag").val(),schul_id:$("#s_id").val(),essen:essen},
	  function(data){
			alert("Hinzugefügte Verdienstgruppen erscheinen erst nach dem speichern!");
			$("#verdienst").val("");
			$("#beitrag").val("");
			$("#essen").removeAttr("checked");
			$(".showVerdienstTr").hide();
		
	});

}

function applySchuelerData(){	
	$("#e1_nachname").val($("#s_nachname").val());
	$("#e1_strasse").val($("#s_strasse").val());
	$("#e1_plz").val($("#s_plz").val());
	insert_ort('e1_plz','e1_ort',0);
}

function checkAufsichtsperson(){
	if(!$("#agb:checked").length){
		if(confirm("Verdienstnachweise wurden nicht durch eine Aufsichtsperson bestätigt. Möchten Sie trotzdem fortfahren?"))
			return true;
		else
			return false;
	
	}
	return true;
}