//con esto bloqueamos la pantalla cuando inicia alguna accion ajax..
$(document).ajaxStart(function() {
  $.blockUI({
    css: {
      border: 'none',
      padding: '15px',
      backgroundColor: '#000',
      '-webkit-border-radius': '10px',
      '-moz-border-radius': '10px',
      opacity: .5,
      color: '#fff'
    },
    message: 'Por Favor, espere...'
  })
});

$(document).ready(function() {
  //cuando termina la accion ajax, se desbloquea..
  $(document).ajaxStop(function() {
    $.unblockUI();
  });

  // Traigo todos los usuarios existentes
  window.arrayrespuesta = new Array();
  var loadTable = function() {
    $.ajax({
        type: "GET",
        url: "/user",
        dataType: "json",
      })
      .done(function(respuesta, status) {
        window.arrayrespuesta = respuesta.users;
        window.table = $('#tabla-empresas').DataTable({
          data: respuesta.users,
          "columns": [{
              "data": "username",
              "className": "username"
            },
            {
              "data": "email"
            }
          ]
        });
      })
      .fail(function(jqXHR, textStatus) {});
  };

  loadTable();

  // Metodo para refrescar la tabla.
  var tableReload = function(datosnuevos) {
    window.arrayrespuesta = datosnuevos;
    $('#tabla-empresas').DataTable().clear().draw();
    $('#tabla-empresas').DataTable().rows.add(datosnuevos).draw();
  };

  // Pinta la fila.
  $('#tabla-empresas tbody').on('click', 'tr', function() {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
    } else {
      window.table.$('tr.active').removeClass('active');
      $(this).addClass('active');
      // Busco el dato de la fila seleccionada.
      var username = window.table.$("tr.active").find(".username").text();
      // Busco los datos del usuario seleccionado.
      window.arrayrespuesta.forEach(function(item, index, arr) {
        if (item.username == username) {
          // Completo los datos de grilla de edición lateral derecha.
          $('div.form-group #userId').val(item.id);
          $('div.form-group #username').val(item.username);
          $('div.form-group #email').val(item.email);
          $('div.form-group #role').val(item.role);

          // Completo el modal de edición de usuarios.
          $('input#nuevoUsername').val(item.username);
          $('input#nuevoPassword').val(item.password);
          $('input#nuevoNombre').val(item.nombre);
          $('input#nuevoApellido').val(item.apellido);
          $('input#nuevoEmail').val(item.email);

          window.userSelected = item;
        }
      });
    }
  });

  // Modal Agregar Empresa.
  $("#addEmpresa").click(function() {
    $("#modalAddEmpresa").modal();
    // Limpio los campos del modal
    $('input#nuevoUsername').val('');
    $('input#nuevoPassword').val('');
    $('input#nuevoNombre').val('');
    $('input#nuevoApellido').val('');
    $('select#nuevoEmail').val('');

    // Limpio los campos de la tabla del costado para arreglar efecto visual y evitar confusiones (Ver!!!!)
    // $('div.form-group #empresaID').val('');
    // $('div.form-group #nombre').val('');
    // $('div.form-group #domicilio').val('');
    // $('div.form-group #localidad').val('');

    // Deselecciono la empresa de la tabla
    $('#tabla-empresas tbody tr').removeClass('active');

    //edito el titulo del modal
    $("h4.modal-title").text("Nuevo Usuario");
    //establecemos que botones corresponde poder verse  en este caso, como agregamos no hace falta editar ni borrar
    $('button#newEmpresa').css("display", "");
    $('button#editDatesEmpresa').css("display", "none");
    $('button#deleteEmpresa').css("display", "none");
  });

  // Se ejecuta al seleccionar "Guardar", del modal Agregar Empresa.
  $("#newEmpresa").click(function() {
    // Guardo los valores recibidos del form en cada variable.
    var username = $('div.modal-body div.form-group #nuevoUsername').val();
    var password = $('div.modal-body div.form-group #nuevoPassword').val();
    var nombre = $('div.modal-body div.form-group #nuevoNombre').val();
    var apellido = $('div.modal-body div.form-group #nuevoApellido').val();
    var email = $('div.modal-body div.form-group #nuevoEmail').val();
    var role = 1;

    // Envío los datos del nuevo usuario al backend.
    $.ajax({
        type: "POST",
        url: "/user/new",
        async: false,
        data: {
          "username": username,
          "password": password,
          "nombre": nombre,
          "apellido": apellido,
          "email": email,
          "role": role
        },
        dataType: "json"
      })
      .done(function(respuesta) {
        alert(respuesta.msg);
        $("#modalAddEmpresa").modal('toggle');
        tableReload(respuesta.users);

        $('div.form-group #username').val('');
        $('div.form-group #password').val('');
        $('div.form-group #nombre').val('');
        $('div.form-group #apellido').val('');
        $('div.form-group #email').val('');
        $('div.form-group #role').val('');
      });
  });

  $("#editEmpresa").click(function() {
    $("#modalAddEmpresa").modal();
    // Con esto remuevo el seleccionar del dropdown
    // $('select#nuevaProvincia :contains(--seleccionar--)').attr("disabled", "true").removeAttr("selected");
    // $('select#nuevaSituacionIVA :contains(--seleccionar--)').attr("disabled", "true").removeAttr("selected");
    // $('select#nuevoRubro :contains(--seleccionar--)').attr("disabled", "true").removeAttr("selected");
    // $('select#nuevoCodIngresosBrutos :contains(--seleccionar--)').attr("disabled", "true").removeAttr("selected");

    $("h4.modal-title").text("Editar Usuario");
    $('button#newEmpresa').css("display", "none");
    $('button#editDatesEmpresa').css("display", "");
    $('button#deleteEmpresa').css("display", "");

    // Cuando editamos un usuario, ocultamos su contraseña.
    $('input#nuevoPassword').css("display", "none");
    $('label#nuevoPassword').css("display", "none");
  });

  // Se ejecuta al seleccionar "Guardar", del modal editar empresa.
  $("#editDatesEmpresa").click(function() {
    // Guardo los valores recibidos del form en cada variable.
    var id = window.userSelected.id;
    var username = $('div.modal-body div.form-group #nuevoUsername').val();
    var nombre = $('div.modal-body div.form-group #nuevoNombre').val();
    var apellido = $('div.modal-body div.form-group #nuevoApellido').val();
    var email = $('div.modal-body div.form-group #nuevoEmail').val();
    var role = window.userSelected.role;
    var password = window.userSelected.password;

    // Envío al backend los datos del usuario a editar.
    $.ajax({
        type: "POST",
        url: "/user/edit",
        async: false,
        data: {
          "id": id,
          "username": username,
          "password": password,
          "nombre": nombre,
          "apellido": apellido,
          "email": email,
          "role": role
        },
        dataType: "json"
      })
      .done(function(respuesta) {
        alert(respuesta.msg);

        $("#modalAddEmpresa").modal('toggle');
        tableReload(respuesta.users);
        $('div.modal-body div.form-group #nuevoId').val();
        $('div.modal-body div.form-group #nuevoUsername').val();
        $('div.modal-body div.form-group #nuevoPassword').val();
        $('div.modal-body div.form-group #nuevoNombre').val();
        $('div.modal-body div.form-group #nuevoApellido').val();
        $('div.modal-body div.form-group #nuevoEmail').val();
      });
  });

  $("#deleteEmpresa").click(function(){
    var id =  window.userSelected.id;
    var result = confirm("Esta seguro de que desea borrar la empresa?");
    if (result) {
      $.ajax({
        type: "POST",
        url: "/user/delete",
        async: false,
        data: {
          "id": id,
        },
        dataType: "json",
      })
      .done(function(respuesta){
        $("#modalAddEmpresa").modal('toggle');
        tableReload(respuesta.users);
        // $('div.form-group #empresaID').val('');
        // $('div.form-group #nombre').val('');
        // $('div.form-group #domicilio').val('');
        // $('div.form-group #localidad').val('');
        // $('div.form-group #provincia').val('');
      });
    }
  });
});
