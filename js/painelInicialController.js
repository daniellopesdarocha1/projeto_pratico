var app = angular.module('app', ['ui.mask','angular-loading-bar']);

app.controller('painelInicialController', function($scope, $http){
    $scope.showCadastro = false;
    $scope.noticia = objNoticia();
    $scope.allNoticias = {};
    
    $scope.abreCadastroNoticia = function(){
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
    
    $scope.listarNoticias();
    
});

function objNoticia(){
    return {
        idnoticia : -1,
        noticiatitulo : "",
        noticiadescricao : "",
        noticiatexto : "",
        noticiadata : ""
    };
}