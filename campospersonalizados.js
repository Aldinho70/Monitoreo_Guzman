var cur_unit = null; // global variable
var cur_prop = null; // global variable
var sess = null;

// Print message to log
function msg(text) { $("#log").prepend(text + "<br/>"); }

function init() { // Execute after login succeed
  	$unitsSelect = $("#units");
	sess = wialon.core.Session.getInstance(); // get instance of current Session
  
  
	// flags to specify what kind of data should be returned
	var flags = wialon.item.Item.dataFlag.base | wialon.item.Resource.dataFlag.base | wialon.item.Item.dataFlag.messages | wialon.item.Resource.dataFlag.notifications;

    sess.updateDataFlags( // load items to current session
	[{type: "type", data: "avl_unit", flags: flags, mode: 1}], // Items specification
		function (code) { // updateDataFlags callback
    		if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code

            // get loaded 'avl_unit's items  
	    	var units = sess.getItems("avl_unit");
    		if (!units || !units.length){ msg("Units not found"); return; } // check if units found

		    for (var i = 0; i< units.length; i++){ // construct Select object using found units
			    var u = units[i]; // current item in cycle
              
                // append option to select
			    $unitsSelect.append("<option value='"+ u.getId() +"'>"+ u.getName()+ "</option>");
			}

            // bind action to select change event
          $unitsSelect.change( getProperties  );
	    }
	);
}

function getProperties(){ // construct properties Select list for selected item

	if(!$("#units").val()){ msg("Properties item"); return;} // exit if no item selected
	
  	clearForm(); // clear fields
  	var id = parseInt( $("#units").val() );
  
  	// IMPORTANT! for loading custom fields needed loaded library "itemCustomFields"
  
	sess.loadLibrary("itemCustomFields");

    // flags to specify what kind of data should be returned
	
    var flags = wialon.util.Number.or(wialon.item.Item.dataFlag.base, wialon.item.Item.dataFlag.customFields, wialon.item.Item.dataFlag.adminFields);
  	
    sess.updateDataFlags( // load items to current session
	[{type: "type", data: "avl_unit", flags: flags, mode: 0}], // Items specification
		function (code) { // updateDataFlags callback
          
            if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code

            // get loaded 'avl_unit's item by ID  
	    	var unit = sess.getItem( id );
          
			var pr  = unit.getCustomFields();
          
			// save to global variable
            cur_unit = unit;
            cur_prop = pr;

			// reset select
			$("#props").html('<option>Seleccionar campo...</option>')
            
          	for (var i in pr ) {  // construct select list


            	$("#props").append("<option value='" + pr[i].id + "'>" + pr[i].n + "</option>");
				msg( 'cargar campo: ' +  pr[i].n)
            }
				msg('');
          	
          	// bind action to select change event
          	$("#props").change( renderProp );
        
	    }
	);
}

function clearForm(){ // clear fields function
	cur_prop = null;
	$("#prop_id").val("");
	$("#prop_name").val("");
	$("#prop_value").val("");
	$('#props').prop('selectedIndex',0);
}
  
function renderProp(){ // get and show information about selected property
	var prop_id = $("#props").val();

	if( !prop_id ){ msg("Seleccione la unidad"); return; } // exit if no item selected
	if( !$("#props").val() ){ clearForm(); return; } // clear fields if empty element selected

	// put property information to corresponding fields
	$("#prop_id").val( prop_id );
	$("#prop_name").val( cur_prop[prop_id].n );
	$("#prop_value").val( cur_prop[prop_id].v );
}

function createProperty(){ // create property for selected unit using entered data
    // get property information from corresponding fields
  
  
    var prop_id =  $("#prop_id").val(),
    	name = $("#prop_name").val(),
    	value = $("#prop_value").val();
  
  	// validate ID
  	if (prop_id in cur_prop) {
      	msg('You can not create a property with an existing ID!');
        return;
  	}

	// check empty field    	
    if  ( !name.length || !value.length || !cur_unit) {
    	msg('Please fill in all fields.')
    	return;
    }
  
    // add property
  	cur_unit.createCustomField( {id: prop_id, n: name, v: value} );

  	msg( 'Property add: name=' + name + ', value=' +  value );

  	// update DOM
  	$('#units').change();
  	getProperties();
}

function updateProperties(){ // update selected property using entered data
    // get property information from corresponding fields
  
  
    var prop_id =  $("#prop_id").val(),
    	name = $("#prop_name").val(),
    	value = $("#prop_value").val();

	// check exist editionly field  
    if  ( !(prop_id in cur_prop) || !name.length || !value.length || !cur_unit) {
    	msg('Porvavor rellene bien los campos.')
    	return;
    }

	// check empty field    	
    if  ( !name.length || !value.length || !cur_unit) {
    	msg('Por favor rellene bien los campos.')
    	return;
    }
    // update property
  	cur_unit.updateCustomField( {id: prop_id, n: name, v: value} );

  	msg( 'Campos actualizados: nombre=' + name + ', valor=' +  value );

  	// update DOM
  	getProperties();
}

function deleteProperty(){ // delete selected property
    // get property information from corresponding fields    
    var prop_id =  $("#prop_id").val();

    if  ( !prop_id ) return;

    if ( !(prop_id in cur_prop) ) {
    	msg('Id no encontrado en la unidad');
    	return;
    }

    // confirm user for delete property;
    var answer = confirm('Do you really want to delete property "' + $("#prop_name").val() + '"?')

    if (!answer) return;
  
    // delete property
  	cur_unit.deleteCustomField( prop_id );
  	
   	//delete cur_unit[name];

  	msg( 'campo borrado: id=' + prop_id );
  	
  	// update DOM
  	clearForm();
  	$('#units').change();
  	getProperties();
}

// execute when DOM ready
$(document).ready(function () {
    // bind actions to button clicks
	//$("#create_btn").click( createProperty );
	$("#update_btn").click( updateProperties );
	//$("#delete_btn").click( deleteProperty );
  
    wialon.core.Session.getInstance().initSession("https://hst-api.wialon.com"); // init session
    // For more info about how to generate token check
    // http://sdk.wialon.com/playground/demo/app_auth_token
	wialon.core.Session.getInstance().loginToken("36bcd0bff1677e00ca6b5f8e244cb1ab82BF4854135C8907E43D375BB8448DFDFA416474", "", // try to login
	    function (code) { // login callback
    		if (code){ msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
	    	msg("Logueado exitosamente");
        init(); // when login suceed then run init() function
	});
});
