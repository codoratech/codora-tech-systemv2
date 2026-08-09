/**
 * Codora Tech — i18n (PT default / EN)
 * Applies translations to every element with [data-i18n], persists the
 * chosen language in localStorage, and wires the topbar language switcher.
 */
'use strict';

(function () {
  var STORAGE_KEY = 'codora-lang';

  var dict = {
    'nav.dashboard': { pt: 'Dashboard', en: 'Dashboard' },
    'nav.clients': { pt: 'Clientes', en: 'Clients' },
    'nav.projects': { pt: 'Projetos', en: 'Projects' },
    'nav.services': { pt: 'Serviços', en: 'Services' },
    'nav.workOrders': { pt: 'Ordens de Serviço', en: 'Work Orders' },
    'nav.schedule': { pt: 'Agenda', en: 'Schedule' },
    'nav.finance': { pt: 'Financeiro', en: 'Finance' },
    'nav.portfolio': { pt: 'Portfólio', en: 'Portfolio' },
    'nav.reports': { pt: 'Relatórios', en: 'Reports' },
    'nav.settings': { pt: 'Configurações', en: 'Settings' },
    'nav.headerMain': { pt: 'Principal', en: 'Main' },
    'nav.headerManagement': { pt: 'Gestão', en: 'Management' },
    'nav.headerOther': { pt: 'Outros', en: 'Other' },

    'topbar.search': { pt: 'Buscar...', en: 'Search...' },
    'topbar.quickActions': { pt: 'Ações rápidas', en: 'Quick actions' },
    'topbar.newClient': { pt: 'Novo cliente', en: 'New client' },
    'topbar.newProject': { pt: 'Novo projeto', en: 'New project' },
    'topbar.newWorkOrder': { pt: 'Nova ordem de serviço', en: 'New work order' },
    'topbar.notifications': { pt: 'Notificações', en: 'Notifications' },
    'topbar.notif1Title': { pt: 'Novo cliente cadastrado', en: 'New client registered' },
    'topbar.notif1Sub': { pt: 'Construtora Vega Ltda.', en: 'Construtora Vega Ltda.' },
    'topbar.notif2Title': { pt: 'Ordem de serviço aberta', en: 'Work order opened' },
    'topbar.notif2Sub': { pt: 'Desenvolvimento de app — Nortis', en: 'App development — Nortis' },
    'topbar.notif3Title': { pt: 'Pagamento recebido', en: 'Payment received' },
    'topbar.notif3Sub': { pt: 'Ordem #0110 — Construtora Vega', en: 'Order #0110 — Construtora Vega' },
    'topbar.viewAllNotif': { pt: 'Ver todas as notificações', en: 'View all notifications' },
    'topbar.myProfile': { pt: 'Meu perfil', en: 'My profile' },
    'topbar.settings': { pt: 'Configurações', en: 'Settings' },
    'topbar.billing': { pt: 'Financeiro', en: 'Billing' },
    'topbar.logout': { pt: 'Sair', en: 'Log out' },
    'topbar.role': { pt: 'Administradora', en: 'Administrator' },

    'dashboard.greeting': { pt: 'Olá, Ana 👋', en: 'Hello, Ana 👋' },
    'dashboard.welcomeText': {
      pt: 'Vocês têm <strong>6 projetos ativos</strong> e 9 ordens de serviço em andamento esta semana.',
      en: 'You have <strong>6 active projects</strong> and 9 work orders in progress this week.'
    },
    'dashboard.viewProjects': { pt: 'Ver projetos', en: 'View projects' },

    'hero.eyebrow': { pt: 'Visão geral', en: 'Overview' },
    'hero.activeClients': { pt: 'Clientes ativos', en: 'Active clients' },
    'hero.activeProjects': { pt: 'Projetos ativos', en: 'Active projects' },
    'hero.openWorkOrders': { pt: 'Ordens em aberto', en: 'Open work orders' },
    'hero.upcomingAppointments': { pt: 'Próximos agendamentos', en: 'Upcoming appointments' },
    'hero.receivable': { pt: 'A receber', en: 'Receivable' },

    'kpi.totalClients': { pt: 'Total de clientes', en: 'Total clients' },
    'kpi.activeProjects': { pt: 'Projetos ativos', en: 'Active projects' },
    'kpi.completedProjects': { pt: 'Projetos concluídos', en: 'Completed projects' },
    'kpi.workOrders': { pt: 'Ordens de serviço', en: 'Work orders' },
    'kpi.vsLastMonth': { pt: 'vs. mês anterior', en: 'vs. last month' },

    'chart.projectsByMonth': { pt: 'Projetos por mês', en: 'Monthly projects' },
    'chart.projectsByMonthSub': { pt: 'Novos vs. concluídos, últimos 7 meses', en: 'New vs. completed, last 7 months' },
    'chart.revenueOverview': { pt: 'Receita (visão interna)', en: 'Revenue (internal view)' },
    'chart.companyGrowth': { pt: 'de crescimento no semestre', en: 'growth this half-year' },
    'chart.projectStatus': { pt: 'Status dos projetos', en: 'Project status' },
    'chart.projectStatusSub': { pt: '18 projetos no total', en: '18 projects total' },
    'chart.receivables': { pt: 'Contas a receber', en: 'Accounts receivable' },
    'chart.receivablesSub': { pt: 'R$ 18.400 em aberto', en: 'R$ 18,400 outstanding' },
    'chart.statusInProgress': { pt: 'Em andamento', en: 'In progress' },
    'chart.statusReview': { pt: 'Em revisão', en: 'In review' },
    'chart.statusCompleted': { pt: 'Concluídos', en: 'Completed' },

    'table.recentWorkOrders': { pt: 'Ordens de serviço recentes', en: 'Recent work orders' },
    'table.order': { pt: 'Ordem', en: 'Order' },
    'table.client': { pt: 'Cliente', en: 'Client' },
    'table.value': { pt: 'Valor', en: 'Value' },
    'table.status': { pt: 'Status', en: 'Status' },
    'table.latestClients': { pt: 'Últimos clientes', en: 'Latest clients' },
    'table.service': { pt: 'Serviço', en: 'Service' },
    'table.viewAll': { pt: 'Ver todos', en: 'View all' },

    'clients.title': { pt: 'Clientes', en: 'Clients' },
    'clients.subtitle': { pt: 'Gerencie todos os clientes cadastrados da Codora Tech.', en: 'Manage all registered clients of Codora Tech.' },
    'clients.search': { pt: 'Buscar', en: 'Search' },
    'clients.searchPlaceholder': { pt: 'Buscar por nome, e-mail ou código...', en: 'Search by name, email or code...' },
    'clients.filterStatus': { pt: 'Status', en: 'Status' },
    'clients.filterCity': { pt: 'Cidade', en: 'City' },
    'clients.filterAll': { pt: 'Todos', en: 'All' },
    'clients.clearFilters': { pt: 'Limpar filtros', en: 'Clear filters' },
    'clients.export': { pt: 'Exportar', en: 'Export' },
    'clients.addClient': { pt: 'Adicionar cliente', en: 'Add client' },
    'clients.listTitle': { pt: 'Clientes cadastrados', en: 'Registered clients' },
    'clients.resultsCount': { pt: '8 clientes encontrados', en: '8 clients found' },
    'clients.colCode': { pt: 'Código', en: 'Code' },
    'clients.colCompany': { pt: 'Empresa', en: 'Company' },
    'clients.colResponsible': { pt: 'Responsável', en: 'Responsible' },
    'clients.colPhone': { pt: 'Telefone', en: 'Phone' },
    'clients.colEmail': { pt: 'E-mail', en: 'Email' },
    'clients.colCity': { pt: 'Cidade', en: 'City' },
    'clients.colStatus': { pt: 'Status', en: 'Status' },
    'clients.colRegistered': { pt: 'Cadastro', en: 'Registered' },
    'clients.colActions': { pt: 'Ações', en: 'Actions' },
    'clients.statusActive': { pt: 'Ativo', en: 'Active' },
    'clients.statusInactive': { pt: 'Inativo', en: 'Inactive' },
    'clients.statusPending': { pt: 'Pendente', en: 'Pending' },
    'clients.previous': { pt: 'Anterior', en: 'Previous' },
    'clients.next': { pt: 'Próxima', en: 'Next' },
    'clients.paginationInfo': { pt: 'Mostrando 1–8 de 24 clientes', en: 'Showing 1–8 of 24 clients' },
    'clients.emptyTitle': { pt: 'Nenhum cliente cadastrado', en: 'No clients registered' },
    'clients.emptyDescription': { pt: 'Assim que você cadastrar um cliente, ele vai aparecer aqui.', en: 'Once you register a client, it will show up here.' },
    'clients.emptyAddFirst': { pt: 'Adicionar primeiro cliente', en: 'Add first client' },
    'clients.addModalTitle': { pt: 'Adicionar cliente', en: 'Add client' },
    'clients.editModalTitle': { pt: 'Editar cliente', en: 'Edit client' },
    'clients.viewModalTitle': { pt: 'Detalhes do cliente', en: 'Client details' },
    'clients.deleteTitle': { pt: 'Excluir cliente?', en: 'Delete client?' },
    'clients.deleteDescription': { pt: 'Essa ação não poderá ser desfeita. O cliente será removido permanentemente da lista.', en: 'This action cannot be undone. The client will be permanently removed from the list.' },
    'clients.cancel': { pt: 'Cancelar', en: 'Cancel' },
    'clients.close': { pt: 'Fechar', en: 'Close' },
    'clients.saveClient': { pt: 'Salvar cliente', en: 'Save client' },
    'clients.saveChanges': { pt: 'Salvar alterações', en: 'Save changes' },
    'clients.confirmDelete': { pt: 'Sim, excluir', en: 'Yes, delete' },
    'clients.fieldCode': { pt: 'Código', en: 'Code' },
    'clients.fieldCompany': { pt: 'Nome da empresa', en: 'Company name' },
    'clients.fieldCompanyPlaceholder': { pt: 'Ex.: Aurea Capital', en: 'E.g.: Aurea Capital' },
    'clients.fieldResponsible': { pt: 'Responsável', en: 'Responsible person' },
    'clients.fieldResponsiblePlaceholder': { pt: 'Nome do contato principal', en: 'Main contact name' },
    'clients.fieldDocument': { pt: 'CPF/CNPJ', en: 'Tax ID' },
    'clients.fieldStatus': { pt: 'Status', en: 'Status' },
    'clients.fieldPhone': { pt: 'Telefone', en: 'Phone' },
    'clients.fieldEmail': { pt: 'E-mail', en: 'Email' },
    'clients.fieldCity': { pt: 'Cidade', en: 'City' },
    'clients.fieldState': { pt: 'Estado', en: 'State' },
    'clients.fieldZip': { pt: 'CEP', en: 'ZIP code' },
    'clients.fieldAddress': { pt: 'Endereço', en: 'Address' },
    'clients.fieldNumber': { pt: 'Número', en: 'Number' },
    'clients.fieldNeighborhood': { pt: 'Bairro', en: 'Neighborhood' },
    'clients.fieldNotes': { pt: 'Observações', en: 'Notes' },
    'clients.fieldNotesPlaceholder': { pt: 'Informações adicionais sobre o cliente...', en: 'Additional information about the client...' },
    'clients.fieldRegistered': { pt: 'Cadastrado em', en: 'Registered on' },

    'services.title': { pt: 'Serviços', en: 'Services' },
    'services.subtitle': { pt: 'Gerencie o catálogo de serviços oferecidos pela Codora Tech.', en: "Manage the catalog of services offered by Codora Tech." },
    'services.search': { pt: 'Buscar', en: 'Search' },
    'services.searchPlaceholder': { pt: 'Buscar por nome ou categoria...', en: 'Search by name or category...' },
    'services.filterCategory': { pt: 'Categoria', en: 'Category' },
    'services.filterStatus': { pt: 'Status', en: 'Status' },
    'services.filterAll': { pt: 'Todas', en: 'All' },
    'services.clearFilters': { pt: 'Limpar filtros', en: 'Clear filters' },
    'services.export': { pt: 'Exportar', en: 'Export' },
    'services.newService': { pt: 'Novo serviço', en: 'New service' },
    'services.listTitle': { pt: 'Catálogo de serviços', en: 'Service catalog' },
    'services.resultsCount': { pt: '7 serviços encontrados', en: '7 services found' },
    'services.colName': { pt: 'Serviço', en: 'Service' },
    'services.colCategory': { pt: 'Categoria', en: 'Category' },
    'services.colDescription': { pt: 'Descrição', en: 'Description' },
    'services.colPrice': { pt: 'Valor de referência', en: 'Reference price' },
    'services.colStatus': { pt: 'Status', en: 'Status' },
    'services.colUpdated': { pt: 'Atualizado em', en: 'Updated on' },
    'services.colActions': { pt: 'Ações', en: 'Actions' },
    'services.categoryDevelopment': { pt: 'Desenvolvimento', en: 'Development' },
    'services.categoryDesign': { pt: 'Design', en: 'Design' },
    'services.categoryConsulting': { pt: 'Consultoria', en: 'Consulting' },
    'services.categoryMaintenance': { pt: 'Manutenção', en: 'Maintenance' },
    'services.statusActive': { pt: 'Ativo', en: 'Active' },
    'services.statusInactive': { pt: 'Inativo', en: 'Inactive' },
    'services.previous': { pt: 'Anterior', en: 'Previous' },
    'services.next': { pt: 'Próxima', en: 'Next' },
    'services.paginationInfo': { pt: 'Mostrando 1–7 de 7 serviços', en: 'Showing 1–7 of 7 services' },
    'services.emptyTitle': { pt: 'Nenhum serviço cadastrado', en: 'No services registered' },
    'services.emptyDescription': { pt: 'Assim que você cadastrar um serviço, ele vai aparecer aqui.', en: 'Once you register a service, it will show up here.' },
    'services.emptyAddFirst': { pt: 'Adicionar primeiro serviço', en: 'Add first service' },
    'services.addModalTitle': { pt: 'Novo serviço', en: 'New service' },
    'services.editModalTitle': { pt: 'Editar serviço', en: 'Edit service' },
    'services.viewModalTitle': { pt: 'Detalhes do serviço', en: 'Service details' },
    'services.deleteTitle': { pt: 'Excluir serviço?', en: 'Delete service?' },
    'services.deleteDescription': { pt: 'Essa ação não poderá ser desfeita. O serviço será removido permanentemente do catálogo.', en: 'This action cannot be undone. The service will be permanently removed from the catalog.' },
    'services.cancel': { pt: 'Cancelar', en: 'Cancel' },
    'services.close': { pt: 'Fechar', en: 'Close' },
    'services.saveService': { pt: 'Salvar serviço', en: 'Save service' },
    'services.saveChanges': { pt: 'Salvar alterações', en: 'Save changes' },
    'services.confirmDelete': { pt: 'Sim, excluir', en: 'Yes, delete' },
    'services.fieldName': { pt: 'Nome do serviço', en: 'Service name' },
    'services.fieldNamePlaceholder': { pt: 'Ex.: Desenvolvimento Web', en: 'E.g.: Web Development' },
    'services.fieldCategory': { pt: 'Categoria', en: 'Category' },
    'services.fieldDescription': { pt: 'Descrição', en: 'Description' },
    'services.fieldDescriptionPlaceholder': { pt: 'Descreva o que está incluso neste serviço...', en: 'Describe what is included in this service...' },
    'services.fieldPrice': { pt: 'Valor de referência', en: 'Reference price' },
    'services.fieldStatus': { pt: 'Status', en: 'Status' },
    'services.fieldCreated': { pt: 'Criado em', en: 'Created on' },

    'activity.title': { pt: 'Atividade recente', en: 'Recent activity' },
    'activity.newClient': { pt: 'Novo cliente adicionado', en: 'New client added' },
    'activity.newClientDesc': { pt: 'Construtora Vega Ltda. entrou para a carteira de clientes.', en: 'Construtora Vega Ltda. joined the client portfolio.' },
    'activity.workOrderOpened': { pt: 'Ordem de serviço aberta', en: 'Work order opened' },
    'activity.workOrderOpenedDesc': { pt: 'Nortis Alimentos abriu a ordem #0111 — desenvolvimento de app.', en: 'Nortis Alimentos opened order #0111 — app development.' },
    'activity.projectReview': { pt: 'Projeto movido para revisão', en: 'Project moved to review' },
    'activity.projectReviewDesc': { pt: '"Portal Financeiro — Aurea" entrou em revisão.', en: '"Aurea Finance Portal" entered review.' },
    'activity.paymentReceived': { pt: 'Pagamento recebido', en: 'Payment received' },
    'activity.paymentReceivedDesc': { pt: 'Construtora Vega Ltda. quitou a ordem #0110.', en: 'Construtora Vega Ltda. settled order #0110.' },

    'schedule.title': { pt: 'Próximos compromissos', en: 'Upcoming schedule' },
    'schedule.item1Title': { pt: 'Reunião de kickoff', en: 'Kickoff meeting' },
    'schedule.item1Sub': { pt: 'Cliente: Nortis Alimentos', en: 'Client: Nortis Alimentos' },
    'schedule.item2Title': { pt: 'Entrega — Sprint 4', en: 'Delivery — Sprint 4' },
    'schedule.item2Sub': { pt: 'Portal Financeiro Aurea', en: 'Aurea Finance Portal' },
    'schedule.item3Title': { pt: 'Apresentação de proposta', en: 'Proposal presentation' },
    'schedule.item3Sub': { pt: 'Construtora Vega Ltda.', en: 'Construtora Vega Ltda.' },

    'footer.rights': { pt: 'Todos os direitos reservados.', en: 'All rights reserved.' }
  };

  function getLang() {
    return localStorage.getItem(STORAGE_KEY) || 'pt';
  }

  function applyLang(lang) {
    document.documentElement.setAttribute('lang', lang === 'pt' ? 'pt-BR' : 'en');

    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      var entry = dict[key];
      if (!entry) return;
      var value = entry[lang] || entry.pt;
      if (el.hasAttribute('data-i18n-html')) {
        el.innerHTML = value;
      } else {
        el.textContent = value;
      }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
      var key = el.getAttribute('data-i18n-placeholder');
      var entry = dict[key];
      if (!entry) return;
      el.setAttribute('placeholder', entry[lang] || entry.pt);
    });

    document.querySelectorAll('.codora-lang-switch [data-lang]').forEach(function (el) {
      el.classList.toggle('active', el.getAttribute('data-lang') === lang);
    });

    var currentLabel = document.getElementById('codoraCurrentLang');
    if (currentLabel) currentLabel.textContent = lang.toUpperCase();
  }

  function setLang(lang) {
    localStorage.setItem(STORAGE_KEY, lang);
    applyLang(lang);
  }

  document.addEventListener('DOMContentLoaded', function () {
    applyLang(getLang());

    document.querySelectorAll('.codora-lang-switch [data-lang]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        setLang(el.getAttribute('data-lang'));
      });
    });
  });

  window.codoraI18n = { setLang: setLang, getLang: getLang };
})();
