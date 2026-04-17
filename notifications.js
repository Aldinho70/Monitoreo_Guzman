function msg(text) { $("#log").prepend(text + "<br/>"); }
              
              function init() { // ESTO ES PARA DESPUES DEL LOGIN
                  var sess = wialon.core.Session.getInstance(); // OBTENEMOS INSTANCIA DE SESION ACTUAL
                  //DEBEMOS ESPECIFICAR QUE DATOS QUEREMOS. SI ES POR AVL UNIT O AVL RESOURCE. Y SON DIFERENTES FLAGS PARA CARA UNA
                  var flags = wialon.item.Item.dataFlag.base | wialon.item.Resource.dataFlag.base | wialon.item.Item.dataFlag.messages | wialon.item.Resource.dataFlag.notifications;
                  
                  sess.loadLibrary("resourceNotifications"); // CARGAMOS LA LIBRERIA DE NOTIFICACIONES
                  
                  sess.updateDataFlags( // CARGAMSO OBJETOS A LA SESION ACTUAL
                  [{type: "type", data: "avl_resource", flags: flags, mode: 1}], // Items (avl_resource) ESPECIFICAMENTE
                  function (code) { // UN LLAMADO A ACTUALIZAR DATOS
                      if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // SI HAY ERROR TRUENA
              
                      // OBTENER OBJETOS CON ACCESO A NOTIFICACIONES DE AVL RESOURCES 
                      var res = sess.getItems("avl_resource");
                      for (var i = 0; i< res.length; i++) { // CONSTRUIMOS LA LISTA CON LOS ELEMENTOS DISPONIBLES		
                          //addEvent(res[i].getId()); // AGREGAMOS EVENTO A CUALQUIER OBJETO
                          res[i].addListener("messageRegistered", showData); // REGISTRAMOS EVENTO AL RECIBIR UN MENSAJE
                      }
                  });
                  $("#count").text(0); // CONTEO DE NOTIFICIACIONES
                  $("#close_popup").click(close_popup); // EVENTO AL CERRAR LA VENTANILLA
                  $("#notification").on("click", ".close_btn", delete_info); // EVENTO AL ELIMINAR LA NOTIFICIACION
              }
              
              function showData(event) {
                  var data = event.getData(); // AGARRAMOS DATA DE LOS EVENTOS
                      
                  if (data.tp && data.tp == "unm") {
                      $("#notification").prepend("<tr><td>" + data.name + "</td><td>" + data.txt + "</td><td id='" + data.t + "' class='close_btn'>x</td><tr>"); // AGREGAMOS COLUMNA CON INFORMACION TABLA
                      $("#count").text(parseInt($("#count").text()) + 1); // OBTENEMOS CONTEO DE NOTIFICACIONES
                      $("#container").show();
                  }
              }
              
             /* function close_popup() {
                  $("#container").hide();
              }
              */
              function delete_info(event) {
                  // BORRAR NOTI DE LA LISTA
                  $(event.target.parentNode).remove();
              }
              
              // EJECUTAR CUANDO "DOM" ESTA LISTO
              $(document).ready(function () {
                  wialon.core.Session.getInstance().initSession("https://hst-api.wialon.com"); // ABRIMOS SESION
                      wialon.core.Session.getInstance().loginToken("b5b6d584a63f7313cb15c66dcdd5507a12F8E69165E91074B5117F830206832521F76207", "", // INTENTO DE CONEXION
                      function (code) { // LLAMADO A FUNCION LOGIN
                          if (code){ msg(wialon.core.Errors.getErrorText(code)); return; } // NADA SI MARCA ERROR
                      init(); // ESTO SE EJECUTA CUANDO ABRES SESION
                  });
              })
              