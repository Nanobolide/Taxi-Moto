<?php

namespace App\Utils;

use App\Models\Course;

class GeoUtils
{
    /**
     * Calcule la distance en kilomètres entre deux points géographiques
     * en utilisant la formule de Haversine.
     *
     * @param float $lat1 Latitude du premier point
     * @param float $lon1 Longitude du premier point
     * @param float $lat2 Latitude du deuxième point
     * @param float $lon2 Longitude du deuxième point
     * @return float Distance en kilomètres
     */
    public static function haversine_distance($lat1, $lon1, $lat2, $lon2)
    {
        // Rayon de la Terre en kilomètres
        $rayonTerre = 6371;

        // Convertir les degrés en radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Différences de coordonnées
        $diffLat = $lat2 - $lat1;
        $diffLon = $lon2 - $lon1;

        // Application de la formule de Haversine
        $a = sin($diffLat / 2) * sin($diffLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($diffLon / 2) * sin($diffLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calcul de la distance
        return $rayonTerre * $c;
    }


    public static function estimer_duree($latitude_depart, $longitude_depart, $latitude_arrivee, $longitude_arrivee, $vitesseMoyenne = 40)
    {
        if ($latitude_depart && $longitude_depart && $latitude_arrivee && $longitude_arrivee) {
            $distanceKm = self::haversine_distance($latitude_depart, $longitude_depart, $latitude_arrivee, $longitude_arrivee);
            $dureeMinutes = ($distanceKm / $vitesseMoyenne) * 60;
            return $dureeMinutes;
        }
        return 0;
    }


}
