<br>
<center align="center" class="titulo"><h3><?php echo $titulo_rep; ?></h3></center>
<?php
$tmpl = array(
    'table_open' => '<table border="1" style=" width: 100%" class="table">',
    'heading_row_start' => '<tr>',
    'heading_row_end' => '</tr>',
    'heading_cell_start' => '<th>',
    'heading_cell_end' => '</th>',
    'row_start' => '<tr>',
    'row_end' => '</tr>',
    'cell_start' => '<td>',
    'cell_end' => '</td>',
    'row_alt_start' => '<tr>',
    'row_alt_end' => '</tr>',
    'cell_alt_start' => '<td>',
    'cell_alt_end' => '</td>',
    'table_close' => '</table>'
);
$this->table->set_template($tmpl);
$this->table->set_heading('Actividad', 'Tipo de <br>Actividad', 'Encargado', 'Hora de<br> inicio', 'Hora de<br> fin','D&iacute;a','Sala','Bloque<br>/Secci&oacute;n','Fecha<br> Inicio','Fecha<br> Fin','Login');
echo $this->table->generate($datos_rsf);
if ($datos_rsf->num_rows() == 0) {
    echo '<h3 class=" round_div"><img src="./images/warning.png" alt=" ">&nbsp;No se encontraron resultados</h3>';
} else {
    echo '<br> <img style=" width: 100%" src="./images/barra.png"><center align="center" style="text-align: center !important;"> <b>Resultado: ' . $datos_rsf->num_rows() . ' Reservaciones.</b></center> <img style=" width: 100%" src="./images/barra.png"><br>';
}

    ?>

<style>
    h3{
        margin-top: 10px;
    }
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
    .table {
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
</style>