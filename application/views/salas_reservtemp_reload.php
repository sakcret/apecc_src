<?php

function getv_key($s, $array) {
    reset($array);
    $key = 0;
    for ($x = 0; $x < count($array); $x++) {
        if (($array[$x]['NumeroSerie'] == $s)) {
            $key = $x;
        } else {
            $key = false;
        }
    }
    return $key;
}

$tmp_row = $tmp_col = $tmp_sal = 0;
$es = $equipos_salas->result_array();
$index_rt = 0;

function get_key_array($array, $f, $c, $s) {
    $newa = array();
    for ($x = 0; $x < count($array); $x++) {
        if (($array[$x]['idSala'] == $s) && ($array[$x]['Fila'] == $f) && ($array[$x]['Columna'] == $c)) {
            $newa = $array[$x];
        }
    }
    return $newa;
}

$s = $salas->result_array();
$numreg = $salas->num_rows();
$porcentaje = (100 / $numreg) - 1;
echo ' <div id="tabs">' . PHP_EOL . '<ul>' . PHP_EOL;
for ($is = 0; $is < $numreg; $is++) {
    echo '<li style="width:' . $porcentaje . '%"><a style=" width: 90%"  id="tabf_' . $s[$is]["idSala"] . '" onclick="sala_actual(' . $s[$is]["idSala"] . ')" href="#tabs-' . $s[$is]["idSala"] . '">Sala ' . $s[$is]["Sala"] . '</a></li>' . PHP_EOL;
}
echo '</ul>' . PHP_EOL;
for ($i = 0; $i < $numreg; $i++) {
    $tmp_sal = $s[$i]["idSala"];
    if ($i == 0) {
        $sala_actual_1 = $tmp_sal;
    }
    $num_eq = 0;
    ?>
    <script type="text/javascript">
        $(function() {
            $('#<?php echo $s[$i]["idSala"]; ?> td').click(function() {
                var tr = $(this).parent();
                for (var i = 0; i < tr.children().length; i++) {
                    if (tr.children().get(i) == this) {
                        var column = i;
                        break;
                    }
                }
                var tbody = tr.parent();
                for (var j = 0; j < tbody.children().length; j++) {
                    if (tbody.children().get(j) == tr.get(0)) {
                        var row = j+1;
                        break;
                    }
                }
                $('#<?php echo $s[$i]["idSala"]; ?>info').text('Fila: '+row + ', Columna: ' + column+' en la sala: '+<?php echo $s[$i]["idSala"]; ?>);
            });
        });
    </script>         
    <div id="tabs-<?php echo $s[$i]["idSala"]; ?>">
        <br>
        <div style="width:900px;"><center>
                <div class="ui-grid ui-widget ui-widget-content ui-corner-all" style="width:100%;">
                    <div class="ui-grid-header ui-widget-header ui-corner-top" style="width:98.6%;">SALA: <?php echo $s[$i]["Sala"] . ' ' . $s[$i]["Comentario"]; ?></div>
                    <table id="<?php echo $s[$i]["idSala"]; ?>" class="over ui-grid-content ui-widget-content" style="width:100%;">
                        <thead>
                            <tr>
                                <th><img src="./images/borrar_todo.png" onclick="libera_sala('<?php echo $s[$i]["idSala"]; ?>')" title="Liberar sala de Fijas"></th>
                                <?php
                                $index_col = 'A';
                                for ($j = 0; $j < $s[$i]["Columnas"]; $j++) {
                                    echo '<th height=30 class="ui-state-default" style="font-size: 20px;">' . $index_col . '</th>' . PHP_EOL;
                                    $index_col++;
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index_row = $s[$i]["Indice"];
                            for ($fi = 0; $fi < $s[$i]["Filas"]; $fi++) {
                                $tmp_row = $fi + 1;
                                echo '<tr>' . PHP_EOL;
                                echo '<th width=30 class="ui-state-default" style="font-size: 20px;">' . $index_row . '</th>' . PHP_EOL;
                                for ($co = 0; $co < $s[$i]["Columnas"]; $co++) {
                                    $hi = $hf = $lo = $horas = $importe = $estado_reserv = '';
                                    $id = '0';
                                    $tmp_col = $co + 1;
                                    $temp = array();
                                    $temp = get_key_array($es, $tmp_row, $tmp_col, $tmp_sal);
                                    $valores = array_values($temp);
                                    (array_key_exists(0, $valores)) ? $numeroSerie = $valores[0] : $numeroSerie = '';
                                    (array_key_exists(4, $valores)) ? $estado = $valores[4] : $estado = '';
                                    if ((count($reserv_temp) > 0) && ($numeroSerie != '')) {
                                        for ($k = 0; $k < count($reserv_temp); $k++) {
                                            if ($reserv_temp[$k]['NumeroSerie'] == $numeroSerie) {
                                                $id = $reserv_temp[$k]['idReservacionesMomentaneas'];
                                                $hi = $reserv_temp[$k]['HoraInicio'];
                                                $hf = $reserv_temp[$k]['HoraFin'];
                                                $importe = $reserv_temp[$k]['Importe'];
                                                $horas = $reserv_temp[$k]['Horas'];
                                                $lo = $reserv_temp[$k]['Login'];
                                                $estado_reserv = $reserv_temp[$k]['Estado']; //obtengo el estado de la reservacion ya sea A=activo o I=indeterminada
                                                $importe = $reserv_temp[$k]['Importe'];
                                                $horas = $reserv_temp[$k]['Horas'];
                                            }
                                        }
                                    }
                                    echo '<td width="130" height="120" class="ui-state-focus">';
                                    if ($estado != '') {
                                        echo '<div align="center" class="label_numser">' . $numeroSerie . '</div>
                                                <div align="center" onclick="asigna_equipo(\'' . $numeroSerie . '\',$(\'#' . $numeroSerie . '_img\'),$(this))" id="' . $numeroSerie . '" edo="' . $estado . '" imp="' . $importe . '" hrs="' . $horas . '" idreserv="' . $id . '" horai="' . $hi . '" estado_reserv="' . $estado_reserv . '" horaf="' . $hf . '" usuario="' . $lo . '">' .
                                        '<img style="width: 100px !important;" id="' . $numeroSerie . '_img" src="./images/pc_edos/pc_' . $estado . '.png"/>
                                                </div><div class="ui-corner-all boxshadowround ui-widget-content" align="center">
                                                <img class="sos-pop" title="Sistemas Operativos" onclick="ver_sos(\'' . $numeroSerie . '\')" src="./images/pc_edos/pc_sos.png">
                                                
                                                <div class="ui-widget-content popup-ui" id="pop_sw" aria-label="Login options">
                                                <div align="left" id="pop_so_' . $numeroSerie . '"></div>
                                                </div>
                                                
                                                <img title="Software" onclick="ver_software(\'' . $numeroSerie . '\')" src="./images/pc_edos/pc_sw.png">
                                                <div class="ui-widget-content popup-ui" id="pop_sw" aria-label="Login options">
                                                <div align="left" id="pop_sw_' . $numeroSerie . '"></div>
                                                </div>
                                                
                                                <img class="sos-pop" title="Detalles del equipo" onclick="ver_detalles(\'' . $numeroSerie . '\')" src="./images/pc_edos/pc_dt.png">
                                                <div class="ui-widget-content popup-ui" id="pop_dt" aria-label="Login options">
                                                <div align="left" id="pop_dt_' . $numeroSerie . '"></div>
                                                </div>
                                                </div>';
                                        $num_eq++;
                                    }
                                    echo '</td>' . PHP_EOL;
                                }
                                echo '</tr>' . PHP_EOL;
                                $index_row--;
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="ui-grid-footer ui-widget-header ui-corner-bottom ui-helper-clearfix">
                        Has seleccionado el equipo localizado en: <span id="<?php echo $s[$i]["idSala"]; ?>info">--------</span>&nbsp;<span class="total-eq"><?php echo $num_eq; ?> Equipos</span>
                    </div>
                </div> <!--fin del grid-->
            </center>
        </div> 
        <br>
    </div><!--Fin una tab-->
    <?php
}
echo '</div><!--Fin del tabs-->';
?>
    <script type="text/javascript">
        var sala_select=<?php echo $sala_actual_1; ?>;
    </script>
    