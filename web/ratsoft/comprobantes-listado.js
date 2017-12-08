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
      
      $('#comprobanteDetalle').change(function(){
        var option = $(this).find('option:selected').val();
        var opciones = "<option value='' selected='selected'>--seleccionar--</option>";
        window.arrayrespuesta.forEach(function(item,index,arr){
          if(option == item.cod_comp.id){
            opciones = opciones + "<option value='"+item.id+"'>"+item.tipo_comp+"</option>";

          }
        })
        $("#tipoComprobante").html(opciones);
      });
      
      var comboFill2 = function(url,value,text,text2,cssid) {
            this.url = url;
            this.value = value;
            this.text = text;
            this.cssid = cssid;
            this.text2 = text2
            $.get(url,function(resp,estado,jqXHR){
                var arrayResp = JSON.parse(resp);
                var opciones = "";
                var obj = arrayResp.data[0];
                arrayResp.data.forEach(
                    function(item,index){
                        var obj = arrayResp.data[index];
                        opciones = opciones + "<option value='"+obj[value]+"'>"+obj[text]+" -- "+obj[text2]+"</option>";
                    }
                );
                //agrego la opcion generica "--seleccionar--" al combo
                opciones = opciones + '<option value="" selected="selected">--seleccionar--</option>';
                $(cssid).html(opciones);
            });
       };

       comboFill2("/comprobantes","id","codigo","detalle","#comprobanteDetalle");

    // Hacemos que la interfaz arranque con el boton de edición dehabilitado, porque no hay ninguna comprobantes seleccionada aún.
    $("#boton-editar").attr("disabled","true");
    // selecciona el campo que desea

    $('#chkComprobanteDetalle').on('change',function() {
         if(this.checked){
           $('#comprobanteDetalle').attr("disabled","disabled").val('');
           $('#comprobanteDetalleModal').removeAttr("disabled").val('');
           $('#codigoDetalleModal').removeAttr("disabled").val('');
           $('#tipoComprobante').attr("disabled","disabled").val('');
         }
         else {
           $('#comprobanteDetalle').removeAttr("disabled").val('');
           $('#comprobanteDetalleModal').attr("disabled","disabled").val('');
           $('#codigoDetalleModal').attr("disabled","disabled").val('');
           $('#tipoComprobante').removeAttr("disabled").val('');
         }
      });

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
        $('#tabla-comprobante').DataTable().clear().draw();
        $('#tabla-comprobante').DataTable().rows.add(datosnuevos).draw();
      };
      //--------------------------------CARGAR DATOS MODAL-----------------------------------//
       $('#tabla-comprobante tbody').on( 'click', 'tr', function () {
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
                $('#comprobanteDetalle').val(item.cod_comp.id);               
                $('#comprobanteDetalle').trigger('change'); 
                $('#tipoComprobante').val(item.id); 
              
              //llenamos el modal con los datos de la comprobantes
              
              item.blk_exe == 1 ? $('#blk_exe').prop('checked', true) : $('#blk_exe').prop('checked', false) ;
              item.blk_exe == 1 ? $('#modalblk_exe').prop('checked', true) : $('#modalblk_exe').prop('checked', false) ;
              
              item.blk_perciva == 1 ? $('#blk_perciva').prop('checked', true) : $('#blk_perciva').prop('checked', false) ;
              item.blk_perciva == 1 ? $('#modalblk_perciva').prop('checked', true) : $('#modalblk_perciva').prop('checked', false) ;

              item.blk_perciibb == 1 ? $('#blk_perciibb').prop('checked', true) : $('#blk_perciibb').prop('checked', false) ;
              item.blk_perciibb == 1 ? $('#modalblk_perciibb').prop('checked', true) : $('#modalblk_perciibb').prop('checked', false) ;

              item.blk_ret == 1 ? $('#blk_ret').prop('checked', true) : $('#blk_ret').prop('checked', false) ;
              item.blk_ret == 1 ? $('#modalblk_ret').prop('checked', true) : $('#modalblk_ret').prop('checked', false) ;

              item.blk_netos == 1 ? $('#blk_netos').prop('checked', true) : $('#blk_netos').prop('checked', false) ;
              item.blk_netos == 1 ? $('#modalblk_netos').prop('checked', true) : $('#modalblk_netos').prop('checked', false) ;

              item.blk_iva == 1 ? $('#blk_iva').prop('checked', true) : $('#blk_iva').prop('checked', false) ;
              item.blk_iva == 1 ? $('#modalblk_iva').prop('checked', true) : $('#modalblk_iva').prop('checked', false) ;

              item.blk_nograv == 1 ? $('#blk_nograv').prop('checked', true) : $('#blk_nograv').prop('checked', false) ;
              item.blk_nograv == 1 ? $('#modalblk_nograv').prop('checked', true) : $('#modalblk_nograv').prop('checked', false) ;

              item.blk_total == 1 ? $('#blk_total').prop('checked', true) : $('#blk_total').prop('checked', false) ;
              item.blk_total == 1 ? $('#modalblk_total').prop('checked', true) : $('#modalblk_total').prop('checked', false) ;

              item.autoiva == 1 ? $('#autoiva').prop('checked', true) : $('#autoiva').prop('checked', false) ;
              item.autoiva == 1 ? $('#modalautoiva').prop('checked', true) : $('#modalautoiva').prop('checked', false) ;

              item.autoneto == 1 ? $('#autoneto').prop('checked', true) : $('#autoneto').prop('checked', false) ;
              item.autoneto == 1 ? $('#modalautoneto').prop('checked', true) : $('#modalautoneto').prop('checked', false) ;
              
              item.autototal == 1 ? $('#autototal').prop('checked', true) : $('#autototal').prop('checked', false) ;
              item.autototal == 1 ? $('#modalautototal').prop('checked', true) : $('#modalautototal').prop('checked', false) ;
              }
            });
          }
        }); 
    // A partir de acá metemos lo que pasa cuando picamos sobre alguna comprobantes
    // ---------------------AGREGAR comprobantes---------------------------------------------//
      $("#boton-add").click(function(){        
         $("#modalAddComprobante").modal();
         //reseteo las validaciones
         // $("#modal-formulario").validate().resetForm();
         $("#modal-formulario").find('.has-error').removeClass("has-error"); //limpia las clases has-error que pone los recuadros rojos
         $("#modal-formulario").find('.has-success').removeClass("has-success");//limpia los recuadros verdes de los datos correctos ingresados
         $('#tabla-comprobante tbody tr').removeClass('active');
         //cambio el titulo
         $("h4.modal-title").text("Nuevo comprobante");
         //muestro los botones correspondientes
         $('button#newComprobante').css("display", "");
         $('button#editComprobante').css("display", "none");
         $('button#deletecomprobante').css("display", "none");
         $("#boton-editar").attr("disabled","true");
         //limpio los campos
          $('#chkComprobanteDetalle').prop( "checked", false );
          $('#comprobanteDetalle').val('');
          $('#chkTipoComprobanteModal').prop( "checked", false );
          $('#tipoComprobanteDetalleModal').val('');
          $('#tipoComprobante').val('');
          $('#codigoDetalleModal').val('');
          $('#modalblk_exe').prop( "checked", false );
          $('#modalblk_perciva').prop( "checked", false );
          $('#modalblk_perciibb').prop( "checked", false );
          $('#modalblk_ret').prop( "checked", false );
          $('#modalblk_netos').prop( "checked", false );
          $('#modalblk_iva').prop( "checked", false );
          $('#modalblk_nograv').prop( "checked", false );
          $('#modalblk_total').prop( "checked", false );
          $('#modalautoiva').prop( "checked", false );
          $('#modalautoneto').prop( "checked", false );
          $('#modalautototal').prop( "checked", false );
         //limpio los datos del comprobante
          $('#blk_exe').prop( "checked", false );
          $('#blk_perciva').prop( "checked", false );
          $('#blk_perciibb').prop( "checked", false );
          $('#blk_ret').prop( "checked", false );
          $('#blk_netos').prop( "checked", false );
          $('#blk_iva').prop( "checked", false );
          $('#blk_nograv').prop( "checked", false );
          $('#blk_total').prop( "checked", false );
          $('#autoiva').prop( "checked", false );
          $('#autoneto').prop( "checked", false );
          $('#autototal').prop( "checked", false );
         
      });


       $("#newComprobante").click(function(){
          //guardo los valores en cada variable                            
        // if ($("#modal-formulario").valid()){
          
          var detalle; var codigo;  
          var tipo_comp = $('#tipoComprobanteDetalleModal').val().toUpperCase();;  
          var blk_exe;
          var blk_perciva; var blk_perciibb; var blk_ret;  var blk_netos;
          var blk_iva; var blk_nograv; var blk_total;  var autoiva;
          var autoneto;    var autototal;
          var cod_comp
          ! $('#modalblk_exe').is(':checked') ? blk_exe =0 : blk_exe =1;
          ! $('#modalblk_perciva').is(':checked') ?  blk_perciva=0 :  blk_perciva= 1;
          ! $('#modalblk_perciibb').is(':checked') ?  blk_perciibb=0 : blk_perciibb =1;
          ! $('#modalblk_ret').is(':checked') ? blk_ret=0 : blk_ret=1;
          ! $('#modalblk_netos').is(':checked') ? blk_netos=0 : blk_netos=1;
          ! $('#modalblk_iva').is(':checked') ? blk_iva=0 :  blk_iva=1;
          ! $('#modalblk_nograv').is(':checked') ?  blk_nograv=0 :  blk_nograv= 1;
          ! $('#modalblk_total').is(':checked') ?  blk_total=0 :  blk_total=1;
          ! $('#modalautoiva').is(':checked') ?  autoiva=0 :  autoiva=1;
          ! $('#modalautoneto').is(':checked') ?  autoneto=0 :  autoneto=1;
          ! $('#modalautototal').is(':checked') ?  autototal=0 :  autototal=1;
          //verifico si desea crear un nuevo comprobante o un tipo de comprobante nuevo
          if ($('#chkComprobanteDetalle').is(':checked'))
          {  
            detalle = $('#comprobanteDetalleModal').val().toUpperCase();
            codigo = $('#codigoDetalleModal').val().toUpperCase();
            $.ajax({
              type: "POST",
              url: "/comprobantes/newcomp",
              async: false,
              data: 
              {                  
               "detalle" : detalle,
               "codigo" : codigo,
              },
              dataType: "json"
               //------------------creo el  comprobante------------------//
           })
            .done(function(respuesta){
              console.log(respuesta);
              if(respuesta.status = 'OK')
              {
                respuesta.data.forEach(function(item,index,arr){
                  if (item.detalle == detalle){
                      cod_comp = item.id;
                      console.log(cod_comp);
                      console.log(item.id);
                  }
                });
                $.ajax({
                  type: "POST",
                  url: "/comprobantes/newtipo",
                  async: false,
                  data: {                  
                    "cod_comp" : cod_comp,
                    "tipo_comp": tipo_comp,
                    "blk_exe" : blk_exe,
                    "blk_perciva": blk_perciva,
                    "blk_perciibb" : blk_perciibb,
                    "blk_ret" : blk_ret,
                    "blk_netos" : blk_netos,
                    "blk_iva" : blk_iva,
                    "blk_nograv" :blk_nograv,
                    "blk_total" : blk_total,
                    "autoiva" : autoiva,
                    "autoneto" : autoneto,
                    "autototal" : autototal,
                  },
                  dataType: "json"
                })
                //------------------creo el tipo de comprobante------------------//
                .done(function(respuesta)
                {
                  if(respuesta.status = 'OK')
                  {
                    alert(respuesta.msg);
                    ///------------------------------muestro el mensaje correspondiente-------//
                  }
                  else
                  {
                    alert("ERROR \n Razon:" + respuesta.msg);
                    ///------------------------------muestro el mensaje correspondiente-------//
                  } 
                });
              }
              else 
              {
                alert("ERROR \n Razon:" + respuesta.msg);
                ///------------------------------muestro el mensaje correspondiente-------//
              }

            });
          }
          else
          {
            cod_comp = $('#comprobanteDetalle').val();
            $.ajax({
                type: "POST",
                url: "/comprobantes/newtipo",
                async: false,
                data: {                  
                  "cod_comp" : cod_comp,
                  "tipo_comp" : tipo_comp,
                  "blk_exe" : blk_exe,
                  "blk_perciva": blk_perciva,
                  "blk_perciibb" : blk_perciibb,
                  "blk_ret" : blk_ret,
                  "blk_netos" : blk_netos,
                  "blk_iva" : blk_iva,
                  "blk_nograv" :blk_nograv,
                  "blk_total" : blk_total,
                  "autoiva" : autoiva,
                  "autoneto" : autoneto,
                  "autototal" : autototal,
                },
                dataType: "json"
            })
              .done(function(respuesta){
                if(respuesta.status = 'OK')
                {
                  alert(respuesta.msg);
                }
                else
                {
                  alert("ERROR \n Razon:" + respuesta.msg);
                } 
              });
          }


        // }
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
      //          $('#chkComprobanteDetalle').prop( "checked", false );
                 //  $('#comprobanteDetalle').val('');
                 //  $('#chkTipoComprobanteModal').prop( "checked", false );
                 //  $('#tipoComprobanteDetalleModal').val('');
                 //  $('#tipoComprobante').val('');
                 //  $('#codigoDetalleModal').val('');
                 //  $('#modalblk_exe').prop( "checked", false );
                 //  $('#modalblk_perciva').prop( "checked", false );
                 //  $('#modalblk_perciibb').prop( "checked", false );
                 //  $('#modalblk_ret').prop( "checked", false );
                 //  $('#modalblk_netos').prop( "checked", false );
                 //  $('#modalblk_iva').prop( "checked", false );
                 //  $('#modalblk_nograv').prop( "checked", false );
                 //  $('#modalblk_total').prop( "checked", false );
                 //  $('#modalautoiva').prop( "checked", false );
                 //  $('#modalautoneto').prop( "checked", false );
                 //  $('#modalautototal').prop( "checked", false );
                 // //limpio los datos del comprobante
                 //  $('#blk_exe').prop( "checked", false );
                 //  $('#blk_perciva').prop( "checked", false );
                 //  $('#blk_perciibb').prop( "checked", false );
                 //  $('#blk_ret').prop( "checked", false );
                 //  $('#blk_netos').prop( "checked", false );
                 //  $('#blk_iva').prop( "checked", false );
                 //  $('#blk_nograv').prop( "checked", false );
                 //  $('#blk_total').prop( "checked", false );
                 //  $('#autoiva').prop( "checked", false );
                 //  $('#autoneto').prop( "checked", false );
                 //  $('#autototal').prop( "checked", false );
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