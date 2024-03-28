<?php

//CRUD
class TarefaService {

	private $conexao;
	private $tarefa;

	// Construtor da classe TarefaService
	public function __construct(Conexao $conexao, Tarefa $tarefa) {
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	// Método para inserir uma nova tarefa no banco de dados
	public function inserir() { //create
		$query = 'INSERT INTO tb_tarefas(tarefa, horario_lembrete, categoria) VALUES (:tarefa, :horario_lembrete, :categoria)';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		$stmt->bindValue(':horario_lembrete', $this->tarefa->__get('horario_lembrete'));
		$stmt->bindValue(':categoria', $this->tarefa->__get('categoria'));
		$stmt->execute();
	}

	// Método para recuperar todas as tarefas do banco de dados
	public function recuperar($ordenacao = 'data_cadastro DESC') { //read
		$query = '
			SELECT 
				t.id, s.status, t.tarefa 
			FROM 
				tb_tarefas AS t
				LEFT JOIN tb_status AS s ON (t.id_status = s.id) ORDER BY ' . $ordenacao;
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Método para atualizar uma tarefa no banco de dados
	public function atualizar() { //update
		$query = "UPDATE tb_tarefas SET tarefa = :tarefa, horario_lembrete = :horario_lembrete WHERE id = :id";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		$stmt->bindValue(':horario_lembrete', $this->tarefa->__get('horario_lembrete'));
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	// Método para remover uma tarefa do banco de dados
	public function remover() { //delete
		$query = 'DELETE FROM tb_tarefas WHERE id = :id';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		$stmt->execute();
	}

	// Método para marcar uma tarefa como realizada no banco de dados
	public function marcarRealizada() { //update
		$query = "UPDATE tb_tarefas SET id_status = ? WHERE id = ?";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(1, $this->tarefa->__get('id_status'));
		$stmt->bindValue(2, $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	// Método para recuperar tarefas pendentes do banco de dados
	public function recuperarTarefasPendentes() {
		$query = '
			SELECT 
				t.id, s.status, t.tarefa 
			FROM 
				tb_tarefas AS t
				LEFT JOIN tb_status AS s ON (t.id_status = s.id)
			WHERE
				t.id_status = :id_status
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id_status', 1); // 1 é o ID do status para "pendente"
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Método para recuperar tarefas concluídas do banco de dados
	public function recuperarTarefasConcluidas() {
		$query = '
			SELECT
				t.id, s.status, t.tarefa
			FROM
				tb_tarefas AS t
				LEFT JOIN tb_status AS s ON(t.id_status = s.id)
			WHERE
				t.id_status = :id_status
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id_status', 2); // 2 é o ID do status para "concluída"
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Método para ordenar as tarefas por um atributo específico no banco de dados
	public function ordenarTarefas() {
		$orderBy = $_GET["atribute"];
		$query = "SELECT t.id, s.status, t.tarefa FROM tb_tarefas AS t LEFT JOIN tb_status AS s ON (t.id_status = s.id) ORDER BY $orderBy";
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Método para arquivar uma tarefa no banco de dados
	public function arquivarTarefa() {
		$query = "UPDATE tb_tarefas SET id_status = 3 WHERE id = :id";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	// Método para recuperar tarefas arquivadas do banco de dados
	public function recuperarTarefasArquivadas() {
		$query = '
			SELECT
				t.id, s.status, t.tarefa
			FROM
				tb_tarefas AS t
				LEFT JOIN tb_status AS s ON(t.id_status = s.id)
			WHERE
				t.id_status = :id_status
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id_status', 3); // 3 é o ID do status para "arquivada"
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Método para recuperar tarefas por categoria do banco de dados
	public function recuperarTarefasCategoria($filtro) {
		$query = '
			SELECT
				t.id, s.status, t.tarefa
			FROM
				tb_tarefas AS t
				LEFT JOIN tb_status AS s ON(t.id_status = s.id)
			WHERE
				t.categoria = :categoria
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':categoria', $filtro); // filtro ID da categoria para "1" "2" ou "3"
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// Método para verificar se há tarefas com lembretes na data atual
	public function verificarTarefa() {
		$hoje = date('Y-m-d');

		$query = "SELECT * FROM tb_tarefas WHERE horario_lembrete = :hoje";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindParam(':hoje', $hoje);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}

?>
