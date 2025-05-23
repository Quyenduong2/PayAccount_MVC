<?php
require_once './app/Core/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($username, $email, $full_name, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (username, email, password, full_name, role, is_kyc_verified) 
                  VALUES (:username, :email, :password, :full_name, 'user', 0)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':full_name', $full_name);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Register failed: " . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            error_log("Login failed: Email $email not found");
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            error_log("Login failed: Password does not match for email $email");
            return false;
        }

        $token = bin2hex(random_bytes(16));
        $query = "UPDATE users SET auth_token = :token WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':user_id', $user['user_id']);
        if ($stmt->execute()) {
            $user['auth_token'] = $token;
            error_log("Login successful for email $email");
            return $user;
        } else {
            error_log("Failed to update auth_token for email $email");
            return false;
        }
    }

    public function getKycStatus($user_id)
    {
        $query = "SELECT status FROM kyc_requests WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['status'] : null;
    }


    public function getUserByToken($token)
    {
        $query = "SELECT * FROM users WHERE auth_token = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Update password failed: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers()
    {
        $query = "SELECT user_id, username, email, full_name, role, is_kyc_verified FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($user_id, $username, $email, $role, $full_name = null)
    {
        $query = "UPDATE users SET username = :username, email = :email, role = :role, full_name = :full_name WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            error_log("Update user: user_id=$user_id, username=$username, success=" . ($result ? 'true' : 'false'));
            return $result;
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($user_id)
    {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            error_log("Delete user: user_id=$user_id, success=" . ($result ? 'true' : 'false'));
            return $result;
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return false;
        }
    }

    public function submitKycRequest($user_id, $full_name, $id_number, $id_image)
    {
        $query = "INSERT INTO kyc_requests (user_id, full_name, id_number, id_image, status) VALUES (:user_id, :full_name, :id_number, :id_image, 'pending')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':id_number', $id_number);
        $stmt->bindParam(':id_image', $id_image);
        return $stmt->execute();
    }

    public function getKycRequests()
    {
        $query = "SELECT * FROM kyc_requests";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateKycStatus($request_id, $status, $admin_id)
    {
        $this->db->beginTransaction();
        try {
            // Cập nhật trạng thái yêu cầu KYC
            $query = "UPDATE kyc_requests SET status = :status WHERE request_id = :request_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
            $stmt->execute();

            // Nếu phê duyệt, cập nhật is_kyc_verified trong users
            if ($status === 'approved') {
                $query = "UPDATE users SET is_kyc_verified = 1 WHERE user_id = (SELECT user_id FROM kyc_requests WHERE request_id = :request_id)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();
            error_log("updateKycStatus: request_id=$request_id, status=$status, admin_id=$admin_id");
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("updateKycStatus error: " . $e->getMessage());
            return false;
        }
    }

    public function getUserById($user_id)
    {
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
