
/*function pop()
{
    

    var openbtn=document.getElementByClass("openmodal");
    var closebtn=document.getElementByClass("closemodal");
    var modal=document.getElementByClass("modal");
    
    openbtn.addEventListener("click",()=>{
        modal.classList.add("open");
    });
    closebtn.addEventListener("click",()=>{
        modal.classList.remove("open");
    });

}*/


document.querySelector("#openmodal").addEventListener("click",function(){
    document.querySelector(".popup").classList.add("active");

});

document.querySelector(".popup.closebtn").addEventListener("click",function(){
    document.querySelector(".popup").classList.remove("active");

});

