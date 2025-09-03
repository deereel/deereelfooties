<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

try {
    // $pdo is already available globally from auth/db.php
    echo "<h2>Database Structure Check</h2>";
    echo "<p><strong>Generated on:</strong> " . date('Y-m-d H:i:s') . "</p>";

    // Get all tables in the database
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);

    if (empty($tables)) {
        echo "<p>No tables found in the database.</p>";
    } else {
        echo "<h3>All Tables in Database (" . count($tables) . " tables found):</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li><strong>" . $table[0] . "</strong></li>";
        }
        echo "</ul>";

        // Display structure for each table
        echo "<h3>Table Structures:</h3>";
        foreach ($tables as $table) {
            $tableName = $table[0];
            echo "<h4>{$tableName} Table Structure:</h4>";

            try {
                $stmt = $pdo->query("DESCRIBE `{$tableName}`");
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($columns) {
                    echo "<div style='margin-bottom: 30px;'>";
                    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                    echo "<thead>";
                    echo "<tr style='background-color: #f8f9fa;'>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Field</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Type</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Null</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Key</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Default</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Extra</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    foreach ($columns as $column) {
                        echo "<tr>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Field']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Type']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Null']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Key']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Extra']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<p><em>Columns: " . count($columns) . "</em></p>";
                    echo "</div>";
                } else {
                    echo "<p style='color: #dc3545;'>⚠️ No columns found for table '{$tableName}'</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: #dc3545;'>❌ Error describing table '{$tableName}': " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }

} catch (Exception $e) {
    echo "<p style='color: #dc3545;'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
