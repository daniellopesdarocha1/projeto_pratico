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

$app->post('/alterarNoticia/:idnoticia', 'auth', function ($idnoticia) use ($app, $db) {
        
        $data = json_decode($app->request()->getBody());
    
        $idnoticia = (int)$idnoticia;
    
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
        
        $consulta = $db->con()->prepare('UPDATE noticia 
                                        SET 
                                            noticiatitulo = :NOTICIATITULO, 
                                            noticiadescricao = :NOTICIADESCRICAO, 
                                            noticiatexto = :NOTICIATEXTO, 
                                            noticiadata = :NOTICIADATA
                                        WHERE 
                                            idnoticia = :IDNOTICIA');
    
        $consulta->bindParam(':NOTICIATITULO', $noticiatitulo);
        $consulta->bindParam(':NOTICIADESCRICAO', $noticiadescricao);
        $consulta->bindParam(':NOTICIATEXTO', $noticiatexto);
        $consulta->bindParam(':NOTICIADATA', $data);
        $consulta->bindParam(':IDNOTICIA', $idnoticia);
    
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

$app->get('/getnoticia/:idnoticia', 'auth', function ($idnoticia) use ($app, $db) {
        $idnoticia = (int)$idnoticia;
    
        $consulta = $db->con()->prepare("SELECT
                                            idnoticia,
                                            noticiatitulo,
                                            noticiadescricao,
                                            noticiatexto,
                                            DATE_FORMAT(noticiadata,'%d/%m/%Y') AS noticiadata
                                        FROM
                                            noticia
                                        WHERE
                                            idnoticia = :IDNOTICIA                                            
                                        ORDER BY
                                            noticiadata DESC,
                                            noticiatitulo ASC
                                        ");
        $consulta->bindParam(':IDNOTICIA', $idnoticia);
        $consulta->execute();
        $noticias = $consulta->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("noticia"=>$noticias[0]));
        
    }
);


// gerenciamento de imagens

$app->post('/cadastrarImagem/:idnoticia', 'auth', function ($idnoticia) use ($app, $db) {
        
        if ( !empty( $_FILES ) ) {
            $imagemtitulo = "";
            $imagemarquivo = $idnoticia."_".uniqid()."_".$_FILES[ 'file' ][ 'name' ];
            $idnoticia = (int)$idnoticia;
            
            $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
            $uploadPath = '../upload/'.$imagemarquivo;            
            move_uploaded_file( $tempPath, $uploadPath );
            
            $consulta = $db->con()->prepare('INSERT INTO imagem(imagemtitulo, imagemarquivo, noticia_idnoticia) VALUES (:IMAGEMTITULO, :IMAGEMARQUIVO, :IDNOTICIA)');
            $consulta->bindParam(':IMAGEMTITULO', $imagemtitulo);
            $consulta->bindParam(':IMAGEMARQUIVO', $imagemarquivo);
            $consulta->bindParam(':IDNOTICIA', $idnoticia);

            if($consulta->execute()){
                echo json_encode(array("erro"=>false));
            } else {
                echo json_encode(array("erro"=>true));
            }
            
            
        } else {
            echo json_encode(array("erro"=>true));
        }
        
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
