(function(){
  const grid = document.getElementById('workGrid');
  const btns = document.querySelectorAll('.work-filters .wf-btn');
  if(!grid || !btns.length) return;

  btns.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      btns.forEach(b=>b.classList.remove('is-active'));
      btn.classList.add('is-active');
      const sel = btn.getAttribute('data-filter');
      const cards = grid.querySelectorAll('.work-card');
      cards.forEach(card=>{
        if(sel==='*'){ card.style.display=''; return; }
        if(card.classList.contains(sel.slice(1))){ card.style.display=''; }
        else{ card.style.display='none'; }
      });
    });
  });
})();
