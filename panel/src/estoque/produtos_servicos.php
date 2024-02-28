<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/rlvendas/panel/lib/includes.php");

    if($_POST['cod']){
        $_SESSION['cod_lancamento'] = $_POST['lancamento'];
    }


    if($_POST['acao'] == 'excluir'){
        $query = "delete from produtos_servicos where codigo = '{$_POST['codigo']}'";
        $result = sisLog($query);
    }

    if($_POST['filtro'] == 'filtrar'){
        $_SESSION['textoBusca'] = $_POST['campo'];
      }elseif($_POST['filtro']){
        $_SESSION['textoBusca'] = false;
      }
  
      if($_SESSION['textoBusca']){
        $where = " and a.nome like '%{$_SESSION['textoBusca']}%' or a.descricao like '%{$_SESSION['textoBusca']}%'";
      }

    $query = "select * from lancamentos where codigo = '{$_SESSION['cod_lancamento']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>"><?=(($d->fornecedor)?'Lançcamento':'Saída')?> <?=$d->numero?></h4>
<h6>Produtos / Serviços (Frcdr: <?=$_POST['fornecedor']?>)</h6>
<div class="input-group">
<label class="input-group-text" for="inputGroupFile01">Buscar por </label>
    <input textoBusca type="text" class="form-control" value="<?=$_SESSION['textoBusca']?>" aria-label="Digite a informação para a busca">
    <button filtro="filtrar" class="btn btn-outline-secondary" type="button"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
    <button filtro="limpar" class="btn btn-outline-danger" type="button"><i class="fa-solid fa-eraser"></i></button>
    <?php
    if($_POST['fornecedor']){
    ?>
    <button novo class="btn btn-outline-primary" type="button"><i class="fa-solid fa-file-circle-plus"></i></button>
    <?php
    }
    ?>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Unidade</th>
            <th>Valor</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
<?php
    $query = "select 
                    a.*, 
                    (select count(*) from movimentacao where produto = a.codigo) as qt,
                    b.unidade as unidade_nome,
                    b.descricao as unidade_descricao
                from produtos_servicos a
                    left join unidades_medida b on a.unidade = b.codigo    
                where 1 {$where} order by a.nome limit 100";
    $result = sisLog($query);
    while($d = mysqli_fetch_object($result)){
?> 
        <tr>
            <td><?=$d->nome?></td>
            <td><?="{$d->unidade_nome} ({$d->unidade_descricao})"?></td>
            <td><?=$d->valor?></td>
            <td>
                <i class="fa-regular fa-square-plus text-success me-3" acao="adicionar" codigo="<?=$d->codigo?>" valor='<?=$d->valor?>' style="cursor:pointer"></i>
                <?php
                if($_POST['fornecedor']){
                ?>
                <i class="fa-solid fa-pen-to-square me-3 text-primary" acao="editar" codigo="<?=$d->codigo?>" valor='<?=$d->valor?>' style="cursor:pointer"></i>
                <?php
                if(!$d->qt){
                ?>
                <i class="fa-solid fa-trash-can text-danger" acao="excluir" codigo="<?=$d->codigo?>" valor='<?=$d->valor?>' style="cursor:pointer"></i>
                <?php
                }
                }
                ?>
            </td>
        </tr>
<?php
    }
?>
    </tbody>
</table>


<script>
    $(function(){

        Carregando('none');

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[textoBusca]").val();
          $.ajax({
              url:"src/estoque/produtos_servicos.php",
              type:"POST",
              data:{
                  filtro,
                  campo,
                  fornecedor:'<?=$_POST['fornecedor']?>'
              },
              success:function(dados){
                $(".LateralDireita").html(dados);
              }
          })
        })

        $("button[novo]").click(function(){
          $.ajax({
              url:"src/estoque/produtos_servicos_form.php",
              type:"POST",
              data:{
                fornecedor:'<?=$_POST['fornecedor']?>'
              },
              success:function(dados){
                $(".LateralDireita").html(dados);
              }
          })
        })

        $("i[acao]").click(function(){
            acao = $(this).attr("acao");
            codigo = $(this).attr("codigo");
            valor = $(this).attr("valor");

            if(acao == 'editar'){

                $.ajax({
                    url:"src/estoque/produtos_servicos_form.php",
                    type:"POST",
                    data:{
                        codigo,
                        fornecedor:'<?=$_POST['fornecedor']?>'
                    },
                    success:function(dados){
                        $(".LateralDireita").html(dados);
                    }
                })

            }else if(acao == 'excluir'){

                $.confirm({
                    content:"Deseja realmente excluir o registro do produto/serviço?",
                    title:"Excluir Registro",
                    type:'red',
                    buttons:{
                        'sim':{
                            text:'Sim',
                            btnClass:'btn btn-danger',
                            action:function(){

                                $.ajax({
                                    url:"src/estoque/produtos_servicos.php",
                                    type:"POST",
                                    data:{
                                        acao,
                                        codigo,
                                        fornecedor:'<?=$_POST['fornecedor']?>'
                                    },
                                    success:function(dados){
                                        $(".LateralDireita").html(dados);
                                    }
                                })

                            }
                        },
                        'nao':{
                            text:'Não',
                            btnClass:'btn btn-success',
                            action:function(){

                            }
                        }
                    }
                })

            }else if(acao == 'adicionar'){
                $.ajax({
                    url:"src/estoque/<?=(($_POST['fornecedor'])?'lancamentos_form.php':'saidas_form.php')?>",
                    type:"POST",
                    data:{
                        acao:'adicionar_produto',
                        codigo,
                        cod:'<?=$_SESSION['cod_lancamento']?>',
                        valor,
                        fornecedor:'<?=$_POST['fornecedor']?>'
                    },
                    success:function(dados){
                        $(".LateralDireita").html('');
                        $("#paginaHome").html(dados);
                        let myOffCanvas = document.getElementById('offcanvasDireita');
                        let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                        openedCanvas.hide();
                    }
                })
            }

        })

    })
</script>