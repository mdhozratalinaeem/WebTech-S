const unitPrice = 1000;

const quantityInput = document.getElementById("quantity");
const totalDisplay = document.getElementById("totalPrice");

quantityInput.addEventListener("input", function(){

    let quantity = Number(quantityInput.value);

    // Validation: Prevent negative quantity
    if(quantity < 0){
        alert("Quantity cannot be negative");
        quantity = 0;
        quantityInput.value = 0;
    }

    // Calculate total
    let total = unitPrice * quantity;

    totalDisplay.value = total;

    // Gift coupon condition
    if(total > 1000){
        alert("Congratulations! You are eligible for a gift coupon.");
    }

});