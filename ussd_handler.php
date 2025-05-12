<?php
require_once 'config.php';
require_once 'sms_handler.php'; // Include the SMS handler

// USSD handler for Africa's Talking
$sessionId = isset($_POST['sessionId']) ? $_POST['sessionId'] : null; // Check if sessionId is set
$serviceCode = $_POST['serviceCode'];
$phoneNumber = $_POST['phoneNumber'];
$text = $_POST['text'];

// Initialize response
$response = "";

// Split the text into an array
$textArray = explode("*", $text);
$userLevel = count($textArray);

// Main menu
if ($text === "" || ($userLevel == 1 && $textArray[0] === "")) {
    $response = "CON Welcome to Class Representative Voting System\n";
    $response .= "1. Register\n";
    $response .= "2. Vote\n";
    $response .= "3. View Results\n";
}
// Registration
elseif ($textArray[0] == "1") {
    if ($userLevel == 1) {
        $response = "CON Enter your full name:";
    } elseif ($userLevel == 2) {
        $fullName = $textArray[1];
        $response = "CON Enter your student ID:";
    } elseif ($userLevel == 3) {
        $fullName = $textArray[1];
        $studentId = $textArray[2];
        // Check if user already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE phone_number = ?");
        $checkStmt->bind_param("s", $phoneNumber);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $response = "END You are already registered. Please proceed to vote.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (phone_number, full_name, student_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $phoneNumber, $fullName, $studentId);
            if ($stmt->execute()) {
                $response = "END Registration successful! You can now vote.";
                sendRegistrationSMS($phoneNumber, $fullName); // Send SMS after registration
            } else {
                $response = "END Registration failed. Please try again.";
            }
        }
    }
}
// Voting
elseif ($textArray[0] == "2") {
    $stmt = $conn->prepare("SELECT has_voted FROM users WHERE phone_number = ?");
    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && $user['has_voted']) {
        $response = "END You have already voted.";
    } else {
        if ($userLevel == 1) {
            $response = "CON Select a candidate to vote for:\n";
            $stmt = $conn->query("SELECT id, candidate_name FROM votes");
            while ($row = $stmt->fetch_assoc()) {
                $response .= $row['id'] . ". " . $row['candidate_name'] . "\n";
            }
        } elseif ($userLevel == 2) {
            $candidateId = $textArray[1];
            $stmt = $conn->prepare("UPDATE votes SET vote_count = vote_count + 1 WHERE id = ?");
            $stmt->bind_param("i", $candidateId);
            if ($stmt->execute()) {
                $stmt = $conn->prepare("UPDATE users SET has_voted = TRUE WHERE phone_number = ?");
                $stmt->bind_param("s", $phoneNumber);
                $stmt->execute();
                $response = "END Vote recorded successfully!";
                sendVotingSMS($phoneNumber); // Send SMS after voting
            } else {
                $response = "END Voting failed. Please try again.";
            }
        }
    }
}
// View Results
elseif ($textArray[0] == "3") {
    $response = "END Current Voting Results:\n";
    $stmt = $conn->query("SELECT candidate_name, vote_count FROM votes");
    while ($row = $stmt->fetch_assoc()) {
        $response .= $row['candidate_name'] . ": " . $row['vote_count'] . " votes\n";
    }
}

if ($response === "") {
    $response = "END Invalid input or unexpected error. Please try again.";
}

// Output the response
header('Content-type: text/plain');
echo $response;
?>