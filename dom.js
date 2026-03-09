const form = document.getElementById("student-form")
const rollInput = document.getElementById("roll-no")
const nameInput = document.getElementById("student-name")
const list = document.getElementById("student-list")
const totalCount = document.getElementById("total-count")
const attendanceText = document.getElementById("attendance")
const addBtn = document.getElementById("add-btn")
const searchBox = document.getElementById("search-box")

let presentCount = 0

nameInput.addEventListener("input", function(){
addBtn.disabled = nameInput.value.trim() === ""
})

form.addEventListener("submit", function(e){

e.preventDefault()

let roll = rollInput.value
let name = nameInput.value

if(name === "" || roll === ""){
alert("Enter roll and name")
return
}

let li = document.createElement("li")
li.classList.add("student-item")

let span = document.createElement("span")
span.textContent = roll + " - " + name

let check = document.createElement("input")
check.type = "checkbox"

check.addEventListener("change", function(){

if(check.checked){
li.classList.add("present")
presentCount++
}
else{
li.classList.remove("present")
presentCount--
}

updateAttendance()

})
let editBtn = document.createElement("button")
editBtn.textContent = "Edit"

editBtn.addEventListener("click", function(){

let newRoll = prompt("Enter new roll:", roll)
let newName = prompt("Enter new name:", name)

if(newRoll && newName){
span.textContent = newRoll + " - " + newName
roll = newRoll
name = newName
}

})
let delBtn = document.createElement("button")
delBtn.textContent = "Delete"

delBtn.addEventListener("click", function(){

if(confirm("Are you sure you want to delete this student?")){

if(check.checked) presentCount--

li.remove()
updateCount()
updateAttendance()

}

})

li.appendChild(check)
li.appendChild(span)
li.appendChild(editBtn)
li.appendChild(delBtn)

list.appendChild(li)

rollInput.value=""
nameInput.value=""
addBtn.disabled=true

updateCount()
updateAttendance()

})
function updateCount(){

totalCount.textContent = "Total students: " + list.children.length

}
function updateAttendance(){

let total = list.children.length
let absent = total - presentCount

attendanceText.textContent = 
"Present: " + presentCount + " | Absent: " + absent

}
searchBox.addEventListener("input", function(){

let search = searchBox.value.toLowerCase()

let students = document.querySelectorAll(".student-item")

students.forEach(function(student){

let text = student.innerText.toLowerCase()

if(text.includes(search)){
student.style.display="flex"
}
else{
student.style.display="none"
}

})

})

document.getElementById("sort-btn").addEventListener("click", function(){

let items = Array.from(list.children)

items.sort(function(a,b){

let nameA = a.innerText.toLowerCase()
let nameB = b.innerText.toLowerCase()

return nameA.localeCompare(nameB)

})

items.forEach(item => list.appendChild(item))

})
document.getElementById("highlight-first").addEventListener("click", function(){

let items = document.querySelectorAll(".student-item")

items.forEach(i => i.classList.remove("highlight"))

if(items.length > 0){
items[0].classList.add("highlight")
}

})