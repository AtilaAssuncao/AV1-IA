<?php
require_once '../Algorithm.php';

// Config
$total_generations = 600;
$fit_expression  = '({x} * 2) - (6 * {x}) + 10';
$mutation_rate = 0.01;


$population = Algorithm::ceratePopulation();

$i = 0;
$generation = 0;
$fitFound = false;

while ($generation < $total_generations) {
    echo '<pre>';

    echo 'Geração de número: ' . $i . '<br/>';

    echo 'Tamanho da população inicial: ' . count($population) . '<br/>';

    $fit = Algorithm::fit($population, $fit_expression );

    echo 'Tamanho da população apta: ' . count($fit) . '<br/>';

    $population = Algorithm::twoPointCrossover($fit);

    echo 'Tamanho da população reproduzida: ' . count($population) . '<br/>';

    $population = Algorithm::mutation($population, $mutation_rate);

    $population = Algorithm::convertToDecimal($population);

    echo '<br>';

    $count = count($population);
    $index = 1;
    $total = $count-1;

    while ($count > 0) {
        $individual = $population[Algorithm::issetIndex($index, $total)];
        $temp_fit = Algorithm::functionfit($individual, $fit_expression);

        echo 'Individuo ' . $individual . ' de fit ' . $temp_fit . '<br/>';

        foreach ($fit as $value) {
            if (strcmp($value, $temp_fit) > 0) {
                $population_fit = $temp_fit;
                $fitFound = true;
            }
        }

        $index++;
        $count--;
    }

    echo '<br>';
    echo 'Tamanho da população é: ' . count($population) . '<br/>';
    echo 'O fit é de: ' . $population_fit . '<br/>';
    echo 'Fit found is:' . $fitFound? 'sim': 'não' . '<br/>';
    echo '<hr><br><br><br>';

    $generation++;
    $i++;
}