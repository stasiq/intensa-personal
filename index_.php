<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<script>
    function findNearestFactorial(value) {
        if (value === '' || value === undefined) {
            return '*';
        }
        if (value <= 0) {
            console.log(value);
            return 1;
        }
        let currentValue = 1;
        let idx = 0
        for (let i = 1; currentValue * i <= value; i++) {
            currentValue *= i;
            idx = i;
        }
        return idx - 1;
    }

    console.log(findNearestFactorial(39));


    const numbers = [1, 2, 3, 4, 5, 6];

    separateArray(numbers);

    // должен вернуть { even: [2, 4, 6], odd: [1, 3, 5] }

    function separateArray(array) {
        let street = {even: [], odd: []};
        array.map(el => {
            if (el % 2 > 0) {
                street.odd.push(el);
            } else {
                street.even.push(el);
            }
        });

        return street;
    }

    function getLastEl(array) {
        return array[array.length - 1];
    }


    function doubleNumber(array) {
        return array.map(el => el * 2);
    }


    function calcAvgAge(array) {
        let initialValue = 0;
        let sum = array.reduce(function (accumulator, currentValue) {
            return accumulator + currentValue.age;
        }, initialValue)

    }


</script>
</body>
</html>