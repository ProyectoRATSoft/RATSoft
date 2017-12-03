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
          var roleFormat = (item.roles.length === 0) ? 'ROLE_USER' :'ROLE_ADMIN';
          $('div.form-group #role').val(roleFormat);

          // Completo el modal de edición de usuarios.
          $('input#nuevoUsername').val(item.username);
          // $('input#nuevoPassword').val(item.password);
          $('input#nuevoEmail').val(item.email);
          var role = (item.roles.length != 0) ? 'ROLE_ADMIN' : 'ROLE_USER';
          $("#nuevoRole").val(role);
          // Cargo esta variable global para usar en distintos momentos.
          window.userSelected = item;
        }
      });
    }
  });

  // Modal Agregar Empresa.
  $("#addEmpresa").click(function() {
    $("#modalAddEmpresa").modal();
    $("#modal-formulario").validate().resetForm();
    $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
    $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados       
        
    // Limpio los campos del modal
    $('input#nuevoUsername').val('');
    $('input#nuevoPassword').val('');
    $('input#nuevoEmail').val('');
    $('select#nuevoRole').val('');

    // Deselecciono la empresa de la tabla
    $('#tabla-empresas tbody tr').removeClass('active');

    //edito el titulo del modal
    $("h4.modal-title").text("Nuevo Usuario");
    //establecemos que botones corresponde poder verse  en este caso, como agregamos no hace falta editar ni borrar
    $('button#newEmpresa').css("display", "");
    $('button#editDatesEmpresa').css("display", "none");
    $('button#deleteEmpresa').css("display", "none");

    $('input#nuevoPassword').css("display", "");
    $('label#nuevoPassword').css("display", "");
  });

  // Se ejecuta al seleccionar "Guardar", del modal Agregar Empresa.
  $("#newEmpresa").click(function() {
    if ($("#modal-formulario").valid()){
      // Guardo los valores recibidos del form en cada variable.
      var username = $('div.modal-body div.form-group #nuevoUsername').val();
      var password = $('div.modal-body div.form-group #nuevoPassword').val();
      var role = $('div.modal-body div.form-group #nuevoRole').val();
      var email = $('div.modal-body div.form-group #nuevoEmail').val();

      // Envío los datos del nuevo usuario al backend.
      $.ajax({
          type: "POST",
          url: "/user/new",
          async: false,
          data: {
            "username": username,
            "password": password,
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
          $('div.form-group #email').val('');
          $('div.form-group #role').val('');
          $('div.form-group #password').val('');
        });
    }
  });

  $("#editEmpresa").click(function() {
    $("#modalAddEmpresa").modal();
    $("#modal-formulario").validate().resetForm();
    $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
    $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados       
        
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
    if ($("#modal-formulario").valid()){
      // Guardo los valores recibidos del form en cada variable.
      var id = window.userSelected.id;
      var username = $('div.modal-body div.form-group #nuevoUsername').val();
      var role = $('div.modal-body div.form-group #nuevoRole').val();
      var password = window.userSelected.password;
      var email = $('div.modal-body div.form-group #nuevoEmail').val();

      $("#nuevoRole").change(function(){
        role = $(this).find('option:selected').val();
      });
      debugger
      // Envío al backend los datos del usuario a editar.
      $.ajax({
          type: "POST",
          url: "/user/edit",
          async: false,
          data: {
            "id": id,
            "username": username,
            "password": password,
            "role": role,
            "email": email
          },
          dataType: "json"
        })
        .done(function(respuesta) {
          alert(respuesta.msg);

          $("#modalAddEmpresa").modal('toggle');
          tableReload(respuesta.users);
          $('div.modal-body div.form-group #nuevoId').val();
          $('div.modal-body div.form-group #nuevoEmail').val();
          $('div.modal-body div.form-group #nuevoUsername').val();
          $('div.modal-body div.form-group #nuevoPassword').val();
          $('div.modal-body div.form-group #nuevoRole').val();

          // Completo los datos de grilla de edición lateral derecha.
          $('div.form-group #userId').val(id);
          $('div.form-group #username').val(username);
          $('div.form-group #email').val(email);
          $('div.form-group #role').val(role);
        });
    }
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
      });
    }
  });

  // $("#modalFormulario").validate({
  $("#modal-formulario").validate({
    rules: {
      nuevoUsername: {
        required: true,
        minlength: 3
      },
      nuevoPassword: {
        required: true,
        minlength: 8
      },
      nuevoEmail: {
        required: true
      }
    },
    messages: {
      nuevoUsername: {
        required: "Este campo es requerido",
        minlength: "Este campo de contener al menos 3 caracteres"
      },
      nuevoPassword: {
        required: "Este campo es requerido",
        minlength: "Este campo de contener al menos 8 caracteres"
      },
      nuevoEmail: {
        required: "Este campo es requerido"
        // minlength: "Este campo de contener al menos 8 caracteres"
      }
    },
    highlight: function(element) {
              $(element).closest('.form-group').addClass('has-error');
              //$(element).addClass('has-error fa fa-times');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
        //$(element).('.input-sm').removeClass('glyphicon-remove');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
      } 
  });
});
