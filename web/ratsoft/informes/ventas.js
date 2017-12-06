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

    var loadTable = function() {
      $.ajax({
          type: "GET",
          url: "/search/ventas",
          dataType: "json",
        })
        .done(function(respuesta, status) {
          window.ventas = respuesta.ventas;
          // window.ventasShow = respuesta.ventas;

          window.table = $('#tabla-ventas').DataTable({
            data: window.ventas,
            "columns": [{
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
        })
        .fail(function(jqXHR, textStatus) {});
    };

    loadTable();

    $('#tabla-ventas tbody').on('click', 'tr', function() {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
      } else {
        window.table.$('tr.active').removeClass('active');
        $(this).addClass('active');
      }
    });


    // ----------------------------- ..Date pickers functions.. -----------------------------|

    // Metodo para refrescar la tabla.
    var tableReload = function(datosnuevos) {
      $('#tabla-ventas').DataTable().clear().draw();
      $('#tabla-ventas').DataTable().rows.add(datosnuevos).draw();
    };

    // Filtro que quita del array los elementos que no coincidan con la fecha seleccionada.
    var filtroVentas = function(venta) {
      var fechaVenta = venta.fecha.slice(0, 10);
      fechaVenta = fechaVenta.replace(/-/g, '');
      return window.fechaInit <= fechaVenta && window.fechaEnd >= fechaVenta;
    };

    // Datepicker
    $('#reservation').daterangepicker({
        ranges: {
          'Hoy': [moment(), moment()],
          'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
          'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
          'Este mes': [moment().startOf('month'), moment().endOf('month')],
          'Últimos mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
      },
      function(start, end) {
        // Esto se ejecuta cuando aplico algún filtro.

        // Formateo las fechas seleccionadas en el datepicker.
        window.fechaInit = start.format('YYYYMMDD');
        window.fechaEnd = end.format('YYYYMMDD');

        // Aplico filtro de fechas al total de las ventas.
        var ventasFiltradas = window.ventas.filter(filtroVentas);
        // Recargo la grilla con las ventas que aplican al filtro.
        tableReload(ventasFiltradas);

        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }
    );
  });
