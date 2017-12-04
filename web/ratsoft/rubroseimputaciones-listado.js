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
  $(document).ready(function(){

     //cuando termina la accion ajax, se desbloquea..
     $(document).ajaxStop(function() {
      $.unblockUI();
    });

     // ----------------------------------------Datos de IMPUTACION-----------------------------------------------------

    // Hacemos que la interfaz arranque con el boton de edición dehabilitado, porque no hay ninguna jurisdiccion seleccionada aún.
    $("#boton-editar").attr("disabled","true");
     // Vamos a traer el json para laburar con ajax
     window.arrayImputaciones = new Array();
     var loadTable2 = function(){
      $.ajax({
        type: "GET",
        url: "/imputaciones",
        dataType: "json",
      })
      .done(function(respuesta){
        console.log(respuesta.data);
        window.arrayImputaciones = respuesta.data;
        window.table2 = $('#tabla-imputaciones').DataTable({
          "responsive": true,
          dom: "<'row'<'col-sm-7 text-left'B><'col-sm-5 text-right'f>>" +"<'row'<'col-sm-12't>>" +"<'row'<'col-sm-5 text-left'i><'col-sm-7 text-right'p>>",
          "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar: ",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
          },
          lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 Filas', '25 Filas', '50 Filas', 'Todos' ]
          ],
                  //elegis los formatos para exportar los datos de la tabla, colvis (va a servir para seleccionar que columnas queres q se vean o no), pagelenth para seleccionar la cantidad  datos mostrados 
                  buttons: [
                  {
                    extend: 'collection',
                    text: 'Exportar',
                    //elegis los formatos para exportar los datos de la tabla, colvis (va a seleccionar que mnas queres q se vean o no), pagelenth pla cantidad  datos mostrados             
                    buttons: [ 'copy', 'excel', 'pdf', 'csv', 'print']         
                  }, 'pageLength'    
                  ],
                  "columnDefs": [ 
                //con esto podes setear que columnas queres aparezca o no
                //{ "visible": false, "targets": 3 },
                // { responsivePriority: 1, targets: 0 },
                // { responsivePriority: 2, targets: 3 },                                    
                ],
                retrieve: true,
                data : respuesta.data,
                "columns": [
                    { "data": "id",
                    "className": "id"}, //esto es importante, le ponemos una clase a este campo
                    { "data": "nombre"},
                    { "data": "codigo" }
                    ]
                  });
      })};
      loadTable2();
      var tableReload2 = function(datosnuevos){
        window.arrayImputaciones = datosnuevos;
        $('#tabla-imputaciones').DataTable().clear().draw();
        $('#tabla-imputaciones').DataTable().rows.add(datosnuevos).draw();
      };
      //--------------------------------CARGAR DATOS MODAL-----------------------------------//
       $('#tabla-imputaciones tbody').on( 'click', 'tr', function () {
          if ( $(this).hasClass('active') ) {
            $(this).removeClass('active');
          }
          else {
            window.table2.$('tr.active').removeClass('active');
            $(this).addClass('active');
            $("#boton-editar").removeAttr("disabled");
            $("#boton-editar").attr("enabled","true");
        // metemos en una variable lo que hay en e campo cuit de la jurisdiccion seleccionada
            var id2 = window.table2.$("tr.active").find(".id").text();
            window.arrayImputaciones.forEach(function(item,index,arr){
              if (item.id == id2){
              //llenamos el modal con los datos de la jurisdiccion
              $('div.modal-body div.form-group #nuevoIDImputacion').val(item.id);
              $('div.modal-body div.form-group #nuevaNombreImputacion').val(item.nombre);
              $('div.modal-body div.form-group #nuevoCodigo').val(item.codigo);
              }
            });
          }
        }); 
    // A partir de acá metemos lo que pasa cuando picamos sobre alguna Jurisdiccion
    // ---------------------AGREGAR JURISDICCION---------------------------------------------//
      $("#boton-agregar").click(function(){        
         $("#modalAddImputaciones").modal();
         //reseteo las validaciones
         $("#modal-formulario").validate().resetForm();
         $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
         $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados

        //limpio los campos
         $('#nuevoIDImputacion').val('');
         $('#nuevaNombreImputacion').val('');
         $('#nuevoCodigo').val('');
         //limpio la jurisdiccion
         $('#tabla-jurisdicciones tbody tr').removeClass('active');
         //cambio el titulo
         $("h4.modal-title").text("Nueva Jurisdiccion");
         //muestro los botones correspondientes
         $('button#newImputacion').css("display", "");
         $('button#editImputacion').css("display", "none");
         $('button#deleteImputacion').css("display", "none");
      });


       $("#newImputacion").click(function(){
    //guardo los valores en cada variable                            
         if ($("#modal-formulario").valid()){

            var nombre = $('div.modal-body div.form-group #nuevaNombreImputacion').val();
            var codigo = $('div.modal-body div.form-group #nuevoCodigo').val();
    //realizo un post pasando la url correspondiente al backend, los datos previamente capturados y realizo la funcion correspondiente que me devolvera la respuesta.
            
            $.ajax({
              type: "POST",
              url: "/imputaciones/new",
              async: false,
              data: {                  
                "nombre" : nombre,
                "codigo" : codigo, 
                 },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                alert("va como piña");
              }else{
              }
              $("#modalAddImputaciones").modal('toggle');
              tableReload2(respuesta.data);
              $('#nuevoIDImputacion').val('');
              $('#nuevaNombreImputacion').val('');
              $('#nuevoCodigo').val('');
            });
       }
      });
      //-----------------------------------/AGREGAR JURISDICCION----------------------------------//  
      //-----------------------------------EDITAR JURISDICCION----------------------------------//
      $("#boton-editar").click(function(){        
        $("#modalAddImputaciones").modal();
        $("#modal-formulario").validate().resetForm();
        $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
        $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados
        //limpio los campos
        $("h4.modal-title").text("Editar Imputacion");
        $('button#newImputacion').css("display", "none");
        $('button#editImputacion').css("display", "");
        // $('button#deleteJurisdiccion').css("display", "");
      });

      $("#editImputacion").click(function(){
        if ($("#modal-formulario").valid()){
    //guardamos id para pasarlo en la url
        var id =  $('div.modal-body div.form-group #nuevoIDImputacion').val();
        var nombre = $('div.modal-body div.form-group #nuevaNombreImputacion').val();
        var codigo = $('div.modal-body div.form-group #nuevoCodigo').val();
        console.log(nombre);
        console.log(codigo);     
        $.ajax({
              type: "POST",
              url: "/jurisdicciones/"+id+"/edit",
              async: false,
              data: {                  
                "nombre" : nombre,
                "codigo" : codigo },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                console.log(respuesta);
                alert("va como piña");
              }else{
              }
              $("#modalAddJurisdiccion").modal('toggle');
              tableReload2(respuesta.data);
               $('#nuevoIDImputacion').val('');
               $('#nuevaNombreImputacion').val('');
               $('#nuevoCodigo').val('');
               $("#boton-editar").attr("disabled","true");              
            });
          }
      });
      //-----------------------------------/EDITAR JURISDICCION----------------------------------//
      //-----------------------------------BORRAR JURISDICCION----------------------------------//
      //  $("#deleteJurisdiccion").click(function(){
      //     var id =  $('div.modal-body div.form-group #nuevaNombreImputacion').val();
      //     var result = confirm("Esta seguro de que desea borrar la Imputacionn?");
      //     if (result) {
      //       $.ajax({
      //         type: "GET",
      //         url: "/imputaciones/"+id+"/del",
      //         async: false,
      //         dataType: "json",
      //       })
      //       .done(function(respuesta){
      //         $("#modalAddJurisdiccion").modal('toggle');
      //         tableReload2(respuesta.data);
      //         $('#nuevoIDImputacion').val('');
      //          $('#nuevaNombreImputacion').val('');
      //          $('#nuevoCodigo').val('');
      //          $("#boton-editar").attr("disabled","true");   
      //       });
      //     }                 
      // });   
      //-----------------------------------/BORRAR JURISDICCION----------------------------------//

      //---------------------------------validaciones-------------------------------------------//
      $("#modal-formulario").validate({
          rules: {
              
              nuevaNombreImputacion: { 
                required: true,
                minlength: 3
              },
              nuevoCodigo: { 
                required: true
                //minlength: 3
              },                                
          },
          messages: {
              nuevaNombreImputacion: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
              nuevoCodigo: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },                   
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





     // ----------------------------------------Datos de IMPUTACION-----------------------------------------------------

    // Hacemos que la interfaz arranque con el boton de edición dehabilitado, porque no hay ninguna jurisdiccion seleccionada aún.
    $("#boton-edit").attr("disabled","true");
     // Vamos a traer el json para laburar con ajax
     window.arrayrespuesta = new Array();
     var loadTable = function(){
      $.ajax({
        type: "GET",
        url: "/rubros",
        dataType: "json",
      })
      .done(function(respuesta){
        console.log(respuesta.data);
        window.arrayrespuesta = respuesta.data;
        window.table = $('#tabla-rubros').DataTable({
          "responsive": true,
          dom: "<'row'<'col-sm-7 text-left'B><'col-sm-5 text-right'f>>" +"<'row'<'col-sm-12't>>" +"<'row'<'col-sm-5 text-left'i><'col-sm-7 text-right'p>>",
          "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar: ",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
          },
          lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 Filas', '25 Filas', '50 Filas', 'Todos' ]
          ],
                  //elegis los formatos para exportar los datos de la tabla, colvis (va a servir para seleccionar que columnas queres q se vean o no), pagelenth para seleccionar la cantidad  datos mostrados 
                  buttons: [
                  {
                    extend: 'collection',
                    text: 'Exportar',
                    //elegis los formatos para exportar los datos de la tabla, colvis (va a seleccionar que mnas queres q se vean o no), pagelenth pla cantidad  datos mostrados             
                    buttons: [ 'copy', 'excel', 'pdf', 'csv', 'print']         
                  }, 'pageLength'    
                  ],
                  "columnDefs": [ 
                //con esto podes setear que columnas queres aparezca o no
                //{ "visible": false, "targets": 3 },
                // { responsivePriority: 1, targets: 0 },
                // { responsivePriority: 2, targets: 3 },                                    
                ],
                retrieve: true,
                data : respuesta.data,
                "columns": [
                    { "data": "id",
                    "className": "id"}, //esto es importante, le ponemos una clase a este campo
                    { "data": "nombre"},
                    { "data": "servicio",
                      "mRender": function(data, type, row) {
                          if (data == '1') {
                          return 'SI';
                          } else {
                          return 'NO';
                          } 
                        } 
                      }
                    ]
                  });
      })};
      loadTable();
      var tableReload = function(datosnuevos){
        window.arrayrespuesta = datosnuevos;
        $('#tabla-rubros').DataTable().clear().draw();
        $('#tabla-rubros').DataTable().rows.add(datosnuevos).draw();
      };
      //--------------------------------CARGAR DATOS MODAL-----------------------------------//
       $('#tabla-rubros tbody').on( 'click', 'tr', function () {
          if ( $(this).hasClass('active') ) {
            $(this).removeClass('active');
          }
          else {
            window.table.$('tr.active').removeClass('active');
            $(this).addClass('active');
            $("#boton-edit").removeAttr("disabled");
            $("#boton-edit").attr("enabled","true");
        // metemos en una variable lo que hay en e campo cuit de la jurisdiccion seleccionada
            var id = window.table.$("tr.active").find(".id").text();
            window.arrayrespuesta.forEach(function(item,index,arr){
              if (item.id == id){
              //llenamos el modal con los datos de la jurisdiccion
              $('div.modal-body div.form-group #nuevoIDRubro').val(item.id);
              $('div.modal-body div.form-group #nuevoNombreRubro').val(item.nombre);
              $('div.modal-body div.form-group #nuevoServicio').val(item.servicio);
              }
            });
          }
        }); 
    // A partir de acá metemos lo que pasa cuando picamos sobre alguna Jurisdiccion
    // --------------------------------AGREGAR RUBRO---------------------------------------------//
      $("#boton-add").click(function(){        
         $("#modalAddRubros").modal();
         //reseteo las validaciones
         $("#modal-formulario2").validate().resetForm();
         $("#modal-formulario2").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
         $("#modal-formulario2").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados

        //limpio los campos
         $('#nuevoIDRubro').val('');
         $('#nuevoNombreRubro').val('');
         $('#nuevoServicio').val('');
         //limpio la jurisdiccion
         $('#tabla-rubros tbody tr').removeClass('active');
         //cambio el titulo
         $("h4.modal-title").text("Nuevo Rubro");
         //muestro los botones correspondientes
         $('button#newRubro').css("display", "");
         $('button#editRubro').css("display", "none");
         $('button#deleteRubro').css("display", "none");
      });


       $("#newRubro").click(function(){
    //guardo los valores en cada variable                            
        if ($("#modal-formulario2").valid()){

            var nombre = $('div.modal-body div.form-group #nuevoNombreRubro').val();
            var servicio = $('div.modal-body div.form-group #nuevoServicio').val();

            console.log(nombre);
            console.log(servicio);
    //realizo un post pasando la url correspondiente al backend, los datos previamente capturados y realizo la funcion correspondiente que me devolvera la respuesta.
            
            $.ajax({
              type: "POST",
              url: "/rubros/new",
              async: false,
              data: {                  
                "nombre" : nombre,
                "servicio" : servicio, 
                 },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                alert("va como piña");
                console.log(respuesta);
              }else{
              }
              $("#modalAddRubros").modal('toggle');
              tableReload(respuesta.data);
              $('#nuevoIDRubro').val('');
              $('#nuevoNombreRubro').val('');
              $('#nuevoServicio').val('');
            });
          }
      });
      //-----------------------------------/AGREGAR RUBRO----------------------------------//  
      //-----------------------------------EDITAR RUBRO----------------------------------//
      $("#boton-edit").click(function(){        
        $("#modalAddRubros").modal();
        $("#modal-formulario2").validate().resetForm();
        $("#modal-formulario2").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
        $("#modal-formulario2").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados
        //limpio los campos
        $("h4.modal-title").text("Editar Rubro");
        $('button#newRubro').css("display", "none");
        $('button#editRubro').css("display", "");
        // $('button#deleteRubro').css("display", "");
      });

      $("#editRubro").click(function(){
        if ($("#modal-formulario2").valid()){
    //guardamos id para pasarlo en la url
        var id =  $('div.modal-body div.form-group #nuevoIDRubro').val();
        var nombre = $('div.modal-body div.form-group #nuevoNombreRubro').val();
        var servicio = $('div.modal-body div.form-group #nuevoServicio').val();
        console.log(servicio);        
        $.ajax({
              type: "POST",
              url: "/rubros/"+id+"/edit",
              async: false,
              data: {                  
                "nombre" : nombre,
                "servicio" : servicio },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                console.log(respuesta);
                alert("va como piña");
              }else{
              }
              $("#modalAddRubros").modal('toggle');
              tableReload(respuesta.data);
               $('#nuevoIDRubro').val('');
               $('#nuevoNombreRubro').val('');
               $('#nuevoServicio').val('');
               $("#boton-edit").attr("disabled","true");              
            });
          }
      });
      //-----------------------------------/EDITAR RUBRO----------------------------------//
      //-----------------------------------BORRAR RUBRO----------------------------------//
      //  $("#deleteRubro").click(function(){
      //     var id =  $('div.modal-body div.form-group #nuevoId').val();
      //     var result = confirm("Esta seguro de que desea borrar la jurisdiccion?");
      //     if (result) {
      //       $.ajax({
      //         type: "GET",
      //         url: "/jurisdicciones/"+id+"/del",
      //         async: false,
      //         dataType: "json",
      //       })
      //       .done(function(respuesta){
      //         $("#modalAddJurisdiccion").modal('toggle');
      //         tableReload(respuesta.data);
      //         $('#nuevoIDRubro').val('');
      //          $('#nuevoNombreRubro').val('');
      //          $('#nuevoServicio').val('');
      //          $("#boton-edit").attr("disabled","true");   
      //       });
      //     }                 
      // });   
      //-----------------------------------/BORRAR RUBRO----------------------------------//

         //---------------------------------validaciones-------------------------------------------//

      $("#modal-formulario2").validate({
          rules: {
              
              nuevoNombreRubro: { 
                required: true,
                minlength: 3
              },
              nuevoServicio: { 
                required: true
                //minlength: 3
              },                                
          },
          messages: {
              nuevoNombreRubro: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
              nuevoServicio: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },                   
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