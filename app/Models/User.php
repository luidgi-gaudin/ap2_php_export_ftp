<?php

namespace App\Models;

use App\Core\Database;
use DateTime;

class User {
    private string $id = ''; // ID initialisé avec une chaîne vide par défaut
    private string $nom;
    private string $prenom;
    private DateTime $dateNaissance;
    private string $username;
    private string $email;
    private string $role_id;
    private bool $emailConfirmed;
    private string $passwordHash;
    private bool $twoFactorEnabled;
    private int $accessFailedCount;

    public function __construct($id = '', $nom = '', $prenom = '', $dateNaissance = null, $username = '', $email = '', $role_id = '',$emailConfirmed = false, $passwordHash = '', $twoFactorEnabled = false, $accessFailedCount = 0) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance ?? new DateTime();
        $this->username = $username;
        $this->email = $email;
        $this->role_id = $role_id;
        $this->emailConfirmed = $emailConfirmed;
        $this->passwordHash = $passwordHash;
        $this->twoFactorEnabled = $twoFactorEnabled;
        $this->accessFailedCount = $accessFailedCount;
    }

    // Getters et Setters
    public function getId(): string {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom($nom_m): void {
        $this->nom = $nom_m;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom($prenom_m): void {
        $this->prenom = $prenom_m;
    }

    public function getDateNaissance(): DateTime {
        return $this->dateNaissance;
    }

    public function setDateNaissance($date_naissance_m): void {
        $this->dateNaissance = $date_naissance_m;
    }

    public function getUserName(): ?string {
        return $this->username;
    }

    public function setUserName($username): void {
        $this->username = $username;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function getRoleId(): string {
        return $this->role_id;
    }
    public function setRoleId($role_id): void {
        $this->role_id = $role_id;
    }

    public function isEmailConfirmed(): bool {
        return $this->emailConfirmed;
    }

    public function setEmailConfirmed($emailConfirmed): void {
        $this->emailConfirmed = $emailConfirmed;
    }

    public function getPasswordHash(): ?string {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash): void {
        $this->passwordHash = $passwordHash;
    }

    public function isTwoFactorEnabled(): bool {
        return $this->twoFactorEnabled;
    }

    public function setTwoFactorEnabled($twoFactorEnabled): void {
        $this->twoFactorEnabled = $twoFactorEnabled;
    }

    public function getAccessFailedCount(): int {
        return $this->accessFailedCount;
    }

    public function setAccessFailedCount($accessFailedCount): void {
        $this->accessFailedCount = $accessFailedCount;
    }

    public function save(): ?string {
        $db = Database::getInstance();

        // Génération d'un ID si nouveau user
        if (empty($this->id)) {
            $this->id = uniqid('user_');
        }

        // Vérifier si l'utilisateur existe
        $exists = $db->single("SELECT Id FROM users WHERE Id = :id", [':id' => $this->id]);

        $dateFormat = $this->dateNaissance->format('Y-m-d H:i:s');

        if ($exists) {
            // Update
            $sql = "UPDATE users SET Nom_m = :nom, Prenom_m = :prenom, Date_naissance_m = :date_naissance, 
                    UserName = :username, Email = :email, Role_id = :role_id,EmailConfirmed = :email_confirmed,
                    PasswordHash = :password_hash, TwoFactorEnabled = :two_factor_enabled,
                    AccessFailedCount = :access_failed_count WHERE Id = :id";
        } else {
            // Insert
            $sql = "INSERT INTO users (Id, Nom_m, Prenom_m, Date_naissance_m, UserName, Email, Role_id, 
                    EmailConfirmed, PasswordHash, TwoFactorEnabled, AccessFailedCount) 
                    VALUES (:id, :nom, :prenom, :date_naissance, :username, :email, :role_id,:email_confirmed,
                    :password_hash, :two_factor_enabled, :access_failed_count)";
        }

        $db->prepare($sql);
        $db->bind(':id', $this->id);
        $db->bind(':nom', $this->nom);
        $db->bind(':prenom', $this->prenom);
        $db->bind(':date_naissance', $dateFormat);
        $db->bind(':username', $this->username);
        $db->bind(':email', $this->email);
        $db->bind(':role_id', $this->role_id);
        $db->bind(':email_confirmed', $this->emailConfirmed ? 1 : 0);
        $db->bind(':password_hash', $this->passwordHash);
        $db->bind(':two_factor_enabled', $this->twoFactorEnabled ? 1 : 0);
        $db->bind(':access_failed_count', $this->accessFailedCount);

        if ($db->execute()) {
            return $this->id;
        }

        return null;
    }

    /**
     * Méthode pour récupérer un utilisateur par son ID
     * @param string $id
     * @return User|null
     */
    public static function findById(string $id): ?User {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE Id = :id";
        $result = $db->single($sql, [':id' => $id]);

        if ($result) {
            return new User(
                $result['Id'],
                $result['Nom_m'],
                $result['Prenom_m'],
                new DateTime($result['Date_naissance_m']),
                $result['UserName'],
                $result['Email'],
                $result['Role_id'],
                (bool)$result['EmailConfirmed'],
                $result['PasswordHash'],
                (bool)$result['TwoFactorEnabled'],
                $result['AccessFailedCount']
            );
        }

        return null;
    }

    /**
     * Méthode pour récupérer un utilisateur par son nom d'utilisateur
     * @param string $userName
     * @return User|null
     */
    public static function findByUserName(string $userName): ?User {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE UserName = :userName";
        $result = $db->single($sql, [':userName' => $userName]);

        if ($result) {
            return new User(
                $result['Id'],
                $result['Nom_m'],
                $result['Prenom_m'],
                new DateTime($result['Date_naissance_m']),
                $result['UserName'],
                $result['Email'],
                $result['Role_id'],
                (bool)$result['EmailConfirmed'],
                $result['PasswordHash'],
                (bool)$result['TwoFactorEnabled'],
                $result['AccessFailedCount']
            );
        }

        return null;
    }

    /**
     * Méthode pour récupérer un utilisateur par son email
     * @param string $email
     * @return User|null
     */
    public static function findByEmail(string $email): ?User {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE Email = :email";
        $result = $db->single($sql, [':email' => $email]);

        if ($result) {
            return new User(
                $result['Id'],
                $result['Nom_m'],
                $result['Prenom_m'],
                new DateTime($result['Date_naissance_m']),
                $result['UserName'],
                $result['Email'],
                $result['Role_id'],
                (bool)$result['EmailConfirmed'],
                $result['PasswordHash'],
                (bool)$result['TwoFactorEnabled'],
                $result['AccessFailedCount']
            );
        }

        return null;
    }

    public static function getAll(): array {
        $db = Database::getInstance();
        $users = [];

        $results = $db->query("SELECT * FROM users");

        foreach ($results as $row) {
            $users[] = new User(
                $row['Id'],
                $row['Nom_m'],
                $row['Prenom_m'],
                new DateTime($row['Date_naissance_m']),
                $row['UserName'],
                $row['Email'],
                $row['Role_id'],
                (bool)$row['EmailConfirmed'],
                $row['PasswordHash'],
                (bool)$row['TwoFactorEnabled'],
                $row['AccessFailedCount']
            );
        }

        return $users;
    }

    public function delete(): bool {
        $db = Database::getInstance();
        $sql = "DELETE FROM users WHERE Id = :id";
        $db->prepare($sql);
        $db->bind(':id', $this->id);

        return $db->execute();
    }

    // Méthodes d'authentification
    /**
     * Méthode pour authentifier un utilisateur par son nom d'utilisateur et mot de passe
     * @param string $userName
     * @param string $password
     * @return User|null
     */
    public static function authenticate(string $userName, string $password): ?User {
        $user = self::findByUserName($userName);

        if ($user && password_verify($password, $user->getPasswordHash())) {
            return $user;
        }

        return null;
    }
    public function getRole() {
        return Role::findById(User::getRoleId());
    }
}