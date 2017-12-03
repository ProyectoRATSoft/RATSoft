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
     // comboFill("/comprobantes","id","detalle","#comprobanteDetalle");
     // comboFill("/comprobantes","id","codigo","#codigoComprobante");
     // comboFill("/comprobante/tipos","id","tipo_comp","#comprobante");
    // Hacemos que la interfaz arranque con el boton de edición dehabilitado, porque no hay ninguna comprobantes seleccionada aún.
    $("#boton-editar").attr("disabled","true");
     // Vamos a traer el json para laburar con ajax
     window.arrayrespuesta = new Array();
     var loadTable = function(){
      $.ajax({
        type: "GET",
        url: "/comprobantes/tipos",
        dataType: "json",
      })
      .done(function(respuesta){
        console.log(respuesta.data);
        window.arrayrespuesta = respuesta.data;
        window.table = $('#tabla-comprobante').DataTable({
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
                    { "data": "cod_comp.detalle"},
                    { "data": "tipo_comp" }
                    ]
                  });
      })};
      loadTable();
      var tableReload = function(datosnuevos){
        window.arrayrespuesta = datosnuevos;
        $('#tabla-comprobantes').DataTable().clear().draw();
        $('#tabla-comprobantes').DataTable().rows.add(datosnuevos).draw();
      };
      //--------------------------------CARGAR DATOS MODAL-----------------------------------//
       $('#tabla-comprobantes tbody').on( 'click', 'tr', function () {
          if ( $(this).hasClass('active') ) {
            $(this).removeClass('active');
          }
          else {
            window.table.$('tr.active').removeClass('active');
            $(this).addClass('active');
            $("#boton-editar").removeAttr("disabled");
            $("#boton-editar").attr("enabled","true");
        // metemos en una variable lo que hay en e campo cuit de la comprobantes seleccionada
            var id = window.table.$("tr.active").find(".id").text();
            window.arrayrespuesta.forEach(function(item,index,arr){
              if (item.id == id){
              //llenamos el modal con los datos de la comprobantes
              $('div.modal-body div.form-group #nuevoId').val(item.id);
              $('div.modal-body div.form-group #nuevaProvincia').val(item.nombre);
              $('div.modal-body div.form-group #nuevoCodigo').val(item.codigo);
              }
            });
          }
        }); 
    // A partir de acá metemos lo que pasa cuando picamos sobre alguna comprobantes
    // ---------------------AGREGAR comprobantes---------------------------------------------//
      $("#boton-add").click(function(){        
         $("#modalAddComprobante").modal();
         //reseteo las validaciones
         $("#modal-formulario").validate().resetForm();
         $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
         $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados

        //limpio los campos
         $('#nuevoId').val('');
         $('#nuevaProvincia').val('');
         $('#nuevoCodigo').val('');
         //limpio la comprobantes
         $('#tabla-comprobante tbody tr').removeClass('active');
         //cambio el titulo
         $("h4.modal-title").text("Nuevo comprobantes");
         //muestro los botones correspondientes
         $('button#newComprobante').css("display", "");
         $('button#editcomprobante').css("display", "none");
         $('button#deletecomprobante').css("display", "none");
      });


       $("#newcomprobante").click(function(){
    //guardo los valores en cada variable                            
        if ($("#modal-formulario").valid()){

            var comprobantes = $('div.modal-body div.form-group #nuevaProvincia').val();
            var codigo = $('div.modal-body div.form-group #nuevoCodigo').val();
    //realizo un post pasando la url correspondiente al backend, los datos previamente capturados y realizo la funcion correspondiente que me devolvera la respuesta.
            
            $.ajax({
              type: "POST",
              url: "/comprobantes/tipos/new",
              async: false,
              data: {                  
                "comprobantes" : comprobantes,
                "codigo" : codigo, 
                 },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                alert("va como piña");
              }else{
              }
              $("#modalAddComprobante").modal('toggle');
              tableReload(respuesta.data);
              $('#nuevoId').val('');
              $('#nuevaProvincia').val('');
              $('#nuevoCodigo').val('');
            });
        }
      });
      //-----------------------------------/AGREGAR comprobantes----------------------------------//  
      //-----------------------------------EDITAR comprobantes----------------------------------//
      $("#boton-editar").click(function(){        
        $("#modalAddComprobante").modal();
        $("#modal-formulario").validate().resetForm();
        $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
        $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados
        //limpio los campos
        $("h4.modal-title").text("Editar comprobante");
        $('button#newComprobante').css("display", "none");
        $('button#editComprobante').css("display", "");
        // $('button#deleteComprobantes').css("display", "");
      });

      $("#editComprobante").click(function(){
        if ($("#modal-formulario").valid()){
    //guardamos id para pasarlo en la url
        var id =  $('div.modal-body div.form-group #nuevoId').val();
        var comprobantes = $('div.modal-body div.form-group #nuevaProvincia').val();
        var codigo= $('div.modal-body div.form-group #nuevoCodigo').val();        
        $.ajax({
              type: "POST",
              url: "/comprobantes/tipos/"+id+"/edit",
              async: false,
              data: {                  
                "comprobantes" : comprobantes,
                "codigo" : codigo },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                alert("va como piña");
              }else{
              }
              $("#modalAddComprobante").modal('toggle');
              tableReload(respuesta.data);
               $('#nuevoId').val('');
               $('#nuevaProvincia').val('');
               $('#nuevoCodigo').val('');
               $("#boton-editar").attr("disabled","true");              
            });
          }
      });
      //-----------------------------------/EDITAR comprobantes----------------------------------//
      //-----------------------------------BORRAR comprobantes----------------------------------//
      //  $("#deleteComprobantes").click(function(){
      //     var id =  $('div.modal-body div.form-group #nuevoId').val();
      //     var result = confirm("Esta seguro de que desea borrar el comprobante?");
      //     if (result) {
      //       $.ajax({
      //         type: "GET",
      //         url: "/comprobantes/tipos/"+id+"/del",
      //         async: false,
      //         dataType: "json",
      //       })
      //       .done(function(respuesta){
      //         $("#modalAddComprobante").modal('toggle');
      //         tableReload(respuesta.data);
      //         $('#nuevoId').val('');
      //          $('#nuevaProvincia').val('');
      //          $('#nuevoCodigo').val('');
      //          $("#boton-editar").attr("disabled","true");   
      //       });
      //     }                 
      // });   
      //-----------------------------------/BORRAR comprobantes----------------------------------//

      //---------------------------------validaciones-------------------------------------------//
      $("#modal-formulario").validate({
          rules: {
              
              nuevoCodigo: { 
                required: true,
                minlength: 3
              },
              nuevaProvincia: { 
                required: true
                //minlength: 3
              },                                
          },
          messages: {
              nuevoCodigo: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
              nuevaProvincia: { 
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