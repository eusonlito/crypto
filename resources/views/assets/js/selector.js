window.byId = (id, fallback) => document.getElementById(id) || fallback;
window.byIdOptional = (id) => byId(id) || {};
window.byIdCash = (id) => cash(byId(id));

window.byQuery = (selector, fallback) => document.querySelector(selector) || fallback;
window.byQueryOptional = (selector) => document.querySelector(selector) || {};

window.float = (value) => isNaN(value = parseFloat(value)) ? 0 : value;
window.round = (value, decimals = 8) => +(Math.round(value + 'e+' + decimals)  + 'e-' + decimals);
window.percentRound = (value1, value2) => round(Math.abs((float(value1) * 100 / float(value2)) - 100), 2);
