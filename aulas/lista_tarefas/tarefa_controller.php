<?php

require "tarefa.model.php";
require "tarefa.service.php";
require "conexao.php";

// Verifica a ação a ser executada
$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

// Insere uma nova tarefa no banco de dados
if ($acao == 'inserir') {
    // Cria uma nova instância de Tarefa e preenche seus atributos com os dados do formulário
    $tarefa = new Tarefa();
    $tarefa->__set('tarefa', $_POST['tarefa']);
    $tarefa->__set('horario_lembrete', $_POST['horario_lembrete']);
    $tarefa->__set('categoria', $_POST['categoria']);

    // Cria uma instância de Conexao
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e insere a nova tarefa no banco de dados
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->inserir();

    // Redireciona de volta para a página de nova tarefa após inserção bem-sucedida
    header('Location: nova_tarefa.php');
} else if ($acao == 'recuperar') {
    // Recupera todas as tarefas do banco de dados
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e recupera todas as tarefas
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperar();
} else if ($acao == 'atualizar') {
    // Atualiza uma tarefa existente no banco de dados
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);

    // Preenche os atributos da tarefa com os dados do formulário de edição
    $tarefa->__set('id', $_POST['id']);
    $tarefa->__set('tarefa', $_POST['tarefa']);

    // Atualiza a tarefa no banco de dados
    $tarefaService->atualizar();

    // Redireciona de volta para a página adequada após a atualização
    if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
        header('location: index.php');
    } else {
        header('location: todas_tarefas.php');
    }
} else if ($acao == 'ordenarTarefas') {
    // Ordena as tarefas de acordo com algum critério específico
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e ordena as tarefas
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->ordenarTarefas();
} else if ($acao == 'remover') {
    // Remove uma tarefa do banco de dados
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e remove a tarefa do banco de dados
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->remover();

    // Redireciona de volta para a página adequada após a remoção
    if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
        header('location: index.php');
    } else {
        header('location: todas_tarefas.php');
    }
} else if ($acao == 'marcarRealizada') {
    // Marca uma tarefa como realizada no banco de dados
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id'])->__set('id_status', 2);
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e marca a tarefa como realizada
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->marcarRealizada();

    // Redireciona de volta para a página adequada após a marcação
    if (isset($_GET['pag']) && $_GET['pag'] == 'index') {
        header('location: index.php');
    } else {
        header('location: todas_tarefas.php');
    }
} else if ($acao == 'recuperarTarefasPendentes') {
    // Recupera todas as tarefas pendentes do banco de dados
    $tarefa = new Tarefa();
    $tarefa->__set('id_status', 1);
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e recupera as tarefas pendentes
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperarTarefasPendentes();
} else if ($acao == 'filtrar') {
    // Filtra as tarefas com base no filtro especificado
    $filtro = $_GET['filtro'];
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e filtra as tarefas
    $tarefaService = new TarefaService($conexao, $tarefa);

    // Filtra as tarefas de acordo com o filtro especificado
    if ($filtro == 'pendentes') {
        $tarefas = $tarefaService->recuperarTarefasPendentes();
    } else if ($filtro == 'concluidas') {
        $tarefas = $tarefaService->recuperarTarefasConcluidas();
    } else if ($filtro == 'arquivadas') {
        $tarefas = $tarefaService->recuperarTarefasArquivadas();
    } else {
        $tarefas = $tarefaService->recuperar(); // Recuperar todas as tarefas
    }
} else if ($acao == 'categoria') {
    // Filtra as tarefas com base na categoria especificada
    $filtro = $_GET['filtro'];

    // Cria uma nova instância de Tarefa e Conexao
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e filtra as tarefas pela categoria especificada
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperarTarefasCategoria($filtro);
} else if ($acao == 'verificarTarefas') {
    // Verifica se existem tarefas próximas ao vencimento
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);
    
    // Verifica se há tarefas próximas ao vencimento e exibe um alerta se houver
    $vencido = $tarefaService->verificarTarefa();
    if ($vencido) {
        echo "<script>alert('Você possui tarefas vencendo hoje ou que vencerão em breve. Verifique suas tarefas para não perder nenhum prazo!');</script>";
    }
} else if ($acao == 'arquivar') {
    // Arquiva uma tarefa específica
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET['id']);
    $conexao = new Conexao();

    // Instancia o serviço de tarefa e arquiva a tarefa
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->arquivarTarefa();

    // Redireciona de volta para a página de todas as tarefas após arquivamento
    header('Location: todas_tarefas.php');
}

?>