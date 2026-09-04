<?php
/**
 * Módulo de Clientes — Codora Tech Admin Dashboard
 * Listagem, Cadastro, Edição e Exclusão via PHP + PDO + AJAX
 */
session_start();
if (!isset($_SESSION['usuario_id'])) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Sessão expirada. Faça login novamente.']);
        exit;
    }
    header('Location: auth-login.php');
    exit;
}
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Administrador';
$cargoUsuario = $_SESSION['usuario_cargo'] ?? 'Administrador';

require_once '../config/conexao.php';

// Tratamento de requisições AJAX (CRUD)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $empresa = trim($_POST['empresa'] ?? '');
            $responsavel = trim($_POST['responsavel'] ?? '');
            $documento = trim($_POST['documento'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cidade = trim($_POST['cidade'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $cep = trim($_POST['cep'] ?? '');
            $endereco = trim($_POST['endereco'] ?? '');
            $numero = trim($_POST['numero'] ?? '');
            $bairro = trim($_POST['bairro'] ?? '');
            $status = trim($_POST['status'] ?? 'Ativo');
            $observacoes = trim($_POST['observacoes'] ?? '');

            if (empty($empresa) || empty($responsavel) || empty($documento) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Preencha os campos obrigatórios (Empresa, Responsável, Documento, E-mail).']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO clientes (empresa, responsavel, documento, telefone, email, cidade, estado, cep, endereco, numero, bairro, status, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$empresa, $responsavel, $documento, $telefone, $email, $cidade, $estado, $cep, $endereco, $numero, $bairro, $status, $observacoes]);
            
            echo json_encode(['success' => true, 'message' => 'Cliente cadastrado com sucesso!']);
            exit;
        } elseif ($action === 'update') {
            $id = intval($_POST['id'] ?? 0);
            $empresa = trim($_POST['empresa'] ?? '');
            $responsavel = trim($_POST['responsavel'] ?? '');
            $documento = trim($_POST['documento'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cidade = trim($_POST['cidade'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $cep = trim($_POST['cep'] ?? '');
            $endereco = trim($_POST['endereco'] ?? '');
            $numero = trim($_POST['numero'] ?? '');
            $bairro = trim($_POST['bairro'] ?? '');
            $status = trim($_POST['status'] ?? 'Ativo');
            $observacoes = trim($_POST['observacoes'] ?? '');

            if ($id <= 0 || empty($empresa) || empty($responsavel)) {
                echo json_encode(['success' => false, 'message' => 'ID inválido ou campos obrigatórios ausentes.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE clientes SET empresa = ?, responsavel = ?, documento = ?, telefone = ?, email = ?, cidade = ?, estado = ?, cep = ?, endereco = ?, numero = ?, bairro = ?, status = ?, observacoes = ? WHERE id = ?");
            $stmt->execute([$empresa, $responsavel, $documento, $telefone, $email, $cidade, $estado, $cep, $endereco, $numero, $bairro, $status, $observacoes, $id]);

            echo json_encode(['success' => true, 'message' => 'Cliente atualizado com sucesso!']);
            exit;
        } elseif ($action === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID inválido.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Cliente excluído com sucesso!']);
            exit;
        } elseif ($action === 'get') {
            $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->execute([$id]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                echo json_encode(['success' => true, 'data' => $cliente]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cliente não encontrado.']);
            }
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
        exit;
    }
}

// Filtros de busca e listagem
$search = trim($_GET['search'] ?? '');
$filterStatus = trim($_GET['status'] ?? '');
$filterCity = trim($_GET['city'] ?? '');

$query = "SELECT * FROM clientes WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (empresa LIKE ? OR email LIKE ? OR documento LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}
if (!empty($filterStatus) && $filterStatus !== 'Todos') {
    $query .= " AND status = ?";
    $params[] = $filterStatus;
}
if (!empty($filterCity) && $filterCity !== 'Todas') {
    $query .= " AND cidade = ?";
    $params[] = $filterCity;
}
$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalClientes = count($clientes);
?>
<!DOCTYPE html>
<!-- =========================================================
* Codora Tech — Admin Dashboard
* Base template: Sneat Bootstrap 5 HTML Admin (ThemeSelection), customized
* for Codora Tech's brand identity, navigation, and content.
========================================================== -->
<html lang="pt-BR" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Clientes | Codora Tech</title>
  <meta name="description" content="Gestão de clientes da Codora Tech" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Codora Tech brand theme (loaded last so it can override the vendor CSS) -->
  <link rel="stylesheet" href="../assets/css/codora-theme.css" />

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/js/config.js"></script>
  <script src="../assets/js/codora-theme-switcher.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="index.php" class="app-brand-link">
            <span class="codora-brand-mark me-2">CT</span>
            <span class="app-brand-text codora menu-text">Codora Tech</span>
          </a>
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="nav.headerMain">Principal</span>
          </li>
          <li class="menu-item">
            <a href="index.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-grid-alt"></i>
              <div data-i18n="nav.dashboard">Dashboard</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="nav.headerManagement">Gestão</span>
          </li>
          <li class="menu-item active">
            <a href="clients.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-user-pin"></i>
              <div data-i18n="nav.clients">Clientes</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="projects.html" class="menu-link">
              <i class="menu-icon tf-icons bx bx-folder-open"></i>
              <div data-i18n="nav.projects">Projetos</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="services.html" class="menu-link">
              <i class="menu-icon tf-icons bx bx-server"></i>
              <div data-i18n="nav.services">Serviços</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="work-orders.html" class="menu-link">
              <i class="menu-icon tf-icons bx bx-task"></i>
              <div data-i18n="nav.workOrders">Ordens de Serviço</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="agenda.html" class="menu-link">
              <i class="menu-icon tf-icons bx bx-calendar-check"></i>
              <div data-i18n="nav.schedule">Agenda</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <i class="menu-icon tf-icons bx bx-wallet"></i>
              <div data-i18n="nav.finance">Financeiro</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <i class="menu-icon tf-icons bx bx-images"></i>
              <div data-i18n="nav.portfolio">Portfólio</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
              <div data-i18n="nav.reports">Relatórios</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="nav.headerOther">Outros</span>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <i class="menu-icon tf-icons bx bx-cog"></i>
              <div data-i18n="nav.settings">Configurações</div>
            </a>
          </li>
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" data-i18n-placeholder="topbar.search"
                  placeholder="Buscar..." aria-label="Buscar" />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Quick actions -->
              <li class="nav-item dropdown me-2 me-xl-1">
                <a class="nav-link btn btn-accent btn-sm d-flex align-items-center px-3" href="javascript:void(0);"
                  data-bs-toggle="dropdown" aria-label="Ações rápidas">
                  <i class="bx bx-plus me-1"></i>
                  <span class="d-none d-md-inline" data-i18n="topbar.quickActions">Ações rápidas</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addClientModal"><i class="bx bx-user-plus me-2"></i><span
                        data-i18n="topbar.newClient">Novo cliente</span></a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-folder-plus me-2"></i><span
                        data-i18n="topbar.newProject">Novo projeto</span></a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-task me-2"></i><span
                        data-i18n="topbar.newWorkOrder">Nova ordem de serviço</span></a></li>
                </ul>
              </li>

              <!-- Language switcher -->
              <li class="nav-item dropdown codora-lang-switch me-2 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center px-2" href="javascript:void(0)"
                  data-bs-toggle="dropdown" aria-label="Idioma">
                  <i class="bx bx-globe fs-4 me-1"></i>
                  <small id="codoraCurrentLang" class="fw-semibold">PT</small>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item active" href="javascript:void(0)" data-lang="pt">Português</a></li>
                  <li><a class="dropdown-item" href="javascript:void(0)" data-lang="en">English</a></li>
                </ul>
              </li>

              <!-- Theme switcher -->
              <li class="nav-item me-2 me-xl-1">
                <a class="nav-link codora-theme-switch" href="javascript:void(0);" id="codoraThemeToggle"
                  aria-label="Alternar tema">
                  <i class="bx bx-moon" id="codoraThemeIcon"></i>
                </a>
              </li>

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/5.png" alt="Foto de perfil" class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="../assets/img/avatars/5.png" alt="Foto de perfil"
                              class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block"><?php echo htmlspecialchars($nomeUsuario); ?></span>
                          <small class="text-muted" data-i18n="topbar.role"><?php echo htmlspecialchars($cargoUsuario); ?></small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-user me-2"></i><span
                        class="align-middle" data-i18n="topbar.myProfile">Meu perfil</span></a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-cog me-2"></i><span
                        class="align-middle" data-i18n="topbar.settings">Configurações</span></a></li>
                  <li><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-wallet me-2"></i><span
                        class="align-middle" data-i18n="topbar.billing">Financeiro</span></a></li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                   <li><a class="dropdown-item" href="logout.php"><i class="bx bx-power-off me-2"></i><span
                        class="align-middle" data-i18n="topbar.logout">Sair</span></a></li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Page header -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
              <div>
                <h4 class="mb-1" data-i18n="clients.title">Clientes</h4>
                <p class="text-muted mb-0" data-i18n="clients.subtitle">Gerencie todos os clientes cadastrados da Codora Tech.</p>
              </div>
            </div>
            <!-- / Page header -->

            <!-- Action bar / Filters -->
            <div class="card mb-4">
              <div class="card-body">
                <form method="GET" action="clients.php" class="row g-3 align-items-end">
                  <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted mb-1" data-i18n="clients.search">Buscar</label>
                    <div class="input-group">
                      <span class="input-group-text bg-transparent"><i class="bx bx-search"></i></span>
                      <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por empresa, e-mail ou documento..." aria-label="Buscar clientes" />
                    </div>
                  </div>
                  <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted mb-1" data-i18n="clients.filterStatus">Status</label>
                    <select class="form-select" name="status">
                      <option value="Todos" <?php echo ($filterStatus === 'Todos' || $filterStatus === '') ? 'selected' : ''; ?>>Todos</option>
                      <option value="Ativo" <?php echo ($filterStatus === 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                      <option value="Inativo" <?php echo ($filterStatus === 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                      <option value="Pendente" <?php echo ($filterStatus === 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                    </select>
                  </div>
                  <div class="col-6 col-lg-2">
                    <label class="form-label small text-muted mb-1" data-i18n="clients.filterCity">Cidade</label>
                    <select class="form-select" name="city">
                      <option value="Todas" <?php echo ($filterCity === 'Todas' || $filterCity === '') ? 'selected' : ''; ?>>Todas</option>
                      <?php foreach ($cidadesDb as $cidade): ?>
                        <option value="<?php echo htmlspecialchars($cidade); ?>" <?php echo ($filterCity === $cidade) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cidade); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-6 col-lg-auto">
                    <button type="submit" class="btn btn-outline-primary w-100 mb-1">Filtrar</button>
                    <a href="clients.php" class="btn btn-outline-secondary w-100" data-i18n="clients.clearFilters">
                      <i class="bx bx-x me-1"></i> Limpar
                    </a>
                  </div>
                  <div class="col-6 col-lg-auto ms-lg-auto">
                    <div class="d-flex gap-2 justify-content-end">
                      <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addClientModal">
                        <i class="bx bx-plus me-1"></i> <span data-i18n="clients.addClient">Adicionar cliente</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- / Action bar -->

            <!-- Clients table -->
            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0" data-i18n="clients.listTitle">Clientes cadastrados</h5>
                <span class="text-muted small"><?php echo $totalClientes; ?> clientes encontrados</span>
              </div>
              <div class="table-responsive text-nowrap">
                <table class="table">
                  <thead>
                    <tr>
                      <th data-i18n="clients.colCode">Código</th>
                      <th data-i18n="clients.colCompany">Empresa</th>
                      <th data-i18n="clients.colResponsible">Responsável</th>
                      <th data-i18n="clients.colPhone">Telefone</th>
                      <th data-i18n="clients.colEmail">E-mail</th>
                      <th data-i18n="clients.colCity">Cidade</th>
                      <th data-i18n="clients.colStatus">Status</th>
                      <th data-i18n="clients.colRegistered">Cadastro</th>
                      <th data-i18n="clients.colActions">Ações</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php if (empty($clientes)): ?>
                      <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Nenhum cliente encontrado.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($clientes as $cli): ?>
                        <tr>
                          <td><span class="fw-semibold">#<?php echo str_pad($cli['id'], 4, '0', STR_PAD_LEFT); ?></span></td>
                          <td><?php echo htmlspecialchars($cli['empresa']); ?></td>
                          <td><?php echo htmlspecialchars($cli['responsavel']); ?></td>
                          <td><?php echo htmlspecialchars($cli['telefone']); ?></td>
                          <td><?php echo htmlspecialchars($cli['email']); ?></td>
                          <td><?php echo htmlspecialchars($cli['cidade']); ?></td>
                          <td>
                            <?php 
                              $badgeClass = 'bg-label-success';
                              if ($cli['status'] === 'Inativo') $badgeClass = 'bg-label-secondary';
                              if ($cli['status'] === 'Pendente') $badgeClass = 'bg-label-warning';
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($cli['status']); ?></span>
                          </td>
                          <td><?php echo date('d/m/Y', strtotime($cli['created_at'])); ?></td>
                          <td>
                            <div class="d-flex gap-1">
                              <button type="button" class="btn btn-icon btn-sm rounded-circle btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewClientModal" onclick="loadClientForView(<?php echo $cli['id']; ?>)" aria-label="Visualizar cliente"><i class="bx bx-show"></i></button>
                              <button type="button" class="btn btn-icon btn-sm rounded-circle btn-outline-accent" data-bs-toggle="modal" data-bs-target="#editClientModal" onclick="loadClientForEdit(<?php echo $cli['id']; ?>)" aria-label="Editar cliente"><i class="bx bx-edit"></i></button>
                              <button type="button" class="btn btn-icon btn-sm rounded-circle btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteClientModal" onclick="prepareDeleteClient(<?php echo $cli['id']; ?>)" aria-label="Excluir cliente"><i class="bx bx-trash"></i></button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- / Clients table -->

          </div>

          <!-- Add Client Modal -->
          <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addClientModalLabel" data-i18n="clients.addModalTitle">Adicionar cliente</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                  <form id="addClientForm">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Nome da empresa *</label>
                        <input type="text" class="form-control" name="empresa" placeholder="Ex.: Aurea Capital" required />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Responsável *</label>
                        <input type="text" class="form-control" name="responsavel" placeholder="Nome do contato principal" required />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">CPF/CNPJ *</label>
                        <input type="text" class="form-control" name="documento" placeholder="00.000.000/0000-00" required />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                          <option value="Ativo">Ativo</option>
                          <option value="Inativo">Inativo</option>
                          <option value="Pendente">Pendente</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="telefone" placeholder="(11) 4002-8922" />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">E-mail *</label>
                        <input type="email" class="form-control" name="email" placeholder="contato@empresa.com.br" required />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Cidade</label>
                        <input type="text" class="form-control" name="cidade" placeholder="São Paulo" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <input type="text" class="form-control" name="estado" placeholder="SP" maxlength="2" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">CEP</label>
                        <input type="text" class="form-control" name="cep" placeholder="00000-000" />
                      </div>
                      <div class="col-md-8">
                        <label class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco" placeholder="Av. Paulista" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Número</label>
                        <input type="text" class="form-control" name="numero" placeholder="1000" />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Bairro</label>
                        <input type="text" class="form-control" name="bairro" placeholder="Bela Vista" />
                      </div>
                      <div class="col-12">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3" placeholder="Informações adicionais sobre o cliente..."></textarea>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-accent" id="saveAddClientBtn">Salvar cliente</button>
                </div>
              </div>
            </div>
          </div>
          <!-- / Add Client Modal -->

          <!-- Edit Client Modal -->
          <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editClientModalLabel">Editar cliente</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                  <form id="editClientForm">
                    <input type="hidden" name="id" id="edit_id" />
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Nome da empresa *</label>
                        <input type="text" class="form-control" name="empresa" id="edit_empresa" required />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Responsável *</label>
                        <input type="text" class="form-control" name="responsavel" id="edit_responsavel" required />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">CPF/CNPJ *</label>
                        <input type="text" class="form-control" name="documento" id="edit_documento" required />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_status">
                          <option value="Ativo">Ativo</option>
                          <option value="Inativo">Inativo</option>
                          <option value="Pendente">Pendente</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="telefone" id="edit_telefone" />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">E-mail *</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Cidade</label>
                        <input type="text" class="form-control" name="cidade" id="edit_cidade" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <input type="text" class="form-control" name="estado" id="edit_estado" maxlength="2" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">CEP</label>
                        <input type="text" class="form-control" name="cep" id="edit_cep" />
                      </div>
                      <div class="col-md-8">
                        <label class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco" id="edit_endereco" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Número</label>
                        <input type="text" class="form-control" name="numero" id="edit_numero" />
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Bairro</label>
                        <input type="text" class="form-control" name="bairro" id="edit_bairro" />
                      </div>
                      <div class="col-12">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="observacoes" id="edit_observacoes" rows="3"></textarea>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-accent" id="saveEditClientBtn">Salvar alterações</button>
                </div>
              </div>
            </div>
          </div>
          <!-- / Edit Client Modal -->

          <!-- View Client Modal -->
          <div class="modal fade" id="viewClientModal" tabindex="-1" aria-labelledby="viewClientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="viewClientModalLabel">Detalhes do cliente</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                  <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="avatar-initial rounded-circle bg-label-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.1rem;"><i class="bx bx-buildings"></i></span>
                    <div>
                      <h5 class="mb-0" id="view_empresa">-</h5>
                      <span class="badge bg-label-success" id="view_status">-</span>
                    </div>
                  </div>
                  <div class="row g-4">
                    <div class="col-md-6">
                      <small class="text-muted d-block">Código</small>
                      <span class="fw-semibold" id="view_id">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Responsável</small>
                      <span class="fw-semibold" id="view_responsavel">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">CPF/CNPJ</small>
                      <span class="fw-semibold" id="view_documento">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Telefone</small>
                      <span class="fw-semibold" id="view_telefone">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">E-mail</small>
                      <span class="fw-semibold" id="view_email">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Cadastrado em</small>
                      <span class="fw-semibold" id="view_created_at">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Cidade / Estado</small>
                      <span class="fw-semibold" id="view_cidade_estado">-</span>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">CEP</small>
                      <span class="fw-semibold" id="view_cep">-</span>
                    </div>
                    <div class="col-12">
                      <small class="text-muted d-block">Endereço</small>
                      <span class="fw-semibold" id="view_endereco_completo">-</span>
                    </div>
                    <div class="col-12">
                      <small class="text-muted d-block">Observações</small>
                      <span id="view_observacoes">-</span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                  <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#editClientModal" id="viewEditBtn">Editar cliente</button>
                </div>
              </div>
            </div>
          </div>
          <!-- / View Client Modal -->

          <!-- Delete Client Modal -->
          <div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-body text-center p-4 p-md-5">
                  <span class="avatar-initial rounded-circle bg-label-danger d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;font-size:1.5rem;"><i class="bx bx-trash"></i></span>
                  <h5 class="mb-2" id="deleteClientModalLabel">Excluir cliente?</h5>
                  <p class="text-muted mb-4">Essa ação não poderá ser desfeita. O cliente será removido permanentemente da base de dados.</p>
                  <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sim, excluir</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- / Delete Client Modal -->

          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                ©
                <script>document.write(new Date().getFullYear());</script>
                Codora Tech. <span data-i18n="footer.rights">Todos os direitos reservados.</span>
              </div>
              <div>
                <span class="text-muted">Digital solutions designed for people</span>
              </div>
            </div>
          </footer>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Codora Tech i18n -->
  <script src="../assets/js/codora-i18n.js"></script>

  <!-- Clients CRUD AJAX Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Create Client
      const saveAddBtn = document.getElementById('saveAddClientBtn');
      if (saveAddBtn) {
        saveAddBtn.addEventListener('click', function () {
          const form = document.getElementById('addClientForm');
          const formData = new FormData(form);
          formData.append('action', 'create');

          fetch('clients.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Erro: ' + data.message);
            }
          })
          .catch(err => console.error(err));
        });
      }

      // Load Client for Edit
      window.loadClientForEdit = function(id) {
        const formData = new FormData();
        formData.append('action', 'get');
        formData.append('id', id);

        fetch('clients.php', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const c = data.data;
            document.getElementById('edit_id').value = c.id;
            document.getElementById('edit_empresa').value = c.empresa;
            document.getElementById('edit_responsavel').value = c.responsavel;
            document.getElementById('edit_documento').value = c.documento;
            document.getElementById('edit_status').value = c.status;
            document.getElementById('edit_telefone').value = c.telefone;
            document.getElementById('edit_email').value = c.email;
            document.getElementById('edit_cidade').value = c.cidade;
            document.getElementById('edit_estado').value = c.estado;
            document.getElementById('edit_cep').value = c.cep;
            document.getElementById('edit_endereco').value = c.endereco;
            document.getElementById('edit_numero').value = c.numero;
            document.getElementById('edit_bairro').value = c.bairro;
            document.getElementById('edit_observacoes').value = c.observacoes || '';
          } else {
            alert('Erro ao carregar cliente.');
          }
        });
      };

      // Update Client
      const saveEditBtn = document.getElementById('saveEditClientBtn');
      if (saveEditBtn) {
        saveEditBtn.addEventListener('click', function () {
          const form = document.getElementById('editClientForm');
          const formData = new FormData(form);
          formData.append('action', 'update');

          fetch('clients.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Erro: ' + data.message);
            }
          });
        });
      }

      // Load Client for View
      window.loadClientForView = function(id) {
        const formData = new FormData();
        formData.append('action', 'get');
        formData.append('id', id);

        fetch('clients.php', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const c = data.data;
            document.getElementById('view_empresa').textContent = c.empresa;
            document.getElementById('view_status').textContent = c.status;
            document.getElementById('view_id').textContent = '#' + String(c.id).padStart(4, '0');
            document.getElementById('view_responsavel').textContent = c.responsavel;
            document.getElementById('view_documento').textContent = c.documento;
            document.getElementById('view_telefone').textContent = c.telefone;
            document.getElementById('view_email').textContent = c.email;
            document.getElementById('view_created_at').textContent = new Date(c.created_at).toLocaleDateString('pt-BR');
            document.getElementById('view_cidade_estado').textContent = (c.cidade || '-') + ' — ' + (c.estado || '-');
            document.getElementById('view_cep').textContent = c.cep || '-';
            document.getElementById('view_endereco_completo').textContent = (c.endereco || '-') + ', ' + (c.numero || '-') + ' — ' + (c.bairro || '-');
            document.getElementById('view_observacoes').textContent = c.observacoes || 'Nenhuma observação registrada.';
            
            const editBtn = document.getElementById('viewEditBtn');
            if (editBtn) {
              editBtn.setAttribute('onclick', `loadClientForEdit(${c.id})`);
            }
          }
        });
      };

      // Delete Client
      let deleteClientId = null;
      window.prepareDeleteClient = function(id) {
        deleteClientId = id;
      };

      const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
      if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
          if (!deleteClientId) return;
          const formData = new FormData();
          formData.append('action', 'delete');
          formData.append('id', deleteClientId);

          fetch('clients.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              location.reload();
            } else {
              alert('Erro: ' + data.message);
            }
          });
        });
      }
    });
  </script>
</body>

</html>
