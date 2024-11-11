/**
 * The function `calculateCarInsurance` calculates the estimated price of a car insurance policy based
 * on the age of the car and the selected coverage type.
 * @param event - The `event` parameter in the `calculateCarInsurance` function is an event object that
 * represents an event being handled, such as a form submission. In this case, the function is designed
 * to calculate the estimated price of car insurance based on the age of the car and the type of
 * coverage selected by
 */
function calculateCarInsurance(event) {
    event.preventDefault();
    const edad = parseInt(document.getElementById("edadCoche").value);
    const cobertura = document.getElementById("coberturaCoche").value;

    let basePrice = 500;
    if (edad < 25) {
        basePrice += 150;
    } else if (edad > 60) {
        basePrice += 100;
    }

    switch (cobertura) {
        case "completa":
            basePrice *= 1.5;
            break;
        case "premium":
            basePrice *= 2;
            break;
    }

    document.getElementById("resultadoCoche").innerText = `El precio estimado de tu seguro de coche es: €${basePrice.toFixed(2)}`;
}

/**
 * The function `calculateMotoInsurance` calculates the estimated price of motorcycle insurance based
 * on age and coverage type selected.
 * @param event - The `event` parameter in the `calculateMotoInsurance` function is an event object
 * that is passed to the function when it is called. In this case, it is used to prevent the default
 * behavior of a form submission using `event.preventDefault()`. This is commonly done in JavaScript to
 * prevent the
 */
function calculateMotoInsurance(event) {
    event.preventDefault();
    const edad = parseInt(document.getElementById("edadMoto").value);
    const cobertura = document.getElementById("coberturaMoto").value;

    let basePrice = 300;
    if (edad < 25) {
        basePrice += 100;
    } else if (edad > 60) {
        basePrice += 80;
    }

    switch (cobertura) {
        case "completa":
            basePrice *= 1.4;
            break;
        case "premium":
            basePrice *= 1.8;
            break;
    }

    document.getElementById("resultadoMoto").innerText = `El precio estimado de tu seguro de moto es: €${basePrice.toFixed(2)}`;
}

/**
 * The function calculates the estimated price of home insurance based on the property value and
 * selected coverage type.
 * @param event - The `event` parameter in the `calculateHomeInsurance` function is an event object
 * that is passed to the function when it is called. In this case, it is used to prevent the default
 * behavior of a form submission using `event.preventDefault()`. This is commonly done in JavaScript
 * event handling to prevent
 */
function calculateHomeInsurance(event) {
    event.preventDefault();
    const valorVivienda = parseInt(document.getElementById("valorVivienda").value);
    const cobertura = document.getElementById("tipoCoberturaHogar").value;

    let basePrice = valorVivienda * 0.002;
    switch (cobertura) {
        case "completa":
            basePrice *= 1.3;
            break;
        case "premium":
            basePrice *= 1.6;
            break;
    }

    document.getElementById("resultadoHogar").innerText = `El precio estimado de tu seguro de hogar es: €${basePrice.toFixed(2)}`;
}

/* The `calculateHealthInsurance` function is calculating the estimated price of a health insurance
policy based on the age of the insured person and the selected coverage type. */
function calculateHealthInsurance(event) {
    event.preventDefault();
    const edad = parseInt(document.getElementById("edadSalud").value);
    const cobertura = document.getElementById("coberturaSalud").value;

    let basePrice = 200;
    if (edad > 50) {
        basePrice += 100;
    }

    switch (cobertura) {
        case "completa":
            basePrice *= 1.5;
            break;
        case "premium":
            basePrice *= 2;
            break;
    }

    document.getElementById("resultadoSalud").innerText = `El precio estimado de tu seguro de salud es: €${basePrice.toFixed(2)}`;
}
