<?php

class Pagina
{
	private $idPagina;
	private $nomeArquivo;
	private $descricao;
	private $usuarioCriacao;
	private $usuarioAlteracao;
	private $dataCriacao;
	private $dataAlteracao;
	private $status;
	private $limite=0;
	
	function getDataAlteracao() {
		return $this->dataAlteracao;
	}
	function setDataAlteracao($dataAlteracao) {
		$this->dataAlteracao = $dataAlteracao;
	}
	function getDataCriacao() {
		return $this->dataCriacao;
	}
	function setDataCriacao($dataCriacao) {
		$this->dataCriacao = $dataCriacao;
	}
	function getDescricao() {
		return $this->descricao;
	}
	function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	function getIdPagina() {
		return $this->idPagina;
	}
	function setIdPagina($idPagina) {
		$this->idPagina = $idPagina;
	}
	function getNomeArquivo() {
		return $this->nomeArquivo;
	}
	function setNomeArquivo($nomeArquivo) {
		$this->nomeArquivo = $nomeArquivo;
	}
	function getUsuarioAlteracao() {
		return $this->usuarioAlteracao;
	}
	function setUsuarioAlteracao(Usuario $usuarioAlteracao) {
		$this->usuarioAlteracao = $usuarioAlteracao;
	}
	function getUsuarioCriacao() {
		return $this->usuarioCriacao;
	}
	function setUsuarioCriacao(Usuario $usuarioCriacao) {
		$this->usuarioCriacao = $usuarioCriacao;
	}
	function getStatus() {
		return $this->status;
	}
	function setStatus($status) {
		$this->status = $status;
	}
	function getLimite() {
		return $this->limite;
	}
	function setLimite($limite) {
		$this->limite = $limite;
	}
	function getPagina($pagina)
	{
		if ($pagina=="") {
			$pagina = 1;
		}
		return $inicio = ($pagina-1)*$this->getLimite();
	}

	static function configuraPaginacao($cj, $pagina, $totalRegistros, $link, $limite, $direcao)
	{
		//$quantidade = bcdiv($totalRegistros, $limite);
		//$quantidade = intval($totalRegistros/$limite);
		$quantidade = ceil($totalRegistros/$limite);
		//if (bcmod($totalRegistros,$limite))
		//if ($totalRegistros%$limite==0)
		//	$quantidade++;
		?>
		<form name="formIrPara" id="irPara" >
		<div class="Pages">
		<div class="Paginator">
			<?	
			if ($cj=="")
				$cj=0;
			if ($direcao==+1)
			{
				if (($pagina-1)%10==0)
					$cj++;
			}
			elseif ($direcao==-1)
			{
				if (($pagina)%10==0)
					$cj--;
			}
			if ($pagina=="")
				$pagina = 1;
			if ($pagina > 1)
			{
				?>
				<a style="text-decoration: none" href="<? echo $link."&cj=$cj&pagina=".($pagina-1)."&direcao=-1"; ?>" class="Prev">&lt; Ant.</a>
				<?
			}
			$i = 10*$cj;
			$cont=0;
			while ($i < $quantidade)
			{
				?>
				<?
				if ($pagina-1 == $i)
				{
					?><span class="this-page"><?= $i+1 ?></span><?
					//echo $i+1;
				}
				else
				{
					?>
					<a style="text-decoration: none" href="<? echo $link."&cj=$cj&pagina=".($i+1); ?>"><? echo $i+1; ?></a>
					<?
				}
				$i++;
				$cont++;
				if ($cont==10)
					break;
			}
			if ($pagina < $quantidade)
			{
				?>
				<a style="text-decoration: none"href="<? echo $link."&cj=$cj&pagina=".($pagina+1)."&direcao=+1"; ?>" class="Next">Próx. &gt;</a>
				<?
			}
			if ($quantidade!=0)
			{							
				?>
				<br /><br />
				&nbsp;Página <? echo $pagina; ?> de <? echo $quantidade; ?>
				&nbsp;de <?=$totalRegistros?> registros
				&nbsp;
				<script type="text/JavaScript">
				<!--
				function MM_jumpMenu(targ,selObj,restore){ //v3.0
				  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
				  if (restore) selObj.selectedIndex=0;
				}
				//-->
				</script>
				
				  <select name="irPara" onchange="MM_jumpMenu('self',this,1)" class="irPara">
				  	<option>Página</option>
				  	<?
				  	$cjTmp=0;
				  	for ($i=1; $i<=$quantidade; $i++)
				  	{
					  	?>
					    <option value="<? echo $link."&cj=$cjTmp&pagina=".$i; ?>"><?=$i?></option>
					    <?
				  		if ($i%10==0)
				  		{
				  			echo $cjTmp++;
				  		}
					}
				    ?>
				  </select>
				
				
			<?
			}
			else
			{
				
			}
			?>
			</div>
		</div>	
		</form>
	<?
	}
	
}
?>
