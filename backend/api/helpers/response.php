<?php
function json_response($data, $status = 200)
{
    // Clear any buffered output to avoid invalid JSON when warnings are printed
    if (ob_get_level()) {
        $buf = ob_get_clean();
        if ($buf && is_array($data) && $status >= 400) {
            // Attach a short debug snippet for troubleshooting (trim to 1000 chars)
            $data['debug_output'] = mb_substr(trim($buf), 0, 1000);
        }
    }

    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
