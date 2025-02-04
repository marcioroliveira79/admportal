<?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

  $_GET['id_usuario'];
  $_GET['id_mensagem'];
  $_GET['id_destino'];

?>
 <script type="text/javascript">
function resizeWindow()
  {
  window.resizeTo(625,530)
  }
</script>

<script src="prototype.js" type="text/javascript"></script>
<script>
   new Ajax.PeriodicalUpdater('divExample', 'ajax_list_online.php',
   {
   method: 'post',
   parameters: {id_usuario: '<?echo $_GET['id_usuario']?>',id_mensagem: '<?echo $_GET['id_mensagem']?>',id_destino: '<?echo $_GET['id_destino']?>'},
   frequency: 3
   });



</script>



<script type="text/javascript">
  //<![CDATA[
  window.onload = function(){
  setInterval('window.scrollBy(0,10000)', 999);
  }
   //]]>
</script>





  <div id="divExample" >
  </div>






<?
