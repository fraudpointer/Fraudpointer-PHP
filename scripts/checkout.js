function calculate_price(lstBoxCityFromId, lstBoxCityToId, lblPriceValue) {
    var from_select = document.getElementById(lstBoxCityFromId);
    var to_select = document.getElementById(lstBoxCityToId);    
    var price_label = document.getElementById(lblPriceValue);
    price_label.innerHTML = parseInt(from_select.options[from_select.selectedIndex].value) * parseInt(to_select.options[to_select.selectedIndex].value) + " €";
    return false;
} // calculate_price()
//--------------------
