<?php

function initializeDatabase($host, $username, $password, $dbname) {
    try {
        // Connexion à MySQL (sans sélectionner la base de données)
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création de la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        echo "Base de données '$dbname' créée ou déjà existante.<br>";

        // Utilisation de la base de données
        $pdo->exec("USE `$dbname`");

        // Création des tables
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `allergies` (
              `AllergieId` int(11) NOT NULL AUTO_INCREMENT,
              `Libelle_al` longtext NOT NULL,
              PRIMARY KEY (`AllergieId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `antecedents` (
              `AntecedentId` int(11) NOT NULL AUTO_INCREMENT,
              `Libelle_a` longtext NOT NULL,
              PRIMARY KEY (`AntecedentId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `medicaments` (
              `MedicamentId` int(11) NOT NULL AUTO_INCREMENT,
              `Libelle_med` longtext NOT NULL,
              `Contr_indication` longtext NOT NULL,
              `Stock` int(11) NOT NULL,
              PRIMARY KEY (`MedicamentId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `patients` (
              `PatientId` int(11) NOT NULL AUTO_INCREMENT,
              `Nom_p` varchar(50) NOT NULL,
              `Prenom_p` varchar(50) NOT NULL,
              `Sexe_p` longtext NOT NULL,
              `Num_secu` varchar(15) NOT NULL,
              PRIMARY KEY (`PatientId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
              `Id` varchar(255) NOT NULL,
              `Nom_m` longtext NOT NULL,
              `Prenom_m` longtext NOT NULL,
              `Date_naissance_m` datetime(6) NOT NULL,
              `UserName` varchar(256) DEFAULT NULL,
              `Email` varchar(256) DEFAULT NULL,
              `Role` int(11) NOT NULL,
              `EmailConfirmed` tinyint(1) NOT NULL,
              `PasswordHash` longtext DEFAULT NULL,
              `TwoFactorEnabled` tinyint(1) NOT NULL,
              `AccessFailedCount` int(11) NOT NULL,
              PRIMARY KEY (`Id`),
              UNIQUE KEY `UserNameIndex` (`UserName`),
              KEY `EmailIndex` (`Email`)
              CONSTRAINT `FK_Users_Roles_Id` FOREIGN KEY (`Role`) REFERENCES `roles` (`Id`) ON DELETE CASCADE,
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `roles` (
              `Id` varchar(255) NOT NULL,
              `Name` varchar(256) DEFAULT NULL,
              PRIMARY KEY (`Id`),
              UNIQUE KEY `RoleNameIndex` (`Name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
    CREATE TABLE IF NOT EXISTS `ordonnances` (
      `OrdonnanceId` int(11) NOT NULL AUTO_INCREMENT,
      `Posologie` longtext NOT NULL,
      `DateCréation` datetime(6) NOT NULL,
      `Duree_traitement` longtext NOT NULL,
      `Instructions_specifique` longtext NOT NULL,
      `MedecinId` varchar(255) NOT NULL,
      `PatientId` int(11) NOT NULL,
      PRIMARY KEY (`OrdonnanceId`),
      KEY `IX_Ordonnances_MedecinId` (`MedecinId`),
      KEY `IX_Ordonnances_PatientId` (`PatientId`),
      CONSTRAINT `FK_Ordonnances_Users_MedecinId` FOREIGN KEY (`MedecinId`) REFERENCES `users` (`Id`) ON DELETE CASCADE,
      CONSTRAINT `FK_Ordonnances_Patients_PatientId` FOREIGN KEY (`PatientId`) REFERENCES `patients` (`PatientId`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
");

        // Insertion des données initiales
        $pdo->exec("
            INSERT INTO `patients` (`Nom_p`, `Prenom_p`, `Sexe_p`, `Num_secu`) VALUES
            ('Doe', 'John', 'H', '001838529835')
        ");

        echo "Structure de la base de données créée avec succès.<br>";
        return true;

    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage() . "<br>";
        return false;
    }
}

