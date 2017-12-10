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

    // Obtiene todos los checkbox que estén seleccionados.
    var obtenerSeleccionados = function() {
      var seleccionados = [];
      $('.contenedor input:checked').each(function() {
        seleccionados.push($(this).attr('id'));
      });
      window.checkSeleccionados = seleccionados;
    }

    // Por default selecciono todos los checkbox.
    window.stateChecks = true;
    $('input:checkbox').prop('checked', true);

    // Selecciona y des-selecciona todos los checkbox.
    $("#btnCheckAll").click(function () {
      if (window.stateChecks === true) {
        $('input:checkbox').prop('checked', false);
        window.stateChecks = false;
      } else {
        $('input:checkbox').prop('checked', true)
        window.stateChecks = true;
      }
    });

    // Se ejecuta cuando hago click en "Filtrar".
    $('#btnFiltrar').click(function() {
      obtenerSeleccionados();

      if (window.checkSeleccionados.length > 0 && window.stateChecks === false) {
        // Aplico filtro de fechas al total de las ventas.
        var ventasFiltradas = window.ventas.filter(filtroVentas);
        // Recargo la grilla con las ventas que aplican al filtro.
        tableReload(ventasFiltradas);
      } else {
        tableReload(window.ventas);
      }
    });

    // Filtro que quita del array los elementos que no coincidan con las jurisdicciones seleccionadas.
    var filtroVentas = function(venta) {
      var ok = false;
      var jurisdiccionVenta = venta.cliente.jurisdiccion.nombre.trim(); // El método trim(); le quita los espacios adelante.
      $.each(window.checkSeleccionados, function(key, value) {
	       if (jurisdiccionVenta === value) ok = true;
      });
      return ok;
    };

    // Metodo para refrescar la tabla.
    var tableReload = function(datosnuevos) {
      $('#tabla-ventas').DataTable().clear().draw();
      $('#tabla-ventas').DataTable().rows.add(datosnuevos).draw();
    };

    // Pinta la fila seleccionada.
    $('#tabla-ventas tbody').on('click', 'tr', function() {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
      } else {
        window.table.$('tr.active').removeClass('active');
        $(this).addClass('active');
      }
    });
  });
