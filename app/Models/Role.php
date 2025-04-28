<?php

namespace App\Models;

use App\Core\Database;

class Role {
    private string $id;
    private ?string $name;

    public function __construct($id = '', $name = null) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    // Méthodes CRUD
    public function save(): ?string {
        $db = Database::getInstance();

        // Génération d'un ID si nouveau rôle
        if (empty($this->id)) {
            $this->id = uniqid('role_');
        }

        // Vérifier si le rôle existe
        $exists = $db->single("SELECT Id FROM roles WHERE Id = :id", [':id' => $this->id]);

        if ($exists) {
            // Update
            $sql = "UPDATE roles SET Name = :name WHERE Id = :id";
        } else {
            // Insert
            $sql = "INSERT INTO roles (Id, Name) VALUES (:id, :name)";
        }

        $db->prepare($sql);
        $db->bind(':id', $this->id);
        $db->bind(':name', $this->name);

        if ($db->execute()) {
            return $this->id;
        }

        return null;
    }

    public static function findById($id): ?Role {
        $db = Database::getInstance();
        $sql = "SELECT * FROM roles WHERE Id = :id";
        $result = $db->single($sql, [':id' => $id]);

        if ($result) {
            return new Role(
                $result['Id'],
                $result['Name']
            );
        }

        return null;
    }

    public static function findByName($name): ?Role {
        $db = Database::getInstance();
        $sql = "SELECT * FROM roles WHERE Name = :name";
        $result = $db->single($sql, [':name' => $name]);

        if ($result) {
            return new Role(
                $result['Id'],
                $result['Name']
            );
        }

        return null;
    }

    public static function getAll(): array {
        $db = Database::getInstance();
        $roles = [];

        $results = $db->query("SELECT * FROM roles");

        foreach ($results as $row) {
            $roles[] = new Role(
                $row['Id'],
                $row['Name']
            );
        }

        return $roles;
    }

    public function delete(): bool {
        $db = Database::getInstance();
        $sql = "DELETE FROM roles WHERE Id = :id";
        $db->prepare($sql);
        $db->bind(':id', $this->id);

        return $db->execute();
    }
}