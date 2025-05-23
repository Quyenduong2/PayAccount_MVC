<?php
require_once './app/Core/Database.php';

class Game
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getBanners()
    {
        $query = "SELECT game_id, game_name, game_image FROM games_banner";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGames($limit = 9, $search = '')
    {
        $query = "SELECT game_id, game_name, game_image FROM games WHERE game_name LIKE :search LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $searchParam = '%' . $search . '%';
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGameById($id)
    {
        $query = "SELECT * FROM games WHERE game_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

      public function getGameBySlug($game_slug) {
        $query = "SELECT * FROM games WHERE game_slug = :game_slug";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':game_slug', $game_slug, PDO::PARAM_STR);
        try {
            $stmt->execute();
            $game = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Get game by slug: $game_slug, found: " . ($game ? 'true' : 'false'));
            return $game;
        } catch (PDOException $e) {
            error_log("Error getting game by slug: $game_slug, error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllGames()
    {
        $query = "SELECT game_id, game_name,game_slug , game_image FROM games";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($game_name, $game_slug, $game_image)
    {
        $query = "INSERT INTO games (game_name,game_slug ,game_image) VALUES (:game_name, :game_slug , :game_image)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':game_name', $game_name);
        $stmt->bindParam(':game_image', $game_image);
        $stmt->bindParam(':game_slug', $game_slug);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM games WHERE game_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function update($id, $game_name, $game_image)
    {
        $query = "UPDATE games SET game_name = :game_name, game_image = :game_image WHERE game_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':game_name', $game_name);
        $stmt->bindParam(':game_image', $game_image);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM games WHERE game_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
