<?php
include "../lib/includes.php";

$servico_tipo = $_GET['servico_tipo'];

$query = "SELECT * FROM local_fontes WHERE servico_tipo = '{$servico_tipo}'";
$result = mysql_query($query);

if (mysql_num_rows($result)) :
    echo '<option value="">Geral</option>';
    while ($d = mysql_fetch_object($result)) : ?>
        <option value="<?= $d->codigo; ?>"><?= $d->descricao; ?></option>
<?php endwhile;

else :
    echo '<option value=""></option>';
endif;
?>