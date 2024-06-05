window.byId = (id, fallback) => {
    return document.getElementById(id) || fallback;
}

window.byIdOptional = (id) => {
    return byId(id) || {};
}

window.byIdCash = (id) => {
    return cash(byId(id));
}

window.byQuery = (selector, fallback) => {
    return document.querySelector(selector) || fallback;
}

window.byQueryOptional = (selector) => {
    return document.querySelector(selector) || {};
}

window.float = (value) => {
    return isNaN(value = parseFloat(value)) ? 0 : value;
}

window.round = (value, decimals = 8) => {
    return +(Math.round(String(value).includes('e') ? value : (value + 'e+' + decimals)) + 'e-' + decimals);
}

window.percentRound = (first, second) => {
    return round(Math.abs(float(second) - float(first)) / float(first) * 100, 2);
}
