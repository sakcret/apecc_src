<br>
<center align="center" class="titulo"><h3 style=" margin-top: 4px !important"><?php echo $titulo_rep;                ?></h3></center>
<?php
if ($numero_equiposxsala->num_rows() > 0) {
    $cuantosxsala = array();
    foreach ($numero_equiposxsala->result_array() as $eq => $val) {
        $cuantosxsala[$val['sala']] = $val['cuantos'];
    }
    unset($numero_equiposxsala);
    $data = array();
    foreach ($datos_sw->result_array() as $k => $v) {
        $data[$v['Sala']][$v['sistemaOperativo']][$v['software']] = array('ns' => $v['numeroSerie'], 'c' => $v['cuantos']);
    }
    unset($datos_sw);
    foreach ($data as $sala => $sistemasop) {
        if ($sala == '') {
            echo '<h2>Software en equipos de almac&eacute;n</h2>';
        } else {
            echo "<h2>Software en la sala $sala</h2>";
        }
        ?>

        <?php foreach ($sistemasop as $nombreso => $software) {
            ?>
            <h4>Sistema Operativo: <?php echo $nombreso; ?></h4>
            <table classs="software" width="100%" class="borde">
                <thead>
                    <tr>
                        <th class="borde" width="35%">Software</th>
                        <th  class="borde" width="65%">Disponibilidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php ?>
                    <?php foreach ($software as $nobresw => $equipos) {
                        ?>
                        <tr>
                            <td class="borde"><?php echo $nobresw; ?></td>
                            <td class="borde">
                                <?php
                                if (array_key_exists($sala, $cuantosxsala)) {
                                    if ($cuantosxsala[$sala] == $equipos['c']) {
                                        echo 'Todos los Equipos';
                                    } else {
                                        echo $equipos['ns'];
                                    }
                                } else {
                                    echo $equipos['ns'];
                                }
                                ?>
                            </td>

                        </tr>
                    </tbody>
                <?php }
                ?> 
            </thead>
            </table>
            <?php
        }
    }
} else {
    echo '<h3 class=" round_div"><img src="./images/warning.png" alt=" ">&nbsp;No se encontraron resultados</h3>';
}
?>

<style>

    .titulo {
        width: 100%;
        height: 31px;
        color:white;  font-size: 13px;
        text-align: center !important;
        font-weight:  bold !important;
        background-image: url('./images/titulo_barra.png');
        background-position: center;
        background-repeat: no-repeat;

    }

    .shadow{
        color:#000000; 
        background-color:#E6E6E6; 
    }
    .centratexto{
        text-align: center;
    }
    table thead tr th,table tfoot tr th{
        background-color:#EBEBEB;
    }  
    .borde {
        background-color: white;
        border-collapse: collapse;
        border: 1px solid #666666 !important;        
        text-align:left;

    }  
    .round_div{
        background-color: white;
        border-collapse: collapse;
        border: 1px solid #666666 !important;     
        border-style: dotted;
        border-radius: 10px;
        text-align:center;
    }
    .detalle{
        font-size: 9px;
    }
    h1,h2,h3{
        margin: 0px !important;
        padding: 0px !important;
    }
    td{
        background-color: white !important;
        padding: 2px;
    }
    th{
        padding: 2px;
    }
</style>