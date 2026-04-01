<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Mairie de SACRE KEUR</title>
    <style>
        /* Style simple et institutionnel */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .main-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
        }
        h1 {
            color: #1a5a96;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        p.subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .card-link {
            text-decoration: none;
            color: white;
            padding: 25px;
            border-radius: 10px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            font-weight: bold;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .btn-declare {
            background-color: #9b4411; /* Vert pour la création */
        }
        .btn-request {
            background-color: #b92982; /* rose pour la demande */
           
        }
        .card-link:hover {
            transform: translateY(-5px);
            filter: brightness(1.1);
        }
        .icon {
            font-size: 30px;
            margin-bottom: 10px;
        }
        footer {
            margin-top: 30px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>

<div class="main-container">
    <img src="https://via.placeholder.com/80" alt="Logo Mairie" style="margin-bottom: 15px;">
    <h1>Mairie de SACRE KEUR</h1>
    <p class="subtitle">Portail de digitalisation de l'état civil</p>

    <div class="menu-grid">
    <a href="declaration.php" class="card-link btn-declare">
        <span class="icon">👶</span>
        Déclarer une Naissance
    </a>

    <a href="demande.php" class="card-link btn-request">
        <span class="icon">🔍</span>
        Demander un extrait
    </a>
</div>
 <div class="menu-grid">
    <a href="retraits.php" class="card-link btn-declare">
        
        <p>retrait d'extrait</p>
    </a>

    
</div>

    <footer>
        Accessible via <strong>localhost</strong> Système d'archivage par fichiers 
    </footer>
</div>

</body>
</html>