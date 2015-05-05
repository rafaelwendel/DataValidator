DataValidator
==================

Classe php para validação de dados.

- Fácil utilização "$validate->set('nome', $somename)->is_required()->min_length(5);"
- Extremamente adaptável
- Mais de 30 tipos de validações disponíveis

### Créditos

Agradecer ao José Érico (jose.erico.soares@gmail.com) pela contribuição através dos métodos para validar telefone (8 e 9 dígitos), ip, cep(BR) e placa de veículos(BR)

Instalação
------------

Faça o download no GitHub e acople ao seu projeto

Guia de Utilização
-------------

### Inclusão

Basta incluir a classe e criar uma instância para utilizá-la.

    <?php
        include 'DataValidator.php';
        $validate = new Data_Validator();

### Validação Simples

Uma validação consiste em algo parecido com isso

    $somename = "Peter Gabriel";
    $validate->set('nome', $somenome)->is_required(); //Validado

### Validações Encadeadas

Como os métodos validadores sempre retornam a própria instância, é possível encadear as validações e economizar linhas de código. Imagine que o campo "nome" seja obrigatório e que deva conter ao mínimo 5 caracteres e o campo email deva conter um email válido

    $somename = "Peter Gabriel";
    $someemail = "peter@domain.com";
    $validate->set('nome', $somenome)->is_required()->min_length(5)//Validado
             ->set('email', $someemail)->is_email(); //Validado

### Validar os dados

A verificação das validações pode ser feita através da execução do método `validate()`.

    $validate->set('nome', $somename)->is_required()->min_length(5);
    if ($validate->validate()){
        //Tudo certo
    }
    else{
        //Algum dado nao foi validado
    }

### Capturando os erros

Os erros podem ser capturados através da chamada `get_errors()`. Para capturar o(s) erro(s) de um campo específico, basta informar o nome do campo no parâmetro `get_erros($param)`

    $validate->set('nome', 'Aa')->min_length(5);
    if ($validate->validate()){
        echo 'Tudo certo';
    }
    else{
        $todos_erros = $validate->get_errors(); //Captura os erros de todos os campos
        $erros_nome = $validate->get_errors('nome'); //Captura apenas os erros do campo 'nome'
        foreach ($erros_nome as $erro){
            echo '<p>' . $erro . '</p>';
        }
    }

O método `get_errors()` retorna um array encadeado onde o índice é o nome do campo validado e cada posição é um array com cada erro do campo. Ex: Os erros do campo 'nome' e 'email'

    $validate->set('nome', '')->is_required()->min_length(5)
             ->set('email', 'something')->is_email();
    print_r($validate->get_errors());

    /* Exibe
        Array
        (
            [nome] => Array
                (
                    [0] => O campo nome é obrigatório
                    [1] => O campo nome deve conter ao mínimo 5 caracter(es)
                )

            [email] => Array
                (
                    [0] => O email something não é válido
                )

        )
    */

Você pode definir um `pattern` para o índice. Assim é possível definir um prefixo e/ou sufixo padrão para os índices do array de erros

    //define_pattern($prefix = '', $sufix = '');
    $validate->define_pattern('erro_');
    $validate->set('nome', '')->is_required()->min_length(5)
             ->set('email', 'something')->is_email();
    print_r($validate->get_errors());

    /* Exibe os erros. Note que os índices estão com o prefixo definido acima
        Array
        (
            [erro_nome] => Array
                (
                    [0] => O campo nome é obrigatório
                    [1] => O campo nome deve conter ao mínimo 5 caracter(es)
                )

            [erro_email] => Array
                (
                    [0] => O email something não é válido
                )

        )
    */

### Métodos disponíveis

Segue lista de métodos validadores disponíveis na classe DataValidator.

* is_required
* min_length
* max_length
* between_length
* min_value
* max_value
* between_values
* is_email
* is_url
* is_slug
* is_num
* is_integer
* is_float
* is_string
* is_boolean
* is_obj
* is_instance_of
* is_arr
* is_directory
* is_equals
* is_not_equals
* is_cpf
* is_cnpj
* contains
* not_contains
* is_lowercase
* is_uppercase
* is_multiple
* is_positive
* is_negative
* is_date
* is_alpha
* is_alpha_num
* no_whitespaces
* is_phone
* is_zipCode
* is_plate
* is_ip

Se quiser saber o número de métodos validadores disponíveis, execute o método `get_number_validators_methods()`

    echo 'Número de métodos disponíveis: ' . $validate->get_number_validators_methods();
