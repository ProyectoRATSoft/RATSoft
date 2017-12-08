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

    $('#tabla-ventas tbody').on('click', 'tr', function() {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
      } else {
        window.table.$('tr.active').removeClass('active');
        $(this).addClass('active');
      }
    });
  });
