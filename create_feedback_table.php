<?php
require_once 'auth/db.php';

try {
    // Read and execute the feedback table creation script
    $sql = file_get_contents('migrations/create_feedback_table.sql');

    // Split the SQL file into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    echo "✅ Feedback tables created successfully!\n";
    echo "📋 Tables created:\n";
    echo "   - feedback\n";
    echo "   - feedback_responses\n";
    echo "   - feedback_categories\n";
    echo "📊 Indexes and default data added.\n";

} catch (Exception $e) {
    echo "❌ Error creating feedback tables: " . $e->getMessage() . "\n";
    exit(1);
}
?>
