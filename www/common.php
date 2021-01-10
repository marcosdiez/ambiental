<?php


$qr_code_size=100; // mm
$font_size=15;     // mm?
$space_between_qr_and_link = 5; // mm
$base_url = "http://ambiental.webdanfe.com.br/";


$project_folder = "projetos" . DIRECTORY_SEPARATOR;
$extension = ".xlsx";
$error_project_does_not_exist = "Erro, o projeto [%s] não existe";
$invalid_file_content = "Error lendo o arquivo [%s]";
$id_not_found_in_project = "Erro: id [%s] não encontrado em [%s]";
$project_has_no_data = "Erro. Nenhum PDF foi gerado pois a planilha de excel não contém dados.";

function title_to_dict($row){
    $counter = 0;
    $result = [];
    foreach( $row as $cell ){
        $result[$cell] = $counter++;
    }
    return $result;
}

function get_target_file_or_die($project){
    global $project_folder;
    global $extension;
    global $error_project_does_not_exist;
    $target_file = $project_folder . $project . $extension;
    if(file_exists($target_file)){
        return $target_file;
    }
    die(sprintf($error_project_does_not_exist, $project_folder));
}

function get_xlsx($project, $id){
    global $id_not_found_in_project;
    $target_file = get_target_file_or_die($project);
    $xlsx = SimpleXLSX::parse($target_file);
    if(!$xlsx){
        echo SimpleXLSX::parseError();
        die(sprintf($invalid_file_content, $target_file));
    }
    return $xlsx;
}