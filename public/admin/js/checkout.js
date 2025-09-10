document.getElementById("personal-details-trigger").onclick= ()=>{
    document.getElementById("confirmed-tab").click()
}

document.getElementById("payment-trigger").onclick= ()=>{
    document.getElementById("shipped-tab").click()
}

document.getElementById("back-shipping-trigger").onclick= ()=>{
    document.getElementById("order-tab").click()
}

document.getElementById("back-personal-trigger").onclick= ()=>{
    document.getElementById("confirmed-tab").click()
}

document.getElementById("continue-payment-trigger").onclick= ()=>{
    document.getElementById("delivered-tab").click()
}

let productbtn = document.querySelectorAll(".product-btn");

(function () {
    'use strict'

    
    // checkAll.addEventListener('click', checkAllFn)

    function checkAllFn() {
        if (checkAll.checked) {
            document.querySelectorAll('.product-checkbox input').forEach(function (e) {
                e.closest('.product-list').classList.add('selected');
                e.checked = true;
            });
        }
        else {
            document.querySelectorAll('.product-checkbox input').forEach(function (e) {
                e.closest('.product-list').classList.remove('selected');
                e.checked = false;
            });
        }
    }

    //delete Btn
    let productbtn = document.querySelectorAll(".product-btn");

    productbtn.forEach((eleBtn) => {
        eleBtn.onclick = () => {
            let product = eleBtn.closest(".product-list")
            product.remove();
        }
    })

})();