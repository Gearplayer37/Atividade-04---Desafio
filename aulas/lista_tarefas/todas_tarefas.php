<?php

	$acao = 'recuperar';
	require 'tarefa_controller.php';

	/*
	echo '<pre>';
	print_r($tarefas);
	echo '</pre>';
	*/

?>

<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>App Lista Tarefas</title>

		<link rel="stylesheet" href="css/estilo.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

		<style>
			.filtro {
				padding: 8px;
				border-radius: 5px;
				border: 1px solid #ccc;
				outline: none;
			}

			.btn-ordenar {
				margin-right: 5px;
			}
		</style>

		<script>
			function editar(id, txt_tarefa) {

				// Criar um form de edição
				let form = document.createElement('form')
				form.action = 'tarefa_controller.php?acao=atualizar'
				form.method = 'post'
				form.className = 'row'

				// Criar um input para entrada do texto
				let inputTarefa = document.createElement('input')
				inputTarefa.type = 'text'
				inputTarefa.name = 'tarefa'
				inputTarefa.className = 'col-9 form-control'
				inputTarefa.value = txt_tarefa

				// Criar um input hidden para guardar o id da tarefa
				let inputId = document.createElement('input')
				inputId.type = 'hidden'
				inputId.name = 'id'
				inputId.value = id

				// Criar um button para envio do form
				let button = document.createElement('button')
				button.type = 'submit'
				button.className = 'col-3 btn btn-info'
				button.innerHTML = 'Atualizar'

				// Incluir inputTarefa no form
				form.appendChild(inputTarefa)

				// Incluir inputId no form
				form.appendChild(inputId)

				// Incluir button no form
				form.appendChild(button)

				// Teste
				// console.log(form)

				// Selecionar a div tarefa
				let tarefa = document.getElementById('tarefa_'+id)

				// Limpar o texto da tarefa para inclusão do form
				tarefa.innerHTML = ''

				// Incluir form na página
				tarefa.insertBefore(form, tarefa[0])

			}

			function remover(id) {
				location.href = 'todas_tarefas.php?acao=remover&id='+id;
			}

			function orderBy(atribute) {
				location.href = 'todas_tarefas.php?acao=ordenarTarefas&atribute=' + atribute;
			}

			function filtrarTarefas(filtro) {
        		location.href = 'todas_tarefas.php?acao=filtrar&filtro=' + filtro;
    		}

			function filtrarCategorias(filtro) {
        		location.href = 'todas_tarefas.php?acao=categoria&filtro=' + filtro;
				echo ('$filtro');
    		}


			function marcarRealizada(id) {
				location.href = 'todas_tarefas.php?acao=marcarRealizada&id='+id;
			}

			function arquivar(id) {
				location.href = 'todas_tarefas.php?acao=arquivar&id=' + id;
			}
		</script>
	</head>

	<body>
		<nav class="navbar navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="#">
					<img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
					App Lista Tarefas
				</a>
			</div>
		</nav>

		<div class="container app">
			<div class="row">
				<div class="col-sm-3 menu">
					<ul class="list-group">
						<li class="list-group-item"><a href="index.php">Tarefas pendentes</a></li>
						<li class="list-group-item"><a href="nova_tarefa.php">Nova tarefa</a></li>
						<li class="list-group-item active"><a href="#">Todas tarefas</a></li>
						
					</ul>
				</div>

				<div class="col-sm-9">
					<div class="container pagina">
						<div class="row">
							<div class="col">
								<h4>Todas tarefas</h4>
								<hr />

								<div class="form-group">
                                	<label for="filtro">Filtrar Por:</label>
										<select id="filtro" class="form-control filtro" onchange="filtrarTarefas(this.value)">
											<option value="">Selecione...</option>
											<option value="todas">Todas Tarefas</option>
											<option value="pendentes">Tarefas Pendentes</option>
											<option value="concluidas">Tarefas Concluídas</option>
											<option value="arquivadas">Tarefas Arquivadas</option>
										</select>
                            	</div>
								<div class="form-group">
									<label for="filtro">Filtrar Por:</label>
									<select id="filtro" class="form-control filtro" onchange="filtrarCategorias(this.value)">
										<option value="">Selecionar...</option>
										<option value="1">Alta Prioridade</option>
										<option value="2">Média Prioridade</option>
										<option value="3">Baixa Prioridade</option>
									</select>
								</div>

								<button class="btn btn-primary btn-ordenar" onclick="orderBy('id_status')">Ordenar por Status</button>
                            	<button class="btn btn-primary btn-ordenar" onclick="orderBy('data_cadastro')">Ordenar por Data de Criação</button>
                           	 	<button class="btn btn-primary btn-ordenar" onclick="orderBy('tarefa')">Ordenar por Nome</button>

								<?php foreach($tarefas as $indice => $tarefa) { ?>
									<div class="row mb-3 d-flex align-items-center tarefa">
											<div class="col-sm-9" id="tarefa_<?= $tarefa->id ?>">
												<?= $tarefa->tarefa ?> (<?= $tarefa->status ?>)
											</div>
											<div class="col-sm-3 mt-2 d-flex justify-content-between">
												<i class="fas fa-trash-alt fa-lg text-danger" onclick="remover(<?= $tarefa->id ?>)"></i>
												
												<?php if($tarefa->status == 'pendente') { ?>
													<i class="fas fa-edit fa-lg text-info" onclick="editar(<?= $tarefa->id ?>, '<?= $tarefa->tarefa ?>')"></i>
													<i class="fas fa-check-square fa-lg text-success" onclick="marcarRealizada(<?= $tarefa->id ?>)"></i>
													<i class="fas fa-archive fa-lg text-secondary" onclick="arquivar(<?= $tarefa->id ?>)"></i>
												<?php } ?>
											</div>
									</div>

								<?php } ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
