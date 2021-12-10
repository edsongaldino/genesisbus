<?php include("../sistema_mod_include.php"); ?>
<?php
verifica_sessao();

$origem = addslashes($_GET["origem"]);

// *** Include the class
require_once "../ferramentas/Upload-Jquery/resize.php"; 

// Pega o id
$id_produto = (int) base64_decode(addslashes(trim($_GET['id_produto'])));

// Pega os dados
$sql_produto = "SELECT nome_produto FROM produto WHERE id_produto = ".$id_produto."";
$query_produto = $pdo->query($sql_produto);
$executa_produto = $query_produto->fetch( PDO::FETCH_ASSOC );

// Diretorios padrao
$diretorio_raiz_absoluto = "/public_html/conteudos/produto/".$id_produto."/";
$diretorio_raiz_relativo = "../../conteudos/produto/".$id_produto."/";
$diretorio_temp = "../../conteudos/produto/".$id_produto."/temp/";

	
$fotos = new DirectoryIterator($diretorio_temp);


// Verifica todas as fotos
foreach($fotos as $foto) {

	if($foto->isFile()):
		
		$filename = $foto->getFilename();

		$foto_original = copy($diretorio_temp.$foto->getFilename(), $diretorio_raiz_relativo.$foto->getFilename());
		$foto_mini = copy($diretorio_temp.$foto->getFilename(), $diretorio_raiz_relativo."mini/".$foto->getFilename());
    
    // *** 1) Initialise / load image
    $resizeObj = new resize($diretorio_raiz_relativo."mini/".$foto->getFilename());

    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj -> resizeImage(214, 160, 'crop');
    /* Especificando que a nova imagem terá 200 px de largura e altura. E utilizando a opção CROP, que é considerada a melhor
    pois, recorta a imagem na medida sem distorção
    Se quizer ver outras opções, visite o site do desenvolvedor de resize2.php (http://www.jarrodoberto.com/articles/2011/09/image-resizing-made-easy-with-php)
    
    */

    // *** 3) Save image
    $resizeObj -> saveImage($diretorio_raiz_relativo."mini/".$foto->getFilename(), 100);
    

		$sql_inclui_foto = "INSERT INTO produto_fotos (id_produto,arquivo,destaque) VALUES (".$id_produto.",'".$filename."','N');";
    $inclui_foto = $pdo->prepare($sql_inclui_foto);
    $inclui_foto->execute();
		
		// verifica se deu erro pra registrar no log
		if($inclui_foto) {
			// Log
			salvar_log($_SESSION["id_usuario"],"incluir foto do produto",$sql_inclui_foto, $pdo);
			
			// Deleta o arquivo redimensionado
			unlink($diretorio_temp.$foto->getFilename());

		} else {
			// Log
			salvar_log($_SESSION["id_usuario"],"erro",$sql_inclui_foto, $pdo);
		}

	endif;
}


// Verifica se a acao ? pra alterar
if($_GET['acao'] == "alterar") {

	 // Pega todos as fotos
	$sql_fotos = "SELECT id_produto_foto, arquivo, destaque FROM produto_fotos WHERE id_produto = ".$id_produto." ORDER BY id_produto_foto";
  $query_fotos = $pdo->query($sql_fotos);
  $resultado_fotos = $query_fotos->fetchAll( PDO::FETCH_ASSOC );
	$total_fotos = count($query_fotos);

	// Atualiza fotos
	//$sql_atualiza_fotos = "UPDATE produtos_fotos SET destaque = 'N' WHERE id_produto = ".$id_produto."";
	//$query_atualiza_fotos = mysql_query($sql_atualiza_fotos);
	
	//if(!$query_atualiza_fotos) {
		// Log
		//salvar_log($_SESSION["id_usuario"],"erro",$sql_atualiza_fotos);
		//$erro = true;
	//}
	
	// Atualiza foto destaque	
	$sql_atualiza_foto = "UPDATE produto_fotos SET destaque = 'S' WHERE id_produto_foto = '".$_POST['destaque'.$executa_fotos['id_produto_foto']]."'";
  $atualiza_foto = $pdo->prepare($sql_atualiza_foto);
  $atualiza_foto->execute();
	
	if(!$atualiza_foto) {
		// Log
		salvar_log($_SESSION["id_usuario"],"erro",$sql_atualiza_foto, $pdo);
		$erro = true;
	}

	// Pega todas as fotos
	foreach($resultado_fotos AS $executa_fotos) {

		if($_POST['excluir'.$executa_fotos['id_produto_foto']] == 'S') {
      
      $sql_exclui_foto = "DELETE FROM produto_fotos WHERE id_produto_foto = ".$executa_fotos['id_produto_foto']."";
			$exclui_foto = $pdo->query($sql_exclui_foto);
      $exclui_foto->execute();

			if($exclui_foto) {
				
				// Deleta o arquivo redimensionado
				//$ftp_exclui_temp = unlink($diretorio_temp.$executa_fotos['arquivo']);
				$ftp_exclui_raiz = unlink($diretorio_raiz_relativo.$executa_fotos['arquivo']);
				
				if((!$ftp_exclui_raiz)) {
					// Log
					salvar_log($_SESSION["id_usuario"],"erro","erro ao excluir o arquivo ".$executa_fotos['arquivo'], $pdo);
					$erro = true;
				}
			} else {
				// Log
				salvar_log($_SESSION["id_usuario"],"erro",$sql_exclui_foto, $pdo);
				$erro = true;
			}
		}
	}
	
	// Verifica se deu erro
	if($erro == false) {
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=ok&msg=Informa??es das fotos salva com sucesso.&id_produto=".base64_encode($id_produto)."','_self');";
		echo "</script>";
	} else {
		echo "<script>";
		echo "open('".$_SERVER['SCRIPT_NAME']."?aviso=erro&msg=N?o foi poss?vel salvar todas as informa??es das fotos.&id_produto=".base64_encode($id_produto)."','_self');";
		echo "</script>";
	}
}

