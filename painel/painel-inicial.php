<?php
    session_start();
    if(!isset($_SESSION['logado'])){
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Painel Administrativo - Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/estilo.css">
        
    </head>
    <body ng-app="app">

		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="#">
		        	Painel de Notícias
		      </a>
		    </div>
		  </div>
		</nav>
        
        <div ng-controller="painelInicialController">
        
        	<div class="container">
        		<div class="row">
        			<div class="col-xs-12">
        				<div class="well well-sm">
        					<button 
        							type="button" 
        							class="btn btn-success" 
        							ng-click="abreCadastroNoticia()">
        						Cadastrar Notícia
        					</button>
        				</div>
        			</div>
        		</div>
        	</div>
            
            <!--form cadastro noticias-->
            <div class="container" ng-show="showCadastro">
            	<form ng-submit="cadastrarNovaNoticia()">
            		
            	<div class="row mbottom">
            		<div class="col-xs-3 text-right">
            			Titulo: 
            		</div>
            		<div class="col-xs-9">
            			<input
            					type="text" 
            					class="form-control" 
            					ng-model="noticia.noticiatitulo" 
            					required> 
            		</div>
            	</div>
            	<div class="row mbottom">
            		<div class="col-xs-3 text-right">
            			Descricão: 
            		</div>
            		<div class="col-xs-9">
            			<input 
            					type="text" 
            					class="form-control" 
            					ng-model="noticia.noticiadescricao"> 
            		</div>
            	</div>
            	<div class="row mbottom">
            		<div class="col-xs-3 text-right">
            			Data: 
            		</div>
            		<div class="col-xs-9">
            			<input  
            					class="form-control" 
            					ng-model="noticia.noticiadata" 
            					ui-mask="99/99/9999"
            					model-view-value="true"> <!--model-view-value="true" envia as barras para o banco-->
            		</div>
            	</div> 
            	<div class="row mbottom">
            		<div class="col-xs-3 text-right">
            			Texto: 
            		</div>
            		<div class="col-xs-9">
            			<textarea 
            					class="form-control"
            					ng-model="noticia.noticiatexto"
            					rows="5">
            			</textarea>
            		</div>
            	</div>    
            	<div class="row mbottom">
            		<div class="col-xs-9 col-xs-offset-3">
            			<button 
            					class="btn btn-primary" 
            					type="submit">
            				Cadastrar
            			</button>
	    			    <button 
								type="button" 
								class="btn btn-danger" 
								ng-click="fechaCadastroNoticia()">
							Cancelar
						</button>
            		</div>
            	</div>          	

            	</form>     	
            </div>
            <!--/form cadastro noticias-->
            
        </div>
    
        <script src="../js/angular/angular.min.js"></script>
        <script src="../js/angular/ui-utils.min.js"></script>
        <script src="../js/painelInicialController.js"></script>
    </body>
</html>