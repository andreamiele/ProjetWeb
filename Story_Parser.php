<!DOCTYPE html>

<html lang="fr-fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title></title>
</head>

<body>
    <?php
    $userStatus = true; //Request admin(bool)
    if ($userStatus) {
    ?>
        <h1>Upload de votre histoire par fichier texte</h1>
        <h2>Administrateurs uniquement!</h2>
        <?php
        if (!isset($_POST['file'])) {
        ?>
            <form method="POST" action=Story_Parser.php>
                <label for="histoire">Séléctionnez le fichier contenant votre histoire :</label>
                <input type="file" id="histoire" name="histoire">
                <br />
                <input type="submit" value="Valider l'enregistrement de l'histoire">
            </form>
        <?php
        }
        ?>
</body>

<?php
        if (isset($_POST['histoire'])) {
            $fileName = $_POST['histoire'];
            $txt_file = fopen($fileName, 'r');
            $Title;
            $Author;
            $NumPara;
            $Para;
            $Audio;
            $Image;
            $State;

            while ($line = fgets($txt_file)) {
                if ($line . substr(0, 6) == 'Titre: ') {
                    $Title = $line;
                    substr(7, count_chars($line) - 7);
                } else if ($line == '(') {
                    while ($line = fgets($txt_file) && $line!=')') {
                        $Audio[$NumPara] += $line;
                    }
                } else if ($line == '*') {
                    while ($line = fgets($txt_file) && $line!='*') {
                        $Image[$NumPara] += $line;
                    }
                } else if($line . substr(0, 7) == 'Auteur: ') {
                    $Author = $line;
                    substr(8, count_chars($line) - 8);
                } else if ($line[0] == '[') {
                    $NumPara = $line[1];
                } else if ($line == '{') {
                    $Para[$NumPara] = '';
                    while ($line = fgets($txt_file) && $line != '/' && $line != '}') {
                        $Para[$NumPara] += $line;
                    }
                } else if ($line == '/') {
                    $State[$NumPara] = '';
                    while ($line = fgets($txt_file) && $line != '/') {
                        $State[$NumPara] += $line;
                    }
                } else if ($line[0] == '$') {
                    while ($line = fgets($txt_file) && $line[0] == '$') {
                        $State[$NumPara] += $line;
                    }
                }
            }
            fclose($txt_file);
        }
    }
    else
    {
        echo "Acces Denied ! You are not an administrator";
    }