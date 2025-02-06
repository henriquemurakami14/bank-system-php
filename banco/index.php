<?php
    $cpf = "09215320911";
    readline_int($cpf);

    function readline_int(&$variavel){
        $validado = false;
        do {
            if (filter_var($variavel, FILTER_VALIDATE_INT) != false) {
                return (int)$variavel;
                $validado = true;
            
            }elseif($variavel == "0"){
                return (int)0;
                $validado = true;
            }else {
                $variavel = readline("Erro, digite um número inteiro! Digite novamente: ");
            }
        } while (!$validado);
    }
?>