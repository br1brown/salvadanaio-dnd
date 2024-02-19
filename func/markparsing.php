<?php
$k = 'text';
require_once dirname(__DIR__) . '/FE_utils/funzioni.php';

// Assicurati che l'input sia presente e pulito
$input = isset($_GET[$k]) ? $_GET[$k] : '';
$safeInput = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

// Converte il Markdown in HTML
echo Markdown_HTML($safeInput);
