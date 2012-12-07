<?php
/**
 * Description of DataValidator
 *
 * @author Rafael Wendel Pinheiro
 */
class Data_Validator {
    
    protected $_data     = array();
    protected $_errors   = array();
    protected $_pattern  = array();
    protected $_messages = array();
    
    public function __construct() {
        $this->set_messages_default();
    }
    
    public function set($name, $value){
        $this->_data['name'] = $name;
        $this->_data['value'] = $value;
        return $this;
    }
    
    public function set_messages_default(){
        $this->_messages = array(
            'is_required'    => 'O campo %s é obrigatório',
            'min_length'     => 'O campo %s deve conter ao mínimo %s caracter(es)',
            'max_length'     => 'O campo %s deve conter ao máximo %s caracter(es)',
            'between_length' => 'O campo %s deve conter entre %s e %s caracter(es)',
            'min_value'      => 'O valor do campo %s deve ser maior que %s ',
            'max_value'      => 'O valor do campo %s deve ser menor que %s ',
            'between_values' => 'O valor do campo %s deve estar entre %s e %s',
            'is_email'       => 'O email %s não é válido ',
            'is_url'         => 'A URL %s não é válida ',
            'is_slug'        => '%s não é um slug ',
            'is_num'         => 'O valor %s não é numérico ',
            'is_integer'     => 'O valor %s não é inteiro ',
            'is_float'       => 'O valor %s não é float ',
            'is_string'      => 'O valor %s não é String ',
            'is_boolean'     => 'O valor %s não é booleano ',
            'is_identical'   => 'O valor do campo %s deve ser idêntico à %s ',
            'is_cpf'         => 'O valor %s não é um CPF válido '
        );
    }
    
    public function set_message($name, $value){
        if (array_key_exists($name, $this->_messages)){
            $this->_messages[$name] = $value;
        }
    }
    
    public function get_messages($param = false){
        if ($param){
            return $this->_messages[$param];
        }
        return $this->_messages;
    }
    
    public function define_pattern($prefix = '', $sufix = ''){
        $this->_pattern['prefix'] = $prefix;
        $this->_pattern['sufix']  = $sufix;
    }
    
    public function set_error($error){
        $this->_errors[$this->_pattern['prefix'] . $this->_data['name'] . $this->_pattern['sufix']][] = $error;
    }
    
    public function is_required(){
        if (empty ($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_required'], $this->_data['name']));
        }
        return $this;
    } 
    
    public function min_length($length){
        if (strlen($this->_data['value']) < $length){
            $this->set_error(sprintf($this->_messages['min_length'], $this->_data['name'], $length));
        }
        return $this;
    }
    
    public function max_length($length){
        if (strlen($this->_data['value']) > $length){
            $this->set_error(sprintf($this->_messages['max_length'], $this->_data['name'], $length));
        }
        return $this;
    }
    
    public function between_length($min, $max){
        if(strlen($this->_data['value']) < $min || strlen($this->_data['value']) > $max){
            $this->set_error(sprintf($this->_messages['between_legth'], $this->_data['name'], $min, $max));
        }
        return $this;
    }
    
    public function min_value($value){
        if (!is_numeric($this->_data['value']) || $this->_data['value'] < $value){
            $this->set_error(sprintf($this->_messages['min_value'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    public function max_value($value){
        if (!is_numeric($this->_data['value']) || $this->_data['value'] > $value){
            $this->set_error(sprintf($this->_messages['max_value'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    public function between_values($min_value, $max_value){
        if(!is_numeric($this->_data['value']) || ($this->_data['value'] < $min_value && $this->_data['value'] > $max_value )){
            $this->set_error(sprintf($this->_messages['between_values'], $this->_data['name'], $min_value, $max_value));
        }
        return $this;
    }
    
    public function is_email(){
        if (filter_var($this->_data['value'], FILTER_VALIDATE_EMAIL) === FALSE) {
            $this->set_error(sprintf($this->_messages['is_email'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_url(){
        if (filter_var($this->_data['value'], FILTER_VALIDATE_URL) === FALSE) {
            $this->set_error(sprintf($this->_messages['is_url'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_slug(){
        $verify = true;
        
        if (strstr($input, '--')) {
            $verify = false;
        }
        if (!preg_match('@^[0-9a-z\-]+$@', $input)) {
            $verify = false;
        }
        if (preg_match('@^-|-$@', $input)){
            $verify = false;
        }        
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_url'], $this->_data['value']));
        }
        return $this;        
    }
    
    public function is_num(){
        if (!is_numeric($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_num'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_integer(){
        if (!is_numeric($this->_data['value']) && (int) $this->_data['value'] != $this->_data['value']){
            $this->set_error(sprintf($this->_messages['is_integer'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_float(){
        if (!is_float(filter_var($this->_data['value'], FILTER_VALIDATE_FLOAT))){
            $this->set_error(sprintf($this->_messages['is_float'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_string(){
        if(!is_string($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_string'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_boolean(){
        if(!is_bool($this->_data['value'])){
            $this->set_error(sprintf($this->_messages['is_boolean'], $this->_data['value']));
        }
        return $this;
    }
    
    public function is_identical($value){
        if($this->_data['value'] !== $value){
            $this->set_error(sprintf($this->_messages['is_identical'], $this->_data['name'], $value));
        }
        return $this;
    }
    
    public function is_cpf(){
        $verify = true;
        
        $c = preg_replace('/\D/', '', $this->_data['value']);
        
        if (strlen($c) != 11) 
            $verify = false;

        if (preg_match("/^{$c[0]}{11}$/", $c)) 
            $verify = false;
        
        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        
        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) 
            $verify = false;

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) 
            $verify = false;
        
        if(!$verify){
            $this->set_error(sprintf($this->_messages['is_cpf'], $this->_data['value']));
        }
        
        return $this;
    }
    
    public function validate(){
        return (count($this->_errors) > 0 ? false : true);
    }
    
    public function get_errors($param = false){
        if ($param){
            if(isset($this->_errors['msg_' . $param])){
                return $this->_errors['msg_' . $param];
            }
            elseif (isset($this->_errors[$param])){
                return $this->_errors[$param];
            }
            else{
                return false;
            }
        }        
        return $this->_errors;
    }
}