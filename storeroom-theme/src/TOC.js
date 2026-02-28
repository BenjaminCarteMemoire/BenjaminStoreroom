export function generateTOC(){

    const body = document.querySelector('body');
    if( !body.classList.contains('page') && !body.classList.contains('single-post') ) // Not a page or a post, don't have TOC.
        return;

    const TOC_HTML = document.getElementById('toc-list');
    const CONTENT = document.getElementById('content');

    if( !TOC_HTML || !CONTENT )
        return;

    TOC_HTML.innerHTML = '';
    const headings = CONTENT.querySelectorAll('h3');

    headings.forEach( heading => {
        if( !heading.classList.contains('comment-reply-title') ) {

            heading.id = heading.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]/g, "");
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.classList.add('sidebar_author');

            if (heading.tagName === "H3") {
                li.style.marginLeft = '0px';
                li.style.fontSize = '0.9em';
            }

            a.href = `#${heading.id}`;
            a.textContent = heading.textContent;

            li.appendChild(a);
            TOC_HTML.appendChild(li);
        }
    })
}