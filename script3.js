const projects=[

{
title:"Smart Farm Management System (C#)",
desc:`This project was created while doing OOP2 course. It is a C# Windows Forms application using OOP principles with database integration to manage poultry and aquaculture operations efficiently.`
},

{
title:"Smart App to Help Farmers (Figma & Trello )",
desc:`This mobile application helps farmers check crop prices, weather forecasts, and government support programs. Built using Android Studio, Java, and Firebase.`
},

{
title:"Hospital Management System (Java + MySQL)",
desc:`Developed during my OOP1 course. It manages patient records, doctor details, appointments, and billing using Java and MySQL database.`
}

];

const container=document.getElementById("projectContainer");

if(container){

projects.forEach(project=>{

const card=document.createElement("div");

card.classList.add("card");

card.innerHTML=`

<h3>${project.title}</h3>
<p>${project.desc}</p>

`;

container.appendChild(card);

});

}

document.addEventListener("submit",function(e){

if(e.target.id==="contactForm"){

e.preventDefault();

let name=document.getElementById("name").value;
let email=document.getElementById("email").value;
let subject=document.getElementById("subject").value;
let message=document.getElementById("message").value;

if(name=="" || email=="" || subject=="" || message==""){

alert("Please fill all fields");

}else{

alert("Message sent successfully!");

e.target.reset();

}

}

});