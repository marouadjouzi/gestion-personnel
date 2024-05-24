
/*===== EXPANDER MENU  =====*/ 
const showMenu = (toggleId, navbarId, bodyId)=>{
    const toggle = document.getElementById(toggleId),
    navbar = document.getElementById(navbarId),
    bodypadding = document.getElementById(bodyId)
  
    if(toggle && navbar){
      toggle.addEventListener('click', ()=>{
        navbar.classList.toggle('expander')
  
        bodypadding.classList.toggle('body-pd')
      })
    }
  }
  showMenu('nav-toggle','navbar','body-pd')
  
  /*===== LINK ACTIVE  =====*/ 
  const linkColor = document.querySelectorAll('.nav__link')
  function colorLink(){
    linkColor.forEach(l=> l.classList.remove('active'))
    this.classList.add('active')
  }
  linkColor.forEach(l=> l.addEventListener('click', colorLink))
  
  
  /*===== COLLAPSE MENU  =====*/ 
  const linkCollapse = document.getElementsByClassName('collapse__link')
  var i
  
  for(i=0;i<linkCollapse.length;i++){
    linkCollapse[i].addEventListener('click', function(){
      const collapseMenu = this.nextElementSibling
      collapseMenu.classList.toggle('showCollapse')
  
      const rotate = collapseMenu.previousElementSibling
      rotate.classList.toggle('rotate')
    })
  }
  
  
  document.getElementById("openmodal-annual").addEventListener("click", function () {
    document.getElementById("modal-annual").style.display = "block";
  });
  
  document.getElementById("closemodal-annual").addEventListener("click", function () {
    document.getElementById("modal-annual").style.display = "none";
  });
  
  document.getElementById("openmodal-sick").addEventListener("click", function () {
    document.getElementById("modal-sick").style.display = "block";
  });
  
  document.getElementById("closemodal-sick").addEventListener("click", function () {
    document.getElementById("modal-sick").style.display = "none";
  });
  
  document.getElementById("openmodal-unpaid").addEventListener("click", function () {
    document.getElementById("modal-unpaid").style.display = "block";
  });
  
  document.getElementById("closemodal-unpaid").addEventListener("click", function () {
    document.getElementById("modal-unpaid").style.display = "none";
  });

  document.getElementById("openmodal-maladie").addEventListener("click", function () {
    document.getElementById("maladie").style.display = "block";
  });
  
  document.getElementById("closemodal-maladie").addEventListener("click", function () {
    document.getElementById("maladie").style.display = "none";
  });

  document.getElementById("openmodal-absence").addEventListener("click", function () {
    document.getElementById("modal-absence").style.display = "block";
  });
  
  document.getElementById("closemodal-absence").addEventListener("click", function () {
    document.getElementById("modal-absence").style.display = "none";
  });

  document.getElementById("openmodal-pret").addEventListener("click", function () {
    document.getElementById("modal-pret").style.display = "block";
  });
  
  document.getElementById("closemodal-pret").addEventListener("click", function () {
    document.getElementById("modal-pret").style.display = "none";
  });