<?php
    // Include config file
    require_once "./connection.php";

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productID = $_POST['productID'];
        $userID = $_POST['userID']; // Use session userID for security
        $fordate = date('Y-m-d');
        $status = "pending";
        
        // Validate the vehicleID and userID exist in their respective tables
        $stmt = $conn->prepare('SELECT COUNT(*) FROM product WHERE productId = ?');
        $stmt->bind_param('i', $productID);
        $stmt->execute();
        $stmt->bind_result($vehicleCount);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE userId = ?');
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->bind_result($userCount);
        $stmt->fetch();
        $stmt->close();

        if ($vehicleCount > 0 && $userCount > 0) {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare('INSERT INTO orders (productId, userId, date, status) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('iiss', $productID, $userID, $fordate, $status);
      

            if ($stmt->execute()) {
                echo "Order Placement Successfull";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement and the connection
            $stmt->close();
            $conn->close();
        } else {
            echo "Invalid vehicle ID or user ID.";
        }
    } else {
        echo "Invalid request.";
    }
?>
