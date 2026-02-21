// Confirmation
function confirmAction(message){
    return confirm(message);
}

// Validation
function validateForm(){
    let inputs=document.querySelectorAll("input[required]");
    for(let input of inputs){
        if(input.value.trim()===""){
            alert("Please fill all required fields");
            return false;
        }
    }
    return true;
}

// Scroll reveal

function confirmAction(message){
    return confirm(message);
}

function validateForm(){
    let inputs=document.querySelectorAll("input[required]");
    for(let input of inputs){
        if(input.value.trim()===""){
            alert("Please fill all required fields");
            return false;
        }
    }
    return true;
}
//Scroll 
function revealCards(){
    let cards=document.querySelectorAll(".info-card");
    cards.forEach(card=>{
        let position=card.getBoundingClientRect().top;
        let screenPosition=window.innerHeight/1.3;
        if(position<screenPosition){
            card.style.opacity="1";
            card.style.transform="translateY(0)";
        }
    });
}

window.addEventListener("scroll", function(){

    revealCards();

    const navbar=document.querySelector(".navbar");
    if(window.scrollY>50){
        navbar.style.boxShadow="0 5px 20px rgba(0,0,0,0.6)";
    }else{
        navbar.style.boxShadow="none";
    }
});

window.addEventListener("load",revealCards);