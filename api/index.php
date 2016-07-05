<?php

require 'Slim/Slim.php';
require 'Db.class.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$db = new Db;

session_start();

header("Content-Type: application/json");

$app->post(
    '/login',
    function () use ($app) {
        
        $data = json_decode($app->request()->getBody());
        $usuario = (isset($data->usuario)) ? $data->usuario : "";
        $senha   = (isset($data->senha)) ? $data->senha : "";
        
        if($usuario=="admin" && $senha=="123456"){
            
            $_SESSION['logado']=true;
            
            echo json_encode(array("logado"=>true));
        } else {
            echo json_encode(array("logado"=>false));   
        }
        
    }
);

$app->post('/cadastrarNovaNoticia', 'auth', function () use ($app, $db) {
        
        $data = json_decode($app->request()->getBody());
        $noticiatitulo = (isset($data->noticiatitulo)) ? $data->noticiatitulo : "";
        $noticiadescricao = (isset($data->noticiadescricao)) ? $data->noticiadescricao : "";
        $noticiadata = (isset($data->noticiadata)) ? $data->noticiadata : "";
        $noticiatexto = (isset($data->noticiatexto)) ? $data->noticiatexto : "";
        
        $data_tmp = explode('/',$noticiadata);
    
        if(checkdate($data_tmp[1], $data_tmp[0], $data_tmp[2])){
            $data = sprintf('%s-%s-%s', $data_tmp[2], $data_tmp[1], $data_tmp[0]);
        } else {
            $data = NULL; 
        }
        
        $consulta = $db->con()->prepare('INSERT INTO noticia(noticiatitulo, noticiadescricao, noticiatexto, noticiadata) VALUES (:NOTICIATITULO, :NOTICIADESCRICAO, :NOTICIATEXTO, :NOTICIADATA)');
        $consulta->bindParam(':NOTICIATITULO', $noticiatitulo);
        $consulta->bindParam(':NOTICIADESCRICAO', $noticiadescricao);
        $consulta->bindParam(':NOTICIATEXTO', $noticiatexto);
        $consulta->bindParam(':NOTICIADATA', $data);
    
        if($consulta->execute()){
            echo json_encode(array("erro"=>false));
        } else {
            echo json_encode(array("erro"=>true));
        }
        
    }
);

$app->get('/listarNoticias', 'auth', function () use ($app, $db) {
            
        $consulta = $db->con()->prepare("SELECT
                                            idnoticia,
                                            noticiatitulo,
                                            noticiadescricao,
                                            noticiatexto,
                                            DATE_FORMAT(noticiadata,'%d/%m/%Y') AS datanoticia
                                        FROM
                                            noticia
                                        ORDER BY
                                            noticiadata DESC,
                                            noticiatitulo ASC
                                        ");
        $consulta->execute();
        $noticias = $consulta->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("noticias"=>$noticias));
        
    }
);

function auth(){
    if(isset($_SESSION['logado'])){
        return true;
    } else {
        $app = \Slim\Slim::getInstance();
        echo json_encode(array("loginerror"=>true,"msg"=>"Acesso Negado"));
        $app->stop();
    }
}

$app->run();
