<?php
include 'connectDB.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <title>Médiathèque SIMPLON.co</title>
</head>

<body>
    <div id="titreSite">
        <h1>Médiathèque SIMPLON.co</h1>
    </div>

    <div id="wrapperLeft" class="wrapperDiv">
        <h1>Les films de la médiathèque</h1>
        <div class='searchBar'>
            <label for="modFilmRecherche">Vous cherchez un film en particulier?</label>
            <input type="text" id="filmRecherche" name="filmRecherche" required autocomplete="off" list="filmRechercheList">
            <datalist id="filmRechercheList">
                <?php
                //Ici on sort tout les titres de la DB à l'intérieur d'une datalist afin de faciliter le choix pour la modification à suivre
                try {
                    $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                    $requete = "SELECT `titre_film` FROM `filmtb`;";
                    $prepare = $pdo->prepare($requete);
                    $prepare->execute();
                    $res = $prepare->rowCount();
                    $resultat = $prepare->fetchAll();
                    foreach ($resultat as $key => $value) {
                        echo ("<option>" . htmlentities($value['titre_film'], ENT_QUOTES) . "</option>");
                    }
                } catch (PDOException $e) {
                    exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                }
                ?>
            </datalist>
        </div>
        <div id="film">
            <?php
            //Ici on sort tout les titres de la DB à l'intérieur d'une datalist afin de faciliter le choix pour la modification à suivre
            try {
                $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                $requete = "SELECT * FROM `filmtb`;";
                $prepare = $pdo->prepare($requete);
                $prepare->execute();
                $res = $prepare->rowCount();
                $resultat = $prepare->fetchAll();
                foreach ($resultat as $key => $value) {
            ?>
                    <div class='<?php echo (htmlentities($value['titre_film'], ENT_QUOTES)) ?>'>
                        <h1><?php echo (htmlentities($value['titre_film'], ENT_QUOTES)); ?></h1>
                        <img src='<?php echo (htmlentities($value['affiche_film'], ENT_QUOTES)); ?>'>
                        <div>
                            <p>Réalisateur : <?php echo (htmlentities($value['realisateur_film'], ENT_QUOTES)); ?></p>
                            <p>Les acteurs : <?php echo (htmlentities($value['acteurs_film'], ENT_QUOTES)); ?></p>
                            <p>Date de sortie du film : <?php echo (htmlentities($value['date_sortie_film'], ENT_QUOTES)); ?></p>
                            <p>Synopsis : <?php echo (htmlentities($value['synopsis_film'], ENT_QUOTES)); ?></p>
                            <p>Disponible : <?php
                                            if ($value['dispo_film'] == true) {
                                                echo ('Oui');
                                            } else {
                                                echo ('Non');
                                            }
                                            ?>
                            </p>
                        </div>
                    </div>
            <?php
                }
            } catch (PDOException $e) {
                exit("❌🙀❌ OOPS :\n" . $e->getMessage());
            }
            ?>
        </div>
    </div>

    <div id="wrapper" class="WrapperDiv">
        <h1>Gestion médiathèque</h1>
        <!-- Fonction d'ajout de films -->
        <div id="divWrapperScroll">
            <div id="ajoutFilm" class="droite">
                <h1>Ajouter un film</h1>
                <form action="index.php" method="POST">
                    <label for="ajoutFilm_titre">Titre :</label>
                    <input type="text" id="ajoutFilm_titre" name="ajoutFilm_titre" required>
                    <label for="ajoutFilm_affiche">Affiche (url) :</label>
                    <input type="text" id="ajoutFilm_affiche" name="ajoutFilm_affiche" required>
                    <label for="ajoutFilm_acteurs">Acteurs (séparés par une virgule) :</label>
                    <input type="text" id="ajoutFilm_acteurs" name="ajoutFilm_acteurs" required>
                    <label for="ajoutFilm_date_sortie">Date de sortie :</label>
                    <input type="date" id="ajoutFilm_date_sortie" name="ajoutFilm_date_sortie" required>
                    <label for="ajoutFilm_synopsis">Synopsis :</label>
                    <input type="text" id="ajoutFilm_synopsis" name="ajoutFilm_synopsis" required>
                    <label for="ajoutFilm_realisateur">Réalisateur :</label>
                    <input type="text" id="ajoutFilm_realisateur" name="ajoutFilm_realisateur" required>
                    <input type="hidden" value="ajoutFilm" id="ajoutFilmCheck" name="ajoutFilmCheck">
                    <input type="submit" value="Ajouter le film">
                </form>
                <?php
                // TRAITEMENT DES DONNEES TRAITEMENT DES DONNEES TRAITEMENT DES DONNEES TRAITEMENT DES DONNEES TRAITEMENT DES DONNEES TRAITEMENT DES DONNEES TRAITEMENT DES DONNEES
                if (isset($_POST['ajoutFilmCheck'])) {
                    $titre = $_POST['ajoutFilm_titre'];
                    $affiche = $_POST['ajoutFilm_affiche'];
                    $acteurs = $_POST['ajoutFilm_acteurs'];
                    $date_sortie = $_POST['ajoutFilm_date_sortie'];
                    $synopsis = $_POST['ajoutFilm_synopsis'];
                    $realisateur = $_POST['ajoutFilm_realisateur'];

                    try {
                        $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                        $requete = "INSERT INTO `filmtb` (`titre_film`, `affiche_film`,`acteurs_film`, `date_sortie_film`, `synopsis_film`, `realisateur_film`)
                                      VALUES (:titre, :affiche, :acteurs, :date_sortie, :synopsis, :realisateur);";
                        $prepare = $pdo->prepare($requete);
                        $prepare->execute(array(
                            ':titre' => $titre,
                            ':affiche' => $affiche,
                            ':acteurs' => $acteurs,
                            ':date_sortie' => $date_sortie,
                            ':synopsis' => $synopsis,
                            ':realisateur' => $realisateur
                        ));
                        $res = $prepare->rowCount();

                        if ($res == 1) {
                            echo "<p>Le film a été ajouté avec succés !</p>";
                        }
                    } catch (PDOException $e) {
                        exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                    }
                }

                ?>
            </div>

            <!-- Fonction de modification des films -->
            <div id="modFilm" class="droite">
                <h1>Modifier un film</h1>
                <form action="index.php" method="POST">
                    <label for="modFilmRecherche">Quel film souhaitez-vous modifier ?</label>
                    <input type="text" id="modFilmRecherche" name="modFilmRecherche" required autocomplete="off" list="modFilmRechercheList">
                    <datalist id="modFilmRechercheList">
                        <?php
                        //Ici on sort tout les titres de la DB à l'intérieur d'une datalist afin de faciliter le choix pour la modification à suivre
                        try {
                            $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                            $requete = "SELECT `titre_film` FROM `filmtb`;";
                            $prepare = $pdo->prepare($requete);
                            $prepare->execute();
                            $res = $prepare->rowCount();
                            $resultat = $prepare->fetchAll();
                            foreach ($resultat as $key => $value) {
                                echo ("<option>" . htmlentities($value['titre_film'], ENT_QUOTES) . "</option>");
                            }
                        } catch (PDOException $e) {
                            exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                        }
                        ?>
                    </datalist>
                    <input type="submit" value="Rechercher">
                </form>
                <?php
                if (isset($_POST['modFilmRecherche'])) {
                    $modFilmRecherche = $_POST['modFilmRecherche'];
                    try {
                        $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                        $requete = "SELECT * FROM `filmtb` WHERE titre_film = :modFilmRecherche;";
                        $prepare = $pdo->prepare($requete);
                        $prepare->execute(array(
                            ':modFilmRecherche' => $modFilmRecherche
                        ));
                        $res = $prepare->rowCount();
                        $resultat = $prepare->fetchAll();
                        echo ('
                    <form action="index.php" method="POST">
                    <label for="modFilm_titre">Titre :</label>
                    <input type="text" id="modFilm_titre" name="modFilm_titre" value="' . htmlentities($resultat[0]["titre_film"], ENT_QUOTES) . '" required>
                    <label for="modFilm_affiche">Affiche (url) :</label>
                    <input type="text" id="modFilm_affiche" name="modFilm_affiche" value="' . htmlentities($resultat[0]["affiche_film"], ENT_QUOTES) . '" required>
                    <label for="modFilm_acteurs">Acteurs (séparés par une virgule) :</label>
                    <input type="text" id="modFilm_acteurs" name="modFilm_acteurs" value="' . htmlentities($resultat[0]["acteurs_film"], ENT_QUOTES) . '" required>
                    <label for="modFilm_date_sortie">Date de sortie :</label>
                    <input type="date" id="modFilm_date_sortie" name="modFilm_date_sortie" value="' . htmlentities($resultat[0]["date_sortie_film"], ENT_QUOTES) . '" required>
                    <label for="modFilm_synopsis">Synopsis :</label>
                    <input type="text" id="modFilm_synopsis" name="modFilm_synopsis" value="' . htmlentities($resultat[0]["synopsis_film"], ENT_QUOTES) . '" required>
                    <label for="modFilm_realisateur">Réalisateur :</label>
                    <input type="text" id="modFilm_realisateur" name="modFilm_realisateur" value="' . htmlentities($resultat[0]["realisateur_film"], ENT_QUOTES) . '" required>
                    <input type="hidden" value="' . htmlentities($resultat[0]["id_film"], ENT_QUOTES) . '" id="modFilmId" name="modFilmId">
                    <input type="hidden" value="modFilm" id="modFilmCheck" name="modFilmCheck">
                    <input type="submit" value="Modifier le film">
                    </form>');
                    } catch (PDOException $e) {
                        exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                    }
                }

                if (isset($_POST['modFilmCheck'])) {
                    $modFilmTitre = $_POST['modFilm_titre'];
                    $modFilmAffiche = $_POST['modFilm_affiche'];
                    $modFilmActeurs = $_POST['modFilm_acteurs'];
                    $modFilmDateSortie = $_POST['modFilm_date_sortie'];
                    $modFilmSynopsis = $_POST['modFilm_synopsis'];
                    $modFilmRealisateur = $_POST['modFilm_realisateur'];
                    $modFilmId = $_POST['modFilmId'];
                    try {
                        $requete = "UPDATE `filmtb` SET
                      `titre_film` = :titre_film,
                      `affiche_film` = :affiche_film,
                      `acteurs_film` = :acteurs_film,
                      `date_sortie_film` = :date_sortie_film,
                      `synopsis_film` = :synopsis_film,
                      `realisateur_film` = :realisateur_film
                      WHERE `id_film` = :id_film;";
                        $prepare = $pdo->prepare($requete);
                        $prepare->execute(array(
                            ':titre_film' => $modFilmTitre,
                            ':affiche_film' => $modFilmAffiche,
                            ':acteurs_film' => $modFilmActeurs,
                            ':date_sortie_film' => $modFilmDateSortie,
                            ':synopsis_film' => $modFilmSynopsis,
                            ':realisateur_film' => $modFilmRealisateur,
                            ':id_film' => $modFilmId
                        ));
                        $res = $prepare->rowCount();

                        if ($res == 1) {
                            echo "<p>Les informations du film ont été mises à jour</p>";
                        }
                    } catch (PDOException $e) {
                        exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                    }
                }
                ?>
            </div>

            <!-- Fonction de suppression des films -->
            <div id="suprFilm" class="droite">
                <h1>Supprimer un film</h1>
                <form action="index.php" method="POST">
                    <label for="suprFilmRecherche">Quel film souhaitez-vous supprimer ?</label>
                    <input type="text" id="suprFilmRecherche" name="suprFilmRecherche" required autocomplete="off" list="suprFilmRechercheList">
                    <datalist id="suprFilmRechercheList">
                        <?php
                        //Ici on sort tout les titres de la DB à l'intérieur d'une datalist afin de faciliter le choix pour la modification à suivre
                        try {
                            $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                            $requete = "SELECT `titre_film` FROM `filmtb`;";
                            $prepare = $pdo->prepare($requete);
                            $prepare->execute();
                            $res = $prepare->rowCount();
                            $resultat = $prepare->fetchAll();
                            foreach ($resultat as $key => $value) {
                                echo ("<option>" . htmlentities($value['titre_film'], ENT_QUOTES) . "</option>");
                            }
                        } catch (PDOException $e) {
                            exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                        }
                        ?>
                    </datalist>
                    <input type="submit" value="Supprimer">
                </form>
                <?php
                if (isset($_POST['suprFilmRecherche'])) {
                    $suprFilmTitre = $_POST['suprFilmRecherche'];
                    try {
                        $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                        $requete = "DELETE FROM `filmtb` WHERE `titre_film` = :titre_film;";
                        $prepare = $pdo->prepare($requete);
                        $prepare->execute(array(
                            ':titre_film' => $suprFilmTitre
                        ));
                        echo "<p>Le film a bien été supprimé</p>";
                    } catch (PDOException $e) {
                        exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                    }
                }
                ?>
            </div>

            <!-- Fonction de signalement d'emprunt -->
            <div id="empruntFilm" class="droite">
                <h1>Gestion Médiathèque</h1>
                <form action="index.php" method="POST">
                    <label for="gestionFilmRecherche">Quel film souhaitez-vous administrer ?</label>
                    <input type="text" id="gestionFilmRecherche" name="gestionFilmRecherche" required autocomplete="off" list="gestionFilmRechercheList">
                    <datalist id="gestionFilmRechercheList">
                        <?php
                        //Ici on sort tout les titres de la DB à l'intérieur d'une datalist afin de faciliter le choix pour la modification à suivre
                        try {
                            $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                            $requete = "SELECT `titre_film` FROM `filmtb`;";
                            $prepare = $pdo->prepare($requete);
                            $prepare->execute();
                            $res = $prepare->rowCount();
                            $resultat = $prepare->fetchAll();
                            foreach ($resultat as $key => $value) {
                                echo ("<option>" . htmlentities($value['titre_film'], ENT_QUOTES) . "</option>");
                            }
                        } catch (PDOException $e) {
                            exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                        }
                        ?>
                    </datalist>
                    <input type="submit" value="Rechercher">
                </form>
                <?php
                if (isset($_POST['gestionFilmRecherche'])) {
                    $gestionFilmRecherche = $_POST['gestionFilmRecherche'];
                    try {
                        $pdo = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
                        $requete = "SELECT * FROM `filmtb` WHERE titre_film = :titre_film;";
                        $prepare = $pdo->prepare($requete);
                        $prepare->execute(array(
                            ':titre_film' => $gestionFilmRecherche
                        ));
                        $res = $prepare->rowCount();
                        $resultat = $prepare->fetchAll();
                        echo ("
                <h1>" . htmlentities($resultat[0]['titre_film'], ENT_QUOTES) . "</h1>
                <form action='index.php' method='POST'>
                <label for='gestionFilmDateEmprunt'>Date d'emprunt :</label>
                <input type='date' id='gestionFilmDateEmprunt' name='gestionFilmDateEmprunt' value='" . htmlentities($resultat[0]["date_emprunt_film"], ENT_QUOTES) . "' required>
                <label for='gestionFilmDateRetour'>Date de retour :</label>
                <input type='date' id='gestionFilmDateRetour' name='gestionFilmDateRetour' value='" . htmlentities($resultat[0]["date_retour_film"], ENT_QUOTES) . "' required>
                <label for='gestionFilmDispo'>Le film est-il disponible ?</label>
                <select id='gestionFilmDispo' name='gestionFilmDispo' required>
                    <option value='1'>Oui</option>
                    <option value='0'>Non</option>
                </select>
                <input type='hidden' name='gestionFilmId' value='" . htmlentities($resultat[0]['id_film'], ENT_QUOTES) . "'>
                <input type='submit' value='Valider'>
                </form>
                ");
                    } catch (PDOException $e) {
                        exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                    }
                }

                if (isset($_POST['gestionFilmDateEmprunt'])) {
                    $gestionFilmDateEmprunt = $_POST['gestionFilmDateEmprunt'];
                    $gestionFilmDateRetour = $_POST['gestionFilmDateRetour'];
                    $gestionFilmDispo = $_POST['gestionFilmDispo'];
                    $gestionFilmId = $_POST['gestionFilmId'];
                    try {
                        $requete = "UPDATE `filmtb` SET
                    `date_emprunt_film` = :date_emprunt_film,
                    `date_retour_film` = :date_retour_film,
                    `dispo_film` = :dispo_film
                    WHERE `id_film` = :id_film;";
                        $prepare = $pdo->prepare($requete);
                        $prepare->execute(array(
                            ':date_emprunt_film' => $gestionFilmDateEmprunt,
                            ':date_retour_film' => $gestionFilmDateRetour,
                            ':dispo_film' => $gestionFilmDispo,
                            ':id_film' => $gestionFilmId
                        ));
                        $res = $prepare->rowCount();

                        if ($res == 1) {
                            echo "<p>Les données ont été mises à jour</p>";
                        }
                    } catch (PDOException $e) {
                        exit("❌🙀❌ OOPS :\n" . $e->getMessage());
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="app.js"></script>
</body>

</html>