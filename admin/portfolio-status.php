<?php
require_once dirname(__DIR__) . '/app/auth.php';
require_once dirname(__DIR__) . '/app/editor-actions.php';
$user = require_admin();
require_post();
header('Content-Type: application/json; charset=UTF-8');
try {
    echo json_encode(save_portfolio_status(db(), $user) + ['message' => 'Visibilidade do projeto salva.'], JSON_THROW_ON_ERROR);
} catch (InvalidArgumentException $error) {
    http_response_code(422);
    echo json_encode(['message' => $error->getMessage()]);
} catch (Throwable $error) {
    error_log('Codora portfolio status: ' . $error->getMessage());
    http_response_code(503);
    echo json_encode(['message' => 'Não foi possível salvar. Tente novamente.']);
}
