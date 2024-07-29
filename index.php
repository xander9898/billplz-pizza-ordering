<?php require __DIR__ . '/vendor/autoload.php'; ?>
<?php $add_ons = Xander\PizzaOrder\Model\Pizza::$addOnOptions; ?>
<?php $sizeOptions = Xander\PizzaOrder\Model\Pizza::$sizeOptions; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Make Pizza Order</title>
</head>
<body>
    <form id="pizzaForm" action="/src/Controller/OrderController.php" method="POST">
        <div id="pizzasContainer"></div>

        <button type="button" id="addPizzaBtn">Add Pizza</button>
        <br><br>
        <button type="submit">Order</button>
    </form>

    <script>
        function removePizza(index) {
            const pizzaEntry = document.getElementById('pizzaEntry' + index);
            pizzaEntry.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {

            const pizzasContainer = document.getElementById('pizzasContainer');
            const addPizzaBtn = document.getElementById('addPizzaBtn');
            let pizzaCount = 0;

            function createPizzaEntry(index) {
                return `
                <div class="pizza-entry" id="pizzaEntry${index}">
                    <div class="form-group">
                        <label for="pizza${index}">Select Pizza ${index + 1}:</label>
                        <select id="pizza${index}" name="pizzas[${index}][size]" required>
                            <option value="">--Select Pizza--</option>
                            <?php foreach ($sizeOptions as $sizeOption): ?>
                                <option value="<?= $sizeOption; ?>"><?= ucfirst($sizeOption); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Choose AddOns:</label><br>
                            <?php foreach ($add_ons as $add_on): ?>
                                <input type="checkbox" name="pizzas[${index}][add_ons][]" value="<?=$add_on; ?>"> <?= str_replace('_', ' ',ucfirst($add_on)); ?><br>
                            <?php endforeach; ?>
                    </div>
                    <button type="button" class="remove-btn" onclick="removePizza(${index})">Remove Pizza ${index + 1}</button>
                    <br><br>
                </div>
            `;
            }

            function addPizza() {
                pizzaCount++;
                const pizzaEntry = createPizzaEntry(pizzaCount - 1);
                pizzasContainer.insertAdjacentHTML('beforeend', pizzaEntry);
            }

            addPizzaBtn.addEventListener('click', addPizza);

            addPizza();




            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                fetch('./src/Controller/OrderController.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert(data.errors);
                        } else {
                            alert('Your order has been placed. Your total is $' + data.price);
                        }
                    });
            });
        });
    </script>
</body>
</html>
