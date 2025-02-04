Event.observe(window,'load', init, false);

function init(){
$('btEnviar').style.display = 'none';
Event.observe('usuario_menu', 'change',recuperar, false);
}

function recuperar(){
var url = 'cad_regra_menu_ajax.php';
var params = 'usuario_menu='+escape($F('usuario_menu'));
var target = 'resultado';
var retorno = new Ajax.Updater(
  target, url, {              method: 'post', parameters: params
  }
 );
}

