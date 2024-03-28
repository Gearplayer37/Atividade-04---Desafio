
<?php

class Tarefa
{
    private $id;
    private $id_status;
    private $tarefa;
    private $data_cadastro;
    // Nova propriedade adicionada para suportar lembretes
    private $horario_lembrete;
  

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }
}


?>
