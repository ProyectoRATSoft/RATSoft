{% extends 'empresas/base.php.twig' %}
{% block body %}
  <!-- ACA EMPIEZO A TRABAJAR LA INTERFAZ Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h2>
        Empresas
        <small>Buscador</small>
      </h2>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Buscador de Empresas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Busque la empresa deseada..</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Razon Social</th>
                    <th>Cuit</th>
                    <th>Condicion IVA</th>
                    <th>Jurisdiccion</th>                    
                  </tr>
                </thead>
                <tbody>

                  {% set a = controller('AppBundle:Empresas:list',{ 'Request': '' }) | json_encode() %}
                  
                  <!-- {% set empresas = render(controller('AppBundle:Empresas:list',{ 'Request': '' })) 
                  %}
                  {% for item in empresas %}
                    <li>{{ item.nombre }}</li>
                  {% endfor %} -->
                </tbody>
                <tfoot>
                  <tr>
                    <th>Razon Social</th>
                    <th>Cuit</th>
                    <th>Condicion IVA</th>
                    <th>Jurisdiccion</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <div class="col-xs-6">
          <div class="panel-group" id="accordion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                    Datos de la empresa</a>
                  </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="empresaID">ID: </label>
                      <input type="number" required="true" class="form-control input-sm" id="empresaID" name="empresaID" disabled="true" placeholder="ID Empresa" />
                    </div>
                    <div class="form-group">
                      <label for="nombre">Nombre: </label>
                      <input type="text" required="true" class="form-control input-sm" id="nombre" name="nombre"
                      placeholder="Nombre Empresa" disabled="true"/>   
                    </div>
                    <div class="form-group">
                      <label for="domicilio">Domicilio: </label>                  
                      <input type="text" required="true" class="form-control input-sm" id="domicilio" name="domicilio"
                      placeholder="Domicilio Empresa" disabled="true" />
                    </div>
                    <div class="form-group">
                      <label for="localidad">Localidad: </label>                    
                      <input type="text" class="form-control input-sm" id="localidad" name="localidad" placeholder="Localidad empresa" disabled="true" />                    
                    </div>
                    <div class="form-group">
                      <label for="provincia">Provincia: </label>                    
                      <input type="text" class="form-control input-sm" id="provincia" name="provincia" placeholder="provincia empresa" disabled="true"/>                      
                    </div>
                    <div class="form-group">
                      <label for="situacionIVACompleto">Situacion IVA: </label>                      
                      <div class="input-sm"> 
                        <select name="situacionIVA" required="true" class="selectpicker dropdown-toggle" type="button" data-toggle="dropdown">
                          <option disabled selected value>--Seleccione una opcion--</option>  
                          <option>Opcion 1</option>
                          <option>Opcion 2</option>
                          <option>Opcion 3</option>
                        </select>
                      </div>
                      <br>                
                      <input type="text" class="form-control input-sm" name="situacionIVACompleto" disabled="true" placeholder="Situacion IVA descripcion completa" /> 
                    </div>
                    <div class="form-group">
                      <label for="cuit">Cuit: </label>                    
                      <input type="number" required="true" class="form-control input-sm" id="cuit" name="cuit" placeholder="Cuit" disabled="true"/>
                    </div> 
                    <div class="form-group">
                      <label for="ingresosBrutos">Ingresos Brutos: </label>                  
                      <input type="text" required="true" class="form-control input-sm" id="ingresosBrutos" name="ingresosBrutos" placeholder="Ingresos Brutos" disabled="true"/>  
                    </div>
                    <div class="form-group">
                      <label for="rubro">Rubro: </label>
                      <input type="text" class="form-control input-sm" id="rubro" name="rubro" placeholder="Rubro" disabled="true" />
                    </div>
                    <div class="form-group">
                      <label for="titular">Titular: </label>
                      <input type="text" class="form-control input-sm" id="titular" name="titular" placeholder="Titular" disabled="true" />
                    </div>     
                  </div>
                </div>  
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                    Datos de Contacto</a>
                  </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="email">Email: </label>
                      <input type="email" required="true" class="form-control input-sm" id="email" name="email" placeholder="Email" disabled="true" />   
                    </div>
                    <div class="form-group">
                      <label for="telefono">Telefono: </label>
                      <input type="number" required="true" class="form-control input-sm" id="telefono" name="telefono" placeholder="Telefono" disabled="True" />  
                    </div>
                    <div class="form-group">
                      <label for="fax">Fax: </label>                    
                      <input type="number" required="true" class="form-control input-sm" id="fax" name="fax" placeholder="Fax" disabled="true" /> 
                    </div>    
                  </div>
                </div>
              </div>          
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
{% endblock %}
