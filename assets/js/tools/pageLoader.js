export function pageLoader(display = false) {
    
    const loader = document.querySelector(".loader_bg");
    console.log('loader ok !');
    if (!loader) {
        console.error('No loader is found !');
        return;
    }
    
    loader.style.opacity = display ? "1" : "0";
    setTimeout(() => {
        loader.style.display = display ? "flex" : "none";
    }, 1500);
}
