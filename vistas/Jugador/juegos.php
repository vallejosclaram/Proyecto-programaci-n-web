<?php
header("Content-Type: application/json; charset=utf-8");

$games = [
    [
        "id" => 1,
        "nombre" => "Valorant",
        "descripcion" => "Shooter táctico 5v5 de Riot Games con habilidades y precisión absoluta."
    ],
    [
        "id" => 2,
        "nombre" => "Counter Strike",
        "descripcion" => "FPS competitivo clásico donde la estrategia y reflejos definen la partida."
    ]
];

echo json_encode($games);

?>