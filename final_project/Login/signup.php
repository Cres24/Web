<?php
require_once __DIR__ . '/../includes/init.php';

// Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Function to validate email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password strength
function is_strong_password($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special char
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

// Check if the form is submitted
if(isset($_POST["signup"])) {
    try {
        // Sanitize inputs
        $name = sanitize_input((string)($_POST["name"] ?? ''));
        $username = sanitize_input((string)($_POST["username"] ?? ''));
        $email = sanitize_input((string)($_POST["email"] ?? ''));
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        
        // Validate inputs
        if(empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            set_flash('error', 'Please fill in all fields');
            redirect('Login/signup.php');
            exit();
        }
        
        // Validate email
        if (!is_valid_email($email)) {
            set_flash('error', 'Please enter a valid email address');
            redirect('Login/signup.php');
            exit();
        }
        
        // Validate username format
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            set_flash('error', 'Username can only contain letters, numbers, and underscores');
            redirect('Login/signup.php');
            exit();
        }
        
        // Validate password strength
        if (!is_strong_password($password)) {
            set_flash('error', 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character');
            redirect('Login/signup.php');
            exit();
        }
        
        // Check if passwords match
        if ($password !== $confirm_password) {
            set_flash('error', 'Passwords do not match');
            redirect('Login/signup.php');
            exit();
        }
        
        // Check if username or email already exists
        $check_sql = "SELECT username, email FROM users WHERE username = ? OR email = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        
        if (!$check_stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['username'] === $username) {
                set_flash('error', 'Username already exists. Please choose another username.');
            } else {
                set_flash('error', 'Email already exists. Please use another email.');
            }
            redirect('Login/signup.php');
            exit();
        }
        
        mysqli_stmt_close($check_stmt);
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Handle profile picture upload
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (in_array($_FILES['profile_picture']['type'], $allowed_types) && 
                $_FILES['profile_picture']['size'] <= $max_size) {
                
                $upload_dir = __DIR__ . '/../assets/images/profiles/';
                if (!file_exists($upload_dir)) {
                    @mkdir($upload_dir, 0755, true);
                }
                
                if (is_writable($upload_dir)) {
                    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $filename = bin2hex(random_bytes(8)) . '.' . strtolower($file_extension);
                    $target_path = $upload_dir . $filename;
                    
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                        $profile_picture = 'assets/images/profiles/' . $filename;
                    }
                }
            }
        }
        
        // Insert new user using prepared statement
        $insert_sql = "INSERT INTO users (name, username, email, password, profile_picture, created_at) 
                      VALUES (?, ?, ?, ?, ?, NOW())";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        
        if (!$insert_stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($insert_stmt, "sssss", $name, $username, $email, $hashed_password, $profile_picture);
        
        if(mysqli_stmt_execute($insert_stmt)) {
            // Get the new user's ID
            $user_id = mysqli_insert_id($conn);
            
            // Store a single user session object
            $_SESSION['user'] = [
                'id' => $user_id,
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'profile_picture' => $profile_picture,
                'role' => 'user'
            ];
            
            // Set success message
            set_flash('success', 'Sign-up successful! Welcome to ExploreWorld, ' . $name . '!');
            
            // Redirect to dashboard
            redirect('dashboard.php');
            exit();
        } else {
            throw new Exception("Execute failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_close($insert_stmt);
        
    } catch (Exception $e) {
        set_flash('error', 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        redirect('signup.php');
        exit();
    }
}

// Include the HTML form
include("signup.html");
?> 