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

    window.arrayrespuesta = new Array();
    var loadTable = function(){
      $.ajax({
              type: "GET",
              url: "/empresa",
              dataType: "json",
            })
            .done(function(respuesta){
              window.arrayrespuesta = respuesta.data;
              console.log(arrayrespuesta);  
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
    $('#tabla-empresas tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('active') ) {
        $(this).removeClass('active');
      }
      else {
        window.table.$('tr.active').removeClass('active');
        $(this).addClass('active');

    // metemos en una variable lo que hay en e campo cuit de la empresa seleccionada
        var cuit = window.table.$("tr.active").find(".cuit").text();
        console.log(cuit);
        window.arrayrespuesta.forEach(function(item,index,arr){
          if (item.cuit == cuit){
            $('#vpj').removeAttr('href');
            $('#vpj').attr('href',"/informes/ventas-por-jurisdiccion/"+item.id);
            $('#vpcli').removeAttr('href');
            $('#vpcli').attr('href',"/informes/ventas-por-cliente/"+item.id);
            $('#vpp').removeAttr('href');
            $('#vpp').attr('href',"/informes/ventas-por-periodo/"+item.id);
            $('#vpco').removeAttr('href');
            $('#vpco').attr('href',"/informes/ventas-por-comprobante/"+item.id);
            $('#vpf').removeAttr('href');
            $('#vpf').attr('href',"/informes/ventas-por-fecha/"+item.id);
          }
        });
      }
    }); 
  });