<?php
$departamentos = json_decode(file_get_contents(resource_path('data/departamentos.json')), true);
$municipios = json_decode(file_get_contents(resource_path('data/municipios.json')), true);

/*Inicializar las opciones y almacenar los departamentos y municipios en opciones y arreglos*/
$departamentoOpciones = '';
foreach ($departamentos['departamentos'] as $key => $value) {
    $departamentoOpciones .= "<option value=\"$key\">$value</option>";
}

$municipioOpciones = '<option value="">Seleccione un municipio</option>';
foreach ($municipios['municipios'] as $departamento => $mun) {
    foreach ($mun as $key => $value) {
        $municipioOpciones .= "<option class=\"municipio-option $departamento\" data-departamento=\"$departamento\" value=\"$key\">$value</option>";
    }
}
dd($departamentoOpciones . $municipioOpciones);
?>
<div>

</div>
