<?php  
    $cliente = [];
    $contas = 0; 
    
    do {
        $criar_conta = readline("[1] Criar Conta   [2] Remover Conta    [3] Depositar   Selecione a opção: ");
    
        if ($criar_conta == "1" || $criar_conta == "2" || $criar_conta == "3") {
            $validado = true;
        } else {
            $validado = false;
            echo "Opção inválida. Tente novamente.\n";
        }
    } while (!$validado);
    

    if ($criar_conta == "1") {
        cadastro($cliente, $contas);
        clientes($cliente, $contas);
    }elseif($criar_conta == "2") {
        remover_conta();
    }elseif($criar_conta == "3") {
        depositar();
    }
    

    function cadastro(&$cliente, $contas){
        $clientes = json_decode(file_get_contents("clientes.json"), true);


        $cliente[$contas]["nome"] = strtoupper(readline("Digite seu nome: "));
        

        do {
            $cpf = readline("Digite seu cpf: ");
            
            validaCPF($cpf, $clientes);

            if (validaCPF($cpf, $clientes)) {
                $cliente[$contas]["cpf"] = $cpf;
            }else {
                echo "CPF inválido! ";
            }
        } while (!validaCPF($cpf, $clientes));
        

        do {
            $telefone = readline("Digite seu número de telefone: ");

            if (valida_telefone($telefone)){
                $cliente[$contas]["telefone"] = $telefone;
            }else{
                echo "Número de telefone não encontrado! ";
            }

        } while (!valida_telefone($telefone));
        

        do {
            $senha = readline("Crie sua senha:");

            if (valida_senha($senha)) {
                $cliente[$contas]["senha"] = $senha;
            } else {
                echo "Senha Inválida! As senhas devem conter, maiúsculas, minúsculas, números, caracteres especiais. ";
            }

        } while (!valida_senha($senha));

        $cliente[$contas]["saldo"] = 0;

        $json = json_encode($clientes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents("clientes.json", $json);
    }



    function validaCPF(&$cpf, $clientes) {
        $clientes = json_decode(file_get_contents("clientes.json"), true);

        $cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);

        for ($i=0; $i < count($clientes); $i++) { 
            if ($cpf == $clientes[$i]["cpf"]) {
                return false;
            }
        }

        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
    
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        $cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
        return true;
    }



    function valida_telefone(&$telefone) {

        // Remove caracteres não numéricos
        $telefone = preg_replace('/\D/', '', $telefone);
    
        // Verifica se o número tem 10 ou 11 dígitos
        if(strlen($telefone) != 10 && strlen($telefone) != 11){
            return false;
        }
    
        // Formatação para número com 12 dígitos
        if(strlen($telefone) == 11){
            $telefone = preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $telefone);
        } else { 
            // Formatação para número com 10 dígitos 
            $telefone = preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $telefone);
        }
    
        return true;
    }



    function valida_senha($senha){
        // Verifica se a senha tem pelo menos 8 caracteres
        if (strlen($senha) < 8) {
            return false;
        }

        // Verifica se tem pelo menos uma letra maiúscula
        if (!preg_match('/[A-Z]/', $senha)) {
            return false;
        }

        // Verifica se tem pelo menos uma letra minúscula
        if (!preg_match('/[a-z]/', $senha)) {
            return false;
        }

        // Verifica se tem pelo menos um número
        if (!preg_match('/[0-9]/', $senha)) {
            return false;
        }

        // Verifica se tem pelo menos um caractere especial ($, @, #, etc.)
        if (!preg_match('/[\W]/', $senha)) {
            return false;
        }

        return true;         
    }
    
    function remover_conta(){

        $clientes = json_decode(file_get_contents("clientes.json"), true);
        $cpf_encontrado = false;
        $senha_encontrada = false;
        $quantidades_contas = count($clientes);
        
        do {
            
        $cpf = readline("digite seu cpf: ");
        
        validaCPF($cpf, $clientes);

    
        for ($i = 0; $i < $quantidades_contas ; $i++) { 

               if ($cpf == $clientes[$i]["cpf"]){

                    $cpf_encontrado = true;

                    do {
                        $senha = readline("digite sua senha: ");

                        if ($senha == $clientes[$i]["senha"]) {
                            $senha_encontrada = true;
                            unset($clientes[$i]);
                        }else {
                            print("Você errou sua senha. ");
                        }
                    } while ($senha_encontrada == false);
                }

        }if ($cpf_encontrado == false) {
            print "CPF  inválido! ";
        }
        
        }while($cpf_encontrado != true && $senha_encontrada != true);


        $json = json_encode($clientes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents("clientes.json", $json);

    }
    
    function clientes($cliente, $contas){
        $clientes = json_decode(file_get_contents("clientes.json"), true);

        $clientes[] = $cliente[$contas];

        $json = json_encode($clientes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents("clientes.json", $json);

    }

    function depositar(){
        $clientes = json_decode(file_get_contents("clientes.json"), true);
        $cpf_encontrado = false;
        $senha_encontrada = false;
        $valor_validado = false;
        $quantidades_contas = count($clientes);
        
        do {
            
        $cpf = readline("digite seu cpf: ");
        
        validaCPF($cpf, $clientes);

    
        for ($i = 0; $i < $quantidades_contas ; $i++) { 

                if ($cpf == $clientes[$i]["cpf"]){

                $cpf_encontrado = true;

                    do {
                        $senha = readline("digite sua senha: ");

                        if ($senha == $clientes[$i]["senha"]) {
                            $senha_encontrada = true;

                                do {
                                    
                                    $deposito = readline("digite o valor do depósito: ");
                                    $clientes[$i]["saldo"] += $deposito;
                                    $valor_validado = true;
                                } while ($valor_validado == false);

                        }else {
                            print("Você errou sua senha. ");
                        }

                    } while ($senha_encontrada == false);
                }

                }if ($cpf_encontrado == false) {
                    print "CPF  inválido! ";
                }
        
        }while($cpf_encontrado != true && $senha_encontrada != true);


        $json = json_encode($clientes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents("clientes.json", $json);
    }



    
?>
