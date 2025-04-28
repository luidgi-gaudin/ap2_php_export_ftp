<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-host" content="<?php echo getenv('HOST'); ?>">
    <title>MedManager</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css");
        :root {
            --primary-bg: #e6e9ef;
            --shadow-light: #ffffff;
            --shadow-dark: #c8ccd4;
            --text-color: #4A5568;
            --accent-color: #4299e1;
        }

        /* Reset pour s'assurer que tout prend la pleine hauteur */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--primary-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Hauteur minimale de 100% de la hauteur de la fenêtre */
        }

        .neo-nav {
            background-color: var(--primary-bg);
            padding: 1rem;
            box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
            border-radius: 0 0 15px 15px;
            margin-bottom: 2rem;
        }

        .neo-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .neo-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        }

        .neo-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .neo-menu li {
            margin: 0 0.5rem;
        }

        .neo-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            border-radius: 10px;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .neo-menu a:hover, .neo-menu a.active {
            color: var(--accent-color);
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        }

        .neo-hamburger {
            display: none;
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 10px;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        }

        .neo-hamburger div {
            width: 25px;
            height: 3px;
            background-color: var(--text-color);
            margin: 5px 0;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        /* Style pour le menu déroulant */
        .neo-dropdown {
            position: relative;
        }

        .neo-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .neo-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 180px;
            margin-top: 10px;
            padding: 8px 0;
            list-style: none;
            background-color: var(--primary-bg);
            border-radius: 10px;
            box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
            z-index: 100;
        }

        .neo-dropdown-menu li a {
            display: block;
            padding: 8px 15px;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: none;
        }

        .neo-dropdown-menu li a:hover {
            color: var(--accent-color);
            box-shadow: inset 2px 2px 5px var(--shadow-dark), inset -2px -2px 5px var(--shadow-light);
        }

        /* Animations */
        .dropdown-enter {
            transition: all 0.2s ease-out;
        }
        .dropdown-enter-start {
            opacity: 0;
            transform: translateY(-10px);
        }
        .dropdown-enter-end {
            opacity: 1;
            transform: translateY(0);
        }
        .dropdown-leave {
            transition: all 0.15s ease-in;
            opacity: 0;
            transform: translateY(-10px);
        }

        /* Style pour le contenu principal */
        main {
            flex: 1; /* Prend tout l'espace disponible */
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* Style pour le footer */
        footer {
            margin-top: auto; /* Pousse le footer en bas */
            padding: 1rem;
            background-color: var(--primary-bg);
            box-shadow: 0 -8px 16px var(--shadow-dark);
            text-align: center;
        }

        .footer-info p {
            color: var(--text-color);
            margin: 0;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            box-shadow: inset 2px 2px 5px var(--shadow-dark), inset -2px -2px 5px var(--shadow-light);
        }

        @media (max-width: 768px) {
            .neo-menu {
                position: fixed;
                left: -100%;
                top: 70px;
                flex-direction: column;
                background-color: var(--primary-bg);
                width: 100%;
                text-align: center;
                transition: 0.3s;
                box-shadow: 0 10px 15px var(--shadow-dark);
                z-index: 10;
                padding: 1rem 0;
            }

            .neo-menu.active {
                left: 0;
            }

            .neo-menu li {
                margin: 0.5rem 0;
            }

            .neo-hamburger {
                display: block;
            }

            .neo-hamburger.active div:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }

            .neo-hamburger.active div:nth-child(2) {
                opacity: 0;
            }

            .neo-hamburger.active div:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }
            .neo-dropdown-menu {
                position: static;
                box-shadow: inset 2px 2px 5px var(--shadow-dark), inset -2px -2px 5px var(--shadow-light);
                margin: 8px auto;
                width: 90%;
            }
        }
        body {
            background: #E0E5EC;
            color: #555;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .neo-card {
            border-radius: 20px;
            box-shadow: 8px 8px 16px #b8b9be, -8px -8px 16px #ffffff;
            padding: 20px;
            text-align: center;
        }
        .patient-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            box-shadow: 4px 4px 8px #b8b9be, -4px -4px 8px #ffffff;
        }
        .neo-btn {
            padding: 0.5rem 1rem;
            margin: 5px;
            box-shadow: 4px 4px 8px #b8b9be, -4px -4px 8px #ffffff;
        }
        .neo-btn-primary { color: #4b70a6; }
        .neo-btn-info { color: #5eadb0; }
        .neo-btn-danger { color: #e17a7a; }

    </style>

</head>
<body>
<header>
    <?php
    $host = getenv("HOST");
    ?>
    <nav class="neo-nav" x-data="{ isOpen: false }">
        <div class="neo-container">
            <a href="<?= $host ?>" class="neo-brand">MedManager</a>

            <button class="neo-hamburger" :class="{ 'active': isOpen }" @click="isOpen = !isOpen" aria-label="Menu">
                <div></div>
                <div></div>
                <div></div>
            </button>

            <ul class="neo-menu" :class="{ 'active': isOpen }">
                <?php if (isset($_SESSION["userId"])): ?>
                    <li x-data="{ dropdownOpen: false }" class="neo-dropdown">
                        <a href="#" class="neo-dropdown-toggle" @click.prevent="dropdownOpen = !dropdownOpen">
                            Paramétrage <i class="bi bi-chevron-down" :class="{'bi-chevron-up': dropdownOpen}"></i>
                        </a>
                        <ul class="neo-dropdown-menu" x-show="dropdownOpen" @click.away="dropdownOpen = false"
                            x-transition:enter="dropdown-enter"
                            x-transition:enter-start="dropdown-enter-start"
                            x-transition:enter-end="dropdown-enter-end"
                            x-transition:leave="dropdown-leave">
                            <li><a href="<?= $host ?>/medicament">Médicament</a></li>
                            <li><a href="<?= $host ?>/antecedants">Antécédents</a></li>
                            <li><a href="<?= $host ?>/allergie">Allergie</a></li>
                        </ul>
                    </li>
                    <li><a href="<?= $host ?>/ordonnance" class="<?= strpos($_SERVER['REQUEST_URI'], '/ordonnance') !== false ? 'active' : '' ?>">Ordonnance</a></li>
                    <li><a href="<?= $host ?>/patient" class="<?= strpos($_SERVER['REQUEST_URI'], '/patient') !== false ? 'active' : '' ?>">Patient</a></li>
                    <li><a href="<?= $host ?>/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>">Tableau de bord</a></li>
                    <li><a href="<?= $host ?>/user/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= $host ?>/user/login" class="<?= strpos($_SERVER['REQUEST_URI'], '/user/login') !== false ? 'active' : '' ?>">Connexion</a></li>
                    <li><a href="<?= $host ?>/user/register" class="<?= strpos($_SERVER['REQUEST_URI'], '/user/register') !== false ? 'active' : '' ?>">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<main>