class Student {
    #name;
    #surn;
    #salary;
    constructor(name, surn) {
        this.#name = name;
        this.#surn = surn;
        this.#salary = 3000;
    }

    showI() {
        return this.#firstB(this.#name) + '.' + this.#firstB(this.#surn) + '.';
    }
    #firstB(str) {
        return str[0].toUpperCase();
    }

    improveSalary() {
        this.#salary = this.#salary + this.#salary / 10;
        return this.#salary;
    }
    getName() {
        return this.#name;
    }
    setName(name) {
        if (name.length > 0) {
            this.#name = name;
        }
    }
    

}

stas = new Student('Stas', 'Kon');
val = new Student('Stas', 'Kon');
console.log(stas === val);
stas.setName('Val');
console.log(stas.getName());

console.log(stas.showI());
console.log(stas.improveSalary());

// alert(employee1.salary + employee2.salary);