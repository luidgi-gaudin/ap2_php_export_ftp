<?php
    namespace App\Core;

    use PDO;
    use PDOException;
    use PDOStatement;

    class Database
    {
        private static ?Database $instance = null;
        private PDO $pdo;
        private ?PDOStatement $statement = null;
        private array $error = [];
        private string $lastInsertId = '';
        private int $rowCount = 0;

        private function __construct()
        {
            try {
                $host = getenv('DB_HOST') ?: 'localhost';
                $dbname = getenv('DB_NAME') ?: 'gsb';
                $user = getenv('DB_USER') ?: 'root';
                $password = getenv('DB_PASS') ?: '';
                $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
                $options = [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];

                $this->pdo = new PDO($dsn, $user, $password, $options);
            } catch (PDOException $e) {
                $this->error = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ];
                throw new PDOException("Erreur de connexion à la base de données : " . $e->getMessage(), (int)$e->getCode());
            }
        }

        /**
         * Retourne l'instance unique de Database (pattern Singleton)
         */
        public static function getInstance(): Database
        {
            if (self::$instance === null) {
                self::$instance = new Database();
            }
            return self::$instance;
        }

        /**
         * Prépare une requête SQL
         */
        public function prepare(string $sql): bool
        {
            try {
                $this->statement = $this->pdo->prepare($sql);
                return true;
            } catch (PDOException $e) {
                $this->error = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ];
                return false;
            }
        }

        /**
         * Associe une valeur à un paramètre nommé ou marqueur de la requête préparée
         */
        public function bind(string $param, $value, ?int $type = null): bool
        {
            if ($this->statement === null) {
                return false;
            }

            if (is_null($type)) {
                $type = match(true) {
                    is_int($value) => PDO::PARAM_INT,
                    is_bool($value) => PDO::PARAM_BOOL,
                    is_null($value) => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR
                };
            }

            return $this->statement->bindValue($param, $value, $type);
        }

        /**
         * Exécute une requête préparée
         */
        public function execute(): bool
        {
            if ($this->statement === null) {
                return false;
            }

            try {
                $result = $this->statement->execute();
                $this->lastInsertId = $this->pdo->lastInsertId();
                $this->rowCount = $this->statement->rowCount();
                return $result;
            } catch (PDOException $e) {
                $this->error = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ];
                return false;
            }
        }

        /**
         * Exécute une requête et retourne tous les résultats
         */
        public function query(string $sql, array $params = []): array
        {
            if (!$this->prepare($sql)) {
                return [];
            }

            foreach ($params as $key => $value) {
                $this->bind(is_numeric($key) ? ($key + 1) : $key, $value);
            }

            if (!$this->execute()) {
                return [];
            }

            return $this->statement->fetchAll();
        }

        /**
         * Exécute une requête et retourne un seul résultat
         */
        public function single(string $sql, array $params = []): array|false
        {
            if (!$this->prepare($sql)) {
                return false;
            }

            foreach ($params as $key => $value) {
                $this->bind(is_numeric($key) ? ($key + 1) : $key, $value);
            }

            if (!$this->execute()) {
                return false;
            }

            return $this->statement->fetch();
        }

        /**
         * Retourne le nombre de lignes affectées par la dernière requête
         */
        public function rowCount(): int
        {
            return $this->rowCount;
        }

        /**
         * Retourne le dernier ID inséré
         */
        public function lastInsertId(): string
        {
            return $this->lastInsertId;
        }

        /**
         * Retourne les erreurs éventuelles
         */
        public function getError(): array
        {
            return $this->error;
        }

        /**
         * Démarre une transaction
         */
        public function beginTransaction(): bool
        {
            return $this->pdo->beginTransaction();
        }

        /**
         * Commit une transaction
         */
        public function commit(): bool
        {
            return $this->pdo->commit();
        }

        /**
         * Annule une transaction
         */
        public function rollBack(): bool
        {
            return $this->pdo->rollBack();
        }

        /**
         * Ferme la connexion
         */
        public function close(): void
        {
            $this->statement = null;
            $this->pdo = null;
            self::$instance = null;
        }
    }