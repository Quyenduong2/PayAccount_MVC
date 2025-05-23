<?php
require_once './app/Core/Database.php';

class Account
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT a.account_id, a.user_id, a.game_id, a.account_title, a.price, a.description, a.status, g.game_name 
                  FROM accounts a 
                  JOIN games g ON a.game_id = g.game_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id)
    {
        $query = "SELECT a.account_id, a.user_id, a.game_id, a.account_title, a.price, a.description, a.status, g.game_name 
                  FROM accounts a 
                  JOIN games g ON a.game_id = g.game_id 
                  WHERE a.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM accounts WHERE account_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAccountImages($account_id)
    {
        $query = "SELECT image_path FROM account_images WHERE account_id = :account_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting images for account_id: $account_id, error: " . $e->getMessage());
            return [];
        }
    }

    public function getAccountImagesFromUploads($user_id)
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "$basePath/public/uploads/accounts/$user_id/";
        $baseUrl = "http://localhost$basePath/public/uploads/accounts/$user_id/";
        $image_urls = [];
        if (is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) && is_file($uploadDir . $file)) {
                    $image_urls[] = $baseUrl . $file;
                }
            }
        } else {
            error_log("Account images directory not found for user_id: $user_id");
        }
        return $image_urls;
    }

    public function getAccountsByGameSlug($game_slug)
    {
        $query = "SELECT a.account_id, a.account_title, a.price, a.description, a.facebook_link, a.zalo_link 
                  FROM accounts a 
                  JOIN games g ON a.game_id = g.game_id 
                  WHERE g.game_slug = :game_slug AND a.status = 'approved'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':game_slug', $game_slug, PDO::PARAM_STR);
        try {
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Get accounts by game_slug: $game_slug, found: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting accounts by game_slug: $game_slug, error: " . $e->getMessage());
            return [];
        }
    }

    public function create($user_id, $game_id, $account_title, $price, $description, $image_urls = []) {
        $query = "INSERT INTO accounts (user_id, game_id, account_title, price, description) 
                  VALUES (:user_id, :game_id, :account_title, :price, :description)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->bindParam(':account_title', $account_title);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description);

        try {
            $stmt->execute();
            $account_id = $this->db->lastInsertId();

            // Lưu ảnh vào account_images
            if (!empty($image_urls)) {
                foreach ($image_urls as $image_url) {
                    $query = "INSERT INTO account_images (account_id, image_path) VALUES (:account_id, :image_path)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
                    $stmt->bindParam(':image_path', $image_url);
                    $stmt->execute();
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error creating account: " . $e->getMessage());
            return false;
        }
    }
    public function update($account_id, $account_title, $price, $description, $status)
    {
        $validStatuses = ['pending', 'approved', 'sold', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            error_log("Invalid status value: $status");
            return false;
        }

        $query = "UPDATE accounts SET account_title = :account_title, price = :price, description = :description, status = :status WHERE account_id = :account_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':account_title', $account_title);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            error_log("Update account: account_id=$account_id, status=$status, success=" . ($result ? 'true' : 'false'));
            return $result;
        } catch (PDOException $e) {
            error_log("Update account error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($account_id)
    {
        $query = "DELETE FROM accounts WHERE account_id = :account_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            error_log("Delete account: account_id=$account_id, success=" . ($result ? 'true' : 'false'));
            return $result;
        } catch (PDOException $e) {
            error_log("Delete account error: " . $e->getMessage());
            return false;
        }
    }
}
