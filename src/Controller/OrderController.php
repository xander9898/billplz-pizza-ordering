<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
use Xander\PizzaOrder\Model\Pizza;

$data = $_POST;

echo handle(requestData: $data);

function handle($requestData) :string {
    $response = [];

    $errors = validateData($requestData);
    if (!empty($errors)) {
        $response['errors'] = $errors;
        return json_encode($response);
    }

    $totalPrice = 0;
    foreach ($requestData['pizzas'] as $index => $pizza) {
        $totalPrice += calculatePrice($pizza);
    }

    $response['price'] = $totalPrice;
    return json_encode($response);
}

function validateData($data): array
{
    $errors = [];
    foreach ($data['pizzas'] as $index => $pizza) {
        if (empty($pizza['size'])) {
            $errors[] = "Pizza $index size is required";
        }
    }

    foreach ($data['pizzas'] as $index => $pizza) {
        if (!empty($pizza['add_ons'])) {
            foreach ($pizza['add_ons'] as $add_on) {
                if (!in_array($add_on, Pizza::$addOnOptions)) {
                    $errors[] = "Pizza $index add on $add_on is invalid";
                }
            }
        }
    }

    return $errors;
}

function calculatePrice($data): int
{
    $pizza = new Pizza($data['size'], $data['add_ons'] ?? []);
    return $pizza->price;
}
