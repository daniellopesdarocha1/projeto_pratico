var app = angular.module('app', ['ui.mask','angular-loading-bar']);

app.controller('painelInicialController', function($scope, $http){
    $scope.showCadastro = false;
    $scope.noticia = objNoticia();
    $scope.allNoticias = {};
    
    $scope.abreCadastroNoticia = function(){
        $scope.noticia = objNoticia();
        $scope.showCadastro = true;
    }
    
    $scope.listarNoticias = function(){
        $http.get('../api/listarNoticias')
            .success(function(data){
                $scope.allNoticias = data.noticias;
            })
            .error(function(){
                alert("Falha em obter notícias");
            });
    };

    $scope.getNoticia = function(idnoticia) {
        $http.get('../api/getnoticia/'+idnoticia)
            .success(function(data){
                
                $scope.noticia = data.noticia;
                $scope.showCadastro = true;

            })
            .error(function(){
                alert("Falha em obter notícias");
            });
    }
    
    $scope.processaFormNoticia = function() {
        if ($scope.noticia.idnoticia === -1) {
            $scope.cadastrarNovaNoticia();
        } else {
            $scope.alterarNoticia();
        }
    };

    $scope.cadastrarNovaNoticia = function(){
        $http
            .post('../api/cadastrarNovaNoticia', $scope.noticia)
            .success(function(data){
                
                if(!data.erro) {
                    // deu certo o cadastro
                    
                    $.gritter.add({
                        title : "Sucesso!",
                        text : "Notícia cadastrada com sucesso!",
                        class_name : "gritter"
                    });
                    
                    $scope.showCadastro = false;
                    $scope.noticia = objNoticia();
                    $scope.listarNoticias();
                } else {
                    $.gritter.add({
                        title : "Falha!",
                        text : "Ocorreu um erro!",
                        class_name : "gritter"
                    }); 
                }
            
            })
            .error(function(){
                alert("Falha geral da aplicação!");
            });
    };

    $scope.alterarNoticia = function(){
        $http
            .post('../api/alterarNoticia/'+$scope.noticia.idnoticia, $scope.noticia)
            .success(function(data){
                
                if(!data.erro) {
                    // deu certo a alteração
                    
                    $.gritter.add({
                        title : "Sucesso!",
                        text : "Notícia alterada com sucesso!",
                        class_name : "gritter"
                    });
                    
                    $scope.showCadastro = false;
                    $scope.noticia = objNoticia();
                    $scope.listarNoticias();
                } else {
                    $.gritter.add({
                        title : "Falha!",
                        text : "Ocorreu um erro!",
                        class_name : "gritter"
                    }); 
                }
            
            })
            .error(function(){
                alert("Falha geral da aplicação!");
            });
    };

    
    $scope.listarNoticias();
    
});

function objNoticia(){
    return {
        idnoticia : -1, //Regras: -1 é para cadastrar, diferente de -1 é para alterar
        noticiatitulo : "",
        noticiadescricao : "",
        noticiatexto : "",
        noticiadata : ""
    };
}
