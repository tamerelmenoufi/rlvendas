<?php

    include("../../../lib/includes.php");

    $mes = (($_GET['mes'])?:date("m"));
    $ano = (($_GET['ano'])?:date("Y"));

?>
<style>
    .CaixaValor{
        background-color:blue;
        color:#fff;
        font-weight:bold;
        font-size:11px;
        padding:5px;
        border-radius:3px;
        cursor:pointer;
    }
</style>
<div class="row">
    <div class="col">


    <div id="RelatorioCalendario">
  <table class='table' cellpadding="5" cellspacing="0" border="0" align="center">
      <tr>
          <td colspan="7" align="left" class="titulo">
        Fechamento de caixa em <select id="OpMes">
            <option value="">M&eacute;s</option>
            <?php
                for($i=1;$i<=12;$i++){
            ?>
                <option value="<?=$i?>" <?=(($i == $mes) ? 'selected' : false)?>><?=$i?></option>
            <?php
                }
            ?>
        </select>
        /
        <select id="OpAno">
            <option value="">Ano</option>
            <?php
			for($i = date(Y); $i > date(Y)-4; $i--){
			?>
            <option value="<?=$i?>" <?=(($i == $ano) ? 'selected' : false)?>><?=$i?></option>
            <?php
			}
			?>
        </select>
        </td>
      </tr>
<?php

       for($w=$mes;$w<=$mes;$w++){
		   set_time_limit(90);
			 $w = (($w*1 < 10)?'0'.$w*1:$w);
			 $d1 = mktime(0,0,0, $w, 1, $ano); //verifica o primeiro dia do mes
			 $diaSem = date('w',$d1); //verifica a quantidade de dias da semana para o primeiro dia do mes

?>

			  <tr align='center' class='dias_semana'>
   				  <td class='lista_titulo' width="14%">Domingo<td class='lista_titulo' width="14%">Segunda<td class='lista_titulo' width="14%">Ter&ccedil;a<td class='lista_titulo' width="14%">Quarta<td class='lista_titulo' width="14%">Quinta<td class='lista_titulo' width="14%">Sexta<td class='lista_titulo' width="14%">S&aacute;bado</td>
              </tr>

			  <tr>

			<!--Coloca os dias em Branco-->
			<?php
                        for ($i = 0; $i < $diaSem; $i++) {
                        echo "<td geral>&nbsp;";
                        }

                    //Enquanto houver dias

                        for ($i = 2; $i < 33; $i++) {
							$linha = date('d',$d1);


                    //verifica o dia atual

					    if(date(Y) == $ano and date(m) == $w and date(d) == $linha){
                           $hoje = ' (HOJE)';
                        }else{
                           $hoje = false;
                        }


						echo "<td geral class='lista_agenda' valign='top' cel>";

                        $ini = "%".date("Y-m-d H:i:s", mktime(15, 59, 59, $mes, $dia, $ano))."%";
                        $fim = "%".date("Y-m-d H:i:s", mktime(9, 59, 59, $mes, ($dia+1), $ano))."%";

                        echo $q = "select
                                    (select sum(total) from vendas where data_finalizacao like '%{$ano}-{$w}-{$linha}%' and situacao = 'pago')  as total,
                                    (select sum(total) from vendas where (data_finalizacao between '%{$ano}-{$w}-{$linha} 09:59:59%' and '%{$ano}-{$w}-{$linha}  15:59:59%') and situacao = 'pago')  as turno1,
                                    (select sum(total) from vendas where (data_finalizacao between '{$ini}' and '{$fim}') and situacao = 'pago')  as turno2
                            ";
                        $r = mysqli_query($con, $q);
                        $d = mysqli_fetch_object($r);
                        echo "$linha ".$hoje;
						if($d->total > 0){
                            echo '<div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-group-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    R$ '.number_format($d->total,2,",",".").'
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item" href="#">R$ '.number_format($d->total,2,",",".").'</a>
                                        <a class="dropdown-item" href="#">R$ '.number_format($d->total,2,",",".").'</a>
                                    </div>
                                </div>';
                        }
                        echo "</td>";
					    // Se Sábado desce uma linha
                        if (date('w',$d1) == 6) {
                            echo "<tr>\n";
                        }
                        $d1 = mktime(0,0,0, $w, $i, $ano);
                        if (date('d',$d1) == "01") { break; }
                   }
          ?>
    		</tr>
	  </table>
<?php
        }
?>
</div>



    </div>
</div>

<script>
    $(function(){


        $('#OpMes, #OpAno').change(function () {

            var url = 'vendas/caixa/index.php';
            var mes = $("#OpMes").val();
            var ano = $("#OpAno").val();

            $('.loading').fadeIn(200);

            $.ajax({
                url,
                data:{
                    mes,
                    ano
                },
                success: function (data) {
                    $('#palco').html(data);
                }
            })
            .done(function () {
                $('.loading').fadeOut(200);
            })
            .fail(function (error) {
                alert('Error');
                $('.loading').fadeOut(200);
            })
        });


    })
</script>