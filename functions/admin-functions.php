<?php
/**
 * Admin Pages Function Group
 * Note: Categorize Functions
 */

    require_once 'database.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
// Confessions

function saveConfession($data) {
    $pdo = getDB();
    
    $sql = "INSERT INTO tbl_confessions (Confess_Name, Section, Custom_Clue,  Message, Recipient_Name, Tries, Authenticity) 
            VALUES (:Confess_Name, :Section, :Custom_Clue, :Message, :Recipient_Name, :Tries, :Authenticity)";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind the mapped data
    return $stmt->execute([
        ':Confess_Name'   => $data['Confess_Name'],
        ':Section'        => $data['Section'],
        ':Custom_Clue'    => $data['Custom_Clue'],   
        ':Message'        => $data['Message'],
        ':Recipient_Name' => $data['Recipient_Name'],
        ':Tries'          => 0,     
        ':Authenticity'   => 0
    ]);
}


function getConfessions(){
    $pdo = getDB();

    $sql="SELECT id, Confess_Name, Section, Custom_Clue, Message, Recipient_Name FROM tbl_confessions";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $data = $stmt->fetchAll();

    return $data;
}


// Add to heart
function addContestant($data){
    $pdo = getDB();

    $sql = "INSERT INTO tbl_contestant (Contestant_Name, Section, Vote_Count) 
            VALUES (:Contestant_Name, :Section, :Vote_Count)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':Contestant_Name'   => $data['Contestant_Name'],
        ':Section'           => $data['Section'],
        ':Vote_Count'        => $data['Vote_Count']
    ]);
}


// upload-document
function processExcelUpload($file) {
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['type' => 'error', 'message' => "File upload error: " . $file['error']];
    }
    if (!in_array($file['type'], ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
        return ['type' => 'error', 'message' => "Invalid file type. Please upload an Excel file (.xls or .xlsx)."];
    }
    
    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($file['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        if (count($rows) < 2) {
            return ['type' => 'error', 'message' => "No data found in the file."];
        }
        
        $pdo = getDB();
        $inserted = 0;
        $skipped = 0;
        
        // Prepare insert statement (exclude ConfessionID and created_at as auto-increment/default)
        $stmt = $pdo->prepare("
            INSERT INTO tbl_confessions (Confess_Name, Section, Custom_Clue, Message, Recipient_Name, Tries, Authenticity)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        // Skip header row (row 0), process from row 1
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Map columns (assuming order: A: TimeStamp (ignore), B: Data Privacy (ignore), C: First Name, D: Last Name, E: Section, F: Message, G: Recipient_Name, H: Custom_Clue)
            $firstName = $row[2] ?? ''; // First Name
            $lastName = $row[3] ?? ''; // Last Name
            $section = $row[4] ?? ''; // Section
            $customClue = $row[5] ?? ''; // Custom_Clue
            $messageText = $row[6] ?? ''; // Message
            $recipientName = $row[7] ?? ''; // Recipient_Name
            
            // Skip if required fields are missing (ignore TimeStamp and Custom_Clue)
            if (empty($firstName) || empty($lastName) || empty($section) || empty($messageText) || empty($recipientName)) {
                $skipped++;
                continue;
            }
            
            // Concatenate Confess_Name
            $confessName = trim($firstName . ' ' . $lastName);
            
            // Execute insert
            $stmt->execute([
                $confessName,
                $section,
                $customClue, // Now from spreadsheet
                $messageText,
                $recipientName,
                0, // Tries (default to 0)
                1, // Authenticity
            ]);
            
            $inserted++;
        }
        
        return ['type' => 'success', 'message' => "Upload completed. Inserted: $inserted, Skipped: $skipped."];
    } catch (Exception $e) {
        return ['type' => 'error', 'message' => "Error processing file: " . $e->getMessage()];
    }
}

// Ticketing

function getTotalSales() {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM tbl_confessionorders");
        $result = $stmt->fetch();
        return $result['total'] * 15; // Multiply by 15
    } catch (Exception $e) {
        return 0; // Return 0 on error
    }
}

function getAvgTries() {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT AVG(tries) as avg_tries FROM tbl_confessions");
        $result = $stmt->fetch();
        return round($result['avg_tries'], 2); // Round to 2 decimal places
    } catch (Exception $e) {
        return 0; // Return 0 on error
    }
}

function getConfessionOrders() {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT ORDER_ID, Ticket_Number, Section FROM tbl_confessionorders");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function createConfessionOrder($ticketNumber, $section) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO tbl_confessionorders (Ticket_Number, Section) VALUES (?, ?)");
        $stmt->execute([$ticketNumber, $section]);
        return ['type' => 'success', 'message' => 'Order created successfully.'];
    } catch (Exception $e) {
        return ['type' => 'error', 'message' => $e->getMessage()];
    }
}

function updateConfessionOrder($orderId, $ticketNumber, $section) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("UPDATE tbl_confessionorders SET Ticket_Number = ?, Section = ? WHERE ORDER_ID = ?");
        $stmt->execute([$ticketNumber, $section, $orderId]);
        return ['type' => 'success', 'message' => 'Order updated successfully.'];
    } catch (Exception $e) {
        return ['type' => 'error', 'message' => $e->getMessage()];
    }
}

function deleteConfessionOrder($orderId) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM tbl_confessionorders WHERE ORDER_ID = ?");
        $stmt->execute([$orderId]);
        return ['type' => 'success', 'message' => 'Order deleted successfully.'];
    } catch (Exception $e) {
        return ['type' => 'error', 'message' => $e->getMessage()];
    }
}
?>

