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
  var tableReload = function(datosnuevos) {
    window.arrayrespuesta = datosnuevos;
    $('#tabla-empresas').DataTable().clear().draw();
    $('#tabla-empresas').DataTable().rows.add(datosnuevos).draw();
  };

  $('#tabla-empresas tbody').on('click', 'tr', function() {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
    } else {
      window.table.$('tr.active').removeClass('active');
      $(this).addClass('active');
      // metemos en una variable lo que hay en e campo cuit de la empresa seleccionada
      var username = window.table.$("tr.active").find(".username").text();
      window.arrayrespuesta.forEach(function(item, index, arr) {
        if (item.username == username) {
          $('div.form-group #userId').val(item.id);
          $('div.form-group #username').val(item.username);
          $('div.form-group #email').val(item.email);
          $('div.form-group #role').val(item.role);
        }
      });
    }
  });

  // Modal Agregar Empresa.
  $("#addEmpresa").click(function() {
    $("#modalAddEmpresa").modal();
    //limpio los campos del modal
    $('input#nuevoUsername').val('');
    $('input#nuevoPassword').val('');
    $('input#nuevoNombre').val('');
    $('input#nuevoApellido').val('');
    $('select#nuevoEmail').val('');
    //limpio los campos de la tabla del costado para arreglar efecto visual y evitar confusiones
    $('div.form-group #empresaID').val('');
    $('div.form-group #nombre').val('');
    $('div.form-group #domicilio').val('');
    $('div.form-group #localidad').val('');

    //deselecciono la empresa de la tabla
    $('#tabla-empresas tbody tr').removeClass('active');
    //si esta en modo responsive hay q ver como hacer para que quede cerrado el "+" con sus campos
    //edito el titulo del modal
    $("h4.modal-title").text("Nuevo Usuario");
    //establecemos que botones corresponde poder verse  en este caso, como agregamos no hace falta editar ni borrar
    $('button#newEmpresa').css("display", "");
    $('button#editDatesEmpresa').css("display", "none");
    $('button#deleteEmpresa').css("display", "none");
  });

  // Se ejecuta al seleccionar "Guardar", del modal Agregar Empresa.
  $("#newEmpresa").click(function() {
    // Guardo los valores recibidos, en cada variable.
    var username = $('div.modal-body div.form-group #nuevoUsername').val();
    var password = $('div.modal-body div.form-group #nuevoPassword').val();
    var nombre = $('div.modal-body div.form-group #nuevoNombre').val();
    var apellido = $('div.modal-body div.form-group #nuevoApellido').val();
    var email = $('div.modal-body div.form-group #nuevoEmail').val();
    var role = 1;
    //realizo un post pasando la url correspondiente al backend, los datos previamente capturados y realizo la funcion correspondiente que me devolvera la respuesta.
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
        if (respuesta.status == 'OK') {
          alert("va como piña");
        } else {
          alert("no funca");
        }
        $("#modalAddEmpresa").modal('toggle');
        tableReload(respuesta.data);
        $('div.form-group #username').val('');
        $('div.form-group #password').val('');
        $('div.form-group #nombre').val('');
        $('div.form-group #apellido').val('');
        $('div.form-group #email').val('');
        $('div.form-group #role').val('');
        //deberiamos mostrar algun mensaje tanto como de error, como de que todo salio bien. y luego recargar la grilla para que aparezca la nueva empresa en la tabla.
        //alert(resp);
      });
  });
});
