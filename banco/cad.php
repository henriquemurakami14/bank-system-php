<?php  
    $cliente = [];
    $contas = 0; 
    
    do {
        $criar_conta = readline("[1] Criar Conta   [2] Remover Conta   Selecione a opção: ");
    
        if ($criar_conta == "1" || $criar_conta == "2") {
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
    }
    

    function cadastro(&$cliente, $contas){

        $cliente[$contas]["nome"] = strtoupper(readline("Digite seu nome: "));
        

        do {
            $cpf = readline("Digite seu cpf: ");
            
            validaCPF($cpf);

            if (validaCPF($cpf)) {
                $cliente[$contas]["cpf"] = $cpf;
            }else {
                echo "CPF inválido! ";
            }
        } while (!validaCPF($cpf));
        


        do {
            $telefone = readline("Digite seu número de telefone: ");

            valida_telefone($telefone);

            if (valida_telefone($telefone)){
                $cliente[$contas]["telefone"] = $telefone;
            }else{
                echo "Número de telefone não encontrado! ";
            }

        } while (!valida_telefone($telefone));
        
        $cliente[$contas]["senha"] = readline("Crie sua senha:");

    }

    function validaCPF(&$cpf) {

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
    function valida_senha(){

    }
    
    function remover_conta(){

        $clientes = json_decode(file_get_contents("clientes.json"), true);
        $cpf_encontrado = false;
        $senha_encontrada = false;
        $quantidades_contas = count($clientes);
        
        do {
            
        $cpf = readline("digite seu cpf: ");
        
        validaCPF($cpf, $clientes, $quantidades_contas);

    
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

?>