var app = angular.module('app', ['angular-loading-bar', 'angularFileUpload']);

app.controller('gerenciarImagensController', function($scope, $http, $location, FileUploader){
    
    $scope.noticia = {};

    console.log($location.search().idnoticia);
    
    $scope.getNoticia = function(idnoticia) {
        $http.get('../api/getnoticia/'+idnoticia)
            .success(function(data){
                
                $scope.noticia = data.noticia;
                $scope.showCadastro = true;

            })
            .error(function(){
                alert("Falha em obter notícias");
            });
    };

    $scope.getNoticia($location.search().idnoticia);





    var uploader = $scope.uploader = new FileUploader(
        {
            url : '../api/cadastrarImagem/'+$location.search().idnoticia
        }
    );

    uploader.filters.push(
        {
            name : "tamanhoFila",
            fn : function(item, options){
                return this.queue.length < 4; 
                // se o tamanho da fila for menor do que 4 arquivos ele retorna true. (restrige a qtd de uploads)
            }
        }
    );

    uploader.onSuccessItem = function(fileItem){
        console.log("Item enviado com sucesso!");
        fileItem.remove(); //limpa a fila
    };

    uploader.onWhenAddingFileFailed = function (fileItem){
        console.log("Erro ao adicionar elemento ");
    };


});

app.config(function($locationProvider){
    $locationProvider.html5Mode({
        enabled : true,
        requireBase : false
    });
});
