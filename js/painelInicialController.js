var app = angular.module('app', ['ui.mask','angular-loading-bar', 'socket-io']);

app.controller('painelInicialController', function($scope, $http, socket){
    
    /* chat */
    
    $scope.senha = '123456';
    
    // indica que o chat está online ou offline
    $scope.chat = false;
    
    $scope.chatUsuarios = [];
    
    $scope.novaMensagem = '';
    
    $scope.usuarioAtivo = 0;
    
    socket.emit('adminlogin', $scope.senha);
    
    $scope.chatStatus = function(){
        socket.emit('setChatStatus', $scope.senha);
    };
    
    socket.on('chatstatus', function(data){
        $scope.chat = data.online;
    });
    
    socket.on('usuarioentrou', function(email){
        $scope.chatUsuarios.push({ usuario : email, mensagens : []});
        
        if($scope.chatUsuarios.length==1){
            $scope.usuarioAtivo = 0;
        }
    });
    
    socket.on('usuariosaiu', function(usuario){
        var ind = $scope.buscaUsuario(usuario);
        
        console.log('Usuário saiu');
        console.log($scope.chatUsuarios[ind]);
        
        $.gritter.add({
                        title : "Usuário saiu",
                        text : $scope.chatUsuarios[ind].usuario+" saiu",
                        class_name : "gritter"
                    });
        
        $scope.chatUsuarios.splice(ind, 1);
        $scope.usuarioAtivo = 0;
    });
    
    socket.on('novamensagemparaadmin', function(mensagem){
        var ind = $scope.buscaUsuario(mensagem.de); 
        $scope.chatUsuarios[ind].mensagens.push(
                            { de:mensagem.de, msg:mensagem.msg }
                            );
    });
    
    $scope.buscaUsuario = function(usuario){
        var status = false;
        var cont = 0;
        while(cont < $scope.chatUsuarios.length){
            if($scope.chatUsuarios[cont].usuario==usuario){
                return cont;   
            }
            cont++;
        }
        
        return false;
    }
    
    $scope.enviarMensagem = function(){
        
        $scope.chatUsuarios[$scope.usuarioAtivo].mensagens.push(
            { de:'Admin', msg : $scope.novaMensagem }
        );
        
        socket.emit('enviarmensagemparausuario', 
                    { para :                      $scope.chatUsuarios[$scope.usuarioAtivo].usuario, 
                     msg : $scope.novaMensagem });
        
        $scope.novaMensagem = '';
        $scope.scrollDown();
    }
    
    $scope.setaUsuarioAtivo = function(ind){
        $scope.usuarioAtivo = ind;   
    }
    
    $scope.scrollDown = function(){
        setTimeout(function(){
            $("#mostra_mensagens").scrollTop(1E10);
        }, 800);
    }
    
    /* fim chat */
    
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
    
    $scope.getNoticia = function(idnoticia){
        $http.get('../api/getnoticia/'+idnoticia)
            .success(function(data){
                
                $scope.noticia = data.noticia;
                $scope.showCadastro = true;
            
            })
            .error(function(){
                alert("Falha em obter notícia");
            });
    };
    
    $scope.trocaStatus = function(noticia, novostatus){
        $http.get('../api/trocastatus/'+noticia.idnoticia+"/"+novostatus)
            .success(function(data){
                noticia.noticiastatus = novostatus;        
            })
            .error(function(){
                alert("Falha trocar o status");
            });
    };
    
    $scope.excluirNoticia = function(idnoticia){
        
        if(!confirm("Deseja realmente excluir?")) return false;
        
        $http.get('../api/excluirNoticia/'+idnoticia)
            .success(function(data){
                $scope.listarNoticias(); 
                
                $.gritter.add({
                        title : "Sucesso!",
                        text : "Notícia excluída com sucesso!",
                        class_name : "gritter"
                    });
            
            })
            .error(function(){
                alert("Falha em excluir notícia");
            });
    };
    
    $scope.processaFormNoticia = function(){
        if($scope.noticia.idnoticia===-1){
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
        idnoticia : -1,
        noticiatitulo : "",
        noticiadescricao : "",
        noticiatexto : "",
        noticiadata : ""
    };
}