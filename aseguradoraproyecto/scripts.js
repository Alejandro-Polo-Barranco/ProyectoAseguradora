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
