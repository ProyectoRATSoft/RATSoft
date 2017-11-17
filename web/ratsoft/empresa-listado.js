// <script>
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

    //llenamos el combo pasando 1. url 2. valor 3. descripcion 4. id del campo
    comboFill("/jurisdicciones","id","nombre","#nuevaProvincia")
    comboFill("/iva","id","codigo","#nuevaSituacionIVA")
    comboFill("/rubros","id","nombre","#nuevoRubro")
    comboFill("/iibb","id","nombre","#nuevoCodIngresosBrutos")

    $("#nuevoCodIngresosBrutos").change(function(){
      var option = $(this).find('option:selected').val();
      if(option == "4"){
        $("#nuevoIngresosBrutos").attr("disabled","true");
        $("#nuevoIngresosBrutos").removeAttr("required");
        $("#nuevoIngresosBrutos").val('');
      }else{
        $("#nuevoIngresosBrutos").removeAttr("disabled");
        $("#nuevoIngresosBrutos").attr("required","true");
      }
    });
    // Hacemos que la interfaz arranque con el boton de edición dehabilitado, porque no hay ninguna empresa seleccionada aún.
    $("#boton-editar").attr("disabled","true");
    $("#boton-compras").attr("disabled","true");
    $("#boton-ventas").attr("disabled","true");

    //window.arrayIva = new Array();
    $.get("/iva",function(resp,estado,jqXHR){
      var arrayResp = JSON.parse(resp);
      window.arrayIva = arrayResp.data;
    });
    // Vamos a traer el json para laburar con ajax
    window.arrayrespuesta = new Array();
    var loadTable = function(){
      $.ajax({
              type: "GET",
              url: "/empresa",
              dataType: "json",
            })
            .done(function(respuesta){
              window.arrayrespuesta = respuesta.data;
              window.table = $('#tabla-empresas').DataTable({
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
                      { responsivePriority: 1, targets: 0 },
                      { responsivePriority: 2, targets: 3 },                                    
                ],
                retrieve: true,
                data : respuesta.data,
                "columns": [
                  { "data": "nombre"},
                  { "data": "cuit",
                    "className": "cuit"}, //esto es importante, le ponemos una clase a este campo
                  { "data": "iva.detalle"},
                  { "data": "provincia.nombre" }
                  ]
              });
            })};
    loadTable();
    var tableReload = function(datosnuevos){
      window.arrayrespuesta = datosnuevos;
      $('#tabla-empresas').DataTable().clear().draw();
      $('#tabla-empresas').DataTable().rows.add(datosnuevos).draw();
      };
    // A partir de acá metemos lo que pasa cuando picamos sobre alguna empresa

    $('#tabla-empresas tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('active') ) {
        $(this).removeClass('active');
      }
      else {
        window.table.$('tr.active').removeClass('active');
        $(this).addClass('active');
        $("#boton-editar").removeAttr("disabled");
        $("#boton-editar").attr("enabled","true");
        $("#boton-compras").removeAttr("disabled");
        $("#boton-compras").attr("enabled","true");
        $("#boton-ventas").removeAttr("disabled");
        $("#boton-ventas").attr("enabled","true");
    // metemos en una variable lo que hay en e campo cuit de la empresa seleccionada
        var cuit = window.table.$("tr.active").find(".cuit").text();
        window.arrayrespuesta.forEach(function(item,index,arr){
          if (item.cuit == cuit){
          $('div.form-group #empresaID').val(item.id);
          $('#link-compras').removeAttr('href');
          $('#link-compras').attr('href',"/contable/compras/"+item.id);
          $('#link-ventas').removeAttr('href');
          $('#link-ventas').attr('href',"/contable/ventas/"+item.id);
          $('div.form-group #nombre').val(item.nombre);
          $('div.form-group #domicilio').val(item.domicilio);
          $('div.form-group #localidad').val(item.localidad);
          $('div.form-group #provincia').val(item.provincia.nombre);
          $('div.form-group #iva').val(item.iva.detalle);
          $('div.form-group #cuit').val(item.cuit);
          $('div.form-group #ingresosBrutos').val(item.iibb);
          $('div.form-group #codIngresosBrutos').val(item.iibb_cod.nombre);
          $('div.form-group #rubro').val(item.rubro.nombre);
          $('div.form-group #titular').val(item.titular);
          //llenamos el modal con los datos de la empresa
          $('div.modal-body div.form-group #nuevoId').val(item.id);
          $('div.modal-body div.form-group #nuevoNombre').val(item.nombre);
          $('div.modal-body div.form-group #nuevoDomicilio').val(item.domicilio);
          $('div.modal-body div.form-group #nuevaLocalidad').val(item.localidad);
          $('div.modal-body div.form-group #nuevaProvincia').val(item.provincia.id);
          $('div.modal-body div.form-group #nuevaSituacionIVA').val(item.iva.id);
          $('div.modal-body div.form-group #nuevoIva').val(item.iva.detalle);
          $('div.modal-body div.form-group #nuevoCuit').val(item.cuit);
          $('div.modal-body div.form-group #nuevoIngresosBrutos').val(item.iibb);
          $('div.modal-body div.form-group #nuevoCodIngresosBrutos').val(item.iibb_cod.id);
          $('div.modal-body div.form-group #nuevoRubro').val(item.rubro.id);
          $('div.modal-body div.form-group #nuevoTitular').val(item.titular);
          }
        });
      }
    }); 

    //llama al modal luego de hacer click en el boton
      $("#addEmpresa").click(function(){        
        $("#modalAddEmpresa").modal();
          //limpio los campos del modal
        $("#modal-formulario").validate().resetForm();       
    //limpio los campos del modal
        $('input#nuevoNombre').val('');
        $('input#nuevoId').val('');
        $('input#nuevoDomicilio').val('');
        $('input#nuevaLocalidad').val('');
        $('select#nuevaProvincia').val('');
        $('select#nuevaSituacionIVA').val('');
        $('input#nuevoIva').val('');
        $('input#nuevoCuit').val('');
        $('input#nuevoIngresosBrutos').val('');
        $('select#nuevoCodIngresosBrutos').val('');
        $('select#nuevoRubro').val('');     
        $('input#nuevoTitular').val('');
         //limpio los campos de la tabla del costado para arreglar efecto visual y evitar confusiones
        $('div.form-group #empresaID').val('');
        $('div.form-group #nombre').val('');
        $('div.form-group #domicilio').val('');
        $('div.form-group #localidad').val('');
        $('div.form-group #provincia').val('');
        $('div.form-group #iva').val('');
        $('div.form-group #cuit').val('');
        $('div.form-group #ingresosBrutos').val('');
        $('div.form-group #CodIngresosBrutos').val('');
        $('div.form-group #rubro').val('');
        $('div.form-group #titular').val('');
           //deselecciono la empresa de la tabla
        $('#tabla-empresas tbody tr').removeClass('active');
         //si esta en modo responsive hay q ver como hacer para que quede cerrado el "+" con sus campos
          //edito el titulo del modal
        $("h4.modal-title").text("Nueva Empresa");
       //establecemos que botones corresponde poder verse  en este caso, como agregamos no hace falta editar ni borrar
        $('button#newEmpresa').css("display", "");
        $('button#editDatesEmpresa').css("display", "none");
        $('button#deleteEmpresa').css("display", "none");
             
      });


    // llenamos el campo input cuando se detecta un cambio en el dropdownlist, 
    //hace una busqueda por el codigo seleccionado y va a buscar a la descripcion
    //a la base para llenar el campo con el texto completo

      $('#nuevaSituacionIVA').change(function(){
        var option = $(this).find('option:selected').val();
        window.arrayIva.forEach(function(item,index,arr){
          if(option == item.id){
            $('#nuevoIva').val(item.detalle);
          }
        })
      });
    //detecta el evento click
      $("#newEmpresa").click(function(){
    //guardo los valores en cada variable                            
        if ($("#modal-formulario").valid()){

            var nombre = $('div.modal-body div.form-group #nuevoNombre').val();
            var domicilio = $('div.modal-body div.form-group #nuevoDomicilio').val();
            var localidad = $('div.modal-body div.form-group #nuevaLocalidad').val();
            var provincia = $('div.modal-body div.form-group #nuevaProvincia').val();
            var iva = $('div.modal-body div.form-group #nuevaSituacionIVA').val();
            var cuit = $('div.modal-body div.form-group #nuevoCuit').val();
            var iibb = $('div.modal-body div.form-group #nuevoIngresosBrutos').val();
            var iibbtipo = $('div.modal-body div.form-group #nuevoCodIngresosBrutos').val();
            var rubro = $('div.modal-body div.form-group #nuevoRubro').val();
            var titular = $('div.modal-body div.form-group #nuevoTitular').val();
    //realizo un post pasando la url correspondiente al backend, los datos previamente capturados y realizo la funcion correspondiente que me devolvera la respuesta.
            
            $.ajax({
              type: "POST",
              url: "/empresa/new",
              async: false,
              data: {                  
                "nombre" : nombre , 
                "domicilio" : domicilio, 
                "localidad" : localidad,
                "provincia" : provincia,
                "iva" : iva, 
                "cuit" : cuit,
                "iibb" : iibb,
                "iibbtipo" : iibbtipo,
                "rubro" : rubro,
                "titular" : titular },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                alert("va como piña");
              }else{
              }
              $("#modalAddEmpresa").modal('toggle');
              tableReload(respuesta.data);
              $('div.form-group #empresaID').val('');
              $('div.form-group #nombre').val('');
              $('div.form-group #domicilio').val('');
              $('div.form-group #localidad').val('');
              $('div.form-group #provincia').val('');
              $('div.form-group #iva').val('');
              $('div.form-group #cuit').val('');
              $('div.form-group #ingresosBrutos').val('');
              $('div.form-group #codIngresosBrutos').val('');
              $('div.form-group #rubro').val('');
              $('div.form-group #titular').val('');
    //deberiamos mostrar algun mensaje tanto como de error, como de que todo salio bien. y luego recargar la grilla para que aparezca la nueva empresa en la tabla.
    //alert(resp);
            });
        }
      });
      $("#boton-editar").click(function(){
      //if ( $("#editEmpresa").prop("enabled")) {
        
      
        $("#modalAddEmpresa").modal();
        $("#modal-formulario").validate().resetForm();
        //con esto remuevo el seleccionar del dropdown
        $('select#nuevaProvincia :contains(--seleccionar--)').attr("disabled","true").removeAttr("selected");
        $('select#nuevaSituacionIVA :contains(--seleccionar--)').attr("disabled","true").removeAttr("selected");
        $('select#nuevoRubro :contains(--seleccionar--)').attr("disabled","true").removeAttr("selected");
        $('select#nuevoCodIngresosBrutos :contains(--seleccionar--)').attr("disabled","true").removeAttr("selected");

        $("h4.modal-title").text("Editar Empresa");
        $('button#newEmpresa').css("display", "none");
        $('button#editDatesEmpresa').css("display", "");
        $('button#deleteEmpresa').css("display", "");
      //}
      });

      $("#editDatesEmpresa").click(function(){
        if ($("#modal-formulario").valid()){
    //guardamos id para pasarlo en la url
        var id =  $('div.modal-body div.form-group #nuevoId').val();
        var nombre = $('div.modal-body div.form-group #nuevoNombre').val();
        var domicilio = $('div.modal-body div.form-group #nuevoDomicilio').val();
        var localidad = $('div.modal-body div.form-group #nuevaLocalidad').val();
        var provincia = $('div.modal-body div.form-group #nuevaProvincia').val();
        var iva = $('div.modal-body div.form-group #nuevaSituacionIVA').val();
        var cuit = $('div.modal-body div.form-group #nuevoCuit').val();
        var iibb = $('div.modal-body div.form-group #nuevoIngresosBrutos').val();
        var iibbtipo = $('div.modal-body div.form-group #nuevoCodIngresosBrutos').val();
        var rubro = $('div.modal-body div.form-group #nuevoRubro').val();
        var titular = $('div.modal-body div.form-group #nuevoTitular').val();
    //realizo un post pasando la url e id correspondiente para el backend, los datos previamente capturados y realizo la funcion correspondiente que me devolvera la respuesta.
        
        $.ajax({
              type: "POST",
              url: "/empresa/"+id+"/edit",
              async: false,
              data: {                  
                "nombre" : nombre, 
                "domicilio" : domicilio, 
                "localidad" : localidad,
                "provincia" : provincia,
                "iva" : iva, 
                "cuit" : cuit,
                "iibb" : iibb,
                "iibbtipo" : iibbtipo,
                "rubro" : rubro,
                "titular" : titular },
              dataType: "json"
            })
            .done(function(respuesta){
              if(respuesta.status = 'OK'){
                alert("va como piña");
              }else{
              }
              $("#modalAddEmpresa").modal('toggle');
              tableReload(respuesta.data);
              $('div.form-group #empresaID').val('');
              $('div.form-group #nombre').val('');
              $('div.form-group #domicilio').val('');
              $('div.form-group #localidad').val('');
              $('div.form-group #provincia').val('');
              $('div.form-group #iva').val('');
              $('div.form-group #cuit').val('');
              $('div.form-group #ingresosBrutos').val('');
              $('div.form-group #codIngresosBrutos').val('');
              $('div.form-group #rubro').val('');
              $('div.form-group #titular').val('');
    //deberiamos mostrar algun mensaje tanto como de error, como de que todo salio bien. y luego recargar la grilla para que aparezca la nueva empresa en la tabla.
            });
          }
      });

      $("#deleteEmpresa").click(function(){
          var id =  $('div.modal-body div.form-group #nuevoId').val();
          var result = confirm("Esta seguro de que desea borrar la empresa?");
          if (result) {
            $.ajax({
              type: "GET",
              url: "/empresa/"+id+"/del",
              async: false,
              dataType: "json",
            })
            .done(function(respuesta){
              $("#modalAddEmpresa").modal('toggle');
              tableReload(respuesta.data);
              $('div.form-group #empresaID').val('');
              $('div.form-group #nombre').val('');
              $('div.form-group #domicilio').val('');
              $('div.form-group #localidad').val('');
              $('div.form-group #provincia').val('');
              $('div.form-group #iva').val('');
              $('div.form-group #cuit').val('');
              $('div.form-group #ingresosBrutos').val('');
              $('div.form-group #codIngresosBrutos').val('');
              $('div.form-group #rubro').val('');
              $('div.form-group #titular').val('');
            });
          }                 
      });     
  
      function validaCuit(cuit) {
                if (typeof (cuit) == 'undefined'){
                    return true;
                }
                cuit = cuit.toString().replace(/[-_]/g, "");
                if (cuit == ''){
                    return true;
                }
                if (cuit.length != 11){
                    return false;
                }else{
                    var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
                    var total = 0;
                    for (var i = 0; i < mult.length; i++) {
                        total += parseInt(cuit[i]) * mult[i];
                    };
                    var mod = total % 11;
                    var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
                }
                return digito == parseInt(cuit[10]);
            };
            $.validator.addMethod("cuit", validaCuit, 'CUIT/CUIT Inválido');

      $("#modal-formulario").validate({
          rules: {
              nuevoNombre: { 
                required: true,
                minlength: 3
              },
              nuevoDomicilio: { 
                required: true,
                minlength: 3
              },
              nuevaLocalidad: { 
                required: true,
                minlength: 3
              },
              nuevaProvincia: { 
                required: true
                //minlength: 3
              },
              nuevaSituacionIVA: { 
                required: true
                //minlength: 3
              },
              nuevoCuit: { 
                required: true,
                cuit: true
              },
              nuevoCodIngresosBrutos: { 
                required: true
                //minlength: 3
              },
              nuevoRubro: { 
                required: true
                //minlength: 3
              },
              nuevoTitular: { 
                required: true,
                minlength: 3
              },
                                
          },
          messages: {
              nuevoNombre: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
              
              nuevoDomicilio: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
              nuevaLocalidad: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
              nuevaProvincia: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },
              nuevaSituacionIVA: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },
              nuevoCuit: { 
                required: "Este campo no puede dejarse en vacío"
              },
              nuevoCodIngresosBrutos: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },
              nuevoIngresosBrutos: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },
              nuevoRubro: { 
                required: "Este campo no puede dejarse en vacío",
                //minlength: 3
              },
              nuevoTitular: { 
                required: "Este campo no puede dejarse en vacío",
                minlength: "Este campo de contener al menos 3 caracteres"
              },
                                   
          },
          
      });
      
  });
// </script>