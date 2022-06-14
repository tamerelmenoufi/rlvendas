<?php

    include("../../../lib/includes.php");

    $mes = (($_GET['mes'])?:date("m"));
    $ano = (($_GET['ano'])?:date("Y"));

?>

<div class="row">
    <div class="col">


    <div id="RelatorioCalendario">
  <table class='table' cellpadding="5" cellspacing="0" border="0" align="center">
      <tr>
          <td colspan="7" align="left" class="titulo">
        Registro de envios em <select id="OpMes">
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


                        echo "$linha ".$hoje;

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