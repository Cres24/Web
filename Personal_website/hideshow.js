let display=0;
let div=document.getElementById('see')
let chng=document.querySelector('.click')
function show(){
    if (display==0){
    div.style.display="block"
    chng.textContent="Hide"
    display=1
}
else
{
    div.style.display="none"
    chng.textContent="Show history"
    display=0
}
}