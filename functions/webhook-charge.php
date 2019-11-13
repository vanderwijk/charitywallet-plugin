<?php if($_SERVER['REQUEST_METHOD'] == 'POST') {
    status_header(200);
    return 'Webhook connected';
}