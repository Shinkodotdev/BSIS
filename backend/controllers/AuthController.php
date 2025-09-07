<?php
date_default_timezone_set('Asia/Manila');
require_once __DIR__ . "/../config/db.php";
// If using Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }
    // Add this getter
    public function getPDO()
    {
        return $this->pdo;
    }
    // ✅ Signup function 
    public function signup($data)
    {
        $f_name   = strtoupper(trim($_POST['firstName']));
        $m_name   = strtoupper(trim($_POST['middleName']));
        $l_name   = strtoupper(trim($_POST['lastName']));
        $ext_name = strtoupper(trim($_POST['extensionName']));
        $email    = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $phone    = trim($data['phone']);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        // ✅ Address fields
        $house_no = strtoupper(trim($_POST['house_no']));
        $purok    = strtoupper(trim($_POST['purok']));

        // ✅ Constant values
        $barangay    = "POBLACION SUR";
        $municipality = "TALAVERA";
        $province     = "NUEVA ECIJA";

        $this->pdo->beginTransaction();

        try {
            // 1. Insert into USERS
            $stmt = $this->pdo->prepare("INSERT INTO users (email, password, status) VALUES (?, ?, 0)");
            $stmt->execute([$email, $password]);
            $user_id = $this->pdo->lastInsertId();

            // 2. Insert into USER DETAILS
            $stmt = $this->pdo->prepare("INSERT INTO user_details 
            (user_id, f_name, m_name, l_name, ext_name, contact_no) 
            VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $f_name, $m_name, $l_name, $ext_name, $phone]);

            // 3. Insert into USER BIRTHDATES
            $stmt = $this->pdo->prepare("INSERT INTO user_birthdates (user_id, birth_date) VALUES (?, ?)");
            $stmt->execute([$user_id, null]);

            // ✅ 4. Insert into USER RESIDENCY
            $stmt = $this->pdo->prepare("INSERT INTO user_residency 
            (user_id, house_no, purok, barangay, municipality, province) 
            VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $house_no, $purok, $barangay, $municipality, $province]);

            // 5. Generate verification token
            $token = bin2hex(random_bytes(16));
            $expires_at = date("Y-m-d H:i:s", strtotime("+1 day"));

            $stmt = $this->pdo->prepare("INSERT INTO verifications (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $token, $expires_at]);

            $this->pdo->commit();

            // 6. Send verification email
            require __DIR__ . '/../../vendor/autoload.php';
            $mail = new PHPMailer(true);

            $verifyLink = "http://localhost/BARANGAY_INFORMATION_SYSTEM/backend/actions/verify.php?token=" . $token;

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'poblacionsur648@gmail.com';
            $mail->Password   = 'rutp czsu frkt vrhz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('poblacionsur648@gmail.com', 'Barangay Poblacion Sur System');
            $mail->addAddress($email, "$f_name $l_name");

            $mail->isHTML(true);
            $mail->Subject = "Verify Your Account";
            $mail->AddEmbeddedImage(__DIR__ . "/../../frontend/assets/images/Logo.jpg", "logo", "Logo.webp");
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; line-height:1.6; color:#333;'>
                <div style='text-align:center;'>
                    <img src='cid:logo' alt='Barangay Logo' style='width:100px; height:100px; border-radius:50%;'>
                </div>
                <h2 style='color:#2563eb; text-align:center;'>Barangay Information System</h2>
                <p>Hello <b>{$f_name} {$m_name} {$l_name} {$ext_name}</b>,</p>
                <p>Thank you for signing up! Please verify your email address by clicking below:</p>
                <div style='text-align:center; margin:20px 0;'>
                    <a href='$verifyLink' style='background:#2563eb; color:#fff; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:bold;'>
                    Verify My Account
                    </a>
                </div>
                <p>If the button above doesn’t work, copy and paste this link:</p>
                <p style='word-break: break-all;'><a href='$verifyLink'>$verifyLink</a></p>
                <p style='color:#666; font-size:14px;'>⚠ This link will expire in <b>24 hours</b>.</p>
            </div>
        ";

            $mail->AltBody = "Hello $f_name, verify your account here: $verifyLink";
            $mail->send();

            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Signup failed: " . $e->getMessage());
        }
    }


    // ✅ Login function with verification + approval + role check
    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("
        SELECT u.*, d.f_name, d.l_name, d.m_name, d.ext_name
        FROM users u
        LEFT JOIN user_details d ON u.user_id = d.user_id
        WHERE u.email = ?
    ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Email not found. Please sign up first.");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Incorrect password. Please try again.");
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);

        // Save essential data in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['status']  = $user['status'];
        $_SESSION['name']    = trim(
            $user['f_name'] . " " .
                ($user['m_name'] ? $user['m_name'] . " " : "") .
                $user['l_name'] . " " .
                ($user['ext_name'] ?? "")
        );

        // Generate a unique session token for this login
        $session_token = bin2hex(random_bytes(32));
        $_SESSION['session_token'] = $session_token;

        // Save token in verifications table
        $stmt = $this->pdo->prepare("
        UPDATE verifications
        SET session_token = ?
        WHERE user_id = ?
    ");
        $stmt->execute([$session_token, $user['user_id']]);

        // Determine redirect URL
        $redirectUrl = '';
        switch ($user['status']) {
            case "Pending":
                $redirectUrl = "../../frontend/pages/status/pending.php";
                break;
            case "Rejected":
                $redirectUrl = "../../frontend/pages/status/rejected.php";
                break;
            case "Verified":
                $redirectUrl = "../../frontend/pages/user/dashboard.php";
                break;
            case "Approved":
                switch ($user['role']) {
                    case "Admin":
                        $redirectUrl = "../../frontend/pages/admin/dashboard.php";
                        break;
                    case "Official":
                        $redirectUrl = "../../frontend/pages/official/dashboard.php";
                        break;
                    case "Resident":
                        $redirectUrl = "../../frontend/pages/resident/dashboard.php";
                        break;
                    default:
                        throw new Exception("Invalid role assigned. Contact administrator.");
                }
                break;
            default:
                throw new Exception("Invalid account status. Contact administrator.");
        }

        // ✅ Set success message and redirect URL in session
        $_SESSION['success'] = "Login successful! Welcome back, " . $_SESSION['name'];
        $_SESSION['redirect_after_success'] = $redirectUrl;

        // Redirect to a temporary page that triggers SweetAlert
        header("Location: ../../frontend/pages/login_redirect.php");
        exit;
    }


    // ✅ Forgot Password
    public function forgotPassword($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check if user exists
        $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) throw new Exception("Email not found.");

        $user_id = $user['user_id'];

        // Check for existing password reset
        $stmt = $this->pdo->prepare("
        SELECT * FROM password_resets
        WHERE user_id = ?
        ORDER BY last_sent_at DESC
        LIMIT 1
    ");
        $stmt->execute([$user_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        $token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $now = new DateTime();

        if ($existing) {
            // Use existing last_sent_at or default to epoch
            $lastSent = !empty($existing['last_sent_at']) ? new DateTime($existing['last_sent_at']) : new DateTime('1970-01-01');

            $now = new DateTime();
            $diffSeconds = $now->getTimestamp() - $lastSent->getTimestamp();
            $diffMinutes = $diffSeconds / 60;

            // 30-minute cooldown
            $cooldown = 30;
            if ($diffMinutes < $cooldown) {
                $wait = ceil($cooldown - $diffMinutes);
                throw new Exception("Please wait $wait minutes before requesting a new reset link.");
            }

            // Update existing row with new token and timestamps
            $stmt = $this->pdo->prepare("
        UPDATE password_resets
        SET password_reset_token = ?, expires_at = ?, last_sent_at = NOW()
        WHERE user_id = ?
    ");
            $stmt->execute([$token, $expires_at, $user_id]);
        } else {
            // Insert new row
            $stmt = $this->pdo->prepare("
            INSERT INTO password_resets (user_id, password_reset_token, expires_at, last_sent_at)
            VALUES (?, ?, ?, NOW())
        ");
            $stmt->execute([$user_id, $token, $expires_at]);
        }

        // Send reset email
        require __DIR__ . '/../../vendor/autoload.php';
        $mail = new PHPMailer(true);

        $resetLink = "http://localhost/BARANGAY_INFORMATION_SYSTEM/frontend/pages/reset_password.php?token=" . $token;

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'poblacionsur648@gmail.com';
            $mail->Password   = 'rutp czsu frkt vrhz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('poblacionsur648@gmail.com', 'Barangay Poblacion Sur System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->AddEmbeddedImage(__DIR__ . "/../../frontend/assets/images/Logo.jpg", "logo", "Logo.webp");
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; line-height:1.6; color:#333;'>
                <div style='text-align:center;'>
                    <img src='cid:logo' alt='Barangay Logo' style='width:100px; height:100px; border-radius:50%;'>
                </div>
                <h2 style='color:#2563eb; text-align:center;'>Barangay Information System</h2>
                <p>Hello <b>{$email}</b>,</p>
                <p>You requested a password reset. Please click the button below to reset your password:</p>
                <div style='text-align:center; margin:20px 0;'>
                    <a href='$resetLink' style='background:#2563eb; color:#fff; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:bold;'>
                        Reset My Password
                    </a>
                </div>
                <p>If the button above doesn’t work, copy and paste this link into your browser:</p>
                <p style='word-break: break-all;'><a href='$resetLink'>$resetLink</a></p>
                <p style='color:#666; font-size:14px;'>⚠ This link will expire in <b>1 hour</b>.</p>
                <p style='color:#999; font-size:12px; text-align:center; margin-top:20px;'>If you did not request this, please ignore this email.</p>
            </div>
        ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to send reset email. Please try again.");
        }
    }

    // ✅ Reset Password
    public function resetPassword($token, $password, $confirm)
    {
        if ($password !== $confirm) {
            throw new Exception("Passwords do not match.");
        }

        // Validate token
        $stmt = $this->pdo->prepare("
        SELECT * FROM password_resets 
        WHERE password_reset_token = ? AND password_reset_token IS NOT NULL AND expires_at > NOW()
        LIMIT 1
    ");
        $stmt->execute([$token]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reset) {
            throw new Exception("Invalid or expired reset token.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->pdo->beginTransaction();
        try {
            // Update user's password
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->execute([$hashedPassword, $reset['user_id']]);

            // Clear token, but keep last_sent_at for cooldown
            $stmt = $this->pdo->prepare("
            UPDATE password_resets 
            SET password_reset_token = NULL, expires_at = NULL, created_at = NULL
            WHERE password_reset_id = ?
        ");
            $stmt->execute([$reset['password_reset_id']]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to reset password. Please try again.");
        }
    }

    // ✅ Check if token is valid (used in reset_password.php)
    public function isTokenValid($token)
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM password_resets 
        WHERE password_reset_token = ? AND password_reset_token IS NOT NULL AND expires_at > NOW()
        LIMIT 1
    ");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
