<?php
//Завдання 1
echo "Завдання 1:\n";
$numbers = [];
for ($i = 0; $i < 15; $i++) {
    $numbers[] = rand(1, 100);
}
echo "Початковий масив: " . implode(", ", $numbers) . "\n";
foreach ($numbers as $key => $value) {
    if ($value % 2 == 0) {
        unset($numbers[$key]);
    }
}
echo "Масив після видалення парних чисел: " . implode(", ", $numbers) . "\n";

//Завдання 2
echo "Завдання 2:\n";
echo "Введіть числа через кому: ";
$userInput = readline();
$cleanInput = str_replace(' ', '', $userInput);
$array = explode(',', $cleanInput);
$reversedArray = array_reverse($array);
if ($array === $reversedArray) {
    echo "Результат: Масив є паліндромом.\n";
} else {
    echo "Результат: Масив НЕ є паліндромом.\n";
}

//Завдання 3
echo "Завдання 3:\n";
echo "Введіть числа через кому: ";
$userInput = readline();
$cleanInput = str_replace(' ', '', $userInput);
$numbers = explode(',', $cleanInput);
$evenCount = 0;
foreach ($numbers as $number) {
    if ($number % 2 == 0) {
        $evenCount++;
    }
}
echo "З них парних: " . $evenCount . "\n";

//Завдання 4
echo "Завдання 4:\n";
$sum = 0;
for ($i = 100; $i <= 200; $i++) {
    if ($i % 4 == 0) {
        $sum = $sum + $i;
    }
}
echo "Сума всіх чисел від 100 до 200, які кратні 4, дорівнює: " . $sum . "\n";

//Завдання 5
echo "Завдання 5:\n";
$numbers = [];
for ($i = 0; $i < 10; $i++) {
    $numbers[] = rand(0, 50);
}
echo "Початковий масив: " . implode(", ", $numbers) . "\n";
sort($numbers);
$secondLargest = $numbers[8];
echo "Відсортований масив: " . implode(", ", $numbers) . "\n";
echo "Друге за величиною число: " . $secondLargest . "\n";

//Завдання 6
echo "Завдання 6:\n";
$numbers = [];
for ($i = 0; $i < 15; $i++) {
    $numbers[] = rand(1, 100);
}
echo "Масив: ";
for ($i = 0; $i < 15; $i++) {
    echo $numbers[$i] . " ";
}
echo "\n";
$product = 1;
for ($i = 0; $i < 15; $i++) {
    if ($numbers[$i] % 2 != 0) {
        $product = $product * $numbers[$i];
    }
}
echo "Добуток непарних чисел: " . $product . "\n";

//Завдання 7
echo "Завдання 7:\n";
echo "Введіть дату у форматі день.місяць.рік: ";
$userInput = readline();
$months = [
    1 => "січня", "лютого", "березня", "квітня", "травня", "червня",
    "липня", "серпня", "вересня", "жовтня", "листопада", "грудня"
];
$dateParts = explode('.', $userInput);
$day = $dateParts[0];
$monthNumber = (int)$dateParts[1];
$monthText = $months[$monthNumber];
$year = $dateParts[2];
echo "Результат: " . $day . " " . $monthText . " " . $year . " року\n";

//Завдання 8
echo "Завдання 8:\n";
$numbers = [];
for ($i = 0; $i < 20; $i++) {
    $numbers[] = rand(50, 500);
}
echo "Масив: ";
for ($i = 0; $i < 20; $i++) {
    echo $numbers[$i] . " ";
}
echo "\n";
$count = 0;
for ($i = 0; $i < 20; $i++) {
    if ($numbers[$i] % 100 == 0) {
        $count = $count + 1;
    }
}
echo "Кількість елементів, кратних 100: " . $count . "\n";

//Завдання 9
echo "Завдання 9:\n";
$sum = 0;
echo "Числа, які діляться на 5: ";
for ($i = 20; $i <= 45; $i++) {
    if (fmod($i, 5) == 0) {
        echo $i . " ";
        $sum = $sum + $i;
    }
}
echo "\n";
echo "Сума цих чисел дорівнює: " . $sum . "\n";

//Завдання 10
echo "Завдання 10 (через цикл):\n";
echo "Введіть хвилину години(від 1 до 60): ";
$minute = (int)readline();
$trafficLight = [];
for ($i = 1; $i <= 60; $i++) {
    if ($i % 5 == 1 || $i % 5 == 2 || $i % 5 == 3) {
        $trafficLight[$i] = "Зелений";
    } else {
        $trafficLight[$i] = "Червоний";
    }
}
$currentColor = $trafficLight[$minute];
echo "Зараз горить: " . $currentColor . "\n";
?>
