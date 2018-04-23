/*
ejemplo:

$(document).ready(comboFill("/rubros","id","nombre","#combo"));
url: la url a la que le apuntamos para traernos la info;
value: es el atributo value que le vamos a mapear a cada option del combo
text: es el atributo que le vamos a mapear al texto de cada option del combo
cssid: es la clase, id o etiqueta en el SELECT al que le vamos a aplicar las options.

*/
var comboFill = function(url,value,text,cssid) {
            this.url = url;
            this.value = value;
            this.text = text;
            this.cssid = cssid;
            $.get(url,function(resp,estado,jqXHR){
                var arrayResp = JSON.parse(resp);
                var opciones = "";
                var obj = arrayResp.data[0];
                arrayResp.data.forEach(
                    function(item,index){
                        var obj = arrayResp.data[index];
                        opciones = opciones + "<option value='"+obj[value]+"'>"+obj[text]+"</option>";
                    }
                );
                //agrego la opcion generica "--seleccionar--" al combo
                opciones = opciones + '<option value="" selected="selected">--seleccionar--</option>';
                $(cssid).html(opciones);
            });
       };

// var comboFillv2 = function(array,value,text,cssid) {
//             this.array = array;
//             this.value = value;
//             this.text = text;
//             this.cssid = cssid;
//             // console.log("entre al combo");
//             // console.log(array);
//             var opciones = "";
//                 array.forEach(
//                     function(item,index){
//                         //var obj = array.data;
//                         opciones = opciones + "<option value='"+array[index].id+"'>"+array[index].detalle+"</option>";
//                     }
//                 );
//                 //agrego la opcion generica "--seleccionar--" al combo
//                 opciones = opciones + '<option value="" selected="selected">--seleccionar--</option>';
//                 $(cssid).html(opciones);
            
//        };