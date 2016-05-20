<?php

/**
 * Fonction de débuggage
 *
 * Permet d'arrêter l'exécution du script et d'afficher le contenu d'une
 * variable.
 *
 * @param mixed $objet Variable à afficher
 * @param boolean $detail TRUE pour afficher un var_dump, FALSE pour print_r
 * @param boolean $condition Condition à respecter pour arrêter le traitement
 */
function debug($objet = 'OK', $detail = false, $condition = true)
{    
    if ($condition) {
        echo '<div style="background: ghostwhite; border: 2px solid steelblue; padding: 10px;">';
        echo '<div style="float:right; font-family: Arial; margin: 0; padding: 0; color: grey; font-size: 11px;">Execution Time: ' . round(2, 2) . 's</div>';
        echo '<h1 style="font-family: Arial; font-size: 16px; font-weight: bold; margin: 0; padding: 0; color: steelblue;">DEBUG</h1>';
        echo '<pre>';
        if (!$detail) {
            if ($objet === null) {
                echo 'null';
            } else {
                print_r($objet);
            }
        } else {
            \yii\helpers\VarDumper::dump($objet);
        }
        echo '</pre>';
        echo '</div>';
        exit();
    }
}
