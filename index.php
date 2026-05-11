<?php
require_once 'vendor/autoload.php';

use PHPML\Preprocessing\DataPreprocessor;
use PHPML\Models\LinearRegression;
use PHPML\Models\KMeans;
use PHPML\Analysis\DataAnalyzer;
use PHPML\Evaluation\ModelEvaluator;

// Sample data
 $data = [
    [1, 2, 3],
    [2, 3, 4],
    [3, 4, 5],
    [4, 5, 6],
    [5, 6, 7],
    [6, 7, 8],
    [7, 8, 9],
    [8, 9, 10],
    [9, 10, 11],
    [10, 11, 12]
];

 $labels = [4, 5, 6, 7, 8, 9, 10, 11, 12, 13];

// Data preprocessing
 $preprocessor = new DataPreprocessor($data);
 $normalizedData = $preprocessor->normalize();
 $standardizedData = $preprocessor->standardize();

// Linear Regression
 $regression = new LinearRegression(0.01, 1000);
 $regression->fit($normalizedData, $labels);
 $predictions = $regression->predict($normalizedData);
 $score = $regression->score($normalizedData, $labels);

echo "Linear Regression Score: " . $score . "\n";

// K-Means Clustering
 $kmeans = new KMeans(3, 100);
 $centroids = $kmeans->fit($normalizedData);
 $clusters = $kmeans->predict($normalizedData);

echo "K-Means Centroids:\n";
print_r($centroids);

echo "K-Means Clusters:\n";
print_r($clusters);

// Data Analysis
 $analyzer = new DataAnalyzer($data);
 $summary = $analyzer->getSummary();
 $correlationMatrix = $analyzer->correlationMatrix();

echo "Data Summary:\n";
print_r($summary);

echo "Correlation Matrix:\n";
print_r($correlationMatrix);

// Model Evaluation
 $accuracy = ModelEvaluator::accuracy($labels, $predictions);
 $mse = ModelEvaluator::meanSquaredError($labels, $predictions);

echo "Accuracy: " . $accuracy . "\n";
echo "Mean Squared Error: " . $mse . "\n";
