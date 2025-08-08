<?php
include("database.inc");

function compressImage($source, $destination, $maxFileSize = 2000000) {
    // Get image info
    $imgInfo = getimagesize($source);
    if (!$imgInfo) {
        return false; // Not a valid image
    }
    
    $mime = $imgInfo['mime'];
    
    // Create a new image from file
    switch($mime){
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            // Preserve transparency
            imagealphablending($image, false);
            imagesavealpha($image, true);
            break;
        default:
            return false; // Only process JPEG and PNG
    }
    
    // Initial quality settings
    $quality = 75;
    $success = false;
    
    // Try to compress with decreasing quality until under max size
    for ($i = 0; $i < 5; $i++) {
        if ($mime == 'image/jpeg') {
            $success = imagejpeg($image, $destination, $quality);
        } elseif ($mime == 'image/png') {
            // PNG quality is 0-9 (higher means more compression)
            $pngQuality = 9 - round($quality / 11.11); // Map 0-100 to 9-0
            $success = imagepng($image, $destination, $pngQuality);
        }
        
        if (!$success) {
            break;
        }
        
        $currentSize = filesize($destination);
        if ($currentSize <= $maxFileSize) {
            break;
        }
        
        $quality -= 15; // Reduce quality for next attempt
        if ($quality < 10) $quality = 10;
    }
    
    imagedestroy($image);
    return $success && filesize($destination) <= $maxFileSize;
}

if(isset($_POST['submitButton'])) {
    try {
        // Get form data
        $name = $_POST['fName'];
        $destination = $_POST['destination'];
        $alamat = $_POST['alamat'];
        $noKenderaan = $_POST['noKenderaan'];
        
        // File upload handling
        $targetDir = "pictureFiles/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . uniqid();
        /*
        $originalFileName = basename($_FILES['gambarKenderaan']['name']);
        $fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        $fileName = uniqid() . '_' . $originalFileName;
        $targetFile = $targetDir . $fileName;*/
        
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $createdDate = date("Y-m-d H:i:s");
        
        /*
        // Validate file upload
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        if(!in_array($fileType, $allowedTypes)) {
            throw new Exception("Only JPG, JPEG, PNG, PDF & GIF files are allowed.");
        }

        // Handle image upload differently from PDF
        $tempFile = $_FILES['gambarKenderaan']['tmp_name'];
        
        if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
            // Compress image to under 100KB
            if (!compressImage($tempFile, $targetFile)) {
                throw new Exception("Failed to compress image or image is still too large after compression.");
            }
        } else {
            // For PDF and GIF, just move the file (no compression)
            if (!move_uploaded_file($tempFile, $targetFile)) {
                throw new Exception("Failed to upload file.");
            }
        }
        */

        // Prepare SQL with PDO
        $sql = "INSERT INTO registeredlist (name, destination, alamat, noKenderaan, gambarDir, createdDate) 
                VALUES (:name, :destination, :alamat, :noKenderaan, :gambarDir, :createdDate)";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':destination', $destination);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':noKenderaan', $noKenderaan);
        $stmt->bindParam(':gambarDir', $targetFile);
        $stmt->bindParam(':createdDate', $createdDate);
        
        // Execute the statement
        $stmt->execute();

        include 'openGate.php';
        
        // Success message
        $success = "Registration successful!";
        header("Location: register.php?success=" . urlencode($success));
    } catch (Exception $e) {
        // Handle errors
        $error = "Error: " . $e->getMessage();
        
        // Clean up if there was an error after file upload but before DB insertion
        if (isset($targetFile) && file_exists($targetFile)) {
            unlink($targetFile);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register form</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h1>Register Form</h1>
        <?php if(isset($_GET['success'])): ?>
            <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form id="myForm" action="register.php" method="post" enctype="multipart/form-data">
            <label>Nombor Kenderaan</label>
            <input autocomplete="off" id="noKenderaan" list="suggestionBox" name="noKenderaan" type="text" required>
            <datalist id="suggestionBox"></datalist>
            <label>Nama Pelawat</label>
            <input autocomplete="off" id="nameBox" name="fName" type="text" required>
            
            <label>Nama Penghuni</label>
            <input autocomplete="off" id="destinationBox" name="destination" type="text" required>
            
            <label>No. Alamat Penuh Kediaman Penghuni</label>
            <input autocomplete="off" id="alamatBox" name="alamat" type="text" required>
            <!-- 
            <label>Gambar Kad Pengenalan</label>
            <input name="gambarKenderaan" type="file" required accept="image/*,.pdf">
            <small>Note: Images will be automatically compressed to under 100KB</small> -->
            
            <button name="submitButton" type="submit">Submit</button>
        </form>
    </div>
    <script>
    setTimeout(() => {
        const msg = document.querySelector('.success-message');
        if (msg) msg.style.display = 'none';
    }, 5000); // Hide after 5 seconds

    let suggestionBox = document.getElementById("suggestionBox");
    let noKenderaan = document.getElementById("noKenderaan");
    let nameBox = document.getElementById("nameBox");
    let destinationBox = document.getElementById("destinationBox");
    let alamatBox = document.getElementById("alamatBox");
    let myForm=document.getElementById("myForm");
    noKenderaan.addEventListener("input", function () {
        
        let query = this.value;
        if (query.length === 0) {
            suggestionBox.innerHTML = "";
            myForm.reset();
            return;
        }

        
        fetch("suggestions.php?q=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionBox.innerHTML = "";
                data.forEach(eachdata => {
                    let option = document.createElement("option");
                    option.value = eachdata.noKenderaan;
                    suggestionBox.appendChild(option);
                });
            });

        
        fetch("fillForms.php?name=" + encodeURIComponent(query))
            .then(response2 => response2.json())
            .then(data2 => {
                if (data2 && data2.name) {
                    nameBox.value = data2.name;
                    destinationBox.value = data2.destination;
                    alamatBox.value = data2.alamat;
                }
            });
    });

    </script>
</body>
</html>