// Pega todos as fotos
$sql_fotos = "SELECT id_produto_foto, arquivo, destaque FROM produto_fotos WHERE id_produto = ".$id_produto." ORDER BY id_produto_foto";
$query_fotos = $pdo->query($sql_fotos);
$resultado_fotos = $query_fotos->fetchAll( PDO::FETCH_ASSOC );
$total_fotos = count($resultado_fotos);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include("../sistema_mod_include_head.php"); ?>
</head>
<body>
<div class="titulo-sessao-azul">Gerenciamento das fotos do produto</div>
<?php include("../sistema_aviso.php"); ?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="form1" name="form1" method="post" action="incluir_produto_fotos_destaque.php?acao=alterar&id_produto=<?php echo base64_encode($id_produto); ?>" enctype="multipart/form-data" onsubmit="javascript: this.button.value='Aguarde, carregando...'; this.button.disabled='disabled';">
  <tr>
    <td colspan="2">
      <div id="incluir_produto_fotos_destaque" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Fotos em destaque</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="negrito-padding-b-10">Para marca uma foto como destaque, clique no &quot;checkbox&quot; ao lado da palavra Destaque de cada foto.</td>
              </tr>
              <tr>
                <td class="negrito-padding-b-10">Para excluir uma foto, clique no &quot;checkbox&quot; ao lado da palavra Excluir de cada foto.</td>
              </tr>
              <tr>
                <td><a href="/painel/ferramentas/Upload-Jquery/index.php?origem=produto&id=<?php echo $id_produto; ?>" target="_self"><img src="../imagens/painel/botao_adicionarmaisfoto.gif" title="Adicionar mais foto" width="170" height="30" border="0" /></a></td>
              </tr>
              <tr>
                <td>
                    <table border="0" cellspacing="15" cellpadding="0" align="center">
                      <tr>
                      <?php
                        $i = 1; // Contador para quebrar linha
                        
                        foreach($resultado_fotos as $executa_fotos) {
                      ?>              
                        <td>
                          <table width="140" border="0" cellpadding="0" cellspacing="10" bgcolor="#EFEFEF" align="center">
                          	<tr>
                              <td width="21"><input name="destaque" type="checkbox" id="destaque" value="<?php echo $executa_fotos['id_produto_foto']; ?>" <?php if($executa_fotos['destaque'] == "S") { echo 'checked="checked"'; } ?> /></td>
                              <td width="89">Destaque</td>
                            </tr>
                            <tr>
                              <td colspan="2"><div align="center"><img src="<?php echo $diretorio_raiz_relativo; ?>/mini/<?php echo $executa_fotos['arquivo']; ?>" width="160" height="110" /></div></td>
                            </tr>
                            <tr>
                              <td><input name="excluir<?php echo $executa_fotos['id_produto_foto']; ?>" type="checkbox" class="checkbox" id="excluir<?php echo $executa_fotos['id_produto_foto']; ?>" value="S" /></td>
                              <td>Excluir</td>
                            </tr>
                          </table>
                        </td>
                        <?php if(is_int($i/4) && $i < $total_fotos) { ?>                
                      </tr>
                      <tr>
                        <?php } ?>
                        <?php if($i == $total_fotos) { ?>
                      </tr>
                        <?php } ?>                
                      <?php
                        $i += 1;
                        }
                      ?>              
                    </table>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><a href="javascript: document.form1.submit();"><img src="../imagens/painel/botao_salvaraltecao.gif" title="Salvar altera&ccedil;&atilde;o" width="150" height="30" border="0" /></a></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td><div class="info-observacao">Ap&oacute;s marca as fotos como destaque e / ou exclus&atilde;o, clique em <span class="negrito">Finalizar gerenciamento das fotos</span> para finalizar a inclus&atilde;o ou altera&ccedil;&atilde;o das fotos da produto.</div></td>
    <td><div align="right"><input type="button" name="button" id="button" value="Finalizar gerenciamento das fotos" onclick="javascript: window.open('gerenciar_produtos.php','_self')" /></div></td>
  </tr>
</form>
</table>
<script type="text/javascript">
var incluir_produto_fotos_destaque = new Spry.Widget.TabbedPanels("incluir_produto_fotos_destaque");
</script>
</body>
</html>