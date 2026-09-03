import './bootstrap';

const checkoutForm=document.querySelector('[data-checkout-form]');
checkoutForm?.addEventListener('submit',()=>{
    if(!checkoutForm.checkValidity()) return;
    const button=checkoutForm.querySelector('.checkout-submit');
    if(button){
        button.disabled=true;
        button.querySelector('span')?.replaceChildren('Abriendo WhatsApp…');
    }
});

const filters=document.querySelector('[data-filters]');
const filterForm=document.querySelector('[data-filter-form]');
const setFiltersOpen=open=>{
    filters?.classList.toggle('is-open',open);
    document.body.classList.toggle('filters-open',open);
};
document.querySelector('[data-filter-open]')?.addEventListener('click',()=>setFiltersOpen(true));
document.querySelectorAll('[data-filter-close]').forEach(button=>button.addEventListener('click',()=>setFiltersOpen(false)));
filterForm?.querySelectorAll('input[type="radio"], select').forEach(control=>control.addEventListener('change',()=>filterForm.requestSubmit()));
document.addEventListener('keydown',event=>{if(event.key==='Escape')setFiltersOpen(false)});

// Profundidad reactiva sutil para dispositivos con puntero preciso.
const canHover=window.matchMedia('(hover: hover) and (pointer: fine)').matches;
const reduceMotion=window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if(canHover&&!reduceMotion){
    const heroArt=document.querySelector('.hero-art');
    heroArt?.addEventListener('pointermove',event=>{
        const box=heroArt.getBoundingClientRect();
        const rotateY=((event.clientX-box.left)/box.width-.5)*8;
        const rotateX=-((event.clientY-box.top)/box.height-.5)*8;
        heroArt.style.transform=`rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
    });
    heroArt?.addEventListener('pointerleave',()=>heroArt.style.transform='rotateX(0) rotateY(0)');

    document.querySelectorAll('.product-card').forEach(card=>{
        card.addEventListener('pointermove',event=>{
            const box=card.getBoundingClientRect();
            card.style.setProperty('--tilt-y',`${((event.clientX-box.left)/box.width-.5)*4}deg`);
            card.style.setProperty('--tilt-x',`${-((event.clientY-box.top)/box.height-.5)*4}deg`);
        });
        card.addEventListener('pointerleave',()=>{
            card.style.setProperty('--tilt-x','0deg');
            card.style.setProperty('--tilt-y','0deg');
        });
    });
}

const toggle=(el,open)=>el?.setAttribute('aria-hidden',open?'false':'true');const menu=document.querySelector('[data-menu]'),search=document.querySelector('[data-search]'),input=document.querySelector('[data-search-input]'),results=document.querySelector('[data-search-results]');document.querySelector('[data-menu-open]')?.addEventListener('click',()=>toggle(menu,true));document.querySelectorAll('[data-menu-close]').forEach(b=>b.addEventListener('click',()=>toggle(menu,false)));document.querySelector('[data-search-open]')?.addEventListener('click',()=>{toggle(search,true);setTimeout(()=>input?.focus(),200)});document.querySelectorAll('[data-search-close]').forEach(b=>b.addEventListener('click',()=>toggle(search,false)));let timer;input?.addEventListener('input',()=>{clearTimeout(timer);let q=input.value.trim();if(q.length<2){results.innerHTML='<p>Escribe al menos dos caracteres.</p>';return}timer=setTimeout(async()=>{let data=await fetch(`${input.dataset.url}?q=${encodeURIComponent(q)}`).then(r=>r.json());results.innerHTML=data.length?data.map(p=>`<a href="${p.url}"><strong>${p.name}</strong> · ${p.code||''} · ${p.price?new Intl.NumberFormat('es-CO').format(p.price):'Por confirmar'}</a>`).join(''):`<p>No encontramos productos.</p>`},220)});document.addEventListener('keydown',e=>{if(e.key==='Escape'){toggle(menu,false);toggle(search,false)}});
