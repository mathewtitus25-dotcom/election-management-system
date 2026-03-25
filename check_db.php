<?php

// A simple script to get schema types without wrapping output issues
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=election_system', 'postgres', 'postgres');
$tables = ['users', 'voters', 'panchayats', 'blos', 'candidates', 'votes', 'election_config'];

$result = [];
foreach ($tables as $t) {
    try {
        $stmt = $pdo->query("SELECT column_name, data_type, column_default FROM information_schema.columns WHERE table_schema='public' AND table_name='$t' ORDER BY ordinal_position");
        if ($stmt) {
            foreach ($stmt as $row) {
                // remove long sequences
                $def = $row['column_default'] ? preg_replace("/nextval\('.*?'::regclass\)/", 'auto_increment', $row['column_default']) : 'null';
                $result[$t][$row['column_name']] = [
                    'type' => $row['data_type'],
                    'default' => $def,
                ];
            }
        }
    } catch (Exception $e) {
        $result[$t] = 'ERROR: '.$e->getMessage();
    }
}
echo json_encode($result, JSON_PRETTY_PRINT);
