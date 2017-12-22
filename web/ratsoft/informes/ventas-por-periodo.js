  // Con esto bloqueamos la pantalla cuando inicia alguna accion ajax..
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
    // Cuando termina la accion ajax, se desbloquea..
    $(document).ajaxStop(function() {
      $.unblockUI();
    });

    // Recibo los datos desde el backend.
    window.ventas = window.arrayrespuesta.ventas;

    // Armo la tabla con los datos recibidos.
    window.table = $('#tabla-ventas').DataTable({
       dom: "<'row'<'col-sm-8 text-left'B><'col-sm-4 text-right'f>>" +"<'row'<'col-sm-12't>>" +"<'row'<'col-sm-5 text-left'i><'col-sm-7 text-right'p>>",
                    //configuramos la cantidad de datos que queremos que nos muestre el menu <lf<t>ip>
                    // "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                    // "<'row'<'col-sm-12'tr>>" +
                    // "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    "language": {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Buscar:",
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
                    buttons: [
                                {
                                    extend: 'collection',
                                    text: 'Exportar',
                                    //elegis los formatos para exportar los datos de la tabla, colvis (va a servir para seleccionar que columnas queres q se vean o no), pagelenth para seleccionar la cantidad  datos mostrados 
                                    buttons: [ 'copy', 'excel', 'pdf', 'csv', 'print'] 
                                }, 'colvis', 'pageLength'
                              ],
                                    
                    "columnDefs": [ 
                                  //con esto podes setear que columnas queres aparezca o no
                                  //{ "visible": false, "targets": 3 },
                                  // { responsivePriority: 1, targets: 0 },
                                  // { responsivePriority: 2, targets: 0 },
                                  { targets: [0, 1, 2, 3, 4, 5, 6, 7, 8], visible: true},

                                  //{ targets: '_all', visible: false }
                                
                    ],
                    "responsive": true,   
                    retrieve: true,
                    fixedHeader: true,
      data: window.ventas,
      "columns": [
        {
                "class":          "details-control",
                "orderable":      false,
                "data":           null,
                "defaultContent": "",
                "width" : "2%"
        },         
        {
          "data": "id",
          "className": "id"
        },
        {
          //Concateno periodo mes y año para armar el periodo.
          "mData": null,
          "mRender": function(data, type, full) {
            return full['periodo_mes'] + '-' + full['periodo_ano'];
          },
          "title": "Período"
        },
        {
          "data": "cliente.nombre",
          "title": "Cliente"
        },
        {
          "data": "fecha",
          "title": "Fecha",
          "render": function(value) {
            if (value === null) return "";
            return moment(value).format('DD/MM/YYYY');
          }
        },
        {
          "mData": null,
          "mRender": function(data, type, full) {
            return full['cod_comprobante'].detalle + ' ' + full['tipo_comprobante'].tipo_comp;
          },
          "title": "Comprobante"
        },
        {
          "data": "nro_comprobante",
          "title": "Número de Comprobante"
        },

        {
          "data": "cliente.cuit",
          "title": "C.U.I.T."
        },
        {
          "data": "cliente.jurisdiccion.nombre",
          "title": "Jurisdiccion"
        },
        {
          "data": "total",
          "title": "Total"
        },
        {
          "data": "neto105",
          "title": "Neto 10.5%",
          className: 'none'
        },
        {
          "data": "neto21",
          "title": "Neto 21%",
          className: 'none'
        },
        {
          "data": "neto_exento",
          "title": "Neto exento",
          className: 'none'
        },
        {
          "data": "nogravado",
          "title": "No Gravado",
          className: 'none'
        },
        {
          "data": "iva105",
          "title": "IVA 10.5%",
          className: 'none'
        },
        {
          "data": "iva21",
          "title": "IVA 21%",
          className: 'none'
        },
        {
          "data": "ret_gan",
          "title": "Retenciones de Ganancias",
          className: 'none'
        },
        {
          "data": "retencion",
          "title": "Retencion",
          className: 'none'
        },
        {
          "data": "percepcion",
          "title": "Percepcion",
          className: 'none'
        }
      ]
    });
  table.buttons().container().appendTo( '#tabla-ventas .col-sm-6:eq(0)' ); 

    $('#btnAplicarPeriodo').click(function() {
      window.periodoDesde = $('#periodoDesde').val();
      window.periodoHasta = $('#periodoHasta').val();

      window.periodoDesde = window.periodoDesde.replace(/-/g, '');
      window.periodoHasta = window.periodoHasta.replace(/-/g, '');

      var ventasFiltradas = window.ventas.filter(filtroVentas);
      tableReload(ventasFiltradas);
    });

    var filtroVentas = function(venta) {
      var anoVenta = venta.periodo_ano.toString();
      var mesVenta = venta.periodo_mes.toString();

      if (mesVenta.length === 1) {
        mesFormat = '0' + mesVenta;
      } else {
        mesFormat = mesVenta;
      }

      var periodoVenta = anoVenta + mesFormat;
      return window.periodoDesde <= periodoVenta && window.periodoHasta >= periodoVenta;
    };

    // Metodo para refrescar la tabla.
    var tableReload = function(datosnuevos) {
      $('#tabla-ventas').DataTable().clear().draw();
      $('#tabla-ventas').DataTable().rows.add(datosnuevos).draw();
    };

    $('#tabla-ventas tbody').on('click', 'tr', function() {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
      } else {
        window.table.$('tr.active').removeClass('active');
        $(this).addClass('active');
      }
    });

    // $("#filtros-periodos").validate({
    //   rules: {
    //     periodoDesde: {
    //       required: true
    //     },
    //     periodoHasta: {
    //       required: true
    //     }
    //   },
    //   messages: {
    //     periodoDesde: {
    //       required: "Este campo es requerido"
    //     },
    //     periodoHasta: {
    //       required: "Este campo es requerido"
    //     }
    //   },
    //   highlight: function(element) {
    //             $(element).closest('.form-group').addClass('has-error');
    //   },
    //   unhighlight: function(element) {
    //       $(element).closest('.form-group').removeClass('has-error');
    //   },
    //   errorElement: 'input',
    //   errorClass: 'help-block',
    //   errorPlacement: function(error, element) {
    //     if(element.parent('.input-group').length) {
    //       error.insertAfter(element.parent());
    //     } else {
    //       error.insertAfter(element);
    //     }
    //   }
    // });
  });
