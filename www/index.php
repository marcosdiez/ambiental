<?php
if(sizeof($_GET) == 0){
    header("Location: http://www.floraliz.com.br");
    exit();
}
header('Content-Type: text/html; charset=utf-8');
?>
<html>
<head>
<title>Controle Ambiental</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
a:link {
  text-decoration: none;
}
</style>
</head>
<body>
<?php
// printf("<pre>");
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR .'common.php';



function print_row($headers, $row){
    printf("Informações de Reflorestamento\n<hr><style>    a:link {        ext-decoration: none;      }    </style>");
    $special_headers = [ "link", "longitude", "latitude" ];
    $forma = "<li><b>%s</b>: %s\n";
    foreach( $headers as $header => $blah ){
        if(!in_array($header, $special_headers)){
            printf($forma, $header, $row[$headers[$header]]);
        }
    }
    printf($forma, "link", sprintf("<a href='%s' target='_blank'>%s</a>", $row[$headers["link"]], $row[$headers["link"]]));
    // $pos = str_replace($row[$headers["latitude"]], ",", ".") . "," . str_replace($row[$headers["longitude"]], ",", ".");
    $pos = $row[$headers["latitude"]] . "," . $row[$headers["longitude"]];
    printf($forma, "localização", sprintf("<a href='http://maps.google.com/?q=%s'>%s</a>", $pos, $pos));
    // http://maps.google.com/?q=52.77146932915935%2c-1.6809082031249951
    // printf("\nfim\n");
    printf("<hr>");
    printf("Serviço fornecido pela <a href='http://www.floraliz.com.br/'>Floraliz</a>");
}

function show_data($project, $id){
    $xlsx = get_xlsx($project);
    $first = true;
    foreach( $xlsx->rows() as $row ) {
        if($first){
            $first=false;
            $headers = title_to_dict($row);
            // print_r($headers);
        }else{
            if($row[$headers["id"]] == $id){
                print_row($headers, $row);
                return;
            }
        }
    }
    printf($id_not_found_in_project, $id, $project);
}

if(isset($_GET["q"])){
    $q = explode("/", $_GET["q"]);
    show_data($q[0], $q[1]);
    exit();
}
show_data(@$_GET["projeto"], @$_GET["id"]);